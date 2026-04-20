<?php

//Include our HTML Page Class
require_once("oo_page.inc.php");

class MasterPage
{
    //-------FIELD MEMBERS----------------------------------------
    private $_htmlpage;     //Holds our Custom Instance of an HTML Page
    private $_dynamic_1;    //Field Representing our Dynamic Content #1
    private $_dynamic_2;    //Field Representing our Dynamic Content #2
    private $_dynamic_3;    //Field Representing our Dynamic Content #3
    private $_player_ids;
    
    //-------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults(); 
        $this->_player_ids = [3,7,8,9,10,11,14];
    }
    
    //-------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic1() { return $this->_dynamic_1; }
    public function getDynamic2() { return $this->_dynamic_2; } 
    public function getDynamic3() { return $this->_dynamic_3; }
    public function setDynamic1($phtml) { $this->_dynamic_1 = $phtml; }
    public function setDynamic2($phtml) { $this->_dynamic_2 = $phtml; } 
    public function setDynamic3($phtml) { $this->_dynamic_3 = $phtml; }
    public function getPage(): HTMLPage { return $this->_htmlpage; } 
    
    //-------PUBLIC FUNCTIONS-------------------------------------
                   
    public function createPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Return the HTML Page..
       return $this->_htmlpage->createPage();
    }
    
    public function renderPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Echo the page immediately.
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
    
    //-------PRIVATE FUNCTIONS-----------------------------------    
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("/mobileWebDevCW/css",
                                            "/mobileWebDevCW/js",
                                            "/mobileWebDevCW/fonts",
                                            "/mobileWebDevCW/img",
                                            "/mobileWebDevCW/data");
        $this->_htmlpage->setCustomHead('
            <link href="/mobileWebDevCW/css/bootstrap.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        ');
        $this->addCSSFile("site.css");       
    }
    
    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");
        
        $tuser = isset($_SESSION["myname"]) ? $_SESSION["myname"] : null;

            $this->_dynamic_1 = "";
            $this->_dynamic_2 = "";
            $this->_dynamic_3 = <<<FOOTER
    <div class="fl-footer-terms">
        <span>&copy; {$tcurryear} Matthew Walker &mdash; LJMU</span>
        <ul>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="terms.php">Terms of Service</a></li>
        </ul>
    </div>
    FOOTER;
        
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
                    <li class="nav-item"><a class="nav-link" href="app_exit.php?action=exit">Logout</a></li>
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
            {$this->_dynamic_1}
        </div>
        <div class="row">
            {$this->_dynamic_2}
        </div>
        <footer class="border-top mt-4 py-3 text-muted">
            {$this->_dynamic_3}
        </footer>
    </div>
    MASTER;

        $this->_htmlpage->setBodyContent($tmasterpage);
    }
}
?>