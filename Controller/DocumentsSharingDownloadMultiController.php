<?php

namespace DocumentsSharing\Controller;

use DocumentsSharing\DocumentsSharing;
use DocumentsSharing\Event\Base\DocumentsSharingDocumentEvents;
use DocumentsSharing\Event\DocumentsSharingDocumentEvent;
use DocumentsSharing\Event\Module\DocumentsSharingEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\Exception\InvalidArgumentException;
use Thelia\Core\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Log\Tlog;
use Thelia\Tools\URL;
use DocumentsSharing\Event\DocumentsSharingEvent;
use DocumentsSharing\Model\DocumentsSharingDocumentQuery;
use DocumentsSharing\Model\DocumentsSharingQuery;
use DocumentsSharing\Model\DocumentsSharingGroupeQuery;
use DocumentsSharing\Model\Map\DocumentsSharingTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Model\ConfigQuery;


/**
 * Class DocumentsSharingDownloadMultiController
 * @package DocumentsSharing\Controller
 * @author TheliaStudio
 */
class DocumentsSharingDownloadMultiController extends BaseFrontController
{
    protected $searchFileSharing = null;
    protected $sharing = null;
    protected $log = null;


    public function downloadMultiAction(Request $request, $file_key)
    {
        $customer = $this->getSecurityContext()->getCustomerUser();

        if($customer) {

            $customerId = $customer->getId();
            $this->getLog()->addInfo(
                $this->getTranslator()->trans("GET fileKey:%file_key   CustomerId:%customerId IP:%customerIp",
                    ['%file_key' => $file_key, '%customerId' => $customerId, '%customerIp' => $request->getClientIp()]
                )
            );

            $searchFileSharing = DocumentsSharingDocumentQuery::create()->findByFileKey($file_key);
            if(count($searchFileSharing)) {
                $fileSharing = array();
                foreach ($searchFileSharing as $file) {
                    $fileSharing = [
                        'Id'    =>  $file->getId(),
                        'File'  =>  $file->getFile(),
                        ];
                }

                $search_group = array();
                $list_group = DocumentsSharingGroupeQuery::create()->filterByCustomerId('%'.$customerId.'%', Criteria::LIKE)->find();
                if (count($list_group)) {
                    foreach ($list_group as $group) {
                        $search_group[] = $group->getId();
                    }
                }

                $sharing = DocumentsSharingQuery::create()->orderByDateEndShare(Criteria::ASC)->filterByDocumentId('%'.$fileSharing['Id'].'%', Criteria::LIKE)->filterByCustomerId($customerId)->_or()->filterByGroupeId(implode(',',$search_group), Criteria::IN)->find();//DocumentsSharingTableMap::GROUPE_ID, implode(',',$search_group), Criteria::IN)->find();
                if (count($sharing)) {
                    $sharingShare = array();
                    $downloadState = true;
                    foreach ($sharing as $share) {

                        $dateEnd = new \DateTime(date_format($share->getDateEndShare(), 'Y-m-d H:i:s'));
                        $dateNow = new \DateTime();
                        if ($dateEnd < $dateNow) {
                            $this->getLog()->addInfo(
                                $this->getTranslator()->trans("INFO DateEndShare:%dateEnd  Now:%dateNow  Sharing expired",
                                    ['%dateEnd' => date_format($share->getDateEndShare(), 'Y-m-d H:i:s'), '%dateNow' => date_format($dateNow, 'Y-m-d H:i:s')]
                                )
                            );

                            $event = new DocumentsSharingEvent();
                            $event->setId($share->getId());
                            $this->getDispatcher()->dispatch(DocumentsSharingEvents::DOCUMENTS_SHARING_CHECK, $event);

                            $downloadState = false;

                        } else {
                            $this->getLog()->addInfo(
                                $this->getTranslator()->trans("INFO Start Download File:%file",
                                    ['%file' => $fileSharing['File']]
                                )
                            );

                            $dwnfile = urldecode(preg_replace("/^h(.*?)(upload)\//", '',$fileSharing['File']));

                            $filename = str_replace('/',DS,$dwnfile);
                            $download = $this->downloadFile($filename);
                            if (!$download) {
                                $this->getLog()->addInfo(
                                    $this->getTranslator()->trans("ERROR unknown error!")
                                );

                                $downloadState = false;
                            }

                            $updateSharing = DocumentsSharingQuery::create()->findPk($share->getId());
                            $updateSharing->setDateLastDownload($dateNow)->save();
                        }
                    }
                    if($downloadState){
                        exit;
                    }
                }
            }
            $this->getLog()->addInfo(
                $this->getTranslator()->trans("ERROR Sharing not found !")
            );

            return $this->render('404');
        }

        $this->getLog()->addInfo(
            $this->getTranslator()->trans("ERROR fileKey:%file_key   CustomerId:not logged IP:%customerIp",
                ['%file_key' => $file_key, '%customerIp' => $request->getClientIp()]
            )
        );

        return $this->render('login');

    }

    protected function downloadFile($filename)
    {
        $download = false;
        if($filename) {
            $folder = THELIA_WEB_DIR.'media'.DS.'documentssharing'.DS.'upload';
            $source_filepath = $folder.DS.$filename;
            if(file_exists($source_filepath)) {
                $mineType = mime_content_type($source_filepath);
                $size = filesize($source_filepath);

                header("Content-disposition: attachment; filename=\"" . $filename . "\"");
                header("Content-Type: $mineType");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . $size);
                header("Pragma: no-cache");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
                header("Expires: 0");
                ob_end_clean();
                flush();
                readfile($source_filepath);

                $download = true;
            }
        }
        return $download;
    }

    protected function getLog()
    {
        if ($this->log == null) {
            $this->log = Tlog::getNewInstance();

            $logFilePath = $this->getLogFilePath();

            $this->log->setPrefix("#LEVEL: #DATE #HOUR: ");
            $this->log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
            $this->log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, $logFilePath);
            $this->log->setLevel(Tlog::INFO);
        }

        return $this->log;
    }

    /**
     * @return string The path to the module's log file.
     */
    protected function getLogFilePath()
    {
        return sprintf(THELIA_ROOT."log".DS."%s.log", DocumentsSharing::MESSAGE_DOMAIN);
    }
}