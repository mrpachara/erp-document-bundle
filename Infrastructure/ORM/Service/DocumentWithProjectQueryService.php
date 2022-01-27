<?php

namespace Erp\Bundle\DocumentBundle\Infrastructure\ORM\Service;

use Doctrine\ORM\QueryBuilder;
use Erp\Bundle\CoreBundle\Infrastructure\ORM\Service\SearchQuery;
use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * DocumentWithProject Query Service
 */
class DocumentWithProjectQueryService implements SearchQuery
{
    public function assign(QueryBuilder $qb, array $params, string $alias, ?array $options, array &$context = null)
    {
        $context = (array)$context;

        if(!empty($params['document-with-user'])) {
            $param = $params['document-with-user'];
            if(
                !is_array($param) ||
                !key_exists('user', $param) ||
                !key_exists('types', $param)
            ) {
                throw new \InvalidArgumentException("Invalid document-with-user parameters.");
            }

            /** @var SystemUser $user */
            $user = $param['user'];
            if(!$user instanceof SystemUser) {
                throw new \InvalidArgumentException("Search document-with-user: \$use is not SystemUser.");
            }

            $types = (array)$param['types'];

            if(empty($options['service'])) {
                throw new \InvalidArgumentException("Search document-with-user: service is not found.");
            }

            /** @var DocumentWithProject $service */
            $service = $options['service'];
            if(!($service instanceof DocumentWithProject)) {
                throw new \InvalidArgumentException("Search document-with-user: current service does not support DocumentWithProject.");
            }

            $qb = $service->assignWithUserFilter($qb, $alias, $user, $types);
        }

        return $qb;
    }

    public function paramName()
    {
        return 'documetnWithProject';
    }

}
