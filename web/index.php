<?php
namespace Potherca;

use Potherca\GraphvizWebEditor\Generator;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

set_error_handler(
    function ($p_iError, $p_sError, $p_sFile, $p_iLine ) {
        throw new \ErrorException($p_sError, $p_iError, 0, $p_sFile, $p_iLine);
    }
);

if(defined('PROJECT_ROOT') === false) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
}

require_once(PROJECT_ROOT . '/vendor/autoload.php');

$sFileStorePath = PROJECT_ROOT . '/local_file_storage';
$sPreviousToken = null;

$oFilesystem = new Filesystem(new Adapter($sFileStorePath));

if(isset($_GET['file'])){
    $sStoredFile = $_GET['file'];

    if($oFilesystem->has($sStoredFile)){
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
if(isset($_POST['graph'])) {
    $sGraph = $_POST['graph'];
} else {
    $sGraph = '';
}

if(isset($_POST['verbose'])) {
    $bVerbose = true;
} else {
    $bVerbose = false;
}

if(isset($_GET['token'])) {
    $sToken = $_GET['token'];
}

if(isset($_POST['token'])) {
    $sPreviousToken = $_POST['token'];
}

if(isset($_POST['show-previous']) === false) {
    $bShowPrevious = false;
} else {
    $bShowPrevious = true;
}

if(isset($_POST['image-type']) && in_array($_POST['image-type'], Generator::$aSupportedImageTypes)) {
    $sImageType = $_POST['image-type'];
} else {
    $sImageType = 'svg';
}

$sExtension = '.' . $sImageType;

/*
$oGenerator = new Generator();
$oGenerator->setVerbose($_POST['verbose']);
$oGenerator->setFileSystem($oFilesystem);
$bGenerated = $oGenerator->generateGraph($_POST['graph']);

if ($bGenerated === true) {
    $oGenerator->getLog();
} else {
    $oGenerator->getErrors();
}

$oGenerator->getSupportedImageTypes();
//*/

require_once(PROJECT_ROOT . '/src/generator_logic.php');

// @TODO: Logic and presentation need to be separated from the Template
include PROJECT_ROOT . '/src/index.template.html';

//EOF