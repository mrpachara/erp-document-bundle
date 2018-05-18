<?php

namespace Erp\Bundle\DocumentBundle\Collection;

use Erp\Bundle\CoreBundle\Entity\StatusPresentable;
use Erp\Bundle\CoreBundle\Collection\RestResponse;

/**
 * Document Rest HTTP Response
 */
class DocumentRestResponse extends RestResponse{
  /**
   * Constructor
   *
   * @param mixed $data
   */
  public function __construct($data, $meta = null){
    $meta = (array)$meta;

    $this->actions = [];
    $this->links = [];

    if($data instanceof StatusPresentable) {
      if($data->updatable()) $this->actions[] = 'replace';
      if($data->deletable()) $this->actions[] = 'cancel';
      if($data->deletable()) $this->actions[] = 'reject';
    } else if(is_array($data)) {
      $this->actions[] = 'add';
      $this->searchable = true;
    }

    if(array_key_exists('pagination', $meta)) $this->pagination = $meta['pagination'];
    if(array_key_exists('searchTerm', $meta)) $this->searchTerm = $meta['searchTerm'];

    $this->data = $data;
  }
}
