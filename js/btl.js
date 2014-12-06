///*THEME JS */

//++++ nanoscroller
/*! nanoScrollerJS - v0.7.4 - (c) 2013 James Florentino; Licensed MIT */
!function(a,b,c){"use strict";var d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x;w={paneClass:"pane",sliderClass:"slider",contentClass:"content",iOSNativeScrolling:!1,preventPageScrolling:!1,disableResize:!1,alwaysVisible:!1,flashDelay:1500,sliderMinHeight:20,sliderMaxHeight:null,documentContext:null,windowContext:null},s="scrollbar",r="scroll",k="mousedown",l="mousemove",n="mousewheel",m="mouseup",q="resize",h="drag",u="up",p="panedown",f="DOMMouseScroll",g="down",v="wheel",i="keydown",j="keyup",t="touchmove",d="Microsoft Internet Explorer"===b.navigator.appName&&/msie 7./i.test(b.navigator.appVersion)&&b.ActiveXObject,e=null,x=function(){var a,b,d;return a=c.createElement("div"),b=a.style,b.position="absolute",b.width="100px",b.height="100px",b.overflow=r,b.top="-9999px",c.body.appendChild(a),d=a.offsetWidth-a.clientWidth,c.body.removeChild(a),d},o=function(){function i(d,f){this.el=d,this.options=f,e||(e=x()),this.$el=a(this.el),this.doc=a(this.options.documentContext||c),this.win=a(this.options.windowContext||b),this.$content=this.$el.children("."+f.contentClass),this.$content.attr("tabindex",this.options.tabIndex||0),this.content=this.$content[0],this.options.iOSNativeScrolling&&null!=this.el.style.WebkitOverflowScrolling?this.nativeScrolling():this.generate(),this.createEvents(),this.addEvents(),this.reset()}return i.prototype.preventScrolling=function(a,b){if(this.isActive)if(a.type===f)(b===g&&a.originalEvent.detail>0||b===u&&a.originalEvent.detail<0)&&a.preventDefault();else if(a.type===n){if(!a.originalEvent||!a.originalEvent.wheelDelta)return;(b===g&&a.originalEvent.wheelDelta<0||b===u&&a.originalEvent.wheelDelta>0)&&a.preventDefault()}},i.prototype.nativeScrolling=function(){this.$content.css({WebkitOverflowScrolling:"touch"}),this.iOSNativeScrolling=!0,this.isActive=!0},i.prototype.updateScrollValues=function(){var a;a=this.content,this.maxScrollTop=a.scrollHeight-a.clientHeight,this.prevScrollTop=this.contentScrollTop||0,this.contentScrollTop=a.scrollTop,this.iOSNativeScrolling||(this.maxSliderTop=this.paneHeight-this.sliderHeight,this.sliderTop=0===this.maxScrollTop?0:this.contentScrollTop*this.maxSliderTop/this.maxScrollTop)},i.prototype.createEvents=function(){var a=this;this.events={down:function(b){return a.isBeingDragged=!0,a.offsetY=b.pageY-a.slider.offset().top,a.pane.addClass("active"),a.doc.bind(l,a.events[h]).bind(m,a.events[u]),!1},drag:function(b){return a.sliderY=b.pageY-a.$el.offset().top-a.offsetY,a.scroll(),a.updateScrollValues(),a.contentScrollTop>=a.maxScrollTop&&a.prevScrollTop!==a.maxScrollTop?a.$el.trigger("scrollend"):0===a.contentScrollTop&&0!==a.prevScrollTop&&a.$el.trigger("scrolltop"),!1},up:function(){return a.isBeingDragged=!1,a.pane.removeClass("active"),a.doc.unbind(l,a.events[h]).unbind(m,a.events[u]),!1},resize:function(){a.reset()},panedown:function(b){return a.sliderY=(b.offsetY||b.originalEvent.layerY)-.5*a.sliderHeight,a.scroll(),a.events.down(b),!1},scroll:function(b){a.isBeingDragged||(a.updateScrollValues(),a.iOSNativeScrolling||(a.sliderY=a.sliderTop,a.slider.css({top:a.sliderTop})),null!=b&&(a.contentScrollTop>=a.maxScrollTop?(a.options.preventPageScrolling&&a.preventScrolling(b,g),a.prevScrollTop!==a.maxScrollTop&&a.$el.trigger("scrollend")):0===a.contentScrollTop&&(a.options.preventPageScrolling&&a.preventScrolling(b,u),0!==a.prevScrollTop&&a.$el.trigger("scrolltop"))))},wheel:function(b){var c;if(null!=b)return c=b.delta||b.wheelDelta||b.originalEvent&&b.originalEvent.wheelDelta||-b.detail||b.originalEvent&&-b.originalEvent.detail,c&&(a.sliderY+=-c/3),a.scroll(),!1}}},i.prototype.addEvents=function(){var a;this.removeEvents(),a=this.events,this.options.disableResize||this.win.bind(q,a[q]),this.iOSNativeScrolling||(this.slider.bind(k,a[g]),this.pane.bind(k,a[p]).bind(""+n+" "+f,a[v])),this.$content.bind(""+r+" "+n+" "+f+" "+t,a[r])},i.prototype.removeEvents=function(){var a;a=this.events,this.win.unbind(q,a[q]),this.iOSNativeScrolling||(this.slider.unbind(),this.pane.unbind()),this.$content.unbind(""+r+" "+n+" "+f+" "+t,a[r])},i.prototype.generate=function(){var a,b,c,d,f;return c=this.options,d=c.paneClass,f=c.sliderClass,a=c.contentClass,this.$el.find(""+d).length||this.$el.find(""+f).length||this.$el.append('<div class="'+d+'"><div class="'+f+'" /></div>'),this.pane=this.$el.children("."+d),this.slider=this.pane.find("."+f),e&&(b={right:-e},this.$el.addClass("has-scrollbar")),null!=b&&this.$content.css(b),this},i.prototype.restore=function(){this.stopped=!1,this.pane.show(),this.addEvents()},i.prototype.reset=function(){var a,b,c,f,g,h,i,j,k,l;return this.iOSNativeScrolling?(this.contentHeight=this.content.scrollHeight,void 0):(this.$el.find("."+this.options.paneClass).length||this.generate().stop(),this.stopped&&this.restore(),a=this.content,c=a.style,f=c.overflowY,d&&this.$content.css({height:this.$content.height()}),b=a.scrollHeight+e,k=parseInt(this.$el.css("max-height"),10),k>0&&(this.$el.height(""),this.$el.height(a.scrollHeight>k?k:a.scrollHeight)),h=this.pane.outerHeight(!1),j=parseInt(this.pane.css("top"),10),g=parseInt(this.pane.css("bottom"),10),i=h+j+g,l=Math.round(i/b*i),l<this.options.sliderMinHeight?l=this.options.sliderMinHeight:null!=this.options.sliderMaxHeight&&l>this.options.sliderMaxHeight&&(l=this.options.sliderMaxHeight),f===r&&c.overflowX!==r&&(l+=e),this.maxSliderTop=i-l,this.contentHeight=b,this.paneHeight=h,this.paneOuterHeight=i,this.sliderHeight=l,this.slider.height(l),this.events.scroll(),this.pane.show(),this.isActive=!0,a.scrollHeight===a.clientHeight||this.pane.outerHeight(!0)>=a.scrollHeight&&f!==r?(this.pane.hide(),this.isActive=!1):this.el.clientHeight===a.scrollHeight&&f===r?this.slider.hide():this.slider.show(),this.pane.css({opacity:this.options.alwaysVisible?1:"",visibility:this.options.alwaysVisible?"visible":""}),this)},i.prototype.scroll=function(){return this.isActive?(this.sliderY=Math.max(0,this.sliderY),this.sliderY=Math.min(this.maxSliderTop,this.sliderY),this.$content.scrollTop(-1*((this.paneHeight-this.contentHeight+e)*this.sliderY/this.maxSliderTop)),this.iOSNativeScrolling||this.slider.css({top:this.sliderY}),this):void 0},i.prototype.scrollBottom=function(a){return this.isActive?(this.reset(),this.$content.scrollTop(this.contentHeight-this.$content.height()-a).trigger(n),this):void 0},i.prototype.scrollTop=function(a){return this.isActive?(this.reset(),this.$content.scrollTop(+a).trigger(n),this):void 0},i.prototype.scrollTo=function(b){return this.isActive?(this.reset(),this.scrollTop(a(b).get(0).offsetTop),this):void 0},i.prototype.stop=function(){return this.stopped=!0,this.removeEvents(),this.pane.hide(),this},i.prototype.destroy=function(){return this.stopped||this.stop(),this.pane.length&&this.pane.remove(),d&&this.$content.height(""),this.$content.removeAttr("tabindex"),this.$el.hasClass("has-scrollbar")&&(this.$el.removeClass("has-scrollbar"),this.$content.css({right:""})),this},i.prototype.flash=function(){var a=this;if(this.isActive)return this.reset(),this.pane.addClass("flashed"),setTimeout(function(){a.pane.removeClass("flashed")},this.options.flashDelay),this},i}(),a.fn.nanoScroller=function(b){return this.each(function(){var c,d;if((d=this.nanoscroller)||(c=a.extend({},w,b),this.nanoscroller=d=new o(this,c)),b&&"object"==typeof b){if(a.extend(d.options,b),b.scrollBottom)return d.scrollBottom(b.scrollBottom);if(b.scrollTop)return d.scrollTop(b.scrollTop);if(b.scrollTo)return d.scrollTo(b.scrollTo);if("bottom"===b.scroll)return d.scrollBottom(0);if("top"===b.scroll)return d.scrollTop(0);if(b.scroll&&b.scroll instanceof a)return d.scrollTo(b.scroll);if(b.stop)return d.stop();if(b.destroy)return d.destroy();if(b.flash)return d.flash()}return d.reset()})},a.fn.nanoScroller.Constructor=o}(jQuery,window,document);
//# sourceMappingURL=jquery.nanoscroller.min.js.map

//SWEET ALERT
!function(a,b){function c(b){var c=p(),d=c.querySelector("h2"),e=c.querySelector("p"),f=c.querySelector("button.cancel"),g=c.querySelector("button.confirm");if(d.innerHTML=u(b.title).split("\n").join("<br>"),e.innerHTML=u(b.text||"").split("\n").join("<br>"),b.text&&w(e),y(c.querySelectorAll(".icon")),b.type){for(var h=!1,i=0;i<n.length;i++)if(b.type===n[i]){h=!0;break}if(!h)return a.console.error("Unknown alert type: "+b.type),!1;var j=c.querySelector(".icon."+b.type);switch(w(j),b.type){case"success":s(j,"animate"),s(j.querySelector(".tip"),"animateSuccessTip"),s(j.querySelector(".long"),"animateSuccessLong");break;case"error":s(j,"animateErrorIcon"),s(j.querySelector(".x-mark"),"animateXMark");break;case"warning":s(j,"pulseWarning"),s(j.querySelector(".body"),"pulseWarningIns"),s(j.querySelector(".dot"),"pulseWarningIns")}}if(b.imageUrl){var k=c.querySelector(".icon.custom");k.style.backgroundImage="url("+b.imageUrl+")",w(k);var l=80,m=80;if(b.imageSize){var o=b.imageSize.split("x")[0],q=b.imageSize.split("x")[1];o&&q?(l=o,m=q,k.css({width:o+"px",height:q+"px"})):a.console.error("Parameter imageSize expects value with format WIDTHxHEIGHT, got "+b.imageSize)}k.setAttribute("style",k.getAttribute("style")+"width:"+l+"px; height:"+m+"px")}c.setAttribute("data-has-cancel-button",b.showCancelButton),b.showCancelButton?f.style.display="inline-block":y(f),b.cancelButtonText&&(f.innerHTML=u(b.cancelButtonText)),b.confirmButtonText&&(g.innerHTML=u(b.confirmButtonText)),g.className="confirm btn btn-lg",s(g,b.confirmButtonClass),c.setAttribute("data-allow-ouside-click",b.allowOutsideClick);var r=b.doneFunction?!0:!1;c.setAttribute("data-has-done-function",r),c.setAttribute("data-timer",b.timer)}function d(a,b){for(var c in b)b.hasOwnProperty(c)&&(a[c]=b[c]);return a}function e(){var a=p();B(q(),10),w(a),s(a,"showSweetAlert"),t(a,"hideSweetAlert"),h=b.activeElement;var c=a.querySelector("button.confirm");c.focus(),setTimeout(function(){s(a,"visible")},500);var d=a.getAttribute("data-timer");"null"!==d&&""!==d&&setTimeout(function(){f()},d)}function f(){var c=p();C(q(),5),C(c,5),t(c,"showSweetAlert"),s(c,"hideSweetAlert"),t(c,"visible");var d=c.querySelector(".icon.success");t(d,"animate"),t(d.querySelector(".tip"),"animateSuccessTip"),t(d.querySelector(".long"),"animateSuccessLong");var e=c.querySelector(".icon.error");t(e,"animateErrorIcon"),t(e.querySelector(".x-mark"),"animateXMark");var f=c.querySelector(".icon.warning");t(f,"pulseWarning"),t(f.querySelector(".body"),"pulseWarningIns"),t(f.querySelector(".dot"),"pulseWarningIns"),a.onkeydown=j,b.onclick=i,h&&h.focus(),k=void 0}function g(){var a=p();a.style.marginTop=A(p())}var h,i,j,k,l=".sweet-alert",m=".sweet-overlay",n=["error","warning","info","success"],o={title:"",text:"",type:null,allowOutsideClick:!1,showCancelButton:!1,closeOnConfirm:!0,closeOnCancel:!0,confirmButtonText:"OK",confirmButtonClass:"btn-primary",cancelButtonText:"Cancel",imageUrl:null,imageSize:null,timer:null},p=function(){return b.querySelector(l)},q=function(){return b.querySelector(m)},r=function(a,b){return new RegExp(" "+b+" ").test(" "+a.className+" ")},s=function(a,b){r(a,b)||(a.className+=" "+b)},t=function(a,b){var c=" "+a.className.replace(/[\t\r\n]/g," ")+" ";if(r(a,b)){for(;c.indexOf(" "+b+" ")>=0;)c=c.replace(" "+b+" "," ");a.className=c.replace(/^\s+|\s+$/g,"")}},u=function(a){var c=b.createElement("div");return c.appendChild(b.createTextNode(a)),c.innerHTML},v=function(a){a.style.opacity="",a.style.display="block"},w=function(a){if(a&&!a.length)return v(a);for(var b=0;b<a.length;++b)v(a[b])},x=function(a){a.style.opacity="",a.style.display="none"},y=function(a){if(a&&!a.length)return x(a);for(var b=0;b<a.length;++b)x(a[b])},z=function(a,b){for(var c=b.parentNode;null!==c;){if(c===a)return!0;c=c.parentNode}return!1},A=function(a){a.style.left="-9999px",a.style.display="block";var b=a.clientHeight,c=parseInt(getComputedStyle(a).getPropertyValue("padding"),10);return a.style.left="",a.style.display="none","-"+parseInt(b/2+c)+"px"},B=function(a,b){if(+a.style.opacity<1){b=b||16,a.style.opacity=0,a.style.display="block";var c=+new Date,d=function(){a.style.opacity=+a.style.opacity+(new Date-c)/100,c=+new Date,+a.style.opacity<1&&setTimeout(d,b)};d()}},C=function(a,b){b=b||16,a.style.opacity=1;var c=+new Date,d=function(){a.style.opacity=+a.style.opacity-(new Date-c)/100,c=+new Date,+a.style.opacity>0?setTimeout(d,b):a.style.display="none"};d()},D=function(c){if(MouseEvent){var d=new MouseEvent("click",{view:a,bubbles:!1,cancelable:!0});c.dispatchEvent(d)}else if(b.createEvent){var e=b.createEvent("MouseEvents");e.initEvent("click",!1,!1),c.dispatchEvent(e)}else b.createEventObject?c.fireEvent("onclick"):"function"==typeof c.onclick&&c.onclick()},E=function(b){"function"==typeof b.stopPropagation?(b.stopPropagation(),b.preventDefault()):a.event&&a.event.hasOwnProperty("cancelBubble")&&(a.event.cancelBubble=!0)};a.sweetAlertInitialize=function(){var a='<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert" tabIndex="-1"><div class="icon error"><span class="x-mark"><span class="line left"></span><span class="line right"></span></span></div><div class="icon warning"> <span class="body"></span> <span class="dot"></span> </div> <div class="icon info"></div> <div class="icon success"> <span class="line tip"></span> <span class="line long"></span> <div class="placeholder"></div> <div class="fix"></div> </div> <div class="icon custom"></div> <h2>Title</h2><p class="lead text-muted">Text</p><p><button class="cancel btn btn-default btn-lg" tabIndex="2">Cancel</button> <button class="confirm btn btn-lg" tabIndex="1">OK</button></p></div>',c=b.createElement("div");c.innerHTML=a,b.body.appendChild(c)},a.sweetAlert=a.swal=function(){function h(a){var b=a.keyCode||a.which;if(-1!==[9,13,32,27].indexOf(b)){for(var c=a.target||a.srcElement,d=-1,e=0;e<w.length;e++)if(c===w[e]){d=e;break}9===b?(c=-1===d?u:d===w.length-1?w[0]:w[d+1],E(a),c.focus()):(c=13===b||32===b?-1===d?u:void 0:27!==b||v.hidden||"none"===v.style.display?void 0:v,void 0!==c&&D(c,a))}}function l(a){var b=a.target||a.srcElement,c=a.relatedTarget,d=r(n,"visible");if(d){var e=-1;if(null!==c){for(var f=0;f<w.length;f++)if(c===w[f]){e=f;break}-1===e&&b.focus()}else k=b}}if(void 0===arguments[0])return a.console.error("sweetAlert expects at least 1 attribute!"),!1;var m=d({},o);switch(typeof arguments[0]){case"string":m.title=arguments[0],m.text=arguments[1]||"",m.type=arguments[2]||"";break;case"object":if(void 0===arguments[0].title)return a.console.error('Missing "title" argument!'),!1;m.title=arguments[0].title,m.text=arguments[0].text||o.text,m.type=arguments[0].type||o.type,m.allowOutsideClick=arguments[0].allowOutsideClick||o.allowOutsideClick,m.showCancelButton=void 0!==arguments[0].showCancelButton?arguments[0].showCancelButton:o.showCancelButton,m.closeOnConfirm=void 0!==arguments[0].closeOnConfirm?arguments[0].closeOnConfirm:o.closeOnConfirm,m.closeOnCancel=void 0!==arguments[0].closeOnCancel?arguments[0].closeOnCancel:o.closeOnCancel,m.timer=arguments[0].timer||o.timer,m.confirmButtonText=o.showCancelButton?"Confirm":o.confirmButtonText,m.confirmButtonText=arguments[0].confirmButtonText||o.confirmButtonText,m.confirmButtonClass=arguments[0].confirmButtonClass||o.confirmButtonClass,m.cancelButtonText=arguments[0].cancelButtonText||o.cancelButtonText,m.imageUrl=arguments[0].imageUrl||o.imageUrl,m.imageSize=arguments[0].imageSize||o.imageSize,m.doneFunction=arguments[1]||null;break;default:return a.console.error('Unexpected type of argument! Expected "string" or "object", got '+typeof arguments[0]),!1}c(m),g(),e();for(var n=p(),q=function(a){var b=a.target||a.srcElement,c=b.className.indexOf("confirm")>-1,d=r(n,"visible"),e=m.doneFunction&&"true"===n.getAttribute("data-has-done-function");switch(a.type){case"click":if(c&&e&&d)m.doneFunction(!0),m.closeOnConfirm&&f();else if(e&&d){var g=String(m.doneFunction).replace(/\s/g,""),h="function("===g.substring(0,9)&&")"!==g.substring(9,10);h&&m.doneFunction(!1),m.closeOnCancel&&f()}else f()}},s=n.querySelectorAll("button"),t=0;t<s.length;t++)s[t].onclick=q;i=b.onclick,b.onclick=function(a){var b=a.target||a.srcElement,c=n===b,d=z(n,a.target),e=r(n,"visible"),g="true"===n.getAttribute("data-allow-ouside-click");!c&&!d&&e&&g&&f()};var u=n.querySelector("button.confirm"),v=n.querySelector("button.cancel"),w=n.querySelectorAll("button:not([type=hidden])");j=a.onkeydown,a.onkeydown=h,u.onblur=l,v.onblur=l,a.onfocus=function(){a.setTimeout(function(){void 0!==k&&(k.focus(),k=void 0)},0)}},a.swal.setDefaults=function(a){if(!a)throw new Error("userParams is required");if("object"!=typeof a)throw new Error("userParams has to be a object");d(o,a)},function(){"complete"===b.readyState||"interactive"===b.readyState&&b.body?sweetAlertInitialize():b.addEventListener?b.addEventListener("DOMContentLoaded",function(){b.removeEventListener("DOMContentLoaded",arguments.callee,!1),sweetAlertInitialize()},!1):b.attachEvent&&b.attachEvent("onreadystatechange",function(){"complete"===b.readyState&&(b.detachEvent("onreadystatechange",arguments.callee),sweetAlertInitialize())})}()}(window,document);


//SHA1
/*!
 * jQuery JSONView
 * Licensed under the MIT License. 
 */
(function(jQuery) {
  var $, Collapser, JSONFormatter, JSONView;
  JSONFormatter = (function() {
    function JSONFormatter(options) {
      if (options == null) {
        options = {};
      }
      this.options = options;
    }

    JSONFormatter.prototype.htmlEncode = function(html) {
      if (html !== null) {
        return html.toString().replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
      } else {
        return '';
      }
    };

    JSONFormatter.prototype.jsString = function(s) {
      s = JSON.stringify(s).slice(1, -1);
      return this.htmlEncode(s);
    };

    JSONFormatter.prototype.decorateWithSpan = function(value, className) {
      return "<span class=\"" + className + "\">" + (this.htmlEncode(value)) + "</span>";
    };

    JSONFormatter.prototype.valueToHTML = function(value, level) {
      var valueType;
      if (level == null) {
        level = 0;
      }
      valueType = Object.prototype.toString.call(value).match(/\s(.+)]/)[1].toLowerCase();
      return this["" + valueType + "ToHTML"].call(this, value, level);
    };

    JSONFormatter.prototype.nullToHTML = function(value) {
      return this.decorateWithSpan('null', 'null');
    };

    JSONFormatter.prototype.numberToHTML = function(value) {
      return this.decorateWithSpan(value, 'num');
    };

    JSONFormatter.prototype.stringToHTML = function(value) {
      var multilineClass, newLinePattern;
      if (/^(http|https|file):\/\/[^\s]+$/i.test(value)) {
        return "<a href=\"" + (this.htmlEncode(value)) + "\"><span class=\"q\">\"</span>" + (this.jsString(value)) + "<span class=\"q\">\"</span></a>";
      } else {
        multilineClass = '';
        value = this.jsString(value);
        if (this.options.nl2br) {
          newLinePattern = /([^>\\r\\n]?)(\\r\\n|\\n\\r|\\r|\\n)/g;
          if (newLinePattern.test(value)) {
            multilineClass = ' multiline';
            value = (value + '').replace(newLinePattern, '$1' + '<br />');
          }
        }
        return "<span class=\"string" + multilineClass + "\">\"" + value + "\"</span>";
      }
    };

    JSONFormatter.prototype.booleanToHTML = function(value) {
      return this.decorateWithSpan(value, 'bool');
    };

    JSONFormatter.prototype.arrayToHTML = function(array, level) {
      var collapsible, hasContents, index, numProps, output, value, _i, _len;
      if (level == null) {
        level = 0;
      }
      hasContents = false;
      output = '';
      numProps = array.length;
      for (index = _i = 0, _len = array.length; _i < _len; index = ++_i) {
        value = array[index];
        hasContents = true;
        output += '<li>' + this.valueToHTML(value, level + 1);
        if (numProps > 1) {
          output += ',';
        }
        output += '</li>';
        numProps--;
      }
      if (hasContents) {
        collapsible = level === 0 ? '' : ' collapsible';
        return "[<ul class=\"array level" + level + collapsible + "\">" + output + "</ul>]";
      } else {
        return '[ ]';
      }
    };

    JSONFormatter.prototype.objectToHTML = function(object, level) {
      var collapsible, hasContents, numProps, output, prop, value;
      if (level == null) {
        level = 0;
      }
      hasContents = false;
      output = '';
      numProps = 0;
      for (prop in object) {
        numProps++;
      }
      for (prop in object) {
        value = object[prop];
        hasContents = true;
        output += "<li><span class=\"prop\"><span class=\"q\">\"</span>" + (this.jsString(prop)) + "<span class=\"q\">\"</span></span>: " + (this.valueToHTML(value, level + 1));
        if (numProps > 1) {
          output += ',';
        }
        output += '</li>';
        numProps--;
      }
      if (hasContents) {
        collapsible = level === 0 ? '' : ' collapsible';
        return "{<ul class=\"obj level" + level + collapsible + "\">" + output + "</ul>}";
      } else {
        return '{ }';
      }
    };

    JSONFormatter.prototype.jsonToHTML = function(json) {
      return "<div class=\"jsonview\">" + (this.valueToHTML(json)) + "</div>";
    };

    return JSONFormatter;

  })();
  (typeof module !== "undefined" && module !== null) && (module.exports = JSONFormatter);
  Collapser = {
    bindEvent: function(item, collapsed) {
      var collapser;
      collapser = document.createElement('div');
      collapser.className = 'collapser';
      collapser.innerHTML = collapsed ? '+' : '-';
      collapser.addEventListener('click', (function(_this) {
        return function(event) {
          return _this.toggle(event.target);
        };
      })(this));
      item.insertBefore(collapser, item.firstChild);
      if (collapsed) {
        return this.collapse(collapser);
      }
    },
    expand: function(collapser) {
      var ellipsis, target;
      target = this.collapseTarget(collapser);
      ellipsis = target.parentNode.getElementsByClassName('ellipsis')[0];
      target.parentNode.removeChild(ellipsis);
      target.style.display = '';
      return collapser.innerHTML = '-';
    },
    collapse: function(collapser) {
      var ellipsis, target;
      target = this.collapseTarget(collapser);
      target.style.display = 'none';
      ellipsis = document.createElement('span');
      ellipsis.className = 'ellipsis';
      ellipsis.innerHTML = ' &hellip; ';
      target.parentNode.insertBefore(ellipsis, target);
      return collapser.innerHTML = '+';
    },
    toggle: function(collapser) {
      var target;
      target = this.collapseTarget(collapser);
      if (target.style.display === 'none') {
        return this.expand(collapser);
      } else {
        return this.collapse(collapser);
      }
    },
    collapseTarget: function(collapser) {
      var target, targets;
      targets = collapser.parentNode.getElementsByClassName('collapsible');
      if (!targets.length) {
        return;
      }
      return target = targets[0];
    }
  };
  $ = jQuery;
  JSONView = {
    collapse: function(el) {
      if (el.innerHTML === '-') {
        return Collapser.collapse(el);
      }
    },
    expand: function(el) {
      if (el.innerHTML === '+') {
        return Collapser.expand(el);
      }
    },
    toggle: function(el) {
      return Collapser.toggle(el);
    }
  };
  return $.fn.JSONView = function() {
    var args, defaultOptions, formatter, json, method, options, outputDoc;
    args = arguments;
    if (JSONView[args[0]] != null) {
      method = args[0];
      return this.each(function() {
        var $this, level;
        $this = $(this);
        if (args[1] != null) {
          level = args[1];
          return $this.find(".jsonview .collapsible.level" + level).siblings('.collapser').each(function() {
            return JSONView[method](this);
          });
        } else {
          return $this.find('.jsonview > ul > li > .collapsible').siblings('.collapser').each(function() {
            return JSONView[method](this);
          });
        }
      });
    } else {
      json = args[0];
      options = args[1] || {};
      defaultOptions = {
        collapsed: false,
        nl2br: false
      };
      options = $.extend(defaultOptions, options);
      formatter = new JSONFormatter({
        nl2br: options.nl2br
      });
      if (Object.prototype.toString.call(json) === '[object String]') {
        json = JSON.parse(json);
      }
      outputDoc = formatter.jsonToHTML(json);
      return this.each(function() {
        var $this, item, items, _i, _len, _results;
        $this = $(this);
        $this.html(outputDoc);
        items = $this[0].getElementsByClassName('collapsible');
        _results = [];
        for (_i = 0, _len = items.length; _i < _len; _i++) {
          item = items[_i];
          if (item.parentNode.nodeName === 'LI') {
            _results.push(Collapser.bindEvent(item.parentNode, options.collapsed));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
    }
  };
})(jQuery);

var objectDiff = typeof exports != 'undefined' ? exports : {};

/**
 * @param {Object} a
 * @param {Object} b
 * @return {Object}
 */
objectDiff.diff = function diff(a, b) {

	if (a === b) {
		return {
			changed: 'equal',
			value: a
		}
	}

	var value = {};
	var equal = true;

	for (var key in a) {
		if (key in b) {
			if (a[key] === b[key]) {
				value[key] = {
					changed: 'equal',
					value: a[key]
				}
			} else {
				var typeA = typeof a[key];
				var typeB = typeof b[key];
				if (a[key] && b[key] && (typeA == 'object' || typeA == 'function') && (typeB == 'object' || typeB == 'function')) {
					var valueDiff = diff(a[key], b[key]);
					if (valueDiff.changed == 'equal') {
						value[key] = {
							changed: 'equal',
							value: a[key]
						}
					} else {
						equal = false;
						value[key] = valueDiff;
					}
				} else {
					equal = false;
					value[key] = {
						changed: 'primitive change',
						removed: a[key],
						added: b[key]
					}
				}
			}
		} else {
			equal = false;
			value[key] = {
				changed: 'removed',
				value: a[key]
			}
		}
	}

	for (key in b) {
		if (!(key in a)) {
			equal = false;
			value[key] = {
				changed: 'added',
				value: b[key]
			}
		}
	}

	if (equal) {
		return {
			changed: 'equal',
			value: a
		}
	} else {
		return {
			changed: 'object change',
			value: value
		}
	}
};


/**
 * @param {Object} a
 * @param {Object} b
 * @return {Object}
 */
objectDiff.diffOwnProperties = function diffOwnProperties(a, b) {

	if (a === b) {
		return {
			changed: 'equal',
			value: a
		}
	}

	var diff = {};
	var equal = true;
	var keys = Object.keys(a);

	for (var i = 0, length = keys.length; i < length; i++) {
		var key = keys[i];
		if (b.hasOwnProperty(key)) {
			if (a[key] === b[key]) {
				diff[key] = {
					changed: 'equal',
					value: a[key]
				}
			} else {
				var typeA = typeof a[key];
				var typeB = typeof b[key];
				if (a[key] && b[key] && (typeA == 'object' || typeA == 'function') && (typeB == 'object' || typeB == 'function')) {
					var valueDiff = diffOwnProperties(a[key], b[key]);
					if (valueDiff.changed == 'equal') {
						diff[key] = {
							changed: 'equal',
							value: a[key]
						}
					} else {
						equal = false;
						diff[key] = valueDiff;
					}
				} else {
					equal = false;
					diff[key] = {
						changed: 'primitive change',
						removed: a[key],
						added: b[key]
					}
				}
			}
		} else {
			equal = false;
			diff[key] = {
				changed: 'removed',
				value: a[key]
			}
		}
	}

	keys = Object.keys(b);

	for (i = 0, length = keys.length; i < length; i++) {
		key = keys[i];
		if (!a.hasOwnProperty(key)) {
			equal = false;
			diff[key] = {
				changed: 'added',
				value: b[key]
			}
		}
	}

	if (equal) {
		return {
			value: a,
			changed: 'equal'
		}
	} else {
		return {
			changed: 'object change',
			value: diff
		}
	}
};


(function() {

	/**
	 * @param {Object} changes
	 * @return {string}
	 */
	objectDiff.convertToXMLString = function convertToXMLString(changes) {
		var properties = [];

		var diff = changes.value;
		if (changes.changed == 'equal') {
			return inspect(diff);
		}

		for (var key in diff) {
			var changed = diff[key].changed;
			switch (changed) {
				case 'equal':
					properties.push(stringifyObjectKey(escapeHTML(key)) + '<span>: </span>' + inspect(diff[key].value));
					break;

				case 'removed':
					properties.push('<del class="diff">' + stringifyObjectKey(escapeHTML(key)) + '<span>: </span>' + inspect(diff[key].value) + '</del>');
					break;

				case 'added':
					properties.push('<ins class="diff">' + stringifyObjectKey(escapeHTML(key)) + '<span>: </span>' + inspect(diff[key].value) + '</ins>');
					break;

				case 'primitive change':
					var prefix = stringifyObjectKey(escapeHTML(key)) + '<span>: </span>';
					properties.push(
						'<del class="diff diff-key">' + prefix + inspect(diff[key].removed) + '</del><span>,</span>\n' +
						'<ins class="diff diff-key">' + prefix + inspect(diff[key].added) + '</ins>');
					break;

				case 'object change':
					properties.push(stringifyObjectKey(key) + '<span>: </span>' + convertToXMLString(diff[key]));
					break;
			}
		}

		return '<span>{</span>\n<div class="diff-level">' + properties.join('<span>,</span>\n') + '\n</div><span>}</span>';
	};

	/**
	 * @param {string} key
	 * @return {string}
	 */
	function stringifyObjectKey(key) {
		return /^[a-z0-9_$]*$/i.test(key) ?
			key :
			JSON.stringify(key);
	}

	/**
	 * @param {string} string
	 * @return {string}
	 */
	function escapeHTML(string) {
		return string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	}

	/**
	 * @param {Object} obj
	 * @return {string}
	 */
	function inspect(obj) {

		return _inspect('', obj);

		/**
		 * @param {string} accumulator
		 * @param {object} obj
		 * @see http://jsperf.com/continuation-passing-style/3
		 * @return {string}
		 */
		function _inspect(accumulator, obj) {
			switch(typeof obj) {
				case 'object':
					if (!obj) {
						accumulator += 'null';
						break;
					}
					var keys = Object.keys(obj);
					var length = keys.length;
					if (length === 0) {
						accumulator += '<span>{}</span>';
					} else {
						accumulator += '<span>{</span>\n<div class="diff-level">';
						for (var i = 0; i < length; i++) {
							var key = keys[i];
							accumulator = _inspect(accumulator + stringifyObjectKey(escapeHTML(key)) + '<span>: </span>', obj[key]);
							if (i < length - 1) {
								accumulator += '<span>,</span>\n';
							}
						}
						accumulator += '\n</div><span>}</span>'
					}
					break;

				case 'string':
					accumulator += JSON.stringify(escapeHTML(obj));
					break;

				case 'undefined':
					accumulator += 'undefined';
					break;

				default:
					accumulator += escapeHTML(String(obj));
					break;
			}
			return accumulator;
		}
	}
})();


/*!
 * jQuery JSONView
 * Licensed under the MIT License. 
 */

 $.fn.dataTableExt.oApi.fnNewAjax = function ( oSettings, sNewSource  )
{
    if ( typeof sNewSource != 'undefined' && sNewSource != null )
    {
        oSettings.sAjaxSource = sNewSource;
       
    }
    this.fnDraw();
}


function logview_update_filter(d) {
	
	if(window.log_filter_query.match(/date_filter/)) {
		window.log_filter_query=log_filter_query.replace(/(.*)&date_filter=.*&(.*)/, "$1&date_filter=" + d + "&$2");
		
	} else {
		window.log_filter_query += "&date_filter=" + d + "&a=1";
	}
	
	window.logTable.fnNewAjax("logview.php?" + window.log_filter_query + "&datatables_output=1");

}
function logview_prev() {
	console.log($("#date_filter").val());
	d=$("#date_filter").val();
	p=new Date( d );
	p.setDate(p.getDate() + 1);
	d=$("#date_filter").val((p.getMonth() + 1) + '/' + p.getDate() + '/' +  p.getFullYear());
	logview_update_filter($("#date_filter").val());
}
function logview_next() {
	console.log($("#date_filter").val());
	d=$("#date_filter").val();
	p=new Date( d );
	p.setDate(p.getDate() - 1 );
	d=$("#date_filter").val((p.getMonth() + 1) + '/' + p.getDate() + '/' +  p.getFullYear());
	logview_update_filter($("#date_filter").val());
}
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
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function btl_force_reload_ui() {
			console.log("FORCE LOAD");
			u = document.location.href;
			u += (u.match(/\?/) ? '&' : '?') + "json=1";
		
			$.getJSON(u, function(data) {
				btl_call_refreshable_objects(data);
        btl_init_components();

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

function bulk_trap_edit(mode) {
	traps_to_handle=new Array();
			$('.trap_checkbox').each(function() {
				
				if($(this).is(':checked')) {
						traps_to_handle.push($(this).data("trap_id"));
				}
			});
			console.log("Handle Traps");
			console.log(traps_to_handle);

			xajax_bulkEditValuesTrap(traps_to_handle, xajax.getFormValues("traps_bulk_form"), mode);

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


function btl_init_components() {
  /* email input */




/*SELECT BOXES EBND*/
$('.switch').bootstrapSwitch();  



       

    
}
$(document).ready(function() {
		btl_set_bars();

		//SESSION POLLER 
		window.setInterval(function() {
			console.log("POLL SESSION");
			$.get("bartlby_action.php?action=poll_session");
		}, 10000);


/*
SELECT BOXES
*/


    btl_init_components();
    btl_init_one_time_components(); //like tabs




	});


function btl_init_one_time_components() {
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


//tooltip
  $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});


  //popover
  $('[rel="popover"],[data-rel="popover"]').popover({ html : true });

  

  //datatable
  $('.datatable').dataTable({
        "iDisplayLength": 50,
        
    } );

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
  
  window.plugin_search = $('[data-rel="ajax_plugin_search"]').selectize({
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

$('[data-rel="ajax_trap_list"]').selectize({
    plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Trapname",
    load: function(query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: 'trap_list.php?dropdown_search=1&dropdown_name=trap_id&dropdown_term=' + query,
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


   $('[data-rel="ajax_trap_service"]').selectize({
    plugins: ['remove_button', 'drag_drop'],
     valueField: 'value',
    labelField: 'text',
    searchField: 'text',
    create: false,
    placeholder: "Search a Service",
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
  if(typeof $("#service_dead")[0].selectize != "undefined") {
   $("#service_dead")[0].selectize.settings.maxItems=1;
  }
}
if(typeof($("#service_id")[0]) != "undefined") {
  if(typeof $("#service_id")[0].selectize != "undefined") {
   $("#service_id")[0].selectize.settings.maxItems=1;
  }
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



//Trap

	$("#traps_bulk_edit_run").click(function() {
      bulk_trap_edit(1);
    });
    $("#traps_bulk_edit_delete").click(function() {
      if(confirm("You really want to delete the selected services?")) {
        bulk_trap_edit(3);  
      }
      
    });
    //BULK EDIT trap
    $("#traps_bulk_edit_dry_run").click(function() {
      //Get Service id list
      bulk_trap_edit(0);

    });
    $("#traps_bulk_edit").click(function() {
      window.clearTimeout(window.trap_list_timer); //Disable auto reload
      if($('.trap_checkbox').is(":checked") == false) {
        if(!confirm("You have not selected any trap if you continue - all your bulk actions will apply to EVERY trap (system wide)!!")) {
          return;
        }
      }
      $('#myModal').modal('show');
    });

//Trap



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
  
	trap_ajax_url = document.location.href.replace(/\/s.*\.php/, "/traps.php");
    trap_char = "?";
    if(trap_ajax_url.match(/\?/)) {
      trap_char = "&";
    }

  
     
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

  if(typeof(log_filter_query) != "undefined") {
    $("#text_filter").keydown(function() {
      console.log($(this).val());
      var search_for=$(this).val();
         delay(function(){
          console.log("AAA");
            window.logTable.fnFilter( $("#text_filter").val() );
          }, 1000 );
        
    });
    window.logTable = $('#logview_table_load').dataTable({
            "fnInitComplete": function() {
              
            },
            "iDisplayLength": 50,
            "aoColumns": [
              { "sWidth": "1" , "sClass": "center_td" },
              { "sWidth": "1" , "sClass": "center_td" },
              { "sWidth": "90", "sClass": "" },
            ],
            "aaSortingFixed": [[ 0, 'asc' ]],
            "bSort": false,
            "aaSorting": [[ 1, 'asc' ]],
          
            "sDom": "<'row'<'col-sm-12'<'pull-right form-group' T><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
            "sAjaxSource": "logview.php?" + log_filter_query + "&datatables_output=1",
            "bServerSide": true,
            "bProcessing": true,
            "oTableTools": {
            "sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
              "aButtons": ["csv", "pdf","xls" ]
          },
            "oLanguage": {
              "sEmptyTable": "No Entries found",
              "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
            }
            
         
          });
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
            "aButtons": ["csv", "pdf","xls" ]
        },
          "oLanguage": {
            "sEmptyTable": "No Services found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
          }
          
       
        });
        
window.servers_table = $('#servers_table').dataTable({
          "iDisplayLength": 50,
          "fnDrawCallback": function ( oSettings ) {
            checkCheckBoxes();
          },
          "aoColumns": [
            { "sWidth": "1" , "sClass": "center_td" },
            { "sWidth": "10" , "sClass": "" },
            { "sWidth": "1", "sClass": "" },
            { "sWidth": "90", "sClass": "" },
            { "sWidth": "90", "sClass": "" }
          
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
            "aButtons": ["csv", "pdf","xls" ]
        },
          "oLanguage": {
            "sEmptyTable": "No Servers found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
          }
          
       
        });

window.traps_table = $('#traps_table').dataTable({
          "iDisplayLength": 50,
          "fnDrawCallback": function ( oSettings ) {
            checkCheckBoxes();
          },
          "aoColumns": [
            { "sWidth": "1" , "sClass": "center_td" },
            { "sWidth": "10" , "sClass": "" },
            { "sWidth": "1", "sClass": "" },
            { "sWidth": "90", "sClass": "" },
            { "sWidth": "90", "sClass": "" },
              { "sWidth": "90", "sClass": "" }
          
            ],
          "aaSortingFixed": [[ 0, 'asc' ]],
          "bSort": false,
          "aaSorting": [[ 1, 'asc' ]],
          "sDom": "<'row'<'col-sm-12'T<'pull-right form-group'f><'pull-left form-group'l>r<'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'p><'clearfix'>>>",
          "sAjaxSource": trap_ajax_url + trap_char + "datatables_output=1",
          "bServerSide": true,
          "bProcessing": true,
    
        "oTableTools": {
          "sSwfPath": "/themes/classic/js/copy_csv_xls_pdf.swf",
            "aButtons": ["csv", "pdf","xls" ]
        },
          "oLanguage": {
            "sEmptyTable": "No Trap found",
            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Loading'
          }
          
       
        });


  
}





  







	
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


  $("#server_checkbox_select_all").on('ifClicked',function() {
    if(!$(this).is(':checked')) {
      console.log("check all");
      
      $('.server_checkbox').iCheck('check');
    } else {
      console.log("UNCHECK ALL");
    
      $('.server_checkbox').iCheck('uncheck');
    }
  });
  
   $("#trap_checkbox_select_all").on('ifClicked',function() {
    if(!$(this).is(':checked')) {
      console.log("check all");
      
      $('.trap_checkbox').iCheck('check');
    } else {
      console.log("UNCHECK ALL");
    
      $('.trap_checkbox').iCheck('uncheck');
    }
  });
  
   
}


function modify_service_make_24() {
      for(x=0; x<=6; x++) {
        e = document.getElementById('wdays_plan[' + x + ']');
        e.value='00:00-23:59';
      }
      
}

function simulateTriggers() {
      wname=document.fm1.worker_name.value;
      wmail=document.fm1.worker_mail.value;
      wicq=document.fm1.worker_icq.value;
      TRR=document.fm1['worker_triggers[]'];
      wstr='|';
      for(x=0; x<=TRR.length-1; x++) {
        
        if(TRR.options[x].selected) {
          
          wstr =  wstr +  TRR.options[x].value + '|'; 
        }
        
      }
      window.open('trigger.php?user='+wname+'&mail='+wmail+'&icq='+wicq+'&trs=' + wstr, 'tr', 'width=600, height=600, scrollbars=yes');
}






