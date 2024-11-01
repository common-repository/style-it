<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Background selector
 * 
 * prints a background selector
 * <code>
 * sp_admin_background(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_background')) {

    function sp_admin_background($data) {
        ?>
        <div class="sp-admin-bg <?php echo (isset($data['class']) ? $data['class'] : ''); ?>">
            <h3><span class="ti-icon ti-icon-float">c</span><?php _e('Background'); ?></h3>
            <p class="sp-form-row">
                <label><?php _e('Background Image'); ?></label>
                <!-- Check if Img has to be displayed -->
                <input class="sp-bg-img" type="hidden"/>
                <button class="sp-bg-upload button-secondary"><?php _e('Upload Image'); ?></button>
            </p>
            <p class="sp-form-row">
                <label><?php _e('Background Color'); ?></label>
                <input class="sp-bg-color sp-bg-color-elem"  type="text"/>
                <span class="sp-bg-picker"></span>
            </p>
            <p class="sp-form-row">
                <label><?php _e('Background Repear'); ?></label>
                <select class="sp-bg-repeat">
                    <option value="0"><?php _e('Repeat'); ?></option>
                    <option value="repeat-y"><?php _e('Repeat Vertical');?></option>
                    <option value="repeat-x"><?php _e('Repeat Horizontal');?></option>
                </select>
            </p>
            <div class="sp-bg-pos">
                <h4><?php _e('Background Position'); ?></h4>
                <p class="sp-form-row">
                    <label><?php _e('Background Position X axis'); ?></label>
                    <input class="sp-bg-pos-x sp-range"  type="text" value="65"/>
                    <input type="hidden" class="sp-range-val" value='<?php echo json_encode(array("max" => 100, "min" => 0)); ?>'/>
                </p>
                <p class="sp-form-row">
                    <label><?php _e('Background Position Y axis'); ?></label>
                    <input class="sp-bg-pos-y sp-range"  type="text" value="35"/>
                    <input type="hidden" class="sp-range-val" value='<?php echo json_encode(array("max" => 100, "min" => 0)); ?>'/>
                </p>
                <p class="sp-form-row">
                    <label><?php _e('Background Position Unit'); ?></label>
                    <select class="sp-bg-unit">
                        <option value="px"><?php _e('In'); ?> Px</option>
                        <option value="em"><?php _e('In'); ?> Em</option>
                        <option value="%"><?php _e('In'); ?> %</option>
                    </select>
                </p>
            </div>
            <input type="hidden" name="sp-<?php echo md5($data['slug']); ?>" class="sp-bg-value" value='<?php echo stripslashes(json_encode((object) get_option("sp-" . md5($data['slug']), array()))); ?>'/>
        </div>
        <?php
    }

}
/**
 * Input Text
 * 
 * prints a input text.
 * <code>
 * sp_admin_text(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists("sp_admin_text")) {

    function sp_admin_text($data) {
        ?>
        <p class="sp-form-row">
            <label for="sp-<?php echo $data['slug']; ?>"><?php _e($data['name']); ?></label>
            <input type="text" class="sp-input" id="sp-<?php echo $data['slug']; ?>" name="sp-<?php echo md5($data['slug']); ?>" value="<?php echo((isset($data['value'])) ? $data['value'] : get_option('sp-' . md5($data['slug']), '')); ?>"/>
            <span class="desc-icon"><i title="<?php _e($data['msg']); ?>">B</i></span><span class="sp-clear"></span></p>
        <?php
    }

}

/**
 * Textarea
 * 
 * prints a textarea.
 * <code>
 * sp_admin_textarea(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_textarea')) {

    function sp_admin_textarea($data) {
        ?>
        <p class="sp-form-row">
            <label for="sp-<?php echo $data['slug']; ?>"><?php _e($data['name']); ?></label>
            <textarea class="sp-input" id="sp-<?php echo $data['slug']; ?>" name="sp-<?php echo md5($data['slug']); ?>"><?php echo((isset($data['value'])) ? $data['value'] : get_option('sp-' . md5($data['slug']), '')); ?></textarea>
            <span class="desc-icon"><i title="<?php _e($data['msg']); ?>">B</i></span><span class="sp-clear"></span></p>
        <?php
    }

}
/**
 * Input Checkbox
 * 
 * prints a input checkbox.
 * <code>
 * sp_admin_checkbox(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_checkbox')) {

    function sp_admin_checkbox($data) {
        ?>
        <p class="sp-form-row">
            <label for="sp-<?php echo $data['slug']; ?>"><?php _e($data['name']); ?></label>
            <input type="checkbox" class="sp-input" id="sp-<?php echo $data['slug']; ?>" name="sp-<?php echo md5($data['slug']); ?>" <?php echo((get_option('sp-' . md5($data['slug']), '')) ? 'checked="true"' : ''); ?>/>
            <span class="desc-icon"><i title="<?php _e($data['msg']); ?>">B</i></span><span class="sp-clear"></span></p>
        <?php
    }

}
/**
 * Input Radio
 * 
 * prints a input radio.
 * <code>
 * sp_admin_radio(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_radio')) {

    function sp_admin_radio($data) {
        ?>
        <p class = "sp-form-row">
            <label><?php _e($data['name']) ?></label>
        <?php foreach ($data['options'] as $option) { ?>
                <span class = "radio-box"><input type = "radio" name="sp-<?php echo md5($data['slug']); ?>" value = "<?php echo $option['value']; ?>" class = "curr-font-type sp-<?php echo $option['value']; ?>-type"/><span><?php echo $option['name']; ?></span></span>
        <?php } ?>
            <span class = "sp-clear"></span>
        </p>
        <?php
    }

}
/**
 * Image Selector
 * 
 * Image selector using WordPress's media upload
 * <code>
 * sp_admin_imgfield(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_imgfield')) {

    function sp_admin_imgfield($data) {
        ?>
        <p class="sp-form-row">
            <label><?php _e($data['name']); ?></label>
            <!-- Check if Img has to be displayed -->
            <span class="bg-area">
                <input class="sp-img-id" type="hidden" name="sp-<?php echo md5($data['slug']); ?>"/>
                <button class="sp-img-upload button-secondary"><?php _e('Upload Image'); ?></button>
            </span>
            <span class="sp-clear"></span>
        </p>
        <?php
    }

}
/**
 * layerslider
 * 
 * prints a dropdown of layersliders
 * <code>
 * sp_admin_layerslider(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_layerslider')) {

    function sp_admin_layerslider($data) {
        ?>
        <p class="sp-form-row">
            <label><?php _e($data['name']); ?></label>
            <!-- Check if Img has to be displayed -->
            <?php
                print_r(get_option('ti_ls_sliders'));
            ?>
            <span class="sp-clear"></span>
        </p>
        <?php
    }

}
/**
 * Font editor
 * 
 * prints a font editor.
 * <code>
 * sp_admin_font(array('slug' => 'slug-of-element', 'name' => 'Name of Element')) ;
 * </code>
 * @package twistItAdmin
 * @param array $data data for form element
 * @return void
 */
if (!function_exists('sp_admin_font')) {

    function sp_admin_font($data, $val = array()) {
        ?>
        <div class="sp-admin-font <?php echo (isset($data['class']) ? $data['class'] : ''); ?>">
            <h3><span class="ti-icon ti-icon-float">r</span><?php _e('Font'); ?></h3>
            <p class="sp-form-row">
                <label><?php _e('Font Family'); ?></label>
                <!-- Check if Img has to be displayed -->
                <input class="sp-font-ff" type="hidden" />
                <a class="sp-font-choose button-secondary thickbox" href="themes.php?page=styleit-editor&get_font_iframe=1&mode=new&KeepThis=true&TB_iframe=true&height=200&width=600"><?php _e('Select a Font'); ?></a>
            </p>
            <p class="sp-form-row">
                <label><?php _e('Font Size(px)'); ?></label>
                <input class="sp-font-size sp-range"  type="text" value="18"/>
                <input type="hidden" class="sp-range-val" value='<?php echo json_encode(array("max" => 200, "min" => 0)); ?>'/>
            </p>
            <p class="sp-form-row">
                <label><?php _e('Font Color'); ?></label>
                <input class="sp-font-color"  type="text"/>
                <span class="sp-font-picker"></span>

            </p>
            <?php print_r(get_option("sp-" . md5($data['slug']), array())); ?>
            <input type="hidden" name="sp-<?php echo md5($data['slug']); ?>" value='<?php echo stripslashes(json_encode((object) get_option("sp-" . md5($data['slug']), array()))); ?>' class="sp-font-val"/>
        </div>
        <?php
    }

}
/**
 * Title
 * 
 * prints a title.
 * @package twistItAdmin
 * @param mixed $data data for title in SimpleXMLElement format
 * @return void
 */
if (!function_exists('sp_admin_title')) {

    function sp_admin_title($title) {
        ?>
        <h<?php echo $title['size']; ?>><?php echo $title[0]; ?></h<?php echo $title['size']; ?>>
        <?php
    }

}
?>
