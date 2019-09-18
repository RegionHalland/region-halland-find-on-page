<?php

    /**
     * @package Region Halland Find on Page
     */
    /*
    Plugin Name: Region Halland Find on Page
    Description: Front-end-plugin som skapar en array för "Hitta på sidan"
    Version: 2.1.0
    Author: Roland Hydén
    License: MIT
    Text Domain: regionhalland
    */

    // Returna array med find-on-page-data
    function get_region_halland_find_on_page() {
        
        // Wordpress funktion för aktuell post
        global $post;

        // Set variable
        $pageContent = $post->post_content;
        
        // Explode content vua PHP_EOL
        $tmpPageContents = explode(PHP_EOL, $pageContent);
        $findOnPage = array();
        $countX = 1;
        foreach ($tmpPageContents as $tmpPageContent) {
            $tmpValue = $tmpPageContent;
            if (strlen($tmpValue) != 0) {
                if (substr($tmpValue,1,2) == 'h2' || substr($tmpValue,1,2) == 'h3' || substr($tmpValue,1,2) == 'h4') {
                    $tmpContent = str_replace("<h2>","",$tmpValue);
                    $tmpContent = str_replace("</h2>","",$tmpContent);
                    $tmpContent = str_replace("<h3>","",$tmpContent);
                    $tmpContent = str_replace("</h3>","",$tmpContent);
                    $tmpContent = str_replace("<h4>","",$tmpContent);
                    $tmpContent = str_replace("</h4>","",$tmpContent);
                    $toSlug = region_halland_find_on_page_prepare_slug($tmpContent);
                    $toSlug = trim($toSlug);
                    $slug = strtolower($toSlug);
                    $slugWithNumber = $slug . "-" . $countX; 
                    array_push($findOnPage, array(
                       'class' => "content-nav__item-level--" . substr($tmpValue,2,1),
                       'tag' => substr($tmpValue,1,2),
                       'slug' => $slugWithNumber,
                       'content' => $tmpContent
                    ));
                    $countX++;
                }
            }
        }

        return $findOnPage;

    }

    // Funktion för att rensa bort oönskade tecken
    function region_halland_find_on_page_prepare_slug($content) {

        $tmpSlug = str_replace(" ","-",$content);
        $tmpSlug = str_replace("(","",$tmpSlug);
        $tmpSlug = str_replace(")","",$tmpSlug);
        $tmpSlug = str_replace("?","",$tmpSlug);
        $tmpSlug = str_replace("!","",$tmpSlug);
        $tmpSlug = str_replace(",","",$tmpSlug);
        $tmpSlug = str_replace("/","",$tmpSlug);
        $tmpSlug = str_replace("Ö","o",$tmpSlug);
        $tmpSlug = str_replace("ö","o",$tmpSlug);
        $tmpSlug = str_replace("Ä","a",$tmpSlug);
        $tmpSlug = str_replace("ä","a",$tmpSlug);
        $tmpSlug = str_replace("Å","a",$tmpSlug);
        $tmpSlug = str_replace("å","a",$tmpSlug);
        $tmpSlug = strtolower($tmpSlug);

        // Returnera rensad slug
        return $tmpSlug;
                    
    }

    // Metod som anropas när pluginen aktiveras
    function region_halland_find_on_page_activate() {
        // ingenting just nu...
    }

    // Metod som anropas när pluginen avaktiveras
    function region_halland_find_on_page_deactivate() {
        // ingenting just nu...
    }
    
    // Vilken metod som ska anropas när pluginen aktiveras
    register_activation_hook( __FILE__, 'region_halland_find_on_page_activate');

    // Vilken metod som ska anropas när pluginen avaktiveras
    register_deactivation_hook( __FILE__, 'region_halland_find_on_page_deactivate');

?>