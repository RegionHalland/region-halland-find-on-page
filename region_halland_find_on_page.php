<?php

    /**
     * @package Region Halland Find on Page
     */
    /*
    Plugin Name: Region Halland Find on Page
    Description: Front-end-plugin som skapar en array för "Hitta på sidan"
    Version: 2.2.0
    Author: Roland Hydén
    License: GPL-3.0
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

        // Hämta länk-listor
        $myFields = get_field('name_1000199');

        // Om det finns några länk-listor
        if ($myFields) {

            // Loopa igenom alla länkar
            foreach ($myFields as $field) {
                
                // Länk url
                $strLinkUrl     = $field['name_1000201'];
                
                // Längden på url:en
                $lenLinkUrl     = strlen($strLinkUrl);
                
                // Kolla så att det faktiskt finns en länk
                if ($lenLinkUrl > 0 ) {

                    // Splitta länken på "/"
                    $arrUrl = explode("/",$strLinkUrl);
                    $countUrl = count($arrUrl);
                    
                    // välj post_name
                    $strPostName = $arrUrl[$countUrl-2];
                    
                    // Funktion som returnerar postID basterat på post_name
                    $postID = get_region_halland_find_on_page_post_id($strPostName);
                    
                    // Hämta hela posten
                    $post = get_post($postID);
                    
                    // Post title från posten
                    $postTitle = $post->post_title;
                    
                    // Kontrollera om det finns " - " i posten
                    $checkPosTitle = stripos($postTitle, " - ");
                    
                    // Om det finns " - ", använd bara texten före
                    // Om det INTE finns, använd hela titeln
                    if ($checkPosTitle > 0) {
                        $myArrTitle = explode(" - ", $postTitle);
                        $myTitle = $myArrTitle[0];
                    } else {
                        $myTitle = $postTitle;
                    }
                    
                    // Skapa en slug av titeln
                    $mySlug = region_halland_find_on_page_prepare_slug($myTitle);
                    $mySlug = trim($mySlug);
                    $mySlug = strtolower($mySlug);
                    $myPostSlug = $mySlug . "-" . $postID; 

                    // Skicka länktitel + slug till findonpage-arrayen
                    array_push($findOnPage, array(
                       'class' => "content-nav__item-level--2",
                       'tag' => "h2",
                       'slug' => $myPostSlug,
                       'content' => $myTitle
                    ));

                }
            }

        }

        // returnera findonpage-arrayen
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

    function get_region_halland_find_on_page_post_id($post_name) {
        
        // Databas connection
        global $wpdb; 

        // Select
        $sql = "";
        $sql .= "SELECT ID FROM wp_posts ";
        
        // Where
        $sql .= "WHERE ";
        $sql .= "post_type = 'linklists' ";
        $sql .= "AND ";
        $sql .= "post_status = 'publish' ";
        $sql .= "AND ";
        $sql .= "post_name = '$post_name' ";

        // Get result
        $arrID = $wpdb->get_row($sql, ARRAY_A);
        
        // Get ID from result
        $myID = intval($arrID['ID']);
        
        // Return id
        return $myID;
            
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