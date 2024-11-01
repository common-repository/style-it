<?php 
/**
 * Font Editor
 * @package twistItFonts
 */
/**
 * Returns base url(in standalone execution)
 * @package twistItFonts
 */
function BaseURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) &&( $_SERVER["HTTPS"]== "on")) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 
 $cpageURL=explode('/',$pageURL);
 echo str_replace(end($cpageURL),'',$pageURL);
}
/**
 * Returns admin url(in standalone execution)
 * @package twistItFonts
 */
function AdminURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) &&( $_SERVER["HTTPS"]== "on")) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 
 $cpageURL=explode('/',$pageURL);
 $cbaseURL= str_replace(end($cpageURL),'',$pageURL);
 echo str_replace('fonts/', '', $cbaseURL);
}
?><!-- HTML for iframe -->
<html>
    <head>
        <title><?php echo (($_REQUEST['mode'] == "new") ? 'Add a New Font' : 'Edit Font'); ?></title>
        <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript" src="<?php BaseURL(); ?>res/admin-fonts.js"></script>
        <link rel="stylesheet" href="<?php AdminURL(); ?>css/styleit.css" type="text/css"/>
        <link rel="stylesheet" href="<?php BaseURL(); ?>res/font-admin.css" type="text/css"/>
    </head>
    <body>
        <form action="<?php BaseURL(); ?>font-upload.php" method="post" id="sp-admin-font-form" enctype="multipart/form-data">
            <h2 class="sp-font-form-title"><?php echo (($_REQUEST['mode'] == "new") ? 'Add a New Font' : 'Edit Font'); ?></h4>
            <p class="sp-form-row sp-cufon sp-google"><label for="font-type">Font Type</label>
                <span class="radio-box"><input type="radio" name="sp-font-type" value="google" class="curr-font-type sp-google-type"/><span>Google Font</span></span>
                <span class="radio-box"><input type="radio" name="sp-font-type" value="cufon" class="curr-font-type sp-cufon-type"/><span>Cufon Font</span></span>
                <span class="sp-clear"></span>
            </p>
            <p class="sp-form-row sp-cufon sp-hide">
                <label for="font-type">Font File</label>
                <input type="file" name="sp-cufon-file" class="sp-cufon-file" />
                <span class="sp-upload-url sp-hide"><?php BaseURL(); ?>cufon-upload.php</span>
            </p>
            <p class="sp-form-row sp-google sp-hide">
                <label for="font-type">Font Embed Code(Import/Standard)</label>
                <textarea name="sp-google-code" class="sp-google-code"></textarea>
            </p>
            <p class="sp-form-row sp-google sp-hide">
                <label for="font-type">Google Font Family</label>
                <input type="text" name="sp-google-ff" class="sp-google-ff"/>
            </p>
            <p class="sp-form-row row-submit">
                <label>&nbsp;</label><input type="submit" value="<?php echo (($_REQUEST['mode'] == "new") ? 'Add Font' : 'Save Font'); ?>"/>
                <!--set post flags-->
                <?php
                if($_REQUEST['mode']=='new'){?>
                <input type="hidden" name="sp-add-font" class="sp-add-font" value="1"/>
                <?php }else{ ?>
                    <input type="hidden" name="sp-edit-font" class="sp-edit-font" value="<?php echo $_REQUEST['mode'] ?>"/>
                <?php } ?>
               
            </p>
        </form>
    </body>
</html>