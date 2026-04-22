<?php
class WebPage
{
    private $_paths = [
        "css" => "",
        "js" => "",
        "img" => "",
        "fonts" => "",
        "data" => ""
    ];

    private $_assets = [
        "css" => [],
        "js" => []
    ];

    private $_head_title     = "";
    private $_head_otherhtml = "";

    private $_body_content   = "";

    function __construct($ptitle)
    {
        $this->_head_title = $ptitle;
    }

    public function setPath($type, $path) {
        $this->_paths[$type] = $path;
    }

    public function addAsset($type, $file) {
        $this->_assets[$type][] = $file;
    }

    public function setCustomHead($pheadhtml)
    {
        $this->_head_otherhtml = $pheadhtml;
    }

    public function setBodyContent($pbodycontent)
    {
        $this->_body_content = $pbodycontent;
    }

    public function renderPage()
    {
        echo $this->createPage();
    }

    public function createPage()
    {
            $thtmlmarkup = <<<HTML
<!DOCTYPE html>
<html lang="en">
{$this->buildHead()}
{$this->buildBody()}
</html>
HTML;
        return $thtmlmarkup;
    }

    public function setMediaDirectory($pcss,$pjs,$pfonts,$pimg,$pdata)
    {
        $this->setPath('css', $pcss);
        $this->setPath('js', $pjs);
        $this->setPath('fonts', $pfonts);
        $this->setPath('img', $pimg);
        $this->setPath('data', $pdata);
    }

    public function addCSSFile($pcssfile)
    {
        $this->addAsset('css', $pcssfile);
    }

    public function addScriptFile($pjsfile)
    {
        $this->addAsset('js', $pjsfile);
    }

    private function buildHead()
{
    $thead = <<<HEAD
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$this->_head_title}</title>
    {$this->_head_otherhtml}
    <!-- Include External CSS -->
    {$this->renderStyles()}
</head>
HEAD;
    return $thead;
}

    private function renderStyles()
    {
        $thtml = "";
        $tpathcss = $this->buildPaths($this->_assets['css'], $this->_paths['css']);
        foreach($tpathcss as $tcssfile)
        {
            $tcssmarkup = <<<SCRIPT
<link href="{$tcssfile}" rel="stylesheet">

SCRIPT;
            $thtml .=$tcssmarkup;
        }
        return $thtml;
    }

    private function buildBody()
    {
        $thtml = <<<BODY
<body>
    <!--PHP GENERATED PAGE CONTENT -->
    {$this->_body_content}

    <!-- EXTERNAL SCRIPTS -->
    {$this->renderScripts()}
</body>
BODY;
        return $thtml;
    }

    private function renderScripts()
    {
        $thtml = "";
        $tpathjs = $this->buildPaths($this->_assets['js'], $this->_paths['js']);
        foreach($tpathjs as $tjsfile)
        {
        $tjsmarkup = <<<SCRIPT
<script src="{$tjsfile}"></script>

SCRIPT;
        $thtml .=$tjsmarkup;
        }
        return $thtml;
    }

    private function buildPaths($files, $basePath)
    {
    return array_map(fn($f) => "{$basePath}/{$f}", $files);
    }
}
?>
