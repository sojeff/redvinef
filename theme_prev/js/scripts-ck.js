$(function(){function e(e){$("#modal-create").modal({position:["256px"],opacity:["80"],focus:!1,overlayId:"modal-overlay",containerId:"modal-container",onShow:function(t){var n=this;$(".create",t.data[0]).click(function(){$.isFunction(e)&&e.apply();n.close()})}})}$(".field-username").watermark("Username");$(".field-password").watermark("Password");$(".field-search").watermark("Find Knowledge by Entering a Keyword",{useNative:!1});$(".field-title").watermark("Enter in the Title for this Thread",{useNative:!1});$(".field-jumppad").watermark("Enter in the Title for this JumpPad",{useNative:!1});$(".field-comment").watermark("Share Some Knowledge",{useNative:!1});$(".field-post").watermark("Share Some Knowledge by Adding a Comment",{useNative:!1});$(".field-persona").watermark("Find People to Join this JumpPad",{useNative:!1});$(".field-link").watermark("http://",{useNative:!1});$(".field-content").watermark("Connect to Content",{useNative:!1});$("a.thread, a.jumppad").click(function(t){t.preventDefault();e(function(){window.location.href=""})});$(".button-followsmall a").click(function(){$(this).hasClass("active")?$(this).removeClass("active"):$(this).addClass("active")});$(".field-comment, .field-post").autosize();$(".button-followall").click(function(){$(".block-persona > .button-followsmall a").hasClass("active")===!1&&$(".block-persona > .button-followsmall a").addClass("active")});$(".date").click(function(){if($(this).hasClass("active")===!1){$("#nav-sort > li > a").removeClass("active");$(this).addClass("active")}});$(".new").click(function(){if($(this).hasClass("active")===!1){$("#nav-sort > li > a").removeClass("active");$(this).addClass("active")}});$(".alpha").click(function(){if($(this).hasClass("active")===!1){$("#nav-sort > li > a").removeClass("active");$(this).addClass("active")}});$(".relevance").click(function(){if($(this).hasClass("active")===!1){$("#nav-sort > li > a").removeClass("active");$(this).addClass("active")}});$(".like").click(function(){if($(this).hasClass("active")===!1){$("#nav-curate > li > a").removeClass("active");$(this).addClass("active")}});$(".flag").click(function(){if($(this).hasClass("active")===!1){$("#nav-curate > li > a").removeClass("active");$(this).addClass("active")}});$(".tag").click(function(){if($(this).hasClass("active")===!1){$("#nav-curate > li > a").removeClass("active");$(this).addClass("active")}});$(".share").click(function(){if($(this).hasClass("active")===!1){$("#nav-curate > li > a").removeClass("active");$(this).addClass("active")}});$(".nav-comment > .like").click(function(){if($(this).hasClass("active")===!1){$(".nav-comment > li > a").removeClass("active");$(this).addClass("active")}});$(".nav-comment > .flag").click(function(){if($(this).hasClass("active")===!1){$(".nav-comment > li > a").removeClass("active");$(this).addClass("active")}});$(".nav-comment > .reply").click(function(){if($(this).hasClass("active")===!1){$(".nav-comment > li > a").removeClass("active");$(this).addClass("active")}});$(".nav-comment > .share").click(function(){if($(this).hasClass("active")===!1){$(".nav-comment > li > a").removeClass("active");$(this).addClass("active")}})});