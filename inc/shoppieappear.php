<?php
/**
 * Visual Editor
 * @package twistITVisualEditor
 */

/**
 * Class to handle visual editor
 * @package twistITVisualEditor
 * @author Unizoe Web Solutions.
 */
class shoppieAppear {

    /**
     * List of elements editable
     * @var SimpleXMLElement 
     */
    public $app_list;

    /**
     * Constructor, loads element list and convert it to SimpleXMLElement object.
     * @return void
     * @package twistITVisualEditor
     */
    function shoppieAppear() {
        $this->app_list = dirname(__FILE__) . "/elems.xml";
        $tmp = file_get_contents($this->app_list);
        $this->app_list = new SimpleXMLElement($tmp);
    }

    /**
     * Admin page for visual editor
     * @return void
     * @package twistITVisualEditor
     */
    function getAdminPage() {
        ?>
        <div class="update-nag">StyleIt is one of many features of <a href="http://unizoe.com/twist-it/">Twist It WordPress Theme</a>.</div>
        <div class="wrap">
        <?php if (function_exists('sp_admin_leftmenu')) {
            sp_admin_leftmenu();
        } ?>
            <div class="sp-admin-area">
                <div class="ti-admin-icon">l</div>
                <h2 class="sp-admin-title"><?php _e('Appearance Editor'); ?></h2>
                <p class="sp-form-row admin-area"><label for="sp-appear-dd"><?php _e('Choose an Element'); ?></label>
                    <select id="sp-appear-dd">
                        <option value="0"> - - <?php _e('Choose Elements'); ?> - -</option>
                        <?php
                        $i = 0;
                        foreach ($this->app_list->section as $section) {
                            ?>
                            <option value="0"> - - <?php echo _e($section['name']); ?> - - </option>

                            <?php
                            foreach ($section->element as $element) {
                                ?>
                                <option value="<?php echo $i; ?>"><?php _e($element['name']); ?></option>
                                <?php
                                $i++;
                            }
                        }
                        ?>

                    </select>
                </p>
                <div class="sp-hide sp-appear-forms">
                    <?php
                    $i = 0;
                    foreach ($this->app_list->section as $section) {
                        ?>
                                <?php
                                foreach ($section->element as $element) {
                                    ?><div id="sp-appear-<?php echo $i; ?>" class="sp-appearance">
                                <form method="post" class="sp-appear-form">
                                    <?php
                                    switch ($element['editor']) {
                                        case 'background':
                                            sp_admin_background(array('slug' => 'sp-bg-' . $element['selector'], 'name' => $element['name']));
                                            break;
                                        case 'font':
                                            sp_admin_font(array('slug' => 'sp-font-' . $element['selector'], 'name' => $element['name']));
                                            break;
                                        case 'both':
                                            sp_admin_font(array('slug' => 'sp-font-' . $element['selector'], 'class' => 'appearance-common ti-font-2c', 'name' => $element['name']));
                                            sp_admin_background(array('slug' => 'sp-bg-' . $element['selector'], 'class' => 'appearance-common ti-bg-2c', 'name' => $element['name']));
                                            break;
                                    }
                                    ?>
                                    <div class="sp-form-row"><label>&nbsp;</label><button class="button blue appearance-save"><i>w</i><?php _e('Save'); ?></button></div>

                                </form>
                            </div>
                            <?php
                            $i++;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Triggered at init by the framework
     * @return void
     * @package twistITVisualEditor
     */
    function updateAdminPage() {
        if (isset($_REQUEST['ti_update_appearance'])) {
            $this->ajaxUpdate();
            die();
        }
        if (isset($_REQUEST['get_font_iframe'])) {
            $this->getFontIframe();
            die();
        }
    }

    /**
     * Includes font iframe file
     * @return void
     */
    function getFontIframe() {
        require_once (dirname(__FILE__) . "/iframe-fonts.php");
    }

    /**
     * Updates appearance settings
     * 
     * This is a common function to update background and fonts of a defined element.
     * Handles : $_REQUEST['bg_elem'],$_REQUEST['bg'],$_REQUEST['font_elem'] and $_REQUEST['font']
     * @return void
     * @package twistITVisualEditor
     */
    function ajaxUpdate() {
        if (isset($_POST['bg_elem'])) {
            $bg = json_decode(stripcslashes($_POST['bg']));
            if (isset($bg->bg_img)) {
                $bg->bg_img_url = wp_get_attachment_url($bg->bg_img);
            }
            update_option($_POST['bg_elem'], $bg);
        }
        if (isset($_POST['font_elem'])) {
            $ti_fonts = get_option("sp-fonts", array());
            $font = json_decode(stripcslashes($_POST['font']));
            $font->font_desc = $ti_fonts[$font->font];
            update_option($_POST['font_elem'], $font);
        }
        print_r(get_option($_POST['bg_elem']));
        print_r(get_option($_POST['font_elem']));
        echo "ok";
    }

}
?>
