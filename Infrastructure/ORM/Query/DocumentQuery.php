<?php
namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Query;

use Erp\Bundle\DocumentBundle\Domain\CQRS\Query\DocumentQuery as QueryInterface;
use Erp\Bundle\DocumentBundle\Infrastructure\ORM\Helper\DocumentQueryHelper;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Erp\Bundle\DocumentBundle\Entity\Document;
use Erp\Bundle\SystemBundle\Entity\SystemUser;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\CoreAccountQueryHelper;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Helper\ErpQueryHelper;
use Erp\Bundle\DocumentBundle\Entity\Purchase;
use Erp\Bundle\DocumentBundle\Entity\Income;
use Erp\Bundle\DocumentBundle\Entity\PurchaseRequest;
use Erp\Bundle\CoreBundle\Domain\CQRS\Query\CoreAccountQuery;
use Erp\Bundle\MasterBundle\Entity\Employee;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Erp\Bundle\DocumentBundle\Entity\PurchaseOrder;
use Erp\Bundle\DocumentBundle\Entity\Expense;
use Erp\Bundle\DocumentBundle\Entity\DeliveryNote;
use Erp\Bundle\DocumentBundle\Entity\BillingNote;
use Erp\Bundle\DocumentBundle\Entity\TaxInvoice;
use Erp\Bundle\DocumentBundle\Entity\Revenue;

/**
 *
 * @author pachara
 *        
 */
class DocumentQuery implements QueryInterface
{
    /**
     * @var EntityRepository
     */
    private $repos;

    /**
     * @var EntityRepository
     */
    private $reposPurchase;

    /**
     * @var EntityRepository
     */
    private $reposIncome;
    
    /**
     * @var EntityRepository
     */
    private $reposPruchaseRequest;
    
    /**
     * @var EntityRepository
     */
    private $reposPruchaseOrder;
    
    /**
     * @var EntityRepository
     */
    private $reposExpense;
    
    /**
     * @var EntityRepository
     */
    private $reposDeliveryNote;
    
    /**
     * @var EntityRepository
     */
    private $reposBillingNote;
    
    /**
     * @var EntityRepository
     */
    private $reposTaxInvoice;
    
    /**
     * @var EntityRepository
     */
    private $reposRevenue;
    
    private $docHq;
    
    private $accHq;
    
    private $erpHq;
    
    private $accQuery;
    
    private $authorizationChecker;
    private $tokenStorage;
    
    /**
     */
    public function __construct(
        RegistryInterface $doctrine,
        DocumentQueryHelper $docHq,
        CoreAccountQueryHelper $accHq,
        ErpQueryHelper $erpHq,
        CoreAccountQuery $accQuery,
        
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->repos = $doctrine->getRepository(Document::class);
        $this->reposPurchase = $doctrine->getRepository(Purchase::class);
        $this->reposIncome = $doctrine->getRepository(Income::class);
        
        $this->reposPruchaseRequest = $doctrine->getRepository(PurchaseRequest::class);
        $this->reposPruchaseOrder = $doctrine->getRepository(PurchaseOrder::class);
        $this->reposExpense = $doctrine->getRepository(Expense::class);
        
        $this->reposDeliveryNote = $doctrine->getRepository(DeliveryNote::class);
        $this->reposBillingNote = $doctrine->getRepository(BillingNote::class);
        $this->reposTaxInvoice = $doctrine->getRepository(TaxInvoice::class);
        $this->reposRevenue = $doctrine->getRepository(Revenue::class);
        
        $this->docHq = $docHq;
        $this->accHq = $accHq;
        $this->erpHq = $erpHq;
        
        $this->accQuery = $accQuery;
        
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    function getRelatedDocumentTracking(SystemUser $user): array
    {
        $requesters = [];
        foreach($this->accQuery->getRelatedAccount($user) as $account) {
            if($account instanceof Employee) {
                $requesters[] = $account;
            }
        }
        
        $result = [];
        
        // Tracking document
        $qb = $this->repos->createQueryBuilder('_doc');
        $qb
            ->where($qb->expr()->in("_doc.thing",
                $this->repos->createQueryBuilder('_doc_thing')
                    ->select('_thing')
                    ->join('_doc_thing.thing', '_thing')
                    ->where($qb->expr()->eq('_doc_thing.creator', ':creator'))
                ->getDQL()
            ))
            ->setParameter('creator', $user)
        ;

        $index = 1;
        foreach($requesters as $requester) {
            $aliasPurchase = '_docPurchase_thing_'.$index;
            $aliasIncome = '_docIncome_thing_'.$index;
            
            $aliasPurchaseThing = '_purchase_thing_'.$index;
            $aliasIncomeThing = '_income_thing_'.$index;
            
            $requesterPurchase = 'requester_purchase_'.$index;
            $requesterIncome = 'requester_income_'.$index;
            
            $qb
                ->orWhere($qb->expr()->in("_doc.thing",
                    $this->reposPurchase->createQueryBuilder($aliasPurchase)
                        ->select($aliasPurchaseThing)
                        ->join("{$aliasPurchase}.thing", $aliasPurchaseThing)
                        ->where($qb->expr()->eq("{$aliasPurchase}.requester", ":{$requesterPurchase}"))
                    ->getDQL()
                ))
                ->setParameter($requesterPurchase, $requester)
            ;
            
            $qb
                ->orWhere($qb->expr()->in("_doc.thing",
                    $this->reposIncome->createQueryBuilder($aliasIncome)
                        ->select($aliasIncomeThing)
                        ->join("{$aliasIncome}.thing", $aliasIncomeThing)
                        ->where($qb->expr()->eq("{$aliasIncome}.requester", ":{$requesterIncome}"))
                    ->getDQL()
                ))
                ->setParameter($requesterIncome, $requester)
            ;
            
            $index++;
        }
        
        $qb->orderBy('_doc.tstmp', 'DESC');
        
        foreach($qb->getQuery()->getResult() as $doc) {
            $result[] = $doc;
        }
        
        return $result;
    }

    function getRelatedDocumentNext(SystemUser $user): array
    {
        $result = [];
        
        // Next processing document
        $previousToken = $this->tokenStorage->getToken();
        $this->tokenStorage->setToken(new AnonymousToken(null, $user, $user->getRoles()));
        
        $qb = $this->repos->createQueryBuilder('_doc');
        
        // ------------------ Purchse --------------------------------
        // Approve Purchase Request
        if($this->authorizationChecker->isGranted('ROLE_PURCHASE_PR_APPROVE')) {
            $alias = '_doc_pr_approve';
            $subQb = $this->reposPruchaseRequest->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Create Purchase Order from Purchase Request
        if($this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_CREATE')) {
            $alias = '_doc_pr_next';
            $subQb = $this->reposPruchaseRequest->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, true, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Approve Purchase Order
        if($this->authorizationChecker->isGranted('ROLE_PURCHASE_PO_APPROVE')) {
            $alias = '_doc_po_approve';
            $subQb = $this->reposPruchaseOrder->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Create Expense from Purchase Order
        if($this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_CREATE')) {
            $alias = '_doc_po_next';
            $subQb = $this->reposPruchaseOrder->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, true, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Approve Expense
        if($this->authorizationChecker->isGranted('ROLE_PURCHASE_EP_APPROVE')) {
            $alias = '_doc_ep_approve';
            $subQb = $this->reposExpense->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // ------------------ Income --------------------------------
        // Approve Delivery Note
        if($this->authorizationChecker->isGranted('ROLE_INCOME_DN_APPROVE')) {
            $alias = '_doc_dn_approve';
            $subQb = $this->reposDeliveryNote->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Create Billing Note from Delivery Note
        if($this->authorizationChecker->isGranted('ROLE_INCOME_BN_CREATE')) {
            $alias = '_doc_dn_next';
            $subQb = $this->reposDeliveryNote->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, true, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Approve Billing Note
        if($this->authorizationChecker->isGranted('ROLE_INCOME_BN_APPROVE')) {
            $alias = '_doc_bn_approve';
            $subQb = $this->reposBillingNote->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Create Tax Invoice from Billing Note
        if($this->authorizationChecker->isGranted('ROLE_INCOME_TI_CREATE')) {
            $alias = '_doc_bn_next';
            $subQb = $this->reposBillingNote->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, true, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Approve Tax Invoice
        if($this->authorizationChecker->isGranted('ROLE_INCOME_TI_APPROVE')) {
            $alias = '_doc_ti_approve';
            $subQb = $this->reposTaxInvoice->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Create Revenue from Tax Invoice
        if($this->authorizationChecker->isGranted('ROLE_INCOME_RV_CREATE')) {
            $alias = '_doc_ti_next';
            $subQb = $this->reposTaxInvoice->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, true, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        // Approve Revenue
        if($this->authorizationChecker->isGranted('ROLE_INCOME_RV_APPROVE')) {
            $alias = '_doc_rv_approve';
            $subQb = $this->reposRevenue->createQueryBuilder($alias);
            $this->docHq->applyActiveFilter($subQb, 'and');
            $this->docHq->applyApproveFilter($subQb, false, 'and');
            
            $qb->orWhere($qb->expr()->in("_doc", $subQb->getDQL()));
        }
        
        $this->tokenStorage->setToken($previousToken);
        
        $qb->orderBy('_doc.tstmp', 'ASC');
        
        foreach($qb->getQuery()->getResult() as $doc) {
            $result[] = $doc;
        }
        
        return $result;
    }
}

