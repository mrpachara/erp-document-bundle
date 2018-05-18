<?php

namespace Erp\Bundle\DocumentBundle\Entity;

interface DocumentStatus {
  /**
   * @return \DateTimeImmutable
   */
  public function getTstmp();

  /**
   * @return \Erp\Bundle\SystemBundle\Entity\SystemUser
   */
  public function getCreator();

  /**
   * @return bool
   */
  public function getApproved();

  /**
   * @return static
   */
  public function getUpdateOf();

  /**
   * @return static[]
   */
  public function getUpdatedBys();

  /**
   * @return Document
   */
  public function getTransferOf();

  /**
   * @return Document[]
   */
  public function getTransferedBys();

  /**
   * @return TerminatedDocument
   */
  public function getTerminated();
}
