<?php
/* File handles add/edit of fonts based on post parameters */
if (isset($_POST['sp-add-font'])) {
    //new font entry
    $font = array();
    if ($_POST['sp-font-type'] == 'google') {
        $font['type'] = 'google';
        $font['src'] = $_POST['sp-google-code'];
        $font['family'] = $_POST['sp-google-ff'];
    } else {
        //Cufon Upload
        //Move File to fonts/cufon/ dir
        $uploads_dir = dirname(__FILE__) . '/cufon';
        if ($_FILES["sp-cufon-file"]["error"] == 0) {
            $tmp_name = $_FILES["sp-cufon-file"]["tmp_name"];
            $name = $_FILES["sp-cufon-file"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
        }
        $font['type'] = 'cufon';
        $font['src'] = "cufon/$name";
        $fc = file_get_contents("$uploads_dir/$name");
        if (preg_match_all('/"font-family":"([a-zA-Z0-9\-\s\.]{5,5000})"/', $fc, $matches)) {
            $font['family'] = implode(',', $matches[1]);
        }
    }
    ?>
    <script type="text/javascript">
        var cfont=<?php echo json_encode($font) ?>;
        parent.sp_add_font(cfont);
    </script>
    <?php
}
if (isset($_POST['sp-edit-font'])) {
    //Update a Google Font
    $font = array();
    if ($_POST['sp-font-type'] == 'google') {
        $font['type'] = 'google';
        $font['src'] = $_POST['sp-google-code'];
        $font['family'] = $_POST['sp-google-ff'];
        $font['font_id']=$_POST['sp-edit-font'];
        ?>
        <script type="text/javascript">
            var cfont=<?php echo json_encode($font) ?>;
            parent.sp_edit_font(cfont);
        </script>
        <?php
    }
}
?>