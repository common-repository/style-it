<?php
/*
 * Font Editor
 * @package twistItFonts
 */

/**
 * Class to manage font editing
 * @package twistItFonts
 * @author Unizoe Web Solutions
 */
class fontManager {

    /**
     * all stored font
     * @var array
     */
    public $sp_fonts;

    /**
     * all cufon font families
     * @var array 
     */
    public $cufon_ff;

    /**
     * constructor, initializes all variables
     * @return void
     * @package twistItFonts
     */
    public function fontManager() {
        $this->sp_fonts = get_option("sp-fonts", array());
        foreach ($this->sp_fonts as $font) {
            if ($font['type'] == 'cufon') {
                $this->cufon_ff[] = $font['family'];
            }
        }
    }

    /**
     * Prints admin page
     * @return void
     * @package twistItFonts
     */
    public function getAdminPage() {
        ?>
        <div class="update-nag">StyleIt is one of many features of <a href="http://unizoe.com/twist-it/">Twist It WordPress Theme</a>.</div>

        <div class="wrap">
            <?php if (function_exists('sp_admin_leftmenu')) {
                sp_admin_leftmenu();
            } ?>
            <div class="sp-admin-area">
                <div class="ti-admin-icon">s</div>
                <h2 class="sp-admin-title"><?php _e('Fonts'); ?> <a href="<?php echo plugins_url('fonts/font-form.php', dirname(__FILE__)); ?>?mode=new&KeepThis=true&TB_iframe=true&height=200&width=600" class="add-new-h2 thickbox"><?php _e('Add New'); ?></a> </h2>
                <div class="admin-area">
                    <?php
                    $page = 1;
                    if (isset($_REQUEST['ti_page'])) {
                        $page = $_REQUEST['ti_page'];
                    }
                    $items = 3;
                    $total = count($this->sp_fonts);
                    $pages = ceil($total / $items);
                    $this->sp_fonts = array_slice($this->sp_fonts, ($items * ($page - 1)), $items);
                    if ($pages > 1) {
                        ?>
                        <div class="pagination"><em>Page <?php echo $page . ' of ' . $pages; ?> </em><ul>
                                <?php
                                for ($i = 0; $i < $pages; $i++) {
                                    echo '<a href="themes.php?page=styleit-fonts&ti_page=' . ($i + 1) . '" class="' . ((($i + 1) == $page) ? 'currentpage' : '') . '">' . ($i + 1) . '</a>';
                                }
                                ?>
                            </ul></div>
                        <?php
                    }
                    ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th><?php _e('Type'); ?></th>
                                <th><?php _e('Name'); ?></th>
                                <th class="sp-tb-center"><?php _e('Preview'); ?></th>
                                <th><?php _e('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><?php _e('Type'); ?></th>
                                <th><?php _e('Name'); ?></th>
                                <th class="sp-tb-center"><?php _e('Preview'); ?></th>
                                <th><?php _e('Actions'); ?></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $key = $items * ($page - 1);
                            foreach ($this->sp_fonts as $font) {
                                ?>
                                <tr>
                                    <td><?php echo ucwords($font['type']); ?></td>
                                    <td><?php echo stripslashes($font['family']); ?></td>
                                    <td><span class="sp-font-preview" id="sa-pre-<?php echo $font['type'] . '-' . $key; ?>">The quick brown fox jumps over the lazy dog</span></td>
                                    <td>
                                        <?php if ($font['type'] == "google") { ?>
                                            <a href="<?php echo plugins_url('fonts/font-form.php', dirname(__FILE__)) ?>?mode=<?php echo $key; ?>&KeepThis=true&TB_iframe=true&height=200&width=600" class="sp-admin-link thickbox"><span class="ti-icon ti-icon-il">C</span><?php _e('Edit'); ?></a>
            <?php } ?>
                                        <a href="#font-<?php echo $key; ?>" class="sp-admin-link sa-delete-font"><span class="ti-icon ti-icon-il">x</span><?php _e('Delete') ?></a>
                                    </td>
                                </tr>
                                <?php
                                $key++;
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Triggered at init by the framework
     * @return void
     * @package twistItFonts
     */
    public function updateAdminPage() {
        if (isset($_REQUEST['sp_add_font'])) {
            $this->addFont();
            die();
        }
        if (isset($_REQUEST['sp_get_font'])) {
            $this->getFont($_REQUEST['sp_get_font']);
            die();
        }
        if (isset($_REQUEST['sp_edit_font'])) {
            $this->editFont($_REQUEST['sp_get_font']);
            die();
        }
        if (isset($_REQUEST['sp_delete_font'])) {
            $this->deleteFont($_REQUEST['sp_delete_font']);
            die();
        }
    }

    /**
     * Adds a font to system
     *
     * Requires $_REQUEST['type'],$_REQUEST['family'],$_REQUEST['src']
     * @return void
     * @package twistItFonts
     */
    public function addFont() {
        if ($_REQUEST['type'] == "cufon") {
            $families = explode(',', $_REQUEST['family']);
            foreach ($families as $family) {
                if (!in_array($family, $this->cufon_ff)) {
                    $this->sp_fonts[] = array('type' => $_REQUEST['type'], 'src' => $_REQUEST['src'], 'family' => $family);
                }
            }
        } else {
            $family = str_replace("'", '"', $_REQUEST['family']);
            $this->sp_fonts[] = array('type' => $_REQUEST['type'], 'src' => $_REQUEST['src'], 'family' => stripslashes($family));
        }
        update_option('sp-fonts', $this->sp_fonts);
    }

    /**
     * Prints a json encoded font by id
     * @param int $key Id of the font
     * @return void
     * @package twistItFonts
     */
    public function getFont($key) {
        $font = $this->sp_fonts[$key];
        $font['family'] = stripslashes($font['family']);
        echo json_encode($font);
    }

    /**
     * Update a font to system
     *
     * Requires $_REQUEST['type'],$_REQUEST['family'],$_REQUEST['src'],$_REQUEST['font_id']
     * @return void
     * @package twistItFonts
     */
    public function editFont() {
        $family = str_replace("'", '"', $_REQUEST['family']);
        $this->sp_fonts[$_REQUEST['font_id']] = array('type' => $_REQUEST['type'], 'src' => $_REQUEST['src'], 'family' => stripslashes($family));
        update_option('sp-fonts', $this->sp_fonts);
    }

    /**
     * Delete a font from system
     *
     * Deletes a font specified by id
     * @param int $key Index of font
     * @return void
     * 
     */
    public function deleteFont($key) {
        $font = $this->sp_fonts[$key];
        unset($this->sp_fonts[$key]);
        update_option('sp-fonts', $this->sp_fonts);
        if ($font['type'] == 'cufon') {
            $this->deleteCufonFile($font['src']);
        }
    }

    /**
     * Delete a cufon font file
     *
     * Deletes a cufon font file specified only if no fonts using the file are found.
     * @param string $cufon Name of cufon file
     * @return void
     * @package twistItFonts
     */
    public function deleteCufonFile($cufon) {
        $inuse = false;
        foreach ($this->sp_fonts as $font) {
            if (($font['type'] == 'cufon' ) && ($font['src'] == $cufon)) {
                $inuse = true;
            }
        }
        if (!$inuse) {
            $file = locate_template('fonts/' . $cufon);
            echo "deleting";
            unlink($file);
        }
    }

}
?>
