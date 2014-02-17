<?
if(defined('PROJECT_ROOT') === false) {
    define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
}

require_once(PROJECT_ROOT . '/generator.php')

// @TODO: This code should be split into separate files for separate concerns.

?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Graphviz</title>
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="application.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/prism/0.1/prism.css" />

    <script src="//cdn.jsdelivr.net/jquery/2.0.3/jquery-2.0.3.min.js"></script>
    <script src="//cdn.jsdelivr.net/ace/1.1.01/min/ace.js"></script>
    <script src="//cdn.jsdelivr.net/ace/1.1.01/min/ext-elastic_tabstops_lite.js"></script>
</head>

<body<?= $bError?' class="error"':''?>>
<!--
<h1>Generate Graphviz Graphs</h1>
-->
    <!-- @TODO: Add useful Graphviz links. Manual, attributes, shapes, etc. -->
    <!-- @TODO: Add button to switch layout of textarea/image from LR to TB -->
    <!-- @TODO: Improve Application styles (also, responsive...?) -->
    <form method="POST"  accept-charset="UTF-8">
        <input type="hidden" name="token" value="<?=$sToken?>" />
        <div name="graph" id="editor"><?=htmlspecialchars($sGraph)?></div>

        <button>
            Render graph in graphviz <br/><small><small><code>[ctrl] + [enter]</code></small></small>
        </button>
        <fieldset>
        <legend>options:</legend>
        <label><input type="checkbox" name="verbose" <?=($bVerbose?' checked="checked"':'')?>/> verbose</label>
        <label><input type="checkbox" name="show-previous" <?=($bShowPrevious?' checked="checked"':'')?>/> show previous graph</label>
        <label>
        <span>Image Format</span>
            <select name="image-type">
            <?foreach($aSupportedImageTypes as $sImageType):?>

                <option
                    value="<?=$sImageType?>"
                    <?=(isset($_POST['image-type']) && $sImageType === $_POST['image-type']?' selected="selected"':'')?>
              >
                    <?=$sImageType?></option>
            <?endforeach?>
            </select>
        </lable>
        </fieldset>
    </form>

    <div class="image-container">
        <?=$sGraphHtml?>
        <!-- @TODO: Replace precvious image HTML with one build in the generator -->
        <?=(isset($sPreviousToken)?'<img src="./file/' . $sPreviousToken .'.dot.png" class="previous-graph' . ($bShowPrevious?'':' hidden') .'"/>':'')?>
    </div>
    <footer>
        <a class="token" href="?token=<?=$sToken?>" target="_blank"><?=$sToken?></a>
        <pre class="output-console"><?=$sOutput?></pre>
    </footer>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/solarized_light");
    editor.getSession().setMode("ace/mode/dot");

    $('form').on("submit", function (p_oEvent) {
        $('.ace_text-input').attr('name','graph').val(editor.getSession().getValue());
    });

    $('input[name="show-previous"]').click(function(p_oEvent){
        $('.previous-graph').toggle('hidden');
    });

    $(document).on('keydown', function(e) {
        if(e.keyCode == 13 && (e.metaKey || e.ctrlKey)) {
            $('form').submit();
        }
    })

</script>
</body>
</html>
