<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Form\Base;

use DocumentsSharing\Form\DocumentsSharingCreateForm as ChildDocumentsSharingCreateForm;
use DocumentsSharing\Form\Type\DocumentsSharingIdType;
use DocumentsSharing\DocumentsSharing;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;

/**
 * Class DocumentsSharingForm
 * @package DocumentsSharing\Form
 * @author TheliaStudio
 */
class DocumentsSharingUpdateForm extends ChildDocumentsSharingCreateForm
{
    const FORM_NAME = "documents_sharing_update";

    public function buildForm()
    {
        parent::buildForm();

        $this->formBuilder
            ->add("id", DocumentsSharingIdType::TYPE_NAME)
            ->add("share_key", "text", array(
                "label" => Translator::getInstance()->trans("share_key", [], DocumentsSharing::MESSAGE_DOMAIN),
                "label_attr" => [
                    "for" => "share_key",
                    "help" => Translator::getInstance()->trans('The key is generated automatically.', [], DocumentsSharing::MESSAGE_DOMAIN),
                ],
                "required" => true,
                "constraints" => array(
                    new NotBlank(),
                ),
                "attr" => array(
                    "readonly" => "readonly",
                )
            ))
        ;
    }
}
