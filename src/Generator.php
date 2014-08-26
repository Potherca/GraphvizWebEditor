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

    // @TODO: Get these values from config/cache
    public function getSupportedImageTypes()
    {
        static $aImageTypes;

        if ($aImageTypes === null) {

            $aResult = executeCommand('dot -T?');
            if (strpos($aResult['stderr'], 'Format: "?" not recognized') !== 0) {
                throw new \UnexpectedValueException('Could not get available file formats');
            } else {
                list($sPrefix1, $sPrefix2, $sList) = explode(':', $aResult['stderr']);
                $aImageTypes = explode(' ', trim($sList));
            }
        }

        return $aImageTypes;
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
