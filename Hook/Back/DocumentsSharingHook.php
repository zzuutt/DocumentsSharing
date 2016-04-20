<?php
/**
 * Created by PhpStorm.
 * User: Luc
 * Date: 25/03/2016
 * Time: 09:16
 */

namespace DocumentsSharing\Hook\Back;

use DocumentsSharing\DocumentsSharing;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;

class DocumentsSharingHook extends BaseHook
{
    public function onMainTopMenuTools(HookRenderBlockEvent $event)
    {
        $event
            ->add(
                array(
                    'url' => URL::getInstance()->absoluteUrl('/admin/module/DocumentsSharing/documents_sharing'),
                    'title' => Translator::getInstance()->trans('Documents Sharing', array(), DocumentsSharing::MESSAGE_DOMAIN)
                )
            )
        ;
    }
}