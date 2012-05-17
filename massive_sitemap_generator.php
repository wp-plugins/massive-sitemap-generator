<?php
/*
Plugin Name: Massive Sitemap Generator
Plugin URI: http://plugins.svn.wordpress.org/massive-sitemap-generator/
Description: Create sitemaps for websites that have over 50,000 pages and posts to be indexed by Google Webmaster Tools
Version: 0.1.1
Author: thepauleh
Author URI: http://paulsellars.com
 */

include("sitemap_generator.php");
/**
 * @author P.Sellars
 */
class massive_sitemap_generator {
    private static $_instance;
    private function __construct(){
        if($_GET['GO']){
            sitemap_generator::Create();
        }
        echo $this->show_admin_panel();
    }
    public static function Create(){
        if(!isset(self::$_instance)){
                $className = __CLASS__;
                self::$_instance = new $className;
            }
        return self::$_instance;
    }
    public static function get_menu(){
    add_options_page( 'Massive Sitemap Generator', 'Massive Sitemap Generator', 'manage_options', 'massive-sitemap-generator', array('massive_sitemap_generator', 'Create'));
    }
    /**
     * Display the admin panel 
     */
    private function show_admin_panel(){
        $dir = wp_upload_dir();
        $output = "<h2>Massive Sitemap Generator</h2>
            <p>
            Your Upload Directory is: ".$dir['basedir']."<br /><br />
            Please ensure this is writeable by the webserver.<br /><br />
            Files will be placed here by the name of sitemap0.xml, incrementing in the style of
            sitemap1.xml.<br />
            </p>";
        if(!$_GET['GO']){
            $output .= "<p>
            Ready? Press Go to run the generator.
            <form action='' method='GET'>
            <input type='hidden' name='page' value='massive-sitemap-generator' />
            <input type='hidden' name='GO' value='true' />
            <input type='submit' value='Go!' /></p>";
        }
        else{
            $output .= "<b>Thankyou, the sitemap generator has been executed.</b>";
        }
        return $output;
    }
}
add_action( 'admin_menu', array('massive_sitemap_generator','get_menu') );
?>
