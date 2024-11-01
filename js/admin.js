/**
 * @fileOverview Common admin panel JS file
 * @author <a href="mailto:unizoewp@gmail.com">Unizoe Web Solutions</a>
 * @version 1.0
 */
/**
 * Flag to check if bg has been initialized
 */
window.sp_bg_init=0;
/**
 * Flag to check if font has been initialized
 */
window.sp_font_init=0;
/**
 * Listents to custom event saInitForm
 * @name saInitFormLoader
 * @function
 */
jQuery(document).bind('saInitForm',function(){
    /**
     *Loop through each range slider and initialize each of them.
     *@function
     *@memberOf jQuery
     */
    jQuery(".sp-range").each(function(){
        var cobj=jQuery(this);
        if(!jQuery(this).siblings(".sp-slider-box").length){
            jQuery(this).before('<span class="sp-slider-box"><span class="sp-range-slider"></span></span>');
        }
        var sobj=jQuery.evalJSON(cobj.siblings(".sp-range-val").val());
        cval=(cobj.val()/sobj.max)*100;
        cobj.siblings(".sp-slider-box").find(".sp-range-slider").slider({
            value:cval
        });
       
    });
    /**
     *Event Listner to update the background on changing bg_repeat value
     *@memberOf jQuery
     */
    jQuery(".sp-bg-repeat").live("change",function(){
        sp_admin_set_bg_repeat(jQuery(this).val(),jQuery(this).parents(".sp-admin-bg"))
    });
    //    jQuery(".sp-bg-color").farbtastic(function(color){
    //        console.log("farbtastic ready");
    //    });
    /**
     *Loop through each colorpicker and initialize each of them.
     *@memberOf jQuery
     */
    jQuery(".sp-admin-area>.sp-appear-form").find('.sp-bg-picker').each(function(){
        var f=jQuery.farbtastic(this);
        f.linkTo(jQuery(this).siblings(".sp-bg-color"));
        f.linkTo(function(color){
            jQuery(".sp-admin-area>.sp-appear-form").find('.sp-admin-bg').trigger("colorchange",[color]);
        });
    });
    jQuery(".sp-admin-area>.sp-appear-form").find('.sp-font-picker').each(function(){
        var f=jQuery.farbtastic(this);
        f.linkTo(jQuery(this).siblings(".sp-font-color"));
        f.linkTo(function(color){
            jQuery(".sp-admin-area>.sp-appear-form").find('.sp-admin-font').trigger("colorchange",[color]);
        });
    });
    
    //    jQuery(".sp-bg-color").ColorPicker({
    //        onSubmit: function(hsb, hex, rgb, el) {
    //            jQuery(el).val(hex);
    //            jQuery(el).ColorPickerHide();
    //            if(jQuery(el).parents(".sp-admin-bg").length){
    //                sp_admin_set_bg_color(hex,jQuery(el).parents(".sp-admin-bg"));
    //            }
    //            if(jQuery(el).parents(".sp-admin-font").length){
    //                changeFontColor(jQuery(el).parents(".sp-admin-font"),hex);
    //            }
    //        },
    //        onBeforeShow: function () {
    //            jQuery(this).ColorPickerSetColor(this.value);
    //        }
    //    });
    //init script
    var font=jQuery(".sp-admin-area>.sp-appear-form").find(".sp-admin-font");
    if(font.length){
        if(!jQuery.isEmptyObject(jQuery.evalJSON(font.find(".sp-font-val").val()))){
            var f=font;//font
            var fo=jQuery.evalJSON(font.find(".sp-font-val").val());//font object
            var ff=fo.font_desc;//font desc
            window.curr_font=f;
            f.find(".sp-range-slider").slider({
                value:fo.size
            });
            f.find(".sp-font-size").val(fo.size);
            f.find(".sp-font-color").val(fo.color);
            window.sp_set_font(fo.font,ff);
        }
    }
    var bg=jQuery(".sp-admin-area>.sp-appear-form").find(".sp-admin-bg");
    if(bg.length){
        if(!jQuery.isEmptyObject(jQuery.evalJSON(bg.find(".sp-bg-value").val()))){
            var bgo=jQuery.evalJSON(bg.find(".sp-bg-value").val());
            console.log(bgo);
            bg.find(".sp-bg-img").val(bgo.bg_img);
            var sp_preview=window.ti_preview(bg);
            //    if(!sp_preview.length){
            //        elem.prepend('<div class="sp-bg-preview"></div>');
            //        sp_preview=elem.find(".sp-bg-preview");
            //    }
            setPreviewIcons(sp_preview);
            sp_preview.css({
                'background-image':'url('+bgo.bg_img_url+')',
                'background-color':'#'+bgo.bg_color,
                'background-position':bgo.posX+bgo.bgUnit+" "+bgo.posY+bgo.bgUnit,
                'background-repeat':bgo.bg_repeat
            });
            bg.find(".sp-bg-pos-y").val(bgo.posY);
            bg.find(".sp-bg-pos-y").siblings(".sp-slider-box").find(".sp-range-slider").slider({
                value:bgo.posY
            });
            bg.find(".sp-bg-pos-x").val(bgo.posX);
            bg.find(".sp-bg-pos-x").siblings(".sp-slider-box").find(".sp-range-slider").slider({
                value:bgo.posX
            });
            bg.find(".sp-bg-unit").val(bgo.bgUnit);
            bg.find(".sp-bg-repeat").val(bgo.bg_repeat);
            bg.find(".sp-bg-color").val(bgo.bg_color);
        
        }
    }
})  
/*
 * Listents to jQuery document.ready
 * @memberOf jQuery
 */
jQuery(document).ready(function($){
    jQuery("#sp-appear-dd").live("change",function(){
        var apdd= $(this);
        var idx=apdd.val();
        apdd.parent().siblings("form").remove();
        apdd.parent().after($("#sp-appear-"+idx).html());
        jQuery(document).trigger("saInitForm");
    })
    jQuery(".sp-appear-form").live("submit",function(event){
        event.preventDefault();
        var data={
            'sp-admin':1,
            'ti_update_appearance':1
        };
        if($(this).find(".sp-font-val").length){
            data.font=$(this).find(".sp-font-val").val();
            data.font_elem=$(this).find(".sp-font-val").attr("name");
        }
        if($(this).find(".sp-bg-value").length){
            data.bg=$(this).find(".sp-bg-value").val();
            data.bg_elem=$(this).find(".sp-bg-value").attr("name");
        }
        $.post(window.location.href,data,function(resp){
            console.log("resp");
        })
        console.log(data);
    })
    jQuery(".sp-range-slider").live("slide",function(event, ui){
        var r_b=jQuery(this).parent();
        var sobj=jQuery.evalJSON(r_b.siblings(".sp-range-val").val());
        cval=(ui.value/100)*sobj.max;
        r_b.siblings(".sp-range").val(parseInt(cval));
    });
    jQuery(".sp-range-slider").live("slidestop",function(event, ui){
        jQuery(this).parents(".sp-bg-pos").trigger("setbg");
        jQuery(this).parents(".sp-admin-font").trigger("changesize");
    })
    jQuery(".sp-admin-font").live("changesize",function(){
        var val=jQuery(this).find(".sp-font-size").val();
        window.ti_preview($(this)).css({
            'font-size':val+"px",
            'line-height':val+"px"
        });
        window.updateFontJson($(this));
        Cufon.refresh();
    })
    jQuery(".sp-bg-pos").live("setbg",function(){
        sp_admin_set_bg_pos(jQuery(this),jQuery(this).parents(".sp-admin-bg"));
    });
    jQuery(".sp-bg-unit").live("change",function(){
        jQuery(this).parents(".sp-bg-pos").trigger("setbg");
    })
    //    jQuery(".sp-admin-menu li a").live('mouseenter',function(){
    //        jQuery(".active-anim").removeClass("active-anim");
    //        jQuery(this).addClass("active-anim");
    //        jQuery(this).animate({
    //            width:'125px'
    //        }, 200, function(){
    //            
    //            })
    //    }).live('mouseleave',function(){
    //        jQuery(this).animate({
    //            width:'0px'
    //        }, 200, function(){
    //            jQuery(this).removeClass("active-anim");
    //        })
    //    });
    //    
    /* Event handler for slides */
    jQuery(".curr-font-type").live('change',function(){
        //hide and show the form rows
        var type=jQuery(this).val();
        var form=jQuery(this).parents("form");
        console.log("sp-"+type);
        form.find(".sp-admin-row").each(function(){
            if(jQuery(this).hasClass("sp-"+type)){
                console.log("Removing");
                jQuery(this).removeClass("sp-hide");
            }else{
                jQuery(this).addClass("sp-hide");
            }
        })
    })
    //    jQuery(".sp-form-row .desc-icon").live("mouseenter",function(evt){
    //        jQuery(this).find(".sp-hide").removeClass("sp-hide");
    //    }).live("mouseleave",function(evt){
    //        jQuery(this).find("span").addClass("sp-hide");
    //    })
    jQuery(".sp-form-row .desc-icon i").tipTip();
    /* check if page contains background editor */
    if(jQuery(".sp-admin-bg").length){
        
        window.sp_curr_obj=0;
        jQuery('.sp-bg-upload').live('click',function(evt) {
            /* prevent form submission */
            evt.preventDefault();
            window.sp_curr_obj=jQuery(this);
            tb_show('','media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });
        /**
         *Overides default send_to_editor to include background images
         *@param {String} html html of image selected
         *@memberOf window
         */
        window.send_to_editor = function(html) {
            var re_attach_id=/wp-image-(\d*)/;
            var res=html.match(re_attach_id);
            var re_attach_href=/href\=\"(.*?)\"/;
            var res_hr=html.match(re_attach_href);
            sp_admin_set_bg_img(res[1],res_hr[1]);
            tb_remove();
        }
    }
    /*check if page contains a Image Uploader */
    if(jQuery(".sp-img-upload").length){
        window.sp_curr_obj=0;
        jQuery('.sp-img-upload').live('click',function(evt) {
            /* prevent form submission */
            evt.preventDefault();
            window.sp_curr_obj=jQuery(this);
            tb_show('','media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });
        /**
         *Overides default wordpress send_to_editor for sending images to image uploader
         *@param {String} html html of image selected
         *@memberOf window
         */
        window.send_to_editor = function(html) {
            var re_attach_id=/wp-image-(\d*)/;
            var res=html.match(re_attach_id);
            var re_attach_href=/href\=\"(.*?)\"/;
            var res_hr=html.match(re_attach_href);
            window.sp_curr_obj.siblings(".sp-img-id").val(res[1]);
            var preview=window.sp_curr_obj.siblings(".sp-img-preview");
            if(!preview.length){
                window.sp_curr_obj.before('<img class="sp-img-preview" src="" alt="preview"/> ')
                preview=window.sp_curr_obj.siblings(".sp-img-preview");
            }
            preview.attr("src",res_hr[1]);
            //sp_admin_set_bg_img(res[1],res_hr[1]);
            tb_remove();
        }
    }
    /*event handler for deleting fonts*/
    jQuery(".sa-delete-font").live("click",function(evt){
        var re_font=/\#font-(\d+)/;
        var href=jQuery(this).attr("href");
        var res=href.match(re_font);
        window.curr_del_row=jQuery(this).parents("tr");
        sp_delete_font(res[1]);
    });
    /* For choosing fonts*/
    jQuery(".sp-font-choose").live("click",function(evt){
        window.curr_font=jQuery(this).parents(".sp-admin-font");
    })
    jQuery(".sp-admin-font").live("colorchange",function(event,color){
        $(this).find(".sp-font-color").val(color);
        window.changeFontColor($(this),color);
    })
    jQuery(".sp-admin-bg").live("colorchange",function(event,color){
        $(this).find(".sp-bg-color").val(color);
        sp_admin_set_bg_color(color,$(this));
    })
})
/**
 *Sets background color of preview
 *@param {String} color hex color value
 *@param {Object} elem jQuery object for background editor
 **/

var sp_admin_set_bg_color=function(color,elem){
    var bg=elem.find(".sp-bg-value").val();
    bg=jQuery.parseJSON( bg );
    bg.bg_color=color;
    console.log(bg);
    elem.find(".sp-bg-value").val(jQuery.toJSON(bg));
    console.log(elem.find(".sp-bg-value"),jQuery.toJSON(bg));
    var sp_preview=window.ti_preview(elem)
    //    if(!sp_preview.length){
    //        elem.prepend('<div class="sp-bg-preview"></div>');
    //        sp_preview=elem.find(".sp-bg-preview");
    //    }
    setPreviewIcons(sp_preview);
    sp_preview.css({
        'background-color':color
    });
}

var setPreviewIcons=function(elem){
    console.log("setting icons");
    if(!elem.find(".ti-preview-icon").length){
        elem.prepend('<span class="ti-preview-icon"><span class="ti-icon">g</span>Preview</span>');
    }
}
/**
 *Sets background repeat of preview
 *@param {String} val bg repeat value
 *@param {Object} elem jQuery object for background editor
 **/

var sp_admin_set_bg_repeat=function(val,elem){
    var bg=elem.find(".sp-bg-value").val();
    bg=jQuery.evalJSON( bg );
    console.log(bg);
    bg.bg_repeat=val;
    elem.find(".sp-bg-value").val(jQuery.toJSON(bg));
    var sp_preview=window.ti_preview(elem)
    //    if(!sp_preview.length){
    //        elem.prepend('<div class="sp-bg-preview"></div>');
    //        sp_preview=elem.find(".sp-bg-preview");
    //    }
    setPreviewIcons(sp_preview);
    console.log(sp_preview,val,jQuery.toJSON(bg));
    sp_preview.css({
        'background-repeat':val
    });
}
/**
 *Sets background image of preview
 *@param {Number} id attachment id of image
 *@param {String} url url of image
 **/

var sp_admin_set_bg_img=function(id,url){
    var elem=window.sp_curr_obj.parents(".sp-admin-bg");
    var bg=elem.find(".sp-bg-value").val();
    bg=jQuery.evalJSON( bg );
    bg['bg_img']=id;
    bg['bg_img_url']=url;
    elem.find(".sp-bg-value").val(jQuery.toJSON(bg));
    window.sp_curr_obj.siblings(".sp-bg-img").val(id);
    var sp_preview=window.ti_preview(elem);
    //    if(!sp_preview.length){
    //        elem.prepend('<div class="sp-bg-preview"></div>');
    //        sp_preview=elem.find(".sp-bg-preview");
    //    }
    setPreviewIcons(sp_preview);
    console.log(sp_preview);
    sp_preview.css({
        'background-image':'url('+url+')'
    });
};
/**
 *Sets background repeat of preview
 *@param {String} pos bg position value
 *@param {Object} elem jQuery object for background editor
 **/

var sp_admin_set_bg_pos=function(pos,elem){
    var pos={
        'posX':pos.find(".sp-bg-pos-x").val(),
        'posY':pos.find(".sp-bg-pos-y").val(),
        'bgUnit':pos.find(".sp-bg-unit").val()
    }
    var bg=elem.find(".sp-bg-value").val();
    bg=jQuery.evalJSON(bg);
    bg['posX']=pos.posX;
    bg['posY']=pos.posY;
    bg['bgUnit']=pos.bgUnit;
    elem.find(".sp-bg-value").val(jQuery.toJSON(bg));
    var sp_preview=window.ti_preview(elem)
    //    if(!sp_preview.length){
    //        elem.prepend('<div class="sp-bg-preview"></div>');
    //        sp_preview=elem.find(".sp-bg-preview");
    //    }
    setPreviewIcons(sp_preview);
    sp_preview.css({
        'background-position':pos.posX+pos.bgUnit+" "+pos.posY+pos.bgUnit
    });
}
/**
 *Sets Background values on preview
 *@param {Object} elem jQuery object for background editor
 *@param {Object} bgo background object
 *@config {String} bg_img attachment id of the background image
 *@config {String} bg_img_url url of the bg image
 *@config {String} color hex value of bg color
 *@config {String} PosX background position-x
 *@config {String} PosY background position-y
 *@config {String} bgUnit background position unit
 *@config {String} bg_repeat Background Repeat
 */

window.ti_bg_preview=function(elem,bgo){
    preview=window.ti_preview(elem);
    preview.css({
        'background-image':'url('+bgo.bg_img_url+')'
    });
    preview.css({
        'background-color':bgo.bg_color
    });
    preview.css({
        'background-position':bgo.posX+bgo.bgUnit+" "+bgo.posY+bgo.bgUnit
    });
    preview.css({
        'background-repeat':bgo.bg_repeat
    });
    console.log("preview");
}
/**
 *Get preview for apearance editor elements
 *@param {Object} elem jQuery object for element
 *@param {Boolean} reset if to reset existing preview
 */
window.ti_preview=function(elem,reset){
    var preview,bg={};
    if(elem.hasClass("appearance-common")){
        //take the parent and append preview item
        //check if preview is already there
        if(elem.parents("form").find(".sp-admin-font-preview").length)
        {
            if(reset){
                //store background info and remove element 
                preview=elem.parents("form").find(".sp-admin-font-preview");
                bg={};
                bg.img=preview.css('background-image');
                bg.color=preview.css('background-color');
                bg.repeat=preview.css('background-repeat');
                bg.position=preview.css('background-position');
                window.font_panel++;
                preview.remove();
                elem.parents("form").prepend('<div class="sp-admin-font-preview" id="panel-'+window.font_panel+'"><span class="text">Demo Text</span></div>');
                preview= elem.parents("form").find(".sp-admin-font-preview");
                return preview;
            }else{
                return elem.parents("form").find(".sp-admin-font-preview");
            }
        }else{
            //no previews found yet
            window.font_panel++;
            elem.parents("form").prepend('<div class="sp-admin-font-preview" id="panel-'+window.font_panel+'"><span class="text">Demo Text</span></div>');
            return elem.parents("form").find(".sp-admin-font-preview");
        }
    }else{
        if(elem.find(".sp-admin-font-preview").length){
            if(reset){
                window.font_panel++;
                preview= elem.find(".sp-admin-font-preview");
                preview.remove();
                console.log(preview);
                elem.prepend('<div class="sp-admin-font-preview preview-abs" id="panel-'+window.font_panel+'"><span class="text">Demo Text</span></div>');
                preview= elem.find(".sp-admin-font-preview");
                return preview;
            }else{
                return elem.find(".sp-admin-font-preview");
            }
        }else{
            window.font_panel++;
            elem.prepend('<div class="sp-admin-font-preview preview-abs" id="panel-'+window.font_panel+'"><span class="text">Demo Text</span></div>');
            return elem.parents("form").find(".sp-admin-font-preview"); 
        }
    }
}
window.font_panel=0;
/**
 * Set font returned by iframe
 * @param {Number} id id of font choosen
 * @param {Object|String} font description of font
 * @config {String} type type of font cufon/css
 * @config {String} family font-family of the font
 * @memberOf window
 */
window.sp_set_font=function(id,font){
    console.log(font);
    window.curr_font.find(".sp-font-ff").val(id);
    //    if(window.curr_font.find(".sp-admin-font-preview").length){
    //        window.curr_font.find(".sp-admin-font-preview").remove();
    //    }
    //    window.font_panel++;
    //    window.curr_font.prepend('<div class="sp-admin-font-preview" id="panel-'+window.font_panel+'"><span class="text">Demo Text</span></div>')
    var preview=window.ti_preview(window.curr_font,1);
    if(typeof font == "string"){
        font=jQuery.parseJSON(font);
    }
    console.log(font.family);
    if(font.type=="cufon"){
        Cufon.replace("#"+preview.attr("id")+">span.text",{
            'fontFamily':font.family
        });
       
    }else{
        preview.attr("style","font-family:"+font.family);
    }
    var size=window.curr_font.find(".sp-font-size").val();
    preview.css({
        'font-size':size+"px",
        'line-height':size+"px"
    });
    setPreviewIcons(preview);
    var color=window.curr_font.find(".sp-font-color").val();
    preview.css({
        'color':color
    });
    Cufon.refresh();
    if(window.curr_font.parents("form").find(".sp-admin-bg").length){
        var bgo=jQuery.evalJSON(window.curr_font.parents("form").find(".sp-admin-bg").find(".sp-bg-value").val());
        window.ti_bg_preview(window.curr_font,bgo);
    }
    updateFontJson(window.curr_font);
    tb_remove();
}
/**
 * Updates and stores current font settings in JSON format
 * @function
 * @param {Object} elem jQuery object
 * @memberOf window
 */

window.updateFontJson=function(elem){
    var jc=elem.find(".sp-font-val").val();
    var font_s={
        'font':elem.find(".sp-font-ff").val(),
        'size':elem.find(".sp-font-size").val(),
        'color':elem.find(".sp-font-color").val()
    }
    elem.find(".sp-font-val").val(jQuery.toJSON(font_s));
}
/**
 * change color of the font preview
 * @function
 * @param {Object} elem jQuery of element
 * @param {String} color hex colo
 * @memberOf window
 */
window.changeFontColor=function(elem,color){
    var preview=window.ti_preview(elem);
    preview.css({
        'color':color
    });
    window.updateFontJson(elem);
    Cufon.refresh();
}
/**
 * Adds a font to system
 * @function
 * @param {Object} font json object
 * @memberOf window
 */
window.sp_add_font=function(font){
    font['sp_add_font']=1;
    font['sp-admin']=1;
    tb_remove();
    jQuery.post(window.location.href,font,function(){
        window.location.reload(); 
    });
}
/**
 * Updates a font in system
 * @function
 * @param {Object} font json object
 * @memberOf window
 */
window.sp_edit_font=function(font){
    console.log(font);
    font['sp_edit_font']=1;
    font['sp-admin']=1;
    tb_remove();
    jQuery.post(window.location.href,font,function(resp){
        window.location.reload();
    });
}
/**
 * Deltetes a font from system
 * @function
 * @param {Number} font_id json object
 * @memberOf window
 */

window.sp_delete_font=function(font_id){
    window.curr_font=font_id;
    jQuery.prompt('Are you sure to delete this font?',{
        buttons: {
            Ok: true, 
            Cancel: false
        },
        callback: function(e,v,m,f){
            if(v==true){
                jQuery.post(window.location.href,{
                    'sp_delete_font':window.curr_font,
                    'sp-admin':1
                },function(resp){
                    window.location.reload();
                })
                window.curr_del_row.fadeOut(400);
            }
        }
    });
}
/* Hack for shortcodes styling */
var ti_reg_ex=/\[(ti\-.*)\]((?!\[)([A-z\!\@\#\$\%^\&\*\(\)\|\\\"\'\:\;\/\>\<\n\s])*)\[\/(ti\-.*)\]/m;
var ti_replacement='<div class="ti-shortcode no-icon $4"><span class="ti-icon"></span>$4<span class="hidden">attributes:$1|content:$2|tag:$4</span></div>';
jQuery(document).ready(function(){
    jQuery("body").live("afterWpautop",function(event){
        if(tinyMCE.activeEditor){
            content=tinyMCE.activeEditor.getContent();
            //            content=content.replace(ti_reg_ex,ti_replacement);
            //            console.log(content);
            tinyMCE.activeEditor.setContent(content);
        }
    }) 
    jQuery("body").live("afterPreWpautop",function(event){
        console.log("HTML Editor",tinyMCE.activeEditor.getContent());
    })
   

});
window.tiShortcodeStyles=function(){
    console.log("event event event");
    content=tinyMCE.activeEditor.getContent();
    //    content=tinyMCE.activeEditor.getContent();
    //    content=content.replace(ti_reg_ex,ti_replacement);
    //            
    tinyMCE.activeEditor.setContent(content);

}