<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Event\Base;

use Thelia\Core\Event\ActionEvent;
use DocumentsSharing\Model\DocumentsSharing;

/**
* Class DocumentsSharingEvent
* @package DocumentsSharing\Event\Base
* @author TheliaStudio
*/
class DocumentsSharingEvent extends ActionEvent
{
    protected $id;
    protected $shareKey;
    protected $documentId;
    protected $customerId;
    protected $groupeId;
    protected $dateEndShare;
    protected $dateLastDownload;
    protected $deleteFileAfter;
    protected $title;
    protected $description;
    protected $chapo;
    protected $postscriptum;
    protected $documentsSharing;
    protected $locale;

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($v)
    {
        $this->locale = $v;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getShareKey()
    {
        return $this->shareKey;
    }

    public function setShareKey($shareKey)
    {
        $this->shareKey = $shareKey;

        return $this;
    }

    public function getDocumentId()
    {
        return $this->documentId;
    }

    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;

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

    public function getGroupeId()
    {
        return $this->groupeId;
    }

    public function setGroupeId($groupeId)
    {
        $this->groupeId = $groupeId;

        return $this;
    }

    public function getDateEndShare()
    {
        return $this->dateEndShare;
    }

    public function setDateEndShare($dateEndShare)
    {
        $this->dateEndShare = $dateEndShare;

        return $this;
    }

    public function getDateLastDownload()
    {
        return $this->dateLastDownload;
    }

    public function setDateLastDownload($dateLastDownload)
    {
        $this->dateLastDownload = $dateLastDownload;

        return $this;
    }

    public function getDeleteFileAfter()
    {
        return $this->deleteFileAfter;
    }

    public function setDeleteFileAfter($deleteFileAfter)
    {
        $this->deleteFileAfter = $deleteFileAfter;

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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getChapo()
    {
        return $this->chapo;
    }

    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }

    public function getPostscriptum()
    {
        return $this->postscriptum;
    }

    public function setPostscriptum($postscriptum)
    {
        $this->postscriptum = $postscriptum;

        return $this;
    }

    public function getDocumentsSharing()
    {
        return $this->documentsSharing;
    }

    public function setDocumentsSharing(DocumentsSharing $documentsSharing)
    {
        $this->documentsSharing = $documentsSharing;

        return $this;
    }
}