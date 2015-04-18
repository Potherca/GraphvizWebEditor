<?php
namespace Potherca\GraphvizWebEditor;

class Process
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var string */
    private $m_sCommand;
    /** @var string */
    private $m_sInput;
    /** @var string */
    private $m_sOutput;
    /** @var string */
    private $m_sErrorOutput;
    /** @var string */
    private $m_sExitCode;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return string
     */
    public function getInput()
    {
        return $this->m_sInput;
    }

    /**
     * @param string $p_sInput
     */
    public function setInput($p_sInput)
    {
        $this->m_sInput = $p_sInput;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->m_sOutput;
    }

    /**
     * @return string
     */
    public function getErrorOutput()
    {
        return $this->m_sErrorOutput;
    }

    /**
     * @return string
     */
    public function getExitCode()
    {
        return $this->m_sExitCode;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    public function __construct($p_sCommand)
    {
        $this->m_sCommand = $p_sCommand;
    }

    public function run()
    {
        return $this->executeCommand($this->m_sCommand, $this->m_sInput);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * Because exec/sytem/etc. Are a bit lame in giving error feedback a workaround
     * is required. Instead of executing commands derictly, we open a stream, write
     * the command to the stream and read whatever comes back out of the pipes.
     *
     * For general info on Standard input (stdin), Standard output (stdout) and
     * Standard error (stderr) please visit:
     *      http://en.wikipedia.org/wiki/Standard_streams
     *
     * @param string $p_sCommand
     * @param string $p_sInput
     *
     * @return array
     */
    private function executeCommand($p_sCommand, $p_sInput = '')
    {
        $aPipeDescriptor = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );

        $rProcess = proc_open($p_sCommand, $aPipeDescriptor, $aPipes);

        fwrite($aPipes[0], $p_sInput);
        fclose($aPipes[0]);

        $sOutput = stream_get_contents($aPipes[1]);
        fclose($aPipes[1]);

        $sErrorOutput = stream_get_contents($aPipes[2]);
        fclose($aPipes[2]);

        $iExitCode = proc_close($rProcess);

        $this->m_sOutput = $sOutput;
        $this->m_sErrorOutput = $sErrorOutput;
        $this->m_sExitCode = $iExitCode;

        return $iExitCode;
    }
}
/*EOF*/
