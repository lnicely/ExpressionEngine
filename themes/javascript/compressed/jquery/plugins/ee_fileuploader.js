(function(a){var d,g,c,e,j=!0;a.ee_fileuploader=function(b){c=a.extend({},{},b);a.ee_filebrowser.endpoint_request("setup_upload",function(b){d=a(b.uploader).appendTo(document.body);d.removeClass().addClass("before_upload");c.type=="filemanager"?d.find(".button_bar .filebrowser").remove():c.type=="filebrowser"&&d.find(".button_bar .filemanager").remove();a(document).ready(function(){a.ee_fileuploader.build_dialog()});typeof c.load=="function"&&c.load.call(this,d)})};a.ee_fileuploader.build_dialog=
function(){d.dialog({width:600,height:370,resizable:!1,position:["center","center"],modal:!0,draggable:!0,title:EE.fileuploader.window_title,autoOpen:!1,zIndex:99999,open:function(){f("before_upload");e={};a("#file_uploader .button_bar .loading").addClass("visualEscapism");a.ee_fileuploader.reset_upload();g===void 0&&(g=d.html());typeof c.open=="function"&&c.open.call(this,d);i()},close:function(){typeof window.upload_iframe.file!="undefined"&&(j&&a.ajax({url:EE.BASE+"&"+EE.fileuploader.delete_url,
type:"POST",dataType:"json",data:{file:e.file_id,XID:EE.XID},error:function(a,d){console.log(d)}}),typeof c.close=="function"&&c.close.call(this,d,e));d.html(g)}});a(c.trigger).live("click",function(a){a.preventDefault();d.dialog("open")})};var i=function(){a("#file_uploader .button_bar #rename_file").click(function(b){b.preventDefault();a("#file_uploader iframe").contents().find("form").trigger("submit")});a("#file_uploader .button_bar .cancel").live("click",function(b){b.preventDefault();$iframe=
a("#file_uploader iframe").contents();$iframe.find("#edit_file_metadata").size()?($iframe.find("#resize input").each(function(){a(this).val(a(this).data("default")).removeClass("oversized")}),$iframe.find("#rotate input").prop("checked",!1)):d.dialog("close")})};a.ee_fileuploader.reset_upload=function(b){typeof b=="undefined"&&(b=!0);a("#file_uploader .button_bar .loading").addClass("visualEscapism");b===!0&&a("#file_uploader .button_bar #upload_file").addClass("disabled-btn").removeClass("submit").unbind()};
a.ee_fileuploader.enable_upload=function(){a("#file_uploader .button_bar #upload_file").addClass("submit").removeClass("disabled-btn").click(function(b){b.preventDefault();a("#file_uploader .button_bar .loading").removeClass("visualEscapism");a("#file_uploader iframe").contents().find("form").trigger("submit")})};a.ee_fileuploader.set_directory_id=function(b){if(!isNaN(parseInt(b,10))){var c=d.find("iframe").attr("src"),e=c.search("&directory_id="),f=a.ee_filebrowser.get_current_settings();e>0&&(c=
c.substring(0,e));c=c+"&directory_id="+b;a("#dir_choice_form:visible").size()<=0&&(c+="&restrict_directory=true");f.content_type=="image"&&(c+="&restrict_image=true");d.find("iframe").attr("src",c);return b}return!1};a.ee_fileuploader.file_exists=function(b){a.ee_fileuploader.update_file(b);f("file_exists")};a.ee_fileuploader.after_upload=function(b){a.ee_fileuploader.update_file(b);j=!1;typeof c.after_upload=="function"&&c.after_upload.call(this,d,e);f("after_upload");if(c.type=="filemanager")for(var b=
["edit_file","edit_image"],h=0,g=b.length;h<g;h++){var i=a(".mainTable tr.new:first td:has(img) a[href*="+b[h]+"]").attr("href");a("#"+b[h],"#file_uploader .button_bar").attr("href",i)}else c.type=="filebrowser"&&(a("#file_uploader .button_bar").on("click","#choose_file",function(b){d.dialog("close");a.ee_filebrowser.clean_up(e);b.preventDefault()}),a("#file_uploader .button_bar").on("click","#edit_file_modal",function(b){a("#file_uploader iframe").contents().find("form#edit_file").trigger("submit");
f("edit_modal");b.preventDefault()}),a("#file_uploader .button_bar").on("click","#save_file",function(b){a("#file_uploader iframe").contents().find("form#edit_file_metadata").trigger("submit");b.preventDefault()}))};a.ee_fileuploader.update_file=function(a){e=a};var f=function(b){a("#file_uploader").removeClass("before_upload after_upload file_exists edit_modal").addClass(b)}})(jQuery);
