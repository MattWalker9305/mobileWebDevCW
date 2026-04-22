<?php
class HTMLPage
{
    private $_dir_css   = "";
    private $_dir_js    = "";
    private $_dir_img   = "";
    private $_dir_fonts = "";
    private $_dir_data  = "";

    private $_arr_js    = [];
    private $_arr_css   = [];

    private $_head_title     = "";
    private $_head_otherhtml = "";

    private $_body_content   = "";

    function __construct($ptitle)
    {
        $this->_head_title = $ptitle;
    }

    public function addScriptFile($pscriptfile)
    {
        $this->_arr_js[] = $pscriptfile;
    }

    public function addCSSFile($pcssfile)
    {
        $this->_arr_css[] = $pcssfile;
    }

    public function setCustomHead($pheadhtml)
    {
        $this->_head_otherhtml = $pheadhtml;
    }

    public function setDirCSS($pcsspath)
    {
        $this->_dir_css = $pcsspath;
    }

    public function setDirJS($pjspath)
    {
        $this->_dir_js = $pjspath;
    }

    public function setDirImages($pimgpath)
    {
        $this->_dir_img = $pimgpath;
    }

    public function setDirFonts($pfontpath)
    {
        $this->_dir_fonts = $pfontpath;
    }

    public function setDirData($pdatapath)
    {
        $this->_dir_data = $pdatapath;
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
{$this->createHTML_Head()}
{$this->createHTML_Body()}
</html>
HTML;
        return $thtmlmarkup;
    }

    public function setMediaDirectory($pcss,$pjs,$pfonts,$pimg,$pdata)
    {
        $this->setDirCSS($pcss);
        $this->setDirJS($pjs);
        $this->setDirFonts($pfonts);
        $this->setDirImages($pimg);
        $this->setDirData($pdata);
    }

    private function createHTML_Head()
{
    $thead = <<<HEAD
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$this->_head_title}</title>
    {$this->_head_otherhtml}
    <!-- Include External CSS -->
    {$this->createHTML_CSS()}
</head>
HEAD;
    return $thead;
}

    private function createHTML_CSS()
    {
        $thtml = "";
        $tpathcss = $this->toURLs($this->_arr_css,$this->_dir_css);
        foreach($tpathcss as $tcssfile)
        {
            $tcssmarkup = <<<SCRIPT
<link href="{$tcssfile}" rel="stylesheet">

SCRIPT;
            $thtml .=$tcssmarkup;
        }
        return $thtml;
    }

    private function createHTML_Body()
    {
        $this->createHTML_JS();
        $thtml = <<<BODY
<body>
    <!--PHP GENERATED PAGE CONTENT -->
    {$this->_body_content}

    <!-- EXTERNAL SCRIPTS -->
    {$this->createHTML_JS()}
</body>
BODY;
        return $thtml;
    }

    private function createHTML_JS()
    {
        $thtml = "";
        $tpathjs = $this->toURLs($this->_arr_js,$this->_dir_js);
        foreach($tpathjs as $tjsfile)
        {
        $tjsmarkup = <<<SCRIPT
<script src="{$tjsfile}"></script>

SCRIPT;
        $thtml .=$tjsmarkup;
        }
        return $thtml;
    }

    function toURLs(array &$parray,$ppath)
    {
        $tpatharray = [];
        foreach($parray as $tfile)
        {
            $tpatharray[] = "{$ppath}/{$tfile}";
        }
        return $tpatharray;
    }
}
?>
