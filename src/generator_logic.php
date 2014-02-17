<?php
namespace Potherca\GraphvizWebEditor;

// $oFilesystem is currently set from index.php
/** @var $oFilesystem Filesystem */

// $sFileStorePath is currently set from index.php
use League\Flysystem\Filesystem;

$bRedirect = false;
$bError = false;
$sOutput = '';

// @FIXME: I think the functionality is clear. Time to clean this code into separate classes.

    if (isset($sToken)){
        if ($sGraph === '') {
            // Retrieve existing graph
            $sFile = $sToken . '.dot';
            $sGraph = $oFilesystem->read($sFile);
       } else {
            // Create new graph and redirect to it
            $sToken = md5($sGraph);
            $sFile = $sToken . '.dot';
            if($oFilesystem->has($sToken . '.dot') === false) {
                $oFilesystem->write($sFile, $sGraph);
            }
            $bRedirect = true;
        }
    } else {
        // @TODO: This is another IO hit we don't need. Maybe better hard-code the value?
        if($sGraph === ''){
            $sGraph = file_get_contents(PROJECT_ROOT . '/example.dot');
        }

        $sToken = md5($sGraph);
        $sFile = $sToken . '.dot';

        try {
            if($oFilesystem->has($sToken . '.dot') === false) {
                $oFilesystem->write($sFile, $sGraph);
            }
        } catch(\ErrorException $eError){
            $sOutput .= $eError;
            $bError = true;
        }
    }

    $sImageFile = $sFile . $sExtension;

    $sGraphHtml = '<a href="./?file=' . $sImageFile . '" target="_blank"><img src="./?file=' . $sToken . '.dot' . $sExtension . '" /></a>';

    if($oFilesystem->has($sImageFile) === true) {
        $sOutput .= 'File already exists';
        // @TODO: Read out log and add to $sOutput
    } else {
        if($oFilesystem->has($sFile) === false) {
            // Store graph
            try {
                $bStored = $oFilesystem->put($sFile, $sGraph);
                if($bStored === false){
                    $bError = true;
                    $sOutput .= 'Could not store graph "' . $sFile . '"';
                }
            } catch(\Exception $eAny){
                $bError = true;
                $sOutput .= $eAny->getMessage();
            }
        }

        if($bError === false) {
            $aResult = array();
            $sFlags =
                  ($bVerbose?' -v':'')
                . ' -T' . $sImageType .' '              // Output Type
                //. ' -o "' . $sFile . $sExtension . '"'  // Output File
                //. ' "' . $sFile . '"'                   // Input File
            ;

            try {
                $aResult = executeCommand('dot ' . $sFlags, $sGraph);
                $sGraphImage = $aResult['stdout'];

                $bStored = $oFilesystem->put($sImageFile, $sGraphImage);
                if($bStored === false){
                    $bError = true;
                    $sOutput .= 'Could not store graph image "' . $sImageFile . '"';
                }

                $sOutput .= $aResult['stderr'];
            } catch(\Exception $eAny){
                $bError = true;
                $sOutput .= $eAny->getMessage();
                $aResult['return'] = 256;
            }

            if($aResult['return'] > 0 && $oFilesystem->has($sImageFile) === false){
                $bError = true;
            }
        }

        if($bError === true){
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
    }


/*
 * Because exec/sytem/etc. Are a bit lame in giving error feedback a workaround
 * is required. Instead of executing commands derictly, we open a stream, write
 * the command to the stream and read whatever comes back out of the pipes.
 *
 * For general info on Standard input (stdin), Standard output (stdout) and
 * Standard error (stderr) please visit:
 *      http://en.wikipedia.org/wiki/Standard_streams
 */
function executeCommand($p_sCommand, $p_sInput='') {

    $proc = proc_open(
        $p_sCommand
        , array(
              0 => array('pipe', 'r')
            , 1 => array('pipe', 'w')
            , 2 => array('pipe', 'w'))
        , $aPipes
    );

    fwrite($aPipes[0], $p_sInput);
    fclose($aPipes[0]);


    $sStandardOutput = stream_get_contents($aPipes[1]);
    fclose($aPipes[1]);

    $sStandardError = stream_get_contents($aPipes[2]);
    fclose($aPipes[2]);

    $iReturn=proc_close($proc);

    return array(
          'stdin'  => $p_sCommand
        , 'stdout' => $sStandardOutput
        , 'stderr' => $sStandardError
        , 'return' => $iReturn
    );
}

#EOF