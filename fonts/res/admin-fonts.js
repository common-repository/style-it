jQuery(document).ready(function($){
    if($("#sp-admin-font-form").find(".sp-edit-font").length)
    {
        var data={
            'sp_get_font':$("#sp-admin-font-form").find(".sp-edit-font").val(),
            'sp-admin':1
        }
    
        jQuery.post(parent.location.href,data,function(resp){
            resp=jQuery.parseJSON(resp);
            //Publish the form
            //trigger the form click
            jQuery(".sp-"+resp.type+"-type").trigger("click");
            if(resp.type=="google"){
                $("#sp-admin-font-form").find(".sp-google-code").val(resp.src);
                $("#sp-admin-font-form").find(".sp-google-ff").val(resp.family);
            }else{
            }
        });
    }
    $(".curr-font-type").live("change",function(evt){
        var f_type=$(this).val();
        window.curr_f_type=$(this).val();
        $(this).parents().find(".sp-form-row").not(".row-submit").each(function(){
            if($(this).hasClass('sp-'+f_type)){
                $(this).removeClass("sp-hide");
            }else{
                $(this).addClass("sp-hide");
            }
        })
    });
    $("#sp-admin-font-form").live('submit',function(event){
        var form=$(this);
        if(!window.curr_f_type){
            sp_font_notice("Plese Select a Font Type")
            return false;
        }
        if(window.curr_f_type=="cufon"){
            if(!sp_cufon_check(form.find(".sp-cufon-file"))){
                return false;
            }
        }else{
            if(!sp_google_font_check(form.find(".sp-google-code"))){
                return false;
            }
            if(form.find(".sp-google-ff").val()==""){
                sp_font_notice("Plese Provide Google Font Family");
                return false;
            }
        }
    //All tested and validated
    //form.trigger('submit');
    })
})
var sp_font_notice=function(notice){
    var update_div=$(".sp-font-form-title").siblings(".update-nag");
    if(!update_div.length){
        $(".sp-font-form-title").after('<div class="update-nag"></div>')
        update_div=$(".sp-font-form-title").siblings(".update-nag");
    }
    update_div.html(notice);
}

var sp_cufon_check=function(elem){
    var f_file=elem.val();
    if(f_file.indexOf('\\'))
    {
        f_file=f_file.split('\\');
        f_file=f_file[f_file.length-1];
    }
    if(f_file.indexOf('/'))
    {
        f_file=f_file.split('/');
        f_file=f_file[f_file.length-1];
    }
    if(!f_file){
        sp_font_notice("Please select a font file to upload!");
        return false;
    }
    else
    {
        var f_ext=f_file.split('.');
        f_ext=f_ext[f_ext.length-1];
        if((f_ext== 'js'))
        {
            return true;
        }
        else
        {
            sp_font_notice("This Font is not supported, Please use valid cufon fonts(.js files)!");
            return false;
        }
    }
}
var sp_google_font_check=function(elem){
    var val=elem.val();
    if(val==""){
        sp_font_notice("Please enter google embed code!");
        return false;
    }else{
        //Regualar Expressions
        var re_gf=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/;
        var res=val.match(re_gf);
        if(!res){
            sp_font_notice("Invalid Code! Make sure you paste code for either standard or @import tab.");
            return false;
        }else{
            elem.val(res[0]);
            return true;
        }
    }
}