if(typeof console=="undefined"){console={log:function(){return false}}}jQuery(document).ready(function(){var b=jQuery;b(document).bind("ajaxComplete",function(e,f){if(f.hasOwnProperty("status")&&f.status==401){document.location=EE.BASE+"&"+f.responseText}});EE.create_searchbox=(function(){function f(h,j,i){h.setAttribute("type","search");b(h).attr({autosave:i,results:"10",placeholder:j})}function e(h,k){var j=b(h),i=j.css("color");j.focus(function(){j.css("color",i);(j.val()==k&&j.val(""))}).blur(function(){if(j.val()==""||j.val==k){j.val(k).css("color","#888")}}).trigger("blur")}var g=(parseInt(navigator.productSub)>=20020000&&navigator.vendor.indexOf("Apple Computer")!=-1)?f:e;return function(k,j,i){var h=document.getElementById(k);(h&&g(h,j,i))}})();EE.create_searchbox("cp_search_keywords","Search","ee_cp_search");EE.create_searchbox("template_keywords","Search Templates","ee_template_search");b('a[rel="external"]').click(function(){window.open(this.href);return false});function d(){var e={revealSidebarLink:"77%",hideSidebarLink:"100%"},f=b("#mainContent");if(EE.CP_SIDEBAR_STATE=="off"){f.css("width","100%");b("#revealSidebarLink").css("display","block");b("#hideSidebarLink").hide()}b("#revealSidebarLink, #hideSidebarLink").click(function(){var h=b(this),g=h.siblings("a");h.hide().siblings(":not(#activeUser)").slideToggle();f.animate({width:e[this.id]});g.show();return false})}d();if(EE.flashdata!==undefined){var c=b(".notice");types={success:"message_success",notice:"message",error:"message_failure"},show_notices=[];for(type in types){if(types[type] in EE.flashdata){if(type=="error"){notice=c.filter(".failure").slice(0,1)}else{if(type=="success"){notice=c.filter(".success").slice(0,1)}else{notice=c.slice(0,1)}}if(EE.flashdata[types[type]]==notice.html()){show_notices.push({message:EE.flashdata[types[type]],type:type});notice.remove()}}}if(show_notices.length){b.ee_notice(show_notices)}}EE.notepad=(function(){var i=b("#notePad"),g=b("#notepad_form"),l=b("#sidebar_notepad_edit_desc"),f=b("#notePadTextEdit").hide(),h=b("#notePadControls").hide(),k=b("#notePadText").show(),e=k.text(),j=f.val();return{init:function(){if(j){k.html(j.replace(/</ig,"&lt;").replace(/>/ig,"&gt;").replace(/\n/ig,"<br />"))}i.click(EE.notepad.show);h.find("a.cancel").click(EE.notepad.hide);g.submit(EE.notepad.submit);h.find("input.submit").click(EE.notepad.submit);f.autoResize()},submit:function(){j=b.trim(f.val());var m=j.replace(/</ig,"&lt;").replace(/>/ig,"&gt;").replace(/\n/ig,"<br />");f.attr("readonly","readonly").css("opacity",0.5);h.find("#notePadSaveIndicator").show();b.post(g.attr("action"),{notepad:j,XID:EE.XID},function(n){k.html(m||e).show();f.attr("readonly","").css("opacity",1).hide();h.hide().find("#notePadSaveIndicator").hide()},"json");return false},show:function(){if(h.is(":visible")){return false}var m="";if(k.hide().text()!=e){m=k.html().replace(/<br>/ig,"\n").replace(/&lt;/ig,"<").replace(/&gt;/ig,">")}h.show();f.val(m).show().height(0).focus().trigger("keypress")},hide:function(){k.show();f.hide();h.hide();return false}}})();EE.notepad.init();b("#accessoryTabs li a").click(function(){var e=b(this).parent("li");if(e.hasClass("current")){b("#"+this.className).hide();e.removeClass("current")}else{if(e.siblings().hasClass("current")){b("#"+this.className).show().siblings(":not(#accessoryTabs)").hide();e.siblings().removeClass("current")}else{b("#"+this.className).slideDown()}e.addClass("current")}return false});function a(){var f=b("#search"),e=f.clone(),g=b("#cp_search_form").find(".searchButton");submit_handler=function(){var h=b(this).attr("action"),i={cp_search_keywords:b("#cp_search_keywords").attr("value")};b.ajax({url:h+"&ajax=y",data:i,beforeSend:function(){g.toggle()},success:function(j){g.toggle();f=f.replaceWith(e);e.html(j);b("#cp_reset_search").click(function(){e=e.replaceWith(f);b("#cp_search_form").submit(submit_handler);b("#cp_search_keywords").select();return false})},dataType:"html"});return false};b("#cp_search_form").submit(submit_handler)}a();b("h4","#quickLinks").click(function(){window.location.href=EE.BASE+"&C=myaccount&M=quicklinks"}).add("#notePad","#sideBar").hover(function(){b(".sidebar_hover_desc",this).show()},function(){b(".sidebar_hover_desc",this).hide()}).css("cursor","pointer");b("#activeUser").one("mouseover",function(){var j=b('<div id="logOutConfirm">'+EE.lang.logout_confirm+" </div>"),f=30,h=f,l;function k(){b.ajax({url:EE.BASE+"&C=login&M=logout",async:(!b.browser.safari)});window.location=EE.BASE+"&C=login&M=logout"}function g(){if(f<1){setTimeout(k,0)}else{if(f==h){b(window).bind("unload.logout",k)}}j.dialog("option","title",EE.lang.logout+" ("+(f--||"...")+")");l=setTimeout(g,1000)}function e(){clearTimeout(l);b(window).unbind("unload.logout");f=h}var i={};i.Cancel=function(){b(this).dialog("close")};i[EE.lang.logout]=k;j.dialog({autoOpen:false,resizable:false,modal:true,title:EE.lang.logout,position:"center",minHeight:"0px",buttons:i,beforeclose:e});b("a.logOutButton",this).click(function(){b("#logOutConfirm").dialog("open");b(".ui-dialog-buttonpane button:eq(2)").focus();g();return false})});b("a.entryLink","#newsAndStats").click(function(){b(this).siblings(".fullEntry").toggle();return false})});