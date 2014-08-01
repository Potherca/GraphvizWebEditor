<?php
namespace Potherca\GraphvizWebEditor;

class Generator
{
    protected $sOutput = '';
    protected $bError = false;
    protected $bVerbose = false;
    protected $sGraph = '';

    // @TODO: Get these values from config/cache
    static public $aSupportedImageTypes = array('svg');
};
