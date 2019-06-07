<?php

namespace Erp\Bundle\DocumentBundle\Entity;

use Erp\Bundle\SystemBundle\Entity\SystemUser;

/**
 * TerminatedDocument Entity
 */
class TerminatedDocument extends DocumentObjectValue {
  /**
   * @var string
   */
  private $id;

  /**
   * tstmp
   *
   * @var \DateTimeImmutable
   */
  protected $tstmp;

  /**
  * @var string
  */
  private $type;

  /**
   * creator
   *
   * @var SystemUser
   */
  protected $creator;

  /**
   * description
   *
   * @var string
   */
  protected $description;

  /**
   * constructor
   *
   * @param SystemUser|null $creator
   */
  public function __construct(SystemUser $creator = null) {
    $this->creator = $creator;
  }

  /**
   * Get id
   *
   * @return string
   */
  public function getId(){
    return $this->id;
  }

  /**
   * set tstmp
   *
   * @param \DateTimeImmutable $tstmp
   *
   * @return static
   */
  public function setTstmp(\DateTimeImmutable $tstmp)
  {
    $this->tstmp = $tstmp;

    return $this;
  }

  /**
   * get tstmp
   *
   * @return \DateTimeImmutable
   */
  public function getTstmp()
  {
    return $this->tstmp;
  }

  /**
   * get type
   *
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * set type
   *
   * @param string $type
   *
   * @return static
   */
  public function setType(string $type) {
    $this->type = $type;

    return $this;
  }

  /**
   * set creator
   *
   * @return SystemUser
   */
  public function getCreator() {
    return $this->creator;
  }

  /**
   * set creator
   *
   * @param SystemUser $creator
   *
   * @return static
   */
  public function setCreator(SystemUser $creator) {
    $this->creator = $creator;

    return $this;
  }

  /**
   * get description
   *
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * set description
   *
   * @param string $description
   *
   * @return static
   */
  public function setDescription(string $description) {
    $this->description = $description;

    return $this;
  }
}
