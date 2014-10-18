///*THEME JS */

//++++ nanoscroller
/*! nanoScrollerJS - v0.7.4 - (c) 2013 James Florentino; Licensed MIT */
!function(a,b,c){"use strict";var d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x;w={paneClass:"pane",sliderClass:"slider",contentClass:"content",iOSNativeScrolling:!1,preventPageScrolling:!1,disableResize:!1,alwaysVisible:!1,flashDelay:1500,sliderMinHeight:20,sliderMaxHeight:null,documentContext:null,windowContext:null},s="scrollbar",r="scroll",k="mousedown",l="mousemove",n="mousewheel",m="mouseup",q="resize",h="drag",u="up",p="panedown",f="DOMMouseScroll",g="down",v="wheel",i="keydown",j="keyup",t="touchmove",d="Microsoft Internet Explorer"===b.navigator.appName&&/msie 7./i.test(b.navigator.appVersion)&&b.ActiveXObject,e=null,x=function(){var a,b,d;return a=c.createElement("div"),b=a.style,b.position="absolute",b.width="100px",b.height="100px",b.overflow=r,b.top="-9999px",c.body.appendChild(a),d=a.offsetWidth-a.clientWidth,c.body.removeChild(a),d},o=function(){function i(d,f){this.el=d,this.options=f,e||(e=x()),this.$el=a(this.el),this.doc=a(this.options.documentContext||c),this.win=a(this.options.windowContext||b),this.$content=this.$el.children("."+f.contentClass),this.$content.attr("tabindex",this.options.tabIndex||0),this.content=this.$content[0],this.options.iOSNativeScrolling&&null!=this.el.style.WebkitOverflowScrolling?this.nativeScrolling():this.generate(),this.createEvents(),this.addEvents(),this.reset()}return i.prototype.preventScrolling=function(a,b){if(this.isActive)if(a.type===f)(b===g&&a.originalEvent.detail>0||b===u&&a.originalEvent.detail<0)&&a.preventDefault();else if(a.type===n){if(!a.originalEvent||!a.originalEvent.wheelDelta)return;(b===g&&a.originalEvent.wheelDelta<0||b===u&&a.originalEvent.wheelDelta>0)&&a.preventDefault()}},i.prototype.nativeScrolling=function(){this.$content.css({WebkitOverflowScrolling:"touch"}),this.iOSNativeScrolling=!0,this.isActive=!0},i.prototype.updateScrollValues=function(){var a;a=this.content,this.maxScrollTop=a.scrollHeight-a.clientHeight,this.prevScrollTop=this.contentScrollTop||0,this.contentScrollTop=a.scrollTop,this.iOSNativeScrolling||(this.maxSliderTop=this.paneHeight-this.sliderHeight,this.sliderTop=0===this.maxScrollTop?0:this.contentScrollTop*this.maxSliderTop/this.maxScrollTop)},i.prototype.createEvents=function(){var a=this;this.events={down:function(b){return a.isBeingDragged=!0,a.offsetY=b.pageY-a.slider.offset().top,a.pane.addClass("active"),a.doc.bind(l,a.events[h]).bind(m,a.events[u]),!1},drag:function(b){return a.sliderY=b.pageY-a.$el.offset().top-a.offsetY,a.scroll(),a.updateScrollValues(),a.contentScrollTop>=a.maxScrollTop&&a.prevScrollTop!==a.maxScrollTop?a.$el.trigger("scrollend"):0===a.contentScrollTop&&0!==a.prevScrollTop&&a.$el.trigger("scrolltop"),!1},up:function(){return a.isBeingDragged=!1,a.pane.removeClass("active"),a.doc.unbind(l,a.events[h]).unbind(m,a.events[u]),!1},resize:function(){a.reset()},panedown:function(b){return a.sliderY=(b.offsetY||b.originalEvent.layerY)-.5*a.sliderHeight,a.scroll(),a.events.down(b),!1},scroll:function(b){a.isBeingDragged||(a.updateScrollValues(),a.iOSNativeScrolling||(a.sliderY=a.sliderTop,a.slider.css({top:a.sliderTop})),null!=b&&(a.contentScrollTop>=a.maxScrollTop?(a.options.preventPageScrolling&&a.preventScrolling(b,g),a.prevScrollTop!==a.maxScrollTop&&a.$el.trigger("scrollend")):0===a.contentScrollTop&&(a.options.preventPageScrolling&&a.preventScrolling(b,u),0!==a.prevScrollTop&&a.$el.trigger("scrolltop"))))},wheel:function(b){var c;if(null!=b)return c=b.delta||b.wheelDelta||b.originalEvent&&b.originalEvent.wheelDelta||-b.detail||b.originalEvent&&-b.originalEvent.detail,c&&(a.sliderY+=-c/3),a.scroll(),!1}}},i.prototype.addEvents=function(){var a;this.removeEvents(),a=this.events,this.options.disableResize||this.win.bind(q,a[q]),this.iOSNativeScrolling||(this.slider.bind(k,a[g]),this.pane.bind(k,a[p]).bind(""+n+" "+f,a[v])),this.$content.bind(""+r+" "+n+" "+f+" "+t,a[r])},i.prototype.removeEvents=function(){var a;a=this.events,this.win.unbind(q,a[q]),this.iOSNativeScrolling||(this.slider.unbind(),this.pane.unbind()),this.$content.unbind(""+r+" "+n+" "+f+" "+t,a[r])},i.prototype.generate=function(){var a,b,c,d,f;return c=this.options,d=c.paneClass,f=c.sliderClass,a=c.contentClass,this.$el.find(""+d).length||this.$el.find(""+f).length||this.$el.append('<div class="'+d+'"><div class="'+f+'" /></div>'),this.pane=this.$el.children("."+d),this.slider=this.pane.find("."+f),e&&(b={right:-e},this.$el.addClass("has-scrollbar")),null!=b&&this.$content.css(b),this},i.prototype.restore=function(){this.stopped=!1,this.pane.show(),this.addEvents()},i.prototype.reset=function(){var a,b,c,f,g,h,i,j,k,l;return this.iOSNativeScrolling?(this.contentHeight=this.content.scrollHeight,void 0):(this.$el.find("."+this.options.paneClass).length||this.generate().stop(),this.stopped&&this.restore(),a=this.content,c=a.style,f=c.overflowY,d&&this.$content.css({height:this.$content.height()}),b=a.scrollHeight+e,k=parseInt(this.$el.css("max-height"),10),k>0&&(this.$el.height(""),this.$el.height(a.scrollHeight>k?k:a.scrollHeight)),h=this.pane.outerHeight(!1),j=parseInt(this.pane.css("top"),10),g=parseInt(this.pane.css("bottom"),10),i=h+j+g,l=Math.round(i/b*i),l<this.options.sliderMinHeight?l=this.options.sliderMinHeight:null!=this.options.sliderMaxHeight&&l>this.options.sliderMaxHeight&&(l=this.options.sliderMaxHeight),f===r&&c.overflowX!==r&&(l+=e),this.maxSliderTop=i-l,this.contentHeight=b,this.paneHeight=h,this.paneOuterHeight=i,this.sliderHeight=l,this.slider.height(l),this.events.scroll(),this.pane.show(),this.isActive=!0,a.scrollHeight===a.clientHeight||this.pane.outerHeight(!0)>=a.scrollHeight&&f!==r?(this.pane.hide(),this.isActive=!1):this.el.clientHeight===a.scrollHeight&&f===r?this.slider.hide():this.slider.show(),this.pane.css({opacity:this.options.alwaysVisible?1:"",visibility:this.options.alwaysVisible?"visible":""}),this)},i.prototype.scroll=function(){return this.isActive?(this.sliderY=Math.max(0,this.sliderY),this.sliderY=Math.min(this.maxSliderTop,this.sliderY),this.$content.scrollTop(-1*((this.paneHeight-this.contentHeight+e)*this.sliderY/this.maxSliderTop)),this.iOSNativeScrolling||this.slider.css({top:this.sliderY}),this):void 0},i.prototype.scrollBottom=function(a){return this.isActive?(this.reset(),this.$content.scrollTop(this.contentHeight-this.$content.height()-a).trigger(n),this):void 0},i.prototype.scrollTop=function(a){return this.isActive?(this.reset(),this.$content.scrollTop(+a).trigger(n),this):void 0},i.prototype.scrollTo=function(b){return this.isActive?(this.reset(),this.scrollTop(a(b).get(0).offsetTop),this):void 0},i.prototype.stop=function(){return this.stopped=!0,this.removeEvents(),this.pane.hide(),this},i.prototype.destroy=function(){return this.stopped||this.stop(),this.pane.length&&this.pane.remove(),d&&this.$content.height(""),this.$content.removeAttr("tabindex"),this.$el.hasClass("has-scrollbar")&&(this.$el.removeClass("has-scrollbar"),this.$content.css({right:""})),this},i.prototype.flash=function(){var a=this;if(this.isActive)return this.reset(),this.pane.addClass("flashed"),setTimeout(function(){a.pane.removeClass("flashed")},this.options.flashDelay),this},i}(),a.fn.nanoScroller=function(b){return this.each(function(){var c,d;if((d=this.nanoscroller)||(c=a.extend({},w,b),this.nanoscroller=d=new o(this,c)),b&&"object"==typeof b){if(a.extend(d.options,b),b.scrollBottom)return d.scrollBottom(b.scrollBottom);if(b.scrollTop)return d.scrollTop(b.scrollTop);if(b.scrollTo)return d.scrollTo(b.scrollTo);if("bottom"===b.scroll)return d.scrollBottom(0);if("top"===b.scroll)return d.scrollTop(0);if(b.scroll&&b.scroll instanceof a)return d.scrollTo(b.scroll);if(b.stop)return d.stop();if(b.destroy)return d.destroy();if(b.flash)return d.flash()}return d.reset()})},a.fn.nanoScroller.Constructor=o}(jQuery,window,document);
//# sourceMappingURL=jquery.nanoscroller.min.js.map



$(function(){
  //Functions
  function toggleSideBar(_this){
    var b = $("#sidebar-collapse")[0];
    var w = $("#cl-wrapper");
    var s = $(".cl-sidebar");
    
    if(w.hasClass("sb-collapsed")){
      $(".fa",b).addClass("fa-angle-left").removeClass("fa-angle-right");
      w.removeClass("sb-collapsed");
      $.cookie('BTL_sidebar','open',{expires:365, path:'/'});
    }else{
      $(".fa",b).removeClass("fa-angle-left").addClass("fa-angle-right");
      w.addClass("sb-collapsed");
      $.cookie('BTL_sidebar','closed',{expires:365, path:'/'});
    }
    //updateHeight();
  }
    
  function updateHeight(){
    if(!$("#cl-wrapper").hasClass("fixed-menu")){
      var button = $("#cl-wrapper .collapse-button").outerHeight();
      var navH = $("#head-nav").height();
      //var document = $(document).height();
      var cont = $("#pcont").height();
      var sidebar = ($(window).width() > 755 && $(window).width() < 963)?0:$("#cl-wrapper .menu-space .content").height();
      var windowH = $(window).height();
      
      if(sidebar < windowH && cont < windowH){
        if(($(window).width() > 755 && $(window).width() < 963)){
          var height = windowH;
        }else{
          var height = windowH - button;
        }
      }else if((sidebar < cont && sidebar > windowH) || (sidebar < windowH && sidebar < cont)){
        var height = cont + button;
      }else if(sidebar > windowH && sidebar > cont){
        var height = sidebar + button;
      }  
      
      // var height = ($("#pcont").height() < $(window).height())?$(window).height():$(document).height();
      $("#cl-wrapper .menu-space").css("min-height",height);
    }else{
      $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
    }
  }
        

      /*VERTICAL MENU*/
      $(".cl-vnavigation li ul").each(function(){
        $(this).parent().addClass("parent");
      });
      
      $(".cl-vnavigation li ul li.active").each(function(){
        $(this).parent().css({'display':'block'});
        $(this).parent().parent().addClass("open");
        //setTimeout(function(){updateHeight();},200);
      });
      
      $(".cl-vnavigation").delegate(".parent > a","click",function(e){
        $(".cl-vnavigation .parent.open > ul").not($(this).parent().find("ul")).slideUp(300, 'swing',function(){
           $(this).parent().removeClass("open");
        });
        
        var ul = $(this).parent().find("ul");
        ul.slideToggle(300, 'swing', function () {
          var p = $(this).parent();
          if(p.hasClass("open")){
            p.removeClass("open");
          }else{
            p.addClass("open");
          }
          //var menuH = $("#cl-wrapper .menu-space .content").height();
          // var height = ($(document).height() < $(window).height())?$(window).height():menuH;
          //updateHeight();
         $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
         /*if(CodeMirror){
          cm.refresh();
         }*/
         
        });
        e.preventDefault();
      });
      
      /*Small devices toggle*/
      $(".cl-toggle").click(function(e){
        var ul = $(".cl-vnavigation");
        ul.slideToggle(300, 'swing', function () {
        });
        e.preventDefault();
      });
      
      /*Collapse sidebar*/
      $("#sidebar-collapse").click(function(){
          toggleSideBar();
      });
      
      
      if($("#cl-wrapper").hasClass("fixed-menu")){
        var scroll =  $("#cl-wrapper .menu-space");
        scroll.addClass("nano nscroller");
 
        function update_height(){
          var button = $("#cl-wrapper .collapse-button");
          var collapseH = button.outerHeight();
          var navH = $("#head-nav").height();
          var height = $(window).height() - ((button.is(":visible"))?collapseH:0);
          scroll.css("height",height);
          $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        }
        
        $(window).resize(function() {
          update_height();
        });    
            
        update_height();
        $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        
      }else{
        $(window).resize(function(){
          //updateHeight();
        }); 
        //updateHeight();
      }

      
      /*SubMenu hover */
        var tool = $("<div id='sub-menu-nav' style='position:fixed;z-index:9999;'></div>");
        
        function showMenu(_this, e){
          if(($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",_this).length > 0){   
            $(_this).removeClass("ocult");
            var menu = $("ul",_this);
            if(!$(".dropdown-header",_this).length){
              var head = '<li class="dropdown-header">' +  $(_this).children().html()  + "</li>" ;
              menu.prepend(head);
            }
            
            tool.appendTo("body");
            var top = ($(_this).offset().top + 8) - $(window).scrollTop();
            var left = $(_this).width();
            
            tool.css({
              'top': top,
              'left': left + 8
            });
            tool.html('<ul class="sub-menu">' + menu.html() + '</ul>');
            tool.show();
            
            menu.css('top', top);
          }else{
            tool.hide();
          }
        }

        $(".cl-vnavigation li").hover(function(e){
          showMenu(this, e);
        },function(e){
          tool.removeClass("over");
          setTimeout(function(){
            if(!tool.hasClass("over") && !$(".cl-vnavigation li:hover").length > 0){
              tool.hide();
            }
          },500);
        });
        
        tool.hover(function(e){
          $(this).addClass("over");
        },function(){
          $(this).removeClass("over");
          tool.fadeOut("fast");
        });
        
        
        $(document).click(function(){
          tool.hide();
        });
        $(document).on('touchstart click', function(e){
          tool.fadeOut("fast");
        });
        
        tool.click(function(e){
          e.stopPropagation();
        });
     
        $(".cl-vnavigation li").click(function(e){
          if((($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",this).length > 0) && !($(window).width() < 755)){
            showMenu(this, e);
            e.stopPropagation();
          }
        });
        
        $(".cl-vnavigation li").on('touchstart click', function(){
          //alert($(window).width());
        });
        
      $(window).resize(function(){
        //updateHeight();
      });

      var domh = $("#pcont").height();
  
      
      /*Return to top*/
      var offset = 220;
      var duration = 500;
      var button = $('<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>');
      button.appendTo("body");
      
      jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
      });
    
      jQuery('.back-to-top').click(function(event) {
          event.preventDefault();
          jQuery('html, body').animate({scrollTop: 0}, duration);
          return false;
      });
      
  
  /*Bind plugins on hidden elements*/
  /*Dropdown shown event*/
  $('.dropdown').on('shown.bs.dropdown', function () {
    $(".nscroller").nanoScroller();
  });
    
  /*Tabs refresh hidden elements*/
  $('.nav-tabs').on('shown.bs.tab', function (e) {
    $(".nscroller").nanoScroller();
  });
  

});
        
  $(function(){
    if($('body').hasClass('animated')){
      $("#cl-wrapper").css({opacity:1,'margin-left':0});
    }
    
    /*Porlets Actions*/
    $('.minimize').click(function(e){
      var h = $(this).parents(".header");
      var c = h.next('.content');
      var p = h.parent();
      
      c.slideToggle();
      
      p.toggleClass('closed');
      
      e.preventDefault();
    });
    
    $('.refresh').click(function(e){
      var h = $(this).parents(".header");
      var p = h.parent();
      var loading = $('<div class="loading"><i class="fa fa-refresh fa-spin"></i></div>');
      
      loading.appendTo(p);
      loading.fadeIn();
      setTimeout(function() {
        loading.fadeOut();
      }, 1000);
      
      e.preventDefault();
    });
    
    $('.close-down').click(function(e){
      var h = $(this).parents(".header");
      var p = h.parent();
      
      p.fadeOut(function(){
        $(this).remove();
      });
      e.preventDefault();
    });
    /*End of porlets actions*/
    
    /*Chat*/
    
    $('.side-chat .content .contacts li a').click(function(e){
      var user = $('<span>' + $(this).html() + '</span>');
      user.find('i').remove();
      
      $('#chat-box').fadeIn();
      $('#chat-box .header span').html(user.html());
      $("#chat-box .nano").nanoScroller();
      $("#chat-box .nano").nanoScroller({ scroll: 'top' });
      e.preventDefault();
    });
    
    $('#chat-box .header .close').click(function(r){
      var h = $(this).parents(".header");
      var p = h.parent();
      
      p.fadeOut();
      r.preventDefault();
    });
    
    function addText(input){
      var message = input.val();
      var chat = input.parents('#chat-box').find('.content .conversation');
      
      if(message != ''){
       input.val('');
       chat.append('<li class="text-right"><p>' + message + '</p></li>');
       $("#chat-box .nano .content").animate({ scrollTop: $("#chat-box .nano .content .conversation").height() }, 1000);
      }
    }
    
    
    $('.chat-input .input-group button').click(function(){
      addText( $(this).parents('.input-group').find('input'));
    });
    
    $('.chat-input .input-group input').keypress(function(e){
      if(e.which == 13) {
         addText($(this));
      }
    });
    
    $(document).click(function(){
      $('#chat-box').fadeOut();
    
    });
      
    //Check cookie for menu collapse (ON DOCUMENT READY)
    if($.cookie('BTL_sidebar') && $.cookie('BTL_sidebar') == 'closed'){
        $('#cl-wrapper').addClass('sb-collapsed');
        $('.fa',$('#sidebar-collapse')[0]).addClass('fa-angle-right').removeClass('fa-angle-left');
    }
  });


  



////THEME JS


window.global_reload=1;
window.refreshable_objects=new Array();
window.auto_reloader=-1;











$(window).blur(function() 
{
	if(window.auto_reloader != -1) {
		console.log("DISABLE AUTO RELOAD INVISIBLE");
		window.clearInterval(window.auto_reloader);
	}
});
$(window).focus(function() {
	if(window.auto_reloader != -1 && global_reload != 0) {
		console.log("ENABLE AUTORELOAD VISIBLE");
		btl_force_reload_ui();
		btl_start_auto_reload();
	}
});

function addClassToAll(id, src) {
    
    $('[id=' + id + ']').removeClass("inline");
    $('[id=' + id + ']').removeClass("hide");
    $('[id=' + id + ']').addClass(src);
  
}


function addAssignAllImg(id, src) {
	$('[id=' + id + ']').attr("src", src);
}

function quick_look_group() {

 $('#quick_look_table').dataTable({
					"fnInitComplete": function() {
						
					},
					"iDisplayLength": 50,
					"fnDrawCallback": function ( oSettings ) {
						
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							//var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[i] ]._aData[0];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
						//$("#services_table").show();
					},
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 0 ] }
					],
					
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"bPaginate": false,
					"bFilter": false,
					"sDom": '<"top">rt<"bottom"flp><"clear">',
					"aaSorting": [[ 1, 'asc' ]],
				   "oLanguage": {
			    	"sEmptyTable": "No Services found",
            "sProcessing": "<img src='extensions/AutoDiscoverAddons/ajax-loader.gif'> Loading"
        	}
			    
       
				});
	
}

function btl_force_reload_ui() {
			console.log("FORCE LOAD");
			u = document.location.href;
			u += (u.match(/\?/) ? '&' : '?') + "json=1";
		
			$.getJSON(u, function(data) {
				btl_call_refreshable_objects(data);



			});
		
			



}
function btl_start_auto_reload() {
		
		window.auto_reloader= window.setInterval(function() {
	
		btl_force_reload_ui();
			
		},5000);
		
	}
	

function btl_get_refreshable_value(data, key) {
	rv=data.refreshable_objects[key];

	return rv;
}

function btl_add_refreshable_object(fcn_callback) {
		o = {
			callback: fcn_callback			
		}	
		window.refreshable_objects.push(o);
		
}
function toFixed(num, fixed) {
    fixed = fixed || 0;
    fixed = Math.pow(10, fixed);
    return Math.ceil(num * fixed) / fixed;
}
function btl_set_bars() {
	$(".bar").each(function() {
				px=$(this).css("width").replace(/px/, "");
				if(px > 25) {
					$(this).html($(this).data("perc") + '%');
				} else {
					$(this).html("");
				}
			});
		
}
function btl_call_refreshable_objects(data) {
	if(typeof(window.refreshable_objects.length) == "undefined") {
		return;
	}
	for(x=0; x<window.refreshable_objects.length; x++) {
		tw = 	window.refreshable_objects[x];
		tw.callback(data);
	}


	btl_set_bars();

}
	

function btl_change(t) {
		document.location.href='bartlby_action.php?set_instance_id=' + t.selectedIndex + '&action=set_instance_id';
}

function bulk_server_edit(mode) {
	servers_to_handle=new Array();
			$('.server_checkbox').each(function() {
				
				if($(this).is(':checked')) {
						servers_to_handle.push($(this).data("server_id"));
				}
			});
			console.log("Handle Servers");
			console.log(servers_to_handle);

			xajax_bulkEditValuesServer(servers_to_handle, xajax.getFormValues("servers_bulk_form"), mode);

}


function bulk_service_edit(mode) {
	services_to_handle=new Array();
			$('.service_checkbox').each(function() {
				
				if($(this).is(':checked')) {
						services_to_handle.push($(this).data("service_id"));
				}
			});
			console.log("Handle Services");
			console.log(services_to_handle);

			xajax_bulkEditValues(services_to_handle, xajax.getFormValues("services_bulk_form"), mode);

}


$(document).ready(function() {
		btl_set_bars();




/*
SELECT BOXES
*/


/* email input */
window.REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                  '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

$('.email_input').selectize({
    plugins: ['remove_button', 'drag_drop'],
    persist: false,
    maxItems: null,
    delimiter: ';',
    valueField: 'email',
    labelField: 'name',
    searchField: ['name', 'email'],
    options:null,
    render: {
        item: function(item, escape) {
            return '<div>' +
                (item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
            '</div>';
        },
        option: function(item, escape) {
            var label = item.name || item.email;
            var caption = item.name ? item.email : null;
            return '<div>' +
                '<span class="label">' + escape(label) + '</span>' +
                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
            '</div>';
        }
    },
    createFilter: function(input) {
        var match, regex;

        // email@address.com
        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[0]);

        // name <email@address.com>
        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[2]);

        return false;
    },
    create: function(input) {
        if ((new RegExp('^' + window.REGEX_EMAIL + '$', 'i')).test(input)) {
            return {email: input};
        }
        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
        if (match) {
            return {
                email : match[2],
                name  : $.trim(match[1])
            };
        }
        alert('Invalid email address.');
        return false;
    }
});

/* email input */
//chosen - improves select
  /*Slider*/
        $('.service_deepnes').slider().on('slide', function() {
            
            xajax_setServiceDisplayPrio($(this).val());
        });     
      
  $('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
   });
  /*Switch*/
  $('.switch').bootstrapSwitch();  

  $('#grp_service_id').change(function(f) {
          group_str_selected(f);
  });
  $('button[id^=\"remove_service_\"]').click(function(f) {
        group_str_remove(f);
  });
  
  $("#server_checkbox_select_all").click(function() {
    if($(this).is(':checked')) {
      console.log("check all");
      $('.server_checkbox').attr("checked", "checked");
    } else {
      $('.server_checkbox').removeAttr("checked", "checked");
    }
  });


	$('.datepicker').datepicker({nextText: "&nbsp;", prevText:"&nbsp;",showButtonPanel: true});
	$('.datetimepicker').datetimepicker({nextText:"&nbsp;", prevText:"&nbsp;", showButtonPanel: true});
	

  //tabs
  $('#myTab a:first').tab('show');
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });
  $('#coreTabs a:first').tab('show');
  $('#coreTabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });
  
  
	$('[data-rel="chosen"],[rel="chosen"]').selectize({
    	create: false,
    	plugins: ['remove_button', 'drag_drop'],
    	sortField: 'text'
	});


  //Typeahead
  



//Typeahead


  //initialize the calendar
    $('#external-events div.external-event').each(function() {

    // it doesn't need to have a start or end
    var eventObject = {
      title: $.trim($(this).text()) // use the element's text as the event title
    };
    
    // store the Event Object in the DOM element so we can get to it later
    $(this).data('eventObject', eventObject);
    
    // make the event draggable using jQuery UI
    $(this).draggable({
      zIndex: 999,
      revert: true,      // will cause the event to go back to its
      revertDuration: 0  //  original position after the drag
    });
    
  });


  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    editable: false,
    timeFormat: {
        agenda: 'H:mm( - H:mm)' //h:mm{ - h:mm}'
    },
    axisFormat: 'H:mm( - H:mm)',
    droppable: false, // this allows things to be dropped onto the calendar !!!
    timeFormat: 'H:mm( - H:mm)' 
  });
  
  
  if(typeof window.addToCalendar == 'function') {
    addToCalendar();
  }
  
  $('[data-rel="ajax_plugin_search"]').selectize({
    plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: true,
    maxItems: 1,
    placeholder: "Plugin",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'modify_service.php?new=true&dropdown_search=1&dropdown_name=service_plugin&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
              if(res == null) return callback();
              return_items=new Array();
              for(x=0; x<res.length; x++) {
                return_items=return_items.concat(res[x].items);               
                
              }
              


                callback(return_items);
            }
        });
    }
});

	$('[data-rel="ajax_grp_service_id"]').selectize({
    plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Servicegroup",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'grpstr.php?dropdown_search=1&dropdown_name=grp_service_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
              if(res == null) return callback();
              return_items=new Array();
              for(x=0; x<res.length; x++) {
                return_items=return_items.concat(res[x].items);               
                
              }
              


                callback(return_items);
            }
        });
    }
});
	
	$('[data-rel="ajax_grp_service_id"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Servicegroup",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'grpstr.php?dropdown_search=1&dropdown_name=grp_service_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});



	
	

	$('[data-rel="ajax_servergroup_list"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Servicegroup",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'servergroup_list.php?dropdown_search=1&dropdown_name=servergroup_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});



	



	
	$('[data-rel="ajax_servicegroup_list"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Servicegroup",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'servicegroup_list.php?dropdown_search=1&dropdown_name=servicegroup_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});


	

	$('[data-rel="ajax_servicegroup_members"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Servicegroup Member",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'modify_servicegroup.php?dropdown_search=1&dropdown_name=servicegroup_members[]&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});


	
	$('[data-rel="ajax_package_services"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Service",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'package_create.php?dropdown_search=1&dropdown_name=services[]&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});
	

		

	
	$('[data-rel="ajax_service_list_php"]').selectize({
		plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Service",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'service_list.php?dropdown_search=1&dropdown_name=service_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	return_items=new Array();
            	for(x=0; x<res.length; x++) {
            		return_items=return_items.concat(res[x].items);            		
            		
            	}
            	


                callback(return_items);
            }
        });
    }
});







$('[data-rel="ajax_server_list_php"]').selectize({
	plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Server",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'server_list.php?dropdown_search=1&dropdown_name=server_id&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
            	console.log("error");
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	console.log(res[0].items);
                callback(res[0].items);
            }
        });
    }
});
        


if(typeof(global_worker_id) == "undefined") global_worker_id=0;



$('[data-rel="ajax_modify_worker_services_permission"]').selectize({
     valueField: 'value',
     plugins: ['remove_button', 'drag_drop'],
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Select Some Services",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'permission_worker.php?dropdown_search=1&dropdown_name=worker_services[]&worker_id=' + global_worker_id + '&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
            	console.log("error");
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	console.log(res[0].items);
                callback(res[0].items);
            }
        });
    }
});





$('[data-rel="ajax_modify_worker_services"]').selectize({
     valueField: 'value',
     plugins: ['remove_button', 'drag_drop'],
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Select Some Services",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'modify_worker.php?dropdown_search=1&dropdown_name=worker_services[]&worker_id=' + global_worker_id + '&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
            	console.log("error");
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	console.log(res[0].items);
                callback(res[0].items);
            }
        });
    }
});


if(typeof($("#service_dead")[0]) != "undefined") {
	$("#service_dead")[0].selectize.settings.maxItems=1;
}
if(typeof($("#service_id")[0]) != "undefined") {
	$("#service_id")[0].selectize.settings.maxItems=1;
}


$('[data-rel="ajax_report_service"]').selectize({
     valueField: 'value',
     plugins: ['remove_button', 'drag_drop'],
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Select Some Services",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'create_report.php?dropdown_search=1&dropdown_name=report_service&dropdown_term=' + query,
            type: 'GET',
            dataType: 'json',
            error: function() {
            	console.log("error");
                callback();
            },
            success: function(res) {
            	if(res == null) return callback();
            	console.log(res[0].items);
                callback(res[0].items);
            }
        });
    }
});



/*SELECT BOXES EBND*/




       

		$("#service_prio_density").on("change", function() {
			
			xajax_setServiceDisplayPrio($("#service_prio_density").val());
		});

		$("#services_bulk_edit_delete").click(function() {
			if(confirm("You really want to delete the selected services?")) {
				bulk_service_edit(3);	
			}
			
		})
		$("#services_bulk_edit_run").click(function() {
			bulk_service_edit(1);
		});
		//BULK EDIT
		$("#services_bulk_edit_dry_run").click(function() {
			//Get Service id list
			bulk_service_edit(0);

		});
		$("#services_bulk_edit").click(function() {
			window.clearTimeout(window.service_list_timer); //Disable auto reload
			if($('.service_checkbox').is(":checked") == false) {
				if(!confirm("You have not selected any service if you continue - all your bulk actions will apply to EVERY services (system wide)!!")) {
					return;
				}
			}
			$('#myModal').modal('show');
		});

		$("#servers_bulk_edit_run").click(function() {
			bulk_server_edit(1);
		});
		$("#servers_bulk_edit_delete").click(function() {
			if(confirm("You really want to delete the selected services?")) {
				bulk_server_edit(3);	
			}
			
		});
		//BULK EDIT SERVER
		$("#servers_bulk_edit_dry_run").click(function() {
			//Get Service id list
			bulk_server_edit(0);

		});
		$("#servers_bulk_edit").click(function() {
			window.clearTimeout(window.server_list_timer); //Disable auto reload
			if($('.server_checkbox').is(":checked") == false) {
				if(!confirm("You have not selected any server if you continue - all your bulk actions will apply to EVERY server (system wide)!!")) {
					return;
				}
			}
			$('#myModal').modal('show');
		});



		$("#services_bulk_force").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkForce(force_services);
		
	});
	
	
	$("#services_bulk_enable_checks").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkEnableChecks(force_services);
		
	});
	
	$("#services_bulk_disable_checks").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkDisableChecks(force_services);
		
	});
	
	
	$("#services_bulk_enable_notifys").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkEnableNotifys(force_services);
		
	});
	
	$("#services_bulk_disable_notifys").click(function() {
		var force_services = new Array();
			$('.service_checkbox').each(function() {
				if($(this).is(':checked')) {
						force_services.push($(this).data("service_id"));
				}
			});
			xajax_bulkDisableNotifys(force_services);
		
	});
	
	
	
	



	
	
	//Service-DataTable
		s_url = document.location.href.replace(/\/s.*\.php/, "/services.php");
		s_char = "?";
		if(s_url.match(/\?/)) {
			s_char = "&";
		}
		
		server_ajax_url = document.location.href.replace(/\/s.*\.php/, "/servers.php");
		server_char = "?";
		if(server_ajax_url.match(/\?/)) {
			server_char = "&";
		}
				
	//$("#services_table").hide();
	window.oTable = $('#services_table').dataTable({
					"fnInitComplete": function() {
						
					},
					"iDisplayLength": 50,
					"fnDrawCallback": function ( oSettings ) {
			       
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						checkCheckBoxes();
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							//var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[i] ]._aData[1];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
						//$("#services_table").show();
					},
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 1 ] }
					],
					"aoColumns": [
						{ "sWidth": "10" , "sClass": "center_td" },
						{ "sWidth": "10" , "sClass": "center_td" },
						{ "sWidth": "90", "sClass": "center_td" },
						{ "sWidth": "140", "sClass": "center_td" },
						{ "sWidth": "10%", "sClass": "center_td" },
						{ "sWidth": "50%" },
						{ "sWidth": "160" },
						
					],
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"aaSorting": [[ 1, 'asc' ]],
				
			    "sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
			    "sAjaxSource": s_url + s_char + "datatables_output=1",
			    "bServerSide": true,
			    "bProcessing": true,
			    "oTableTools": {
        	"sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
            "aButtons": [
                {
                    "sExtends":    "collection",
                    "sButtonText": "Export",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                }
            ]
        },
			    "oLanguage": {
			    	"sEmptyTable": "No Services found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
        	}
			    
       
				});
				
window.servers_table = $('#servers_table').dataTable({
					"iDisplayLength": 50,
					
					"aoColumns": [
						{ "sWidth": "1" },
						{ "sWidth": "100" },
						{ "sWidth": "100" },
						{ "sWidth": "20" },
						{ "sWidth": "150" }
						],
					"aaSortingFixed": [[ 0, 'asc' ]],
					"bSort": false,
					"aaSorting": [[ 1, 'asc' ]],
					"sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
			    "sAjaxSource": server_ajax_url + server_char + "datatables_output=1",
			    "bServerSide": true,
			    "bProcessing": true,
		
        "oTableTools": {
        	"sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
            "aButtons": [
                {
                    "sExtends":    "collection",
                    "sButtonText": "Export",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                }
            ]
        },
			    "oLanguage": {
			    	"sEmptyTable": "No Servers found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
        	}
			    
       
				});
		 
		$("#toggle_reload").on('ifClicked', function() {
      console.log("AUTO RELOAD TOOGLE");
			if(global_reload == 1) {
					global_reload=0;
					window.clearInterval(window.auto_reloader);
          window.auto_reloader=-1;
          console.log("STOP");
			} else {
				global_reload=1;
				btl_start_auto_reload();
			}
		});	
	});





	
	function downtime_type_selected() {
		drop = document.getElementsByName("downtime_type")[0];
		url ="";
		if(drop.options[drop.selectedIndex].value == 1) 	url = "service_list.php?script=add_downtime.php&pkey=downtime_type&pval=1";
		if(drop.options[drop.selectedIndex].value == 2) 	url = "server_list.php?script=add_downtime.php&pkey=downtime_type&pval=2";
		if(drop.options[drop.selectedIndex].value == 3)  	url = "servergroup_list.php?script=add_downtime.php&pkey=downtime_type&pval=3";
		if(drop.options[drop.selectedIndex].value == 4) 	url = "servicegroup_list.php?script=add_downtime.php&pkey=downtime_type&pval=4";
		
		document.location.href=url;
	}
	function GenericToggleFix(elID, st) {
	//alert(elID);
	//alert(st);
		obj=document.getElementById(elID);
		//alert(obj);
		obj.style.display=st;  
	}
	function GenericToggle(elID) {
		obj=document.getElementById(elID);
		obj.style.display=!(obj.style.display=="block")? "block" : "none";  
	}
	function jsLogout() {
		r=confirm("You really want to logout?");	
		if(r == true) {
			document.location.href='logout.php';	
		}
	}
	function doToggle(elID) {
		switch(elID) {
			case 'main':
				elID="Monitoring";
			break;
			case 'report':
				elID="Reporting";
			break;
			case 'client':
				elID="Server/s";
			break;
			case 'services':
				elID="Service/s";
			break;
			case 'downtimes':
				elID="Downtime/s";
			break;
			case 'worker':
				elID="Worker/s";
			break;
			case 'core':
				elID="Core";
			break;
			
		}
		//imgPlus='themes/'+js_theme_name+'/images/plus.gif';
		//imgMinus='themes/'+js_theme_name+'/images/minus.gif';
		//obj=document.getElementById(elID + "_sub");
		//obj.style.display=!(obj.style.display=="block")? "block" : "none";  
		
		
		//obji=document.getElementById(elID + "_plus");
		//cImg="images" + obji.src.substring(obji.src.lastIndexOf("/"), obji.src.length);
		
		
		//obji.src=!(cImg==imgMinus)? imgMinus : imgPlus;  
		
	}

	var buffer_suggest = 
	{
	        bufferText: false,
	        bufferTime: 500,
	        
	        modified : function(strId, fcn, scr)
	        {
	                setTimeout('buffer_suggest.compareBuffer("'+strId+'","'+document.getElementById(strId).value+'","'+ fcn +'", "'+scr+'");', this.bufferTime);
	        },
	        
	        compareBuffer : function(strId, strText, fcn, scr)
	        {
	            if (strText == document.getElementById(strId).value && strText != this.bufferText)
	            {
	                this.bufferText = strText;
	                buffer_suggest.makeRequest(strId, fcn, scr);
	            }
	        },
	        
	        makeRequest : function(strId, fcn, scr)
	        {
	            	            
	            eval(fcn + "(document.getElementById(strId).value, scr)");
	        }
	}



function serviceManageIconChange(f) {
	selval=f.server_icon.options[f.server_icon.selectedIndex].value;
	ph = document.getElementById("picholder");
	ph.innerHTML="<img src='server_icons/" + selval + "'>";
		
}
function openMap() {
	window.open('create_map.php','','width=1024,height=786, scrollbar=yes, scrollbars=yes')
}
function doReloadButton() {
	var obj = document.getElementById("reload");
        obj.style.visibility = "visible";
}

var menuwidth='250px' //default menu width
var menubgcolor='999999'  //menu bgcolor
var disappeardelay=250  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="yes" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var topedge=ie4 && !window.opera? iecompattest().scrollTop : window.pageYOffset
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure){ //move up?
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge
}
}
return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue()
}
function checkCheckBoxes() {
    $('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
   });
  $("#service_checkbox_select_all").on('ifClicked',function() {
    if(!$(this).is(':checked')) {
      console.log("check all");
      
      $('.service_checkbox').iCheck('check');
    } else {
      console.log("UNCHECK ALL");
    
      $('.service_checkbox').iCheck('uncheck');
    }
  });
  
   
}
function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu







k=hidemenu








=hidemenu








