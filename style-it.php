<?php

/*
  Plugin Name: Style It
  Plugin URI: http://kumarz.in/jlayer-wp/
  Description: Style It is Open Source Release of xml powered appearance editor in Twist It Wordpress theme. Style It supports Google Webfonts API and cufon fonts.
  Version: 1.0
  Author: Unizoe Web Solutions
  Author URI: http://unizoe.com/wp/
  Copyright 2012  Unizoe Web Solutions.  (email : unizoews@gmail.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 * Includes and integrates main elements rendering class
 * @package StyleIt 
 */
require_once(dirname(__FILE__) . '/inc/admin-elems.php');

/**
 * Includes and integrates main appearance class
 * @see shoppieAppear
 * @package StyleIt 
 */
require_once(dirname(__FILE__) . '/inc/shoppieappear.php');
/**
 * Object of appearance Class
 * @see shoppieAppear
 * @global mixed $styleit_appear
 * @package jLayerWP 
 */
global $styleit_appear;
$styleit_appear = new shoppieAppear();
/**
 * Includes and integrates main font class
 * @see fontManager
 * @package StyleIt 
 */
require_once(dirname(__FILE__) . '/inc/fontmanager.php');
/**
 * Object of jLayer parallax class
 * @see LayerSlider
 * @global mixed $styleit_font 
 * @package jLayerWP 
 */
global $styleit_font;
$styleit_font = new fontManager();

/**
 * Hooks into wordpress init action
 * @package jLayerWP 
 */
add_action("init", "styleItWPInit");

/**
 * Starts the update action of the jLayer Parallax Class 
 * @see LayerSlider::updateAdminPage()
 * @return void
 * @package styleIt 
 */
function styleItWPInit() {
    global $styleit_appear, $styleit_font;
    $styleit_appear->updateAdminPage();
    $styleit_font->updateAdminPage();
}

add_action("admin_menu", 'styleItWPMenu');

/**
 * Creates WordPress menu
 * @package styleIt 
 * @return void
 */
function styleItWPMenu() {
    global $styleit_appear, $styleit_font;
    $appear_page = add_submenu_page('themes.php', 'StyleIt Editor', 'StyleIt Editor', 'manage_options', 'styleit-editor', array($styleit_appear, 'getAdminPage'));
    $page = add_submenu_page('themes.php', 'StyleIt Fonts', 'StyleIt Fonts', 'manage_options', 'styleit-fonts', array($styleit_font, 'getAdminPage'));
    add_action('admin_print_styles-' . $page, 'styleItWPRes');
    add_action('admin_print_styles-' . $appear_page, 'styleItWPRes');
}

/**
 * Includes css and js files in WordPress admin panel
 * @package styleIt 
 * @return void
 */
function styleItWPRes() {
    wp_enqueue_style('styleit-admin', plugins_url('css/styleit.css', __FILE__));
    wp_enqueue_style('jquery-ui-custom', plugins_url('css/jquery-ui-1.8.21.custom.css', __FILE__));
    wp_enqueue_style('styleit-font-icons', plugins_url('css/symbols/stylesheet.css', __FILE__));
    wp_enqueue_style(array('media-upload', 'thickbox','farbtastic'));
    wp_enqueue_script(array('jquery', 'media-upload', 'thickbox', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable','jquery-ui-slider','farbtastic'));
    wp_enqueue_script('jquery-tiptip', plugins_url('js/jquery.tipTip.minified.js', __FILE__));
    wp_enqueue_script('jquery-json', plugins_url('js/json.js', __FILE__));
    wp_enqueue_script('cufon-yui', plugins_url('fonts/cufon-yui.js', __FILE__));
    wp_enqueue_script('jquery-prompt', plugins_url( 'js/jquery-impromptu.4.0.min.js' , __FILE__ ));
    wp_enqueue_script('styleit-admin', plugins_url('js/admin.js', __FILE__));
}
/**
 * Font HTML Description
 * 
 * prints all available fonts to be used within </head> tag
 * @package twistItAdmin
 * @param bool $admin if to generate font preview styles
 * @return void
 */
if (!function_exists('sp_font_head')) {

    function sp_font_head($admin) {
        $fonts = get_option('sp-fonts', array());
        //variables
        $google_files = array();
        $cufon_files = array();
        foreach ($fonts as $font) {
            if ($font['type'] == "google") {
                if (!in_array($font['src'], $google_files)) {
                    $google_files[] = $font['src'];
                }
            } else {
                if (!in_array($font['src'], $cufon_files)) {
                    $cufon_files[] = $font['src'];
                }
            }
        }
        /* Load Google Files */
        foreach ($google_files as $file) {
            ?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css"/><?php
        }
        /* Load Cufon Files */
        ?><script type="text/javascript" src="<?php echo plugins_url('fonts/cufon-yui.js', __FILE__); ?>"></script><?php
        foreach ($cufon_files as $file) {
            ?> <script type="text/javascript" src="<?php echo plugins_url('fonts/', __FILE__).$file; ?>"></script> <?php
        }
        $google_str = "";
        $cufon_str = "";
        /* check if is admin page, load selectors as needed */
        if ($admin) {
            $i=0;
            foreach ($fonts as $key => $font) {
                if ($font['type'] == "google") {
                    $google_str.='#sa-pre-' . $font['type'] . '-' . $i . '{font-family:' . stripcslashes($font['family']) . ';}';
                } else {
                    $cufon_str.='Cufon.replace("#sa-pre-' . $font['type'] . '-' . $i . '", { fontFamily: "' . $font['family'] . '" });';
                    //$cufon_str.='Cufon.replace("#sa-pre-' . $font['type'] . '-' . $key . '", {hover:true });';
                }
                $i++;
            }
            ?>
            <style type="text/css">
            <?php echo $google_str; ?>
            </style>
            <script type="text/javascript">
            <?php echo $cufon_str; ?>
            </script>
            <?php
        }
    }
}
/*
 * Font Rendering module
 * @package twistItAdmin
 */
add_action("wp_head", "ti_font_render");
/**
 * Renders fonts and custom css
 * 
 * Prints fonts and custom styles.
 * @package twistItAdmin
 * @return void
 */
function ti_font_render() {
    sp_font_head(false);
    $tmp = file_get_contents(dirname(__FILE__) . "/inc/elems.xml");
    $app_list = new SimpleXMLElement($tmp);
    $css = "/*Dynamic CSS*/\n";
    $js = "/*Dynamic JS*/\n";
    foreach ($app_list->section as $section) {
        foreach ($section->element as $element) {
            $slug = 'sp-bg-' . $element['selector'];
            $data = get_option('sp-' . md5($slug), array());
            if (!empty($data)) {
                $str.=$element['selector'] . "{\n";
                $str.=((isset($data->bg_img_url)) ? 'background-image:url(' . $data->bg_img_url . ');' . "\n" : '');
                $str.=((isset($data->bg_repeat)) ? 'background-repeat:' . $data->bg_repeat . ';' . "\n" : '');
                $str.=((isset($data->bg_color)) ? 'background-color:#' . $data->bg_color . ';' . "\n" : '');
                $str.=((isset($data->posX) && isset($data->posY) && isset($data->bgUnit)) ? 'background-position:' . $data->posX . $data->bgUnit . ' ' . $data->posY . $data->bgUnit . ';' . "\n" : '');
                $str.="}\n";
                $css .= $str;
            }
            $slug = 'sp-font-' . $element['selector'];
            $data = get_option('sp-' . md5($slug), array());
            if (!empty($data)) {
                if ($data->font_desc['type'] == "cufon") {
                    $str = "Cufon.replace('" . $element['selector'] . "',{
                'fontFamily':'" . $data->font_desc['family'] . "'
            });\n";
                    $js .= $str;
                    $str=$element['selector'] . "{\n";
                    $str.='color:'.$data->color.";\n";
                    $str.='font-size:'.$data->size."px;\n";
                    $str.="}\n";
                    $css .= $str;
                } else {
                    //goes to css
                    $str=$element['selector'] . "{\n";
                    $str.='color:'.$data->color.";\n";
                    $str.='font-family:'.stripslashes($data->font_desc['family']).";\n";
                    $str.='font-size:'.$data->size."px;\n";
                    $str.="}\n";
                    $css .= $str;
                }
            }
        }
    }
    ?>
<style type="text/css">
    <?php echo $css; ?>
</style>
<script type="text/javascript">
    <?php echo $js; ?>
</script>
<?php
//    print_r($css);
//    print_r($js);
}
add_action('admin_head', 'styleit_admin_head');

/**
 * Checks and loads fonts for fonts editor
 * @package twistItAdmin
 * @return void
 */
function styleit_admin_head() {
    if (isset($_REQUEST['page'])) {
        if ($_REQUEST['page'] == "styleit-fonts") {
            sp_font_head(true);
        }
        sp_font_head(false);
    }
}

?>
