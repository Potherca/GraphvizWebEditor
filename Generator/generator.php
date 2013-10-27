<?php
    set_error_handler(
        function ($errno, $errstr, $errfile, $errline ) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
    );

    $sOutput = '';
    $bError = false;
    $bVerbose = false;
    $bShowPrevious = true;
    $sGraph = <<<'DOT'
digraph MyCoolGraph {
    graph [
        compound = true     // To clip the head at the cluster border
        dpi = 200
        penwidth = 2        // Make the cluster's borders a bit thicker
        rankdir = "LR"
        ranksep = 1         // Add a bit more space inbetween nodes
    ]

    node [
        fontname = "Bitstream Vera Sans"
        shape = "record"
    ]

    edge [
    ]

    a -> b
}
DOT;
    
    // @FIXME: Sanitize user input!
    if(isset($_POST['graph'])) {
        $sGraph = $_POST['graph'];
    }
    
    if(isset($_POST['verbose'])) {
        $bVerbose = true;
    }
    
    if(isset($_POST['token'])) {
        $sPreviousToken = $_POST['token'];
    }
    
    if(isset($_POST['show-previous']) === false) {
        $bShowPrevious = false;
    }
    
    $sToken = md5($sGraph);
    $sFile = __DIR__ . '/file/' . $sToken . '.dot';
    $sGraphHtml = '<img src="./file/' . $sToken . '.dot.png" />';

    if(file_exists($sFile . '.png') === true) {
        $sOutput = 'File already exists';
    } else {
        if(file_exists($sFile) === false) {
            try {
                file_put_contents($sFile, $sGraph);
            } catch(\Exception $eAny){
                $bError = true;
                $sOutput = $eAny->getMessage();
            }
        }
        
        if($bError === false) {
            $aResult = array();
            $sFlags = 
                  ($bVerbose?' -v':'')
                . ' -Tpng '                     // Output Type
                . ' -o "' . $sFile . '.png"'    // Output File
                . ' "' . $sFile . '"'           // Input File
            ;

            try {
                $aResult = executeCommand('dot ' . $sFlags, $sGraph);
                $sOutput .= $aResult['stdout'];
                $sOutput .= $aResult['stderr'];
            } catch(\Exception $eAny){
                $bError = true;
                $sOutput = $eAny->getMessage();
                $aResult['return'] = 256;
            }

            if($aResult['return'] > 0){
                $bError = true;
            }
        }
        
        if($bError === true){
            $sToken = 'Error!';
            $sGraphHtml = '';
        }
    }
    $sOutput = str_replace(__DIR__, '', $sOutput);

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
