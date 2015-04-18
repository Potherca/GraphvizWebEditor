<?php
namespace Potherca;

use Potherca\GraphvizWebEditor\Generator;
use Potherca\GraphvizWebEditor\Process;

set_error_handler(
    function ($p_iError, $p_sError, $p_sFile, $p_iLine) {
        throw new \ErrorException($p_sError, $p_iError, 0, $p_sFile, $p_iLine);
    }
);

if (defined('PROJECT_ROOT') === false) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
}

require_once(PROJECT_ROOT . '/vendor/autoload.php');
require_once(PROJECT_ROOT . '/src/class.Process.php');

$sFileStorePath = PROJECT_ROOT . '/local_file_storage';
$sPreviousToken = null;

// @FIXME: FlySystem class needs to be wrapped in an application class e.g. Potherca\Filesystem->$m_oFilesystem
$oFilesystem = //new Filesystem();
    new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local($sFileStorePath));


$oProcess = new Process('dot -T?');
$oProcess->run();
$sOutput = $oProcess->getErrorOutput();

$oGenerator = new Generator();
$oGenerator->setFileSystem($oFilesystem);
$aSupportedOutputTypes = $oGenerator->getSupportedOutputTypes($sOutput);
$aSupportedImageTypes = $oGenerator->getSupportedImageTypes($aSupportedOutputTypes);

if (isset($_GET['file'])) {
    $sStoredFile = $_GET['file'];

    if ($oFilesystem->has($sStoredFile)) {
        $sMimeType = $oFilesystem->getMimetype($sStoredFile);
        $sFileContents = $oFilesystem->read($sStoredFile);
        header('Content-Type: ' . $sMimeType);
        echo $sFileContents;
        exit;
    } else {
        // @TODO: Get/guess image format, output/generate error image
        header('Content-Type: text/plain');
        echo 'Could not find file "' . $sStoredFile . '"';
        exit;
    }
}

// @FIXME: Sanitize user input!
if (isset($_POST['graph'])) {
    $sGraph = $_POST['graph'];
} else {
    $sGraph = '';
}

if (isset($_POST['verbose'])) {
    $bVerbose = true;
} else {
    $bVerbose = false;
}
$oGenerator->setVerbose($bVerbose);


if (isset($_GET['token'])) {
    $sToken = $_GET['token'];
}

if (isset($_POST['token'])) {
    $sPreviousToken = $_POST['token'];
}

if (isset($_POST['show-previous']) === false) {
    $bShowPrevious = false;
} else {
    $bShowPrevious = true;
}

if (isset($_POST['image-type']) && in_array($_POST['image-type'], $aSupportedImageTypes)) {
    $sImageType = $_POST['image-type'];
} else {
    /* @FIXME: Use better way of grabbing default image format.
     * AAAaAaAAaAARGH! Hardcoding stuff? Are you bat-shit-crazy? :-/
     */
    $sImageType = 'svg';
}

$sExtension = '.' . $sImageType;

/*
$bGenerated = $oGenerator->generateGraph($_POST['graph']);

if ($bGenerated === true) {
    $oGenerator->getLog();
} else {
    $oGenerator->getErrors();
}

$oGenerator->getSupportedOutputTypes();
//*/

/* @FIXME: Don't use generator_logic.php file.
 * Have you seen that file? Oh god, just shoot me now and get it done with...
 */
require_once(PROJECT_ROOT . '/src/generator_logic.php');

// @TODO: Logic and presentation need to be separated from the Template
include PROJECT_ROOT . '/src/index.template.html';

/*EOF*/
