<?php

namespace Potherca\GraphvizWebEditor;

/** The following variables are currently set from index.php:
 * @var $bVerbose bool
 * @var $oFilesystem Filesystem
 * @var $sExtension string
 * @var $sFileStorePath string
 * @var $sGraph string
 * @var $sImageType string
 */

// $sFileStorePath is currently set from index.php
use League\Flysystem\Filesystem;

$bRedirect = false;
$bError = false;
$sOutput = '';

/* @FIXME: I think the functionality is clear. Time to clean this code into separate classes.
 * Yeah, and which classes should that be, Mr Genius? Hmmm?
 */
if (isset($sToken)) {
    if ($sGraph === '') {
        // Retrieve existing graph
        $sFile = $sToken . '.dot';
        $sGraph = $oFilesystem->read($sFile);
    } else {
        // Create new graph and redirect to it
        $sToken = md5($sGraph);
        $sFile = $sToken . '.dot';
        if ($oFilesystem->has($sToken . '.dot') === false) {
            $oFilesystem->write($sFile, $sGraph);
        }
        $bRedirect = true;
    }
} else {
    // @TODO: This is another IO hit we don't need. Maybe better hard-code the value?
    if ($sGraph === '') {
        $sGraph = file_get_contents(PROJECT_ROOT . '/example.dot');
    }

    $sToken = md5($sGraph);
    $sFile = $sToken . '.dot';

    try {
        if ($oFilesystem->has($sToken . '.dot') === false) {
            $oFilesystem->write($sFile, $sGraph);
        }
    } catch (\ErrorException $eError) {
        $sOutput .= $eError;
        $bError = true;
    }
}

$sImageFile = $sFile . $sExtension;

$sGraphHtml =
      '<a href="./?file=' . $sImageFile . '" target="_blank">'
    . '<img src="./?file=' . $sToken . '.dot' . $sExtension . '" />'
    . '</a>'
;

if ($oFilesystem->has($sImageFile) === true) {
    $sOutput .= 'File already exists';
    // @TODO: Read out log and add to $sOutput
} else {
    if ($oFilesystem->has($sFile) === false) {
        // Store graph
        try {
            $bStored = $oFilesystem->put($sFile, $sGraph);
            if ($bStored === false) {
                $bError = true;
                $sOutput .= 'Could not store graph "' . $sFile . '"';
            }
        } catch (\Exception $eAny) {
            $bError = true;
            $sOutput .= $eAny->getMessage();
        }
    }

    if ($bError === false) {
        $sFlags =
              ($bVerbose?' -v':'')
            . ' -T' . $sImageType .' '              // Output Type
            //. ' -o "' . $sFile . $sExtension . '"'  // Output File
            //. ' "' . $sFile . '"'                   // Input File
        ;

        try {
            $oProcess = new Process('dot ' . $sFlags);
            $oProcess->setInput($sGraph);
            $oProcess->run();
            $sGraphImage = $oProcess->getOutput();

            $bStored = $oFilesystem->put($sImageFile, $sGraphImage);
            if ($bStored === false) {
                $bError = true;
                $sOutput .= 'Could not store graph image "' . $sImageFile . '"';
            }

            $sOutput .= $oProcess->getErrorOutput();
        } catch (\Exception $eAny) {
            $bError = true;
            $sOutput .= $eAny->getMessage();
        }

        if ($oProcess->getExitCode() > 0 && $oFilesystem->has($sImageFile) === false) {
            $bError = true;
        }
    }

    if ($bError === true) {
        $sToken = 'Error!';
        $sGraphHtml = '';
    }
}

if ($bRedirect === true) {
    $sUrl = $_SERVER['REQUEST_URI'];
    $iQueryPosition = strpos($sUrl, '?');
    if ($iQueryPosition === false) {
        $sUrl .= '?token=' . $sToken;
    } else {
        $sUrl = substr_replace($sUrl, '?token=' . $sToken, $iQueryPosition);
    }

    header("Location: " . $sUrl);
    die;
} else {
    $sOutput = str_replace($sFileStorePath.'/', '', $sOutput);
//    $sOutput = htmlspecialchars($sOutput);
}

/*EOF*/
