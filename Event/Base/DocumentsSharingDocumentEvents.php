<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Event\Base;

use DocumentsSharing\Event\Module\DocumentsSharingEvents as ChildDocumentsSharingEvents;

/*
 * Class DocumentsSharingDocumentEvents
 * @package DocumentsSharing\Event\Base
 * @author TheliaStudio
 */
class DocumentsSharingDocumentEvents
{
    const CREATE = ChildDocumentsSharingEvents::DOCUMENTS_SHARING_DOCUMENT_CREATE;
    const UPDATE = ChildDocumentsSharingEvents::DOCUMENTS_SHARING_DOCUMENT_UPDATE;
    const DELETE = ChildDocumentsSharingEvents::DOCUMENTS_SHARING_DOCUMENT_DELETE;
    const DELETE_FILE = ChildDocumentsSharingEvents::DOCUMENTS_SHARING_DOCUMENT_DELETE_FILE;
    const CHECK  = ChildDocumentsSharingEvents::DOCUMENTS_SHARING_DOCUMENT_CHECK;
}