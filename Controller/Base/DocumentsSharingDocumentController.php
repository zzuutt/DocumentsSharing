<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Controller\Base;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;
use DocumentsSharing\Event\DocumentsSharingDocumentEvent;
use DocumentsSharing\Event\DocumentsSharingDocumentEvents;
use DocumentsSharing\Model\DocumentsSharingDocumentQuery;

/**
 * Class DocumentsSharingDocumentController
 * @package DocumentsSharing\Controller\Base
 * @author TheliaStudio
 */
class DocumentsSharingDocumentController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            "documents_sharing_document",
            "id",
            "order",
            AdminResources::MODULE,
            DocumentsSharingDocumentEvents::CREATE,
            DocumentsSharingDocumentEvents::UPDATE,
            DocumentsSharingDocumentEvents::DELETE,
            null,
            null,
            "DocumentsSharing"
        );
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return $this->createForm("documents_sharing_document.create");
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        return $this->createForm("documents_sharing_document.update", "form", $data);
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param mixed $object
     */
    protected function hydrateObjectForm($object)
    {
        $data = array(
            "id" => $object->getId(),
            "file" => $object->getFile(),
            "file_key" => $object->getFileKey(),
            "title" => $object->getTitle(),
            "description" => $object->getDescription(),
            "chapo" => $object->getChapo(),
            "postscriptum" => $object->getPostscriptum(),
        );

        return $this->getUpdateForm($data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getCreationEvent($formData)
    {
        $event = new DocumentsSharingDocumentEvent();

        $event->setFile($formData["file"]);
        $event->setFileKey($this->random(32));
        $event->setTitle($formData["title"]);
        $event->setDescription($formData["description"]);
        $event->setChapo($formData["chapo"]);
        $event->setPostscriptum($formData["postscriptum"]);

        return $event;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new DocumentsSharingDocumentEvent();

        $event->setId($formData["id"]);
        $event->setFile($formData["file"]);
        $event->setFileKey($formData["file_key"]);
        $event->setTitle($formData["title"]);
        $event->setDescription($formData["description"]);
        $event->setChapo($formData["chapo"]);
        $event->setPostscriptum($formData["postscriptum"]);

        return $event;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        $event = new DocumentsSharingDocumentEvent();

        $event->setId($this->getRequest()->request->get("documents_sharing_document_id"));

        return $event;
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param mixed $event
     */
    protected function eventContainsObject($event)
    {
        return null !== $this->getObjectFromEvent($event);
    }

    /**
     * Get the created object from an event.
     *
     * @param mixed $event
     */
    protected function getObjectFromEvent($event)
    {
        return $event->getDocumentsSharingDocument();
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        return DocumentsSharingDocumentQuery::create()
            ->findPk($this->getRequest()->query->get("documents_sharing_document_id"))
        ;
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param mixed $object
     */
    protected function getObjectLabel($object)
    {
        return $object->getTitle();
    }

    /**
     * Returns the object ID from the object
     *
     * @param mixed $object
     */
    protected function getObjectId($object)
    {
        return $object->getId();
    }

    /**
     * Render the main list template
     *
     * @param mixed $currentOrder , if any, null otherwise.
     */
    protected function renderListTemplate($currentOrder)
    {
        $this->getParser()
            ->assign("order", $currentOrder)
        ;

        return $this->render("documents-sharing-documents");
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "documents_sharing_document_id",
                $this->getRequest()->query->get("documents_sharing_document_id")
            )
        ;

        return $this->render("documents-sharing-document-edit");
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToEditionTemplate()
    {
        $id = $this->getRequest()->query->get("documents_sharing_document_id");

        return new RedirectResponse(
            URL::getInstance()->absoluteUrl(
                "/admin/module/DocumentsSharing/documents_sharing_document/edit",
                [
                    "documents_sharing_document_id" => $id,
                ]
            )
        );
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToListTemplate()
    {
        return new RedirectResponse(
            URL::getInstance()->absoluteUrl("/admin/module/DocumentsSharing/documents_sharing_document")
        );
    }

    private function seed_mt_rand() {
        static $done;
        if (!$done) {
            $hash = md5(microtime());
            $length = ((substr($hash,0,1) < '8') ? 8 : 7 );
            mt_srand((int)base_convert(substr($hash,0,$length),16,10));
            $done = TRUE;
        }
    }
    private function random($car) {
        $string = "";
        $chaine = "A-1B2Cabdfgruipzw3D4E5-F6G7@HI8J9K_LMNPQRSAazsdfgjnbvcpomlTUVBWXYZ1234C5678@9DEGHFZfderfgrez-gztrgergtyjhtuisgrTRUYWV_-";
        $this->seed_mt_rand();
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[mt_rand()%strlen($chaine)];
        }
        return $string;
    }
}
