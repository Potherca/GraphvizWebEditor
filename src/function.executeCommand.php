<?php

/*
 * Because exec/sytem/etc. Are a bit lame in giving error feedback a workaround
 * is required. Instead of executing commands derictly, we open a stream, write
 * the command to the stream and read whatever comes back out of the pipes.
 *
 * For general info on Standard input (stdin), Standard output (stdout) and
 * Standard error (stderr) please visit:
 *      http://en.wikipedia.org/wiki/Standard_streams
 */
function executeCommand($p_sCommand, $p_sInput = '')
{
    $aPipeDescriptor = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w')
    );

    $rProcess = proc_open($p_sCommand, $aPipeDescriptor, $aPipes);

    fwrite($aPipes[0], $p_sInput);
    fclose($aPipes[0]);


    $sStandardOutput = stream_get_contents($aPipes[1]);
    fclose($aPipes[1]);

    $sStandardError = stream_get_contents($aPipes[2]);
    fclose($aPipes[2]);

    $iReturn=proc_close($rProcess);

    return array(
        'stdin'  => $p_sCommand
    , 'stdout' => $sStandardOutput
    , 'stderr' => $sStandardError
    , 'return' => $iReturn
    );
}

/*EOF*/
