<html>
    <head>
        <title>Font Iframe</title>
        <?php if(function_exists('sp_font_head')){sp_font_head(true); }?>
        <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('css/jlayer.css', dirname(__FILE__)) ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('css/symbols/stylesheet.css', dirname(__FILE__)) ?>"/>
        <style type="text/css">

            .font-preview{
                width:190px;
                padding:5px;
                float: left;
                border:1px solid #ddd;
                background: #fff;
                margin: 5px;
            }
            .font-text{
                font-size: 18px;
                height:75px;
                display: block;
                text-align: center;
                padding-top:10px;
                overflow: hidden;

            }
            .font-text span{
                vertical-align:middle;
            }
            .font-google:hover{
                background: rgba(0,128,0,0.9);
                cursor: pointer;
                color:#fff;
            }
            .font-cufon:hover{
                background: #34BFEC;
                color:#fff;
                cursor: pointer;
            }
        </style>
        <script type="text/javascript" src="<?php echo plugins_url('js/jquery.js', dirname(__FILE__)) ?>"/></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".font-text").live("click",function(){
                    var id=$(this).siblings(".font-key").val();
                    var font=$(this).siblings(".font-value").text();
                    parent.sp_set_font(id,font);
                })
            })
        </script>
    </head>
    <body>
        <?php
        $fonts = get_option("sp-fonts", array());
        $page = 1;
        if (isset($_REQUEST['ti_page'])) {
            $page = $_REQUEST['ti_page'];
        }
        $items = 3;
        $total = count($fonts);
        $pages = ceil($total / $items);
        $fonts = array_slice($fonts, ($items * ($page - 1)), $items);
        if ($pages > 1) {
            ?>
            <div class="pagination"><em>Page <?php echo $page . ' of ' . $pages; ?> </em><ul>
                    <?php
                    for ($i = 0; $i < $pages; $i++) {
                        echo '<a href="themes.php?page=styleit-editor&get_font_iframe=1&sp-get-fonts=1&KeepThis=true&ti_page=' . ($i + 1) . '" class="' . ((($i + 1) == $page) ? 'currentpage' : '') . '">' . ($i + 1) . '</a>';
                    }
                    ?>
                </ul></div>
            <?php
        }
        $key = $items * ($page - 1);
        foreach ($fonts as $font) {
            $font['family'] = stripslashes($font['family']);
            ?>
            <p class="font-preview">
                <span class="font-text font-<?php echo $font['type']; ?>" id="sa-pre-<?php echo $font['type'] . '-' . (($items * ($page - 1))-$key); ?>"><span><?php _e(stripslashes($font['family'])); ?></span></span>
                <span class="sp-hide font-value"><?php echo json_encode($font); ?></span>
                <input type="hidden" class="font-key" value="<?php echo $key; ?>"/>
            </p>
            <?php
            $key++;
        }
        ?>

    </body>
</html>