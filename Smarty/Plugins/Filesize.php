<?php


namespace DocumentsSharing\Smarty\Plugins;

use Thelia\Core\Template\Smarty\AbstractSmartyPlugin;
use Thelia\Core\Template\Smarty\SmartyPluginDescriptor;

class Filesize extends AbstractSmartyPlugin
{

    /**
     * Check if folder is associated to keyword code
     * @param $params
     * @return bool
     */
    public function file_filesize($params)
    {

        $size = max(0, (int)$params);
        $units = array( 'b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . $units[$power];

    }


    /**
     * @return an array of SmartyPluginDescriptor
     */
    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor("modifier", "filesize", $this, "file_filesize"),
        );
    }
}
