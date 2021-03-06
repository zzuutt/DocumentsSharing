<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Event\Base;

use Thelia\Core\Event\ActionEvent;
use DocumentsSharing\Model\DocumentsSharingGroupe;

/**
* Class DocumentsSharingGroupeEvent
* @package DocumentsSharing\Event\Base
* @author TheliaStudio
*/
class DocumentsSharingGroupeEvent extends ActionEvent
{
    protected $id;
    protected $title;
    protected $customerId;
    protected $documentsSharingGroupe;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getDocumentsSharingGroupe()
    {
        return $this->documentsSharingGroupe;
    }

    public function setDocumentsSharingGroupe(DocumentsSharingGroupe $documentsSharingGroupe)
    {
        $this->documentsSharingGroupe = $documentsSharingGroupe;

        return $this;
    }
}
