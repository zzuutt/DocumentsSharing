<?php
/*************************************************************************************/
/*      This file is part of the module ProductRegistration                               */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace DocumentsSharing\Hook\Front;

use DocumentsSharing\DocumentsSharing;
use DocumentsSharing\Event\DocumentsSharingEvent;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DocumentsSharingAccountDisplayHook extends BaseHook
{
    public function onAccountAdditional(HookRenderBlockEvent $event)
    {
        $customer = $this->getCustomer();

        if (is_null($customer)) {
            // No customer => nothing to do.
            return;
        }

        $customerId = $customer->getId();


        if ($customerId <= 0) {
            // Wrong customer => return.
            return;
        }

        $title = $this->trans('My Documents Sharing', [], DocumentsSharing::MESSAGE_DOMAIN);

        $event->add(array(
            'id'      => 'documents-sharing-'.$customerId,
            'title'   => $title,
            'content' => $this->render(
                'account-additional.html',
                array(
                    'customer_id' => $customerId,
                    'messageDomain' => DocumentsSharing::MESSAGE_DOMAIN,
                    'title'      => $title,
                )
            )
        ));
    }

    public function onAccountCssInclude(HookRenderEvent $event)
    {
        $event->add($this->addCSS("assets/css/style.css"));
    }

    public function onAccountJsInclude(HookRenderEvent $event)
    {
        $event->add($this->addJS("assets/js/script.js"));
    }
}
