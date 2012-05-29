<?php
/**
 * Description of sitemap_generator
 *
 * @author P.Sellars
 */
class sitemap_generator {
    const limit = 50000;
    public static function Create(){
        $counter = self::get_count();
        $split = ($counter / self::limit);
        self::fetch_records($split);
		return $split;
    }
    /**
     * Get the total number of posts and pages to be displayed 
     */
    public function get_count(){
        global $wpdb;
        $sql = "SELECT count(ID) from `{$wpdb->prefix}posts` WHERE `post_status` = 'publish'";
        return $wpdb->get_var($sql);
    }
    public function fetch_records($split){
        global $wpdb;
        for($i = 0; $i <= $split; $i++){
            $lowLimit = ($i * self::limit);
            $sql = "SELECT left(post_modified_gmt,10) as `date`, guid FROM {$wpdb->prefix}posts WHERE `post_status` = 'publish' LIMIT {$lowLimit},".self::limit;
            $item = $wpdb->get_results($sql);
            self::create_sitemap($item, $i);
        }
    }
    public function create_sitemap($item, $sitemap){
        $output = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach($item as $myitem){
            $output .= "<url>
            <loc>{$myitem->guid}</loc>
            <lastmod>{$myitem->date}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
            </url>";
        }
        $output .= "</urlset>";
        self::public_sitemap($output, $sitemap);
    }
    public function public_sitemap($data, $sitemap){
        $dir = wp_upload_dir();
        file_put_contents($dir['basedir']."/sitemap{$sitemap}.xml", $data);
    }
}
?>
