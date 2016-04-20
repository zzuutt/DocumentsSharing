<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace DocumentsSharing\Loop\Base;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\BooleanOrBothType;
use DocumentsSharing\Model\DocumentsSharingQuery;
use DocumentsSharing\Event\DocumentsSharingEvent;
use DocumentsSharing\Event\DocumentsSharingEvents;
use DocumentsSharing\Model\DocumentsSharingGroupeQuery;

/**
 * Class DocumentsSharing
 * @package DocumentsSharing\Loop\Base
 * @author TheliaStudio
 */
class DocumentsSharing extends BaseI18nLoop implements PropelSearchLoopInterface
{
    protected $timestampable = true;

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        $dateNow = new \DateTime();

        /** @var \DocumentsSharing\Model\DocumentsSharing $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $dateEndShare = new \DateTime(date_format($entry->getDateEndShare(), 'Y-m-d H:i:s'));
            $interval = $dateNow->diff($dateEndShare);

            $row = new LoopResultRow($entry);

            $row
                ->set("ID", $entry->getId())
                ->set("SHARE_KEY", $entry->getShareKey())
                ->set("DOCUMENT_ID", $entry->getDocumentId())
                ->set("CUSTOMER_ID", $entry->getCustomerId())
                ->set("GROUPE_ID", $entry->getGroupeId())
                ->set("DATE_END_SHARE", $entry->getDateEndShare())
                ->set("DAYS_REMAINING", $interval->format('%r%a'))
                ->set("DATE_LAST_DOWNLOAD", $entry->getDateLastDownload())
                ->set("DELETE_FILE_AFTER", $entry->getDeleteFileAfter())
                ->set("TITLE", $entry->getVirtualColumn("i18n_TITLE"))
                ->set("DESCRIPTION", $entry->getVirtualColumn("i18n_DESCRIPTION"))
                ->set("CHAPO", $entry->getVirtualColumn("i18n_CHAPO"))
                ->set("POSTSCRIPTUM", $entry->getVirtualColumn("i18n_POSTSCRIPTUM"))
            ;

            $this->addMoreResults($row, $entry);

            $loopResult->addRow($row);
        }

        return $loopResult;
    }

    /**
     * Definition of loop arguments
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       ...
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument("id"),
            Argument::createAnyTypeArgument("share_key"),
            Argument::createAnyTypeArgument("document_id"),
            Argument::createIntListTypeArgument("customer_id"),
            Argument::createIntListTypeArgument("groupe_id"),
            Argument::createBooleanOrBothTypeArgument("delete_file_after", BooleanOrBothType::ANY),
            Argument::createAnyTypeArgument("title"),
            Argument::createEnumListTypeArgument(
                "order",
                [
                    "id",
                    "id-reverse",
                    "share_key",
                    "share_key-reverse",
                    "document_id",
                    "document_id-reverse",
                    "customer_id",
                    "customer_id-reverse",
                    "groupe_id",
                    "groupe_id-reverse",
                    "date_end_share",
                    "date_end_share-reverse",
                    "date_last_download",
                    "date_last_download-reverse",
                    "delete_file_after",
                    "delete_file_after-reverse",
                    "title",
                    "title-reverse",
                    "description",
                    "description-reverse",
                    "chapo",
                    "chapo-reverse",
                    "postscriptum",
                    "postscriptum-reverse",
                ],
                "id"
            )
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        //Check old sharing
        $event = new DocumentsSharingEvent();
        $this->dispatcher->dispatch(DocumentsSharingEvents::CHECK, $event);

        $query = new DocumentsSharingQuery();
        $this->configureI18nProcessing($query, ["TITLE", "DESCRIPTION", "CHAPO", "POSTSCRIPTUM", ]);

        if (null !== $id = $this->getId()) {
            $query->filterById($id);
        }

        if (null !== $share_key = $this->getShareKey()) {
            $share_key = array_map("trim", explode(",", $share_key));
            $query->filterByShareKey($share_key);
        }

        if (null !== $document_id = $this->getDocumentId()) {
            $document_id = array_map("trim", explode(",", $document_id));
            $query->filterByDocumentId($document_id);
        }

        $search_group = array();

        if (null !== $customer_id = $this->getCustomerId()) {
            $query->filterByCustomerId($customer_id);
            foreach($customer_id as $search_customer_id) {
                $list_group = DocumentsSharingGroupeQuery::create()->filterByCustomerId('%'.$search_customer_id.'%', Criteria::LIKE)->find();
                if (count($list_group)) {
                    foreach ($list_group as $group) {
                        $search_group[] = $group->getId();
                    }
                }
            }
            $query->_or()->filterByGroupeId(implode(',',$search_group), Criteria::IN);
        }

        if (null !== $groupe_id = $this->getGroupeId()) {
            $query->filterByGroupeId($groupe_id);
        }

        if (BooleanOrBothType::ANY !== $delete_file_after = $this->getDeleteFileAfter()) {
            $query->filterByDeleteFileAfter($delete_file_after);
        }

        if (null !== $title = $this->getTitle()) {
            $title = array_map("trim", explode(",", $title));
            $query->filterByTitle($title);
        }

        foreach ($this->getOrder() as $order) {
            switch ($order) {
                case "id":
                    $query->orderById();
                    break;
                case "id-reverse":
                    $query->orderById(Criteria::DESC);
                    break;
                case "share_key":
                    $query->orderByShareKey();
                    break;
                case "share_key-reverse":
                    $query->orderByShareKey(Criteria::DESC);
                    break;
                case "document_id":
                    $query->orderByDocumentId();
                    break;
                case "document_id-reverse":
                    $query->orderByDocumentId(Criteria::DESC);
                    break;
                case "customer_id":
                    $query->orderByCustomerId();
                    break;
                case "customer_id-reverse":
                    $query->orderByCustomerId(Criteria::DESC);
                    break;
                case "groupe_id":
                    $query->orderByGroupeId();
                    break;
                case "groupe_id-reverse":
                    $query->orderByGroupeId(Criteria::DESC);
                    break;
                case "date_end_share":
                    $query->orderByDateEndShare();
                    break;
                case "date_end_share-reverse":
                    $query->orderByDateEndShare(Criteria::DESC);
                    break;
                case "date_last_download":
                    $query->orderByDateLastDownload();
                    break;
                case "date_last_download-reverse":
                    $query->orderByDateLastDownload(Criteria::DESC);
                    break;
                case "delete_file_after":
                    $query->orderByDeleteFileAfter();
                    break;
                case "delete_file_after-reverse":
                    $query->orderByDeleteFileAfter(Criteria::DESC);
                    break;
                case "title":
                    $query->addAscendingOrderByColumn("i18n_TITLE");
                    break;
                case "title-reverse":
                    $query->addDescendingOrderByColumn("i18n_TITLE");
                    break;
                case "description":
                    $query->addAscendingOrderByColumn("i18n_DESCRIPTION");
                    break;
                case "description-reverse":
                    $query->addDescendingOrderByColumn("i18n_DESCRIPTION");
                    break;
                case "chapo":
                    $query->addAscendingOrderByColumn("i18n_CHAPO");
                    break;
                case "chapo-reverse":
                    $query->addDescendingOrderByColumn("i18n_CHAPO");
                    break;
                case "postscriptum":
                    $query->addAscendingOrderByColumn("i18n_POSTSCRIPTUM");
                    break;
                case "postscriptum-reverse":
                    $query->addDescendingOrderByColumn("i18n_POSTSCRIPTUM");
                    break;
            }
        }

        return $query;
    }

    protected function addMoreResults(LoopResultRow $row, $entryObject)
    {
    }
}
