/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/style.scss */ \"./scss/style.scss\");\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_style_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cvrautofill.js */ \"./js/cvrautofill.js\");\n/* harmony import */ var _cvrautofill_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_cvrautofill_js__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mmenu.js */ \"./js/mmenu.js\");\n/* harmony import */ var _mmenu_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_mmenu_js__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _sidebar_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./sidebar.js */ \"./js/sidebar.js\");\n/* harmony import */ var _sidebar_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_sidebar_js__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./filters.js */ \"./js/filters.js\");\n/* harmony import */ var _filters_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_filters_js__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./sticky_header.js */ \"./js/sticky_header.js\");\n/* harmony import */ var _sticky_header_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_sticky_header_js__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./stock.js */ \"./js/stock.js\");\n/* harmony import */ var _stock_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_stock_js__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _variation_select_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./variation_select.js */ \"./js/variation_select.js\");\n/* harmony import */ var _variation_select_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_variation_select_js__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _cart_empty_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./cart_empty.js */ \"./js/cart_empty.js\");\n/* harmony import */ var _cart_empty_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_cart_empty_js__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _cart_preview_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./cart_preview.js */ \"./js/cart_preview.js\");\n/* harmony import */ var _cart_preview_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_cart_preview_js__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _instagram_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./instagram.js */ \"./js/instagram.js\");\n/* harmony import */ var _instagram_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_instagram_js__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _megamenu_list_expand_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./megamenu_list_expand.js */ \"./js/megamenu_list_expand.js\");\n/* harmony import */ var _megamenu_list_expand_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_megamenu_list_expand_js__WEBPACK_IMPORTED_MODULE_11__);\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n//# sourceURL=webpack://my-theme/./js/app.js?");

/***/ }),

/***/ "./js/cart_empty.js":
/*!**************************!*\
  !*** ./js/cart_empty.js ***!
  \**************************/
/***/ (() => {

eval("// Hide cart form on Cart page\njQuery(function($){\n\n\tjQuery( document.body ).on( 'wc_cart_emptied', function(){\n\t\t$(\".woocommerce-cart .woocommerce-cart-form\").hide();\n\t});\n\n});\n\n//# sourceURL=webpack://my-theme/./js/cart_empty.js?");

/***/ }),

/***/ "./js/cart_preview.js":
/*!****************************!*\
  !*** ./js/cart_preview.js ***!
  \****************************/
/***/ (() => {

eval("jQuery(function($){\n    var timeout;\n       //Show and hide cart preview on hover\n       $(\".header-cart, #cart-preview\").hover(function(){\n           clearTimeout(timeout);\n           $('#cart-preview').slideDown(300);\n       },function(){\n         timeout = setTimeout(function () {\n           $('#cart-preview').hide();\n         }, 600);\n       });\n   });\n\n\n//# sourceURL=webpack://my-theme/./js/cart_preview.js?");

/***/ }),

/***/ "./js/cvrautofill.js":
/*!***************************!*\
  !*** ./js/cvrautofill.js ***!
  \***************************/
/***/ (() => {

eval("(function($){\n\t$(document).ready(function () {\n\n        // Inserts vat verification button under vat input field\n        jQuery('.append-button').each(function(){\n            var item = jQuery(this);\n            var description = item.attr('vat-button');\n            item.parent().append('<button type=\"button\" class=\"button default\" id=\"vatButton\">'+description+'</button>');\n        });\n\n        // Change button to default color if user edits vat\n        $(\"#billing_vat\").on(\"input\", function(){\n            document.getElementById(\"vatButton\").className=\"button default\";\n        });\n\n        /*\n        * OnClick event for vat verification button\n        * Gets company information from cvrapi.dk based on user vat input and country\n        * Changes vatButton style based on GET success\n        */\n        $(\"#vatButton\").click(function(){\n            var vatButton = document.getElementById(\"vatButton\");\n\n            var vat = $('#billing_vat').val();\n            var country =$('#billing_country').val();\n\n            var buttonFailure = \"CVR nr. er ugyldigt, prøv venligst igen\";\n            var buttonSuccess = \"Oplysninger er successfuldt hentet\";\n            \n            $.getJSON('//cvrapi.dk/api?search=' + vat + \"&country=\" + country, function(data) {\n                if  (vat == \"\") {\n                    vatButton.innerHTML=buttonFailure;\n                    vatButton.className=\"button failure\";\n                }\n                else if (vat != data.vat){\n                    vatButton.innerHTML=buttonFailure;\n                    vatButton.className=\"button failure\";\n                }\n                else{\n                    vatButton.innerHTML=buttonSuccess;\n                    vatButton.className=\"button success\";\n\n                    $('#billing_address_1').val(data.address);\n                    $('#billing_company').val(data.name);\n                    $('#billing_city').val(data.city);\n                    $('#billing_postcode').val(data.zipcode);\n                    $('#billing_phone').val(data.phone);\n                }\n            });\n        });\n\t});\n})(jQuery);\n\n//# sourceURL=webpack://my-theme/./js/cvrautofill.js?");

/***/ }),

/***/ "./js/filters.js":
/*!***********************!*\
  !*** ./js/filters.js ***!
  \***********************/
/***/ (() => {

eval("//Show or hide archive filters\njQuery(function($){\n\t$(\".show_filter\").click(function() {\n\t  $(\"#archive-top-filters\").slideToggle(300);\n\t\t$(\"#archive-top-filters\").css(\"display\",\"grid\");\n\t});\n\n\t$(\".show_cat\").click(function() {\n\t  $(\".widget_product_categories\").slideToggle(300);\n\t});\n});\n\n\n//# sourceURL=webpack://my-theme/./js/filters.js?");

/***/ }),

/***/ "./js/instagram.js":
/*!*************************!*\
  !*** ./js/instagram.js ***!
  \*************************/
/***/ (() => {

eval("!function(e,t){\"use strict\";Object.prototype.hasOwnProperty.call(e,\"lightwidget\")||(e.addEventListener(\"message\",function(e){if(-1===[\"lightwidget.com\",\"dev.lightwidget.com\",\"cdn.lightwidget.com\",\"instansive.com\"].indexOf(e.origin.replace(/^https?:\\/\\//i,\"\")))return!1;var i=function(e){if(-1<e.indexOf(\"{\"))return JSON.parse(e);e=e.split(\":\");return{widgetId:e[2].replace(\"instansive_\",\"\").replace(\"lightwidget_\",\"\"),size:e[1]}}(e.data);if(i.size<=0)return!1;[].forEach.call(t.querySelectorAll('iframe[src*=\"lightwidget.com/widgets/'+i.widgetId+'\"],iframe[data-src*=\"lightwidget.com/widgets/'+i.widgetId+'\"],iframe[src*=\"instansive.com/widgets/'+i.widgetId+'\"]'),function(e){e.style.height=i.size+\"px\"})},!1),e.lightwidget={})}(window,document);\n\n\n//# sourceURL=webpack://my-theme/./js/instagram.js?");

/***/ }),

/***/ "./js/megamenu_list_expand.js":
/*!************************************!*\
  !*** ./js/megamenu_list_expand.js ***!
  \************************************/
/***/ (() => {

eval("jQuery(function($) {\n\n    $(document).ready(function(){\n        $(\".showmore\").click(function(){\n            $(this).toggleClass(\"rotate\");\n        });\n    });\n\n    $('li.mega-menu-column > ul.mega-sub-menu > li.mega-menu-item > ul.mega-sub-menu').each(function(){\n        var $ul = $(this),\n            $lis = $ul.find('li:gt(4)'),\n            isExpanded = $ul.hasClass('expanded');\n        $lis[isExpanded ? 'show' : 'hide']();\n        \n        if($lis.length > 0){\n            $ul\n                .append($('<li class=\"showmore list-expand\">' + (isExpanded ? ' Hvis mindre' : ' Hvis mere') + '</li>')\n                .click(function(event){\n                    var isExpanded = $ul.hasClass('expanded');\n                    event.preventDefault();\n                    $(this).html(isExpanded ? ' Hvis mere' : ' Hvis mindre');\n                    $ul.toggleClass('expanded');\n                    $lis.toggle(500);\n                }));\n        }\n    });\n});\n\n//# sourceURL=webpack://my-theme/./js/megamenu_list_expand.js?");

/***/ }),

/***/ "./js/mmenu.js":
/*!*********************!*\
  !*** ./js/mmenu.js ***!
  \*********************/
/***/ (() => {

eval("!function(t){var e={};function n(o){if(e[o])return e[o].exports;var i=e[o]={i:o,l:!1,exports:{}};return t[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){\"undefined\"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:\"Module\"}),Object.defineProperty(t,\"__esModule\",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&\"object\"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,\"default\",{enumerable:!0,value:t}),2&e&&\"string\"!=typeof t)for(var i in t)n.d(o,i,function(e){return t[e]}.bind(null,i));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,\"a\",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p=\"\",n(n.s=0)}([function(t,e,n){\"use strict\";n.r(e);var o=function(){function t(t){var e=this;this.listener=function(t){(t.matches?e.matchFns:e.unmatchFns).forEach(function(t){t()})},this.toggler=window.matchMedia(t),this.toggler.addListener(this.listener),this.matchFns=[],this.unmatchFns=[]}return t.prototype.add=function(t,e){this.matchFns.push(t),this.unmatchFns.push(e),(this.toggler.matches?t:e)()},t}(),i=function(t){return Array.prototype.slice.call(t)},r=function(t,e){return i((e||document).querySelectorAll(t))},s=(\"ontouchstart\"in window||navigator.msMaxTouchPoints,navigator.userAgent.indexOf(\"MSIE\")>-1||navigator.appVersion.indexOf(\"Trident/\")>-1),a=\"mm-spn\",c=function(){function t(t,e,n,o,i){this.node=t,this.title=e,this.selectedClass=n,this.node.classList.add(a),s&&(o=!1),this.node.classList.add(a+\"--\"+i),this.node.classList.add(a+\"--\"+(o?\"navbar\":\"vertical\")),this._setSelectedl(),this._initAnchors()}return Object.defineProperty(t.prototype,\"prefix\",{get:function(){return a},enumerable:!0,configurable:!0}),t.prototype.openPanel=function(t){var e=t.dataset.mmSpnTitle,n=t.parentElement;n===this.node?this.node.classList.add(a+\"--main\"):(this.node.classList.remove(a+\"--main\"),e||i(n.children).forEach(function(t){t.matches(\"a, span\")&&(e=t.textContent)})),e||(e=this.title),this.node.dataset.mmSpnTitle=e,r(\".\"+a+\"--open\",this.node).forEach(function(t){t.classList.remove(a+\"--open\"),t.classList.remove(a+\"--parent\")}),t.classList.add(a+\"--open\"),t.classList.remove(a+\"--parent\");for(var o=t.parentElement.closest(\"ul\");o;)o.classList.add(a+\"--open\"),o.classList.add(a+\"--parent\"),o=o.parentElement.closest(\"ul\")},t.prototype._setSelectedl=function(){var t=r(\".\"+this.selectedClass,this.node),e=t[t.length-1],n=null;e&&(n=e.closest(\"ul\")),n||(n=this.node.querySelector(\"ul\")),this.openPanel(n)},t.prototype._initAnchors=function(){var t=this;this.node.addEventListener(\"click\",function(e){var n=!1;n=(n=(n=n||function(t){return!!t.target.matches(\"a\")&&(t.stopImmediatePropagation(),!0)}(e))||function(e){var n,o=e.target;return!!(n=o.closest(\"span\")?o.parentElement:!!o.closest(\"li\")&&o)&&(i(n.children).forEach(function(e){e.matches(\"ul\")&&t.openPanel(e)}),e.stopImmediatePropagation(),!0)}(e))||function(e){var n=e.target,o=r(\".\"+a+\"--open\",n),i=o[o.length-1];if(i){var s=i.parentElement.closest(\"ul\");if(s)return t.openPanel(s),e.stopImmediatePropagation(),!0}}(e)})},t}(),d=\"mm-ocd\",u=function(){function t(t,e){var n=this;void 0===t&&(t=null),this.wrapper=document.createElement(\"div\"),this.wrapper.classList.add(\"\"+d),this.wrapper.classList.add(d+\"--\"+e),this.content=document.createElement(\"div\"),this.content.classList.add(d+\"__content\"),this.wrapper.append(this.content),this.backdrop=document.createElement(\"div\"),this.backdrop.classList.add(d+\"__backdrop\"),this.wrapper.append(this.backdrop),document.body.append(this.wrapper),t&&this.content.append(t);var o=function(t){n.close(),t.preventDefault(),t.stopImmediatePropagation()};this.backdrop.addEventListener(\"touchstart\",o),this.backdrop.addEventListener(\"mousedown\",o)}return Object.defineProperty(t.prototype,\"prefix\",{get:function(){return d},enumerable:!0,configurable:!0}),t.prototype.open=function(){this.wrapper.classList.add(d+\"--open\"),document.body.classList.add(d+\"-opened\")},t.prototype.close=function(){this.wrapper.classList.remove(d+\"--open\"),document.body.classList.remove(d+\"-opened\")},t}(),l=function(){function t(t,e){void 0===e&&(e=\"all\"),this.menu=t,this.toggler=new o(e)}return t.prototype.navigation=function(t){var e=this;if(!this.navigator){var n=t.title,o=void 0===n?\"Menu\":n,i=t.selectedClass,r=void 0===i?\"Selected\":i,s=t.slidingSubmenus,a=void 0===s||s,d=t.theme,u=void 0===d?\"light\":d;this.navigator=new c(this.menu,o,r,a,u)}var l=this.navigator.prefix;return this.toggler.add(function(){return e.menu.classList.add(l)},function(){return e.menu.classList.remove(l)}),this.navigator},t.prototype.offcanvas=function(t){var e=this;if(!this.drawer){var n=t.position,o=void 0===n?\"left\":n;this.drawer=new u(null,o)}var i=document.createComment(\"original menu location\");return this.menu.after(i),this.toggler.add(function(){e.drawer.content.append(e.menu)},function(){e.drawer.close(),i.after(e.menu)}),this.drawer},t}();e.default=l;window.MmenuLight=l}]);\n\n//# sourceURL=webpack://my-theme/./js/mmenu.js?");

/***/ }),

/***/ "./js/sidebar.js":
/*!***********************!*\
  !*** ./js/sidebar.js ***!
  \***********************/
/***/ (() => {

eval("jQuery(function($){\n\n    var $sidebar = $('.cart-sidebar');\n    \n      $(\".header_cart_wrapper\").on('click', function(e) {\n        e.preventDefault();\n    \n        if (!$sidebar.hasClass('cart-active')) {\n          $sidebar.addClass('cart-active');\n    \n          $(document).one('click', function closeTooltip(e) {\n              if ($sidebar.has(e.target).length === 0 && $('.header_cart_wrapper').has(e.target).length === 0) {\n                  $sidebar.removeClass('cart-active');\n              } else if ($sidebar.hasClass('cart-active')) {\n                  $(document).one('click', closeTooltip);\n              }\n          });\n          } else {\n            $menu.removeClass('cart-active');\n          }\n    \n      });\n    });\n    \n\n//# sourceURL=webpack://my-theme/./js/sidebar.js?");

/***/ }),

/***/ "./js/sticky_header.js":
/*!*****************************!*\
  !*** ./js/sticky_header.js ***!
  \*****************************/
/***/ (() => {

eval("// Sticky header\njQuery(function($){\n\tvar prevScrollpos = window.pageYOffset;\n\twindow.onscroll = function() {\n\tvar currentScrollPos = window.pageYOffset;\n\t  if (prevScrollpos > currentScrollPos) {\n\t\tdocument.getElementById(\"masthead\").style.top = \"0\";\n\t  } else {\n\t\tdocument.getElementById(\"masthead\").style.top = \"-200px\";\n\t  }\n\t  prevScrollpos = currentScrollPos;\n\t}\n\n});\n\n\n//# sourceURL=webpack://my-theme/./js/sticky_header.js?");

/***/ }),

/***/ "./js/stock.js":
/*!*********************!*\
  !*** ./js/stock.js ***!
  \*********************/
/***/ (() => {

eval("// Show or hide stock\njQuery(function($){\n\t$(\"#stockButton\").click(function() {\n\t  $(\".stock_status\").toggle(\"slide\");\n\t});\n});\n\n//# sourceURL=webpack://my-theme/./js/stock.js?");

/***/ }),

/***/ "./js/variation_select.js":
/*!********************************!*\
  !*** ./js/variation_select.js ***!
  \********************************/
/***/ (() => {

eval("/* Select variation buttons on product page */\n\njQuery(function($){\n\n  // clones select options for each product attribute\n  var clone = $(\".single-product div.product table.variations select\").clone(true,true);\n\n  // adds a \"data-parent-id\" attribute to each select option\n  $(\".single-product div.product table.variations select option\").each(function(){\n      $(this).attr('data-parent-id',$(this).parent().attr('id'));\n  });\n\n  // converts select options to div\n  $(\".single-product div.product table.variations select option\").unwrap().each(function(){\n      if ( $(this).val() == '' ) {\n          $(this).remove();\n          return true;\n      }\n      var option = $('<div class=\"custom_option is-visible\" data-parent-id=\"'+$(this).data('parent-id')+'\" data-value=\"'+$(this).val()+'\">'+$(this).text()+'</div>');\n      $(this).replaceWith(option);\n  });\n  \n  // reinsert the clone of the select options of the attributes in the page that were removed by \"unwrap()\"\n  $(clone).insertBefore('.single-product div.product table.variations .reset_variations').hide();\n\n  // when a user clicks on a div it adds the \"selected\" attribute to the respective select option\n  $(document).on('click', '.custom_option', function(){\n      var parentID = $(this).data('parent-id');\n      if ( $(this).hasClass('on') ) {\n          $(this).removeClass('on');\n          $(\".single-product div.product table.variations select#\"+parentID).val('').trigger(\"change\");\n      } else {\n          $('.custom_option[data-parent-id='+parentID+']').removeClass('on');\n          $(this).addClass('on');\n          $(\".single-product div.product table.variations select#\"+parentID).val($(this).data(\"value\")).trigger(\"change\");\n      }\n      \n  });\n\n  // if a select option is already selected, it adds the \"on\" attribute to the respective div\n  $(\".single-product div.product table.variations select\").each(function(){\n      if ( $(this).find(\"option:selected\").val() ) {\n          var id = $(this).attr('id');\n          $('.custom_option[data-parent-id='+id+']').removeClass('on');\n          var value = $(this).find(\"option:selected\").val();\n          $('.custom_option[data-parent-id='+id+'][data-value='+value+']').addClass('on');\n      }\n  });\n\n  // when the select options change based on the ones selected, it shows or hides the respective divs\n  $('body').on('check_variations', function(){\n      $('div.custom_option').removeClass('is-visible');\n      $('.single-product div.product table.variations select').each(function(){\n          var attrID = $(this).attr(\"id\");\n          $(this).find('option').each(function(){\n              if ( $(this).val() == '' ) {\n                  return;\n              }\n              $('div[data-parent-id=\"'+attrID+'\"][data-value=\"'+$(this).val()+'\"]').addClass('is-visible');\n          });\n      });\n  });\n\n});\n\n\n//# sourceURL=webpack://my-theme/./js/variation_select.js?");

/***/ }),

/***/ "./scss/style.scss":
/*!*************************!*\
  !*** ./scss/style.scss ***!
  \*************************/
/***/ (() => {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack://my-theme/./scss/style.scss?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./js/app.js");
/******/ 	
/******/ })()
;