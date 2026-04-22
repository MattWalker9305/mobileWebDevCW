<?php

require_once("pageObject.php");
define('BASE_PATH', dirname(__DIR__, 2));

class MasterPage
{

    private $_htmlpage;     
    
    private $_regions = [];

    public function setRegion($name, $content)
    {
        $this->_regions[$name] = $content;
    }

    public function getRegion($name)
    {
        return $this->_regions[$name] ?? "";
    }  

    function __construct($ptitle)
    {
        $this->_htmlpage = new WebPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults();
    }
    
    public function getPage(): WebPage { return $this->_htmlpage; }
                   
    public function createPage()
    {
       $this->setMasterContent();
       return $this->_htmlpage->createPage();
    }
    
    public function renderPage()
    {
       $this->setMasterContent();
       $this->_htmlpage->renderPage();
    }
    
    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }
    
    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }
    
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("/mobileWebDevCW/css",
                                            "/mobileWebDevCW/js",
                                            "/mobileWebDevCW/fonts",
                                            "/mobileWebDevCW/img",
                                            "/mobileWebDevCW/data");
        $this->_htmlpage->setCustomHead('
            <link href="/mobileWebDevCW/css/bootstrap.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="/mobileWebDevCW/js/bootstrap.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
        ');
        $this->addCSSFile("site.css");       
    }
    
    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");

        $this->setRegion('top', "");
        $this->setRegion('main', "");
        $this->setRegion('footer', <<<FOOTER
    <div class="fl-footer-terms">
        <span>&copy; {$tcurryear} Matthew Walker - LJMU</span>
        <ul>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="terms.php">Terms of Service</a></li>
        </ul>
    </div>
    FOOTER);
    }
    
    private function setMasterContent()
    {
        $tauth = "";
        if(isset($_SESSION["myuser"]))
        {
            $tuser = htmlspecialchars($_SESSION["myname"] ?? $_SESSION["myuser"]);
            $thome = "dashboard.php";
            $tcurrent = basename($_SERVER['PHP_SELF']);
            $active_dashboard = $tcurrent === "dashboard.php" ? " active" : "";
            $active_transactions = $tcurrent === "transactions.php" ? " active" : "";
            $active_categories = $tcurrent === "categories.php" ? " active" : "";
            $active_reports   = $tcurrent === "reports.php"   ? " active" : "";
            $active_profile   = $tcurrent === "profile.php"   ? " active" : "";

            $tnav = <<<NAV
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link{$active_dashboard}" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link{$active_transactions}" href="transactions.php">Transactions</a></li>
                    <li class="nav-item"><a class="nav-link{$active_categories}" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link{$active_reports}" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link{$active_profile}" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php?action=exit">Logout</a></li>
                </ul>
                <span class="navbar-text ms-3">Signed in as <strong>{$tuser}</strong></span>

    NAV;
        }
        else
        {
            $thome = "index.php";

            $tcurrent = basename($_SERVER['PHP_SELF']);
            $active_login   = $tcurrent === "login.php"   ? " active" : "";

            $tnav = <<<NAV
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link{$active_login}" href="login.php">Login</a></li>
                </ul>
    NAV;
        }



        $tmasterpage = <<<MASTER
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{$thome}">FinTrack</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                {$tnav}
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="py-4">
            {$this->getRegion('top')}
        </div>
        <div class="row">
            {$this->getRegion('main')}
        </div>
        <footer class="border-top mt-4 py-3 text-muted">
            {$this->getRegion('footer')}
        </footer>
    </div>
    MASTER;

        $this->_htmlpage->setBodyContent($tmasterpage);
    }
}
?>