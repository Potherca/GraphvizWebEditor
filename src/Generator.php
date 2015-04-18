<?php
namespace Potherca\GraphvizWebEditor;

use League\Flysystem\FilesystemInterface;

class Generator
{
    protected $sOutput = '';
    protected $bError = false;
    protected $bVerbose = false;
    protected $sGraph = '';
    /**
     * @var FilesystemInterface
     */
    protected $oFilesystem;

    public function getSupportedImageTypes($p_aSupportedTypes)
    {
        static $aSupportedImageTypes;

        if ($aSupportedImageTypes === null) {
            $aImageTypes = array(
                'apng',
                'bmp',
                'gif',
                'jpg',
                'jpeg',
                'mng',
                /*'pdf', /* Requires special treament in the HTML to be displayed*/
                'png',
                'svg',
                'tiff',
                'xbm',
                'xr',
            );

            $aTextTypes = array(
                'plain',
                'plain-ext',
            );

            $aUnknowTypes = array(
                'canon',
                'cmap',
                'cmapx',
                'cmapx_np',
                'dot',
                'eps',
                'fig',
                'gd',
                'gd2',
                'gv',
                'imap',
                'imap_np',
                'ismap',
                'jpe',
                'pdf',
                'ps',
                'ps2',
                'svgz',
                'tk',
                'vml',
                'vmlz',
                'vrml',
                'wbmp',
                'x11',
                'xdot',
                'xlib',
            );
            $aSupportedImageTypes = array_intersect($aImageTypes, $p_aSupportedTypes);
        }

        return $aSupportedImageTypes;
    }

    // @TODO: Get these values from config/cache created at build time...
    public function getSupportedOutputTypes($p_sOutput)
    {
        static $aOutputTypes;

        if ($aOutputTypes === null) {
            if (strpos($p_sOutput, 'Format: "?" not recognized') !== 0) {
                throw new \UnexpectedValueException('Could not get available file formats');
            } else {
                list($sPrefix1, $sPrefix2, $sList) = explode(':', $p_sOutput);
                $aOutputTypes = explode(' ', trim($sList));
            }
        }

        return $aOutputTypes;
    }

    /**
     * @return boolean
     */
    public function getVerbose()
    {
        return $this->bVerbose;
    }

    /**
     * @param boolean $p_bVerbose
     */
    public function setVerbose($p_bVerbose)
    {
        $this->bVerbose = $p_bVerbose;
    }

    /**
     * @return FilesystemInterface
     */
    public function getFilesystem()
    {
        return $this->oFilesystem;
    }

    /**
     * @param FilesystemInterface $p_oFilesystem
     */
    public function setFilesystem($p_oFilesystem)
    {
        $this->oFilesystem = $p_oFilesystem;
    }
}

/*EOF*/
