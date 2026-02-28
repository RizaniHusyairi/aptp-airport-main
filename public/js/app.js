/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (function() {

/*
Template Name: Skote - Admin & Dashboard Template
Author: Themesbrand
Version: 3.3.0.
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Main Js File
*/
(function ($) {
  'use strict';

  function initMetisMenu() {
    //metis menu
    $("#side-menu").metisMenu();
  }

  function initLeftMenuCollapse() {
    $('#vertical-menu-btn').on('click', function (event) {
      event.preventDefault();
      $('body').toggleClass('sidebar-enable');

      if ($(window).width() >= 992) {
        $('body').toggleClass('vertical-collpsed');
      } else {
        $('body').removeClass('vertical-collpsed');
      }
    });
  }

  function initActiveMenu() {
    // === following js will activate the menu in left side bar based on url ====
    $("#sidebar-menu a").each(function () {
      var pageUrl = window.location.href.split(/[?#]/)[0];

      if (this.href == pageUrl) {
        $(this).addClass("active");
        $(this).parent().addClass("mm-active"); // add active to li of the current link

        $(this).parent().parent().addClass("mm-show");
        $(this).parent().parent().prev().addClass("mm-active"); // add active class to an anchor

        $(this).parent().parent().parent().addClass("mm-active");
        $(this).parent().parent().parent().parent().addClass("mm-show"); // add active to li of the current link

        $(this).parent().parent().parent().parent().parent().addClass("mm-active");
      }
    });
  }

  function initMenuItemScroll() {
    // focus active menu in left sidebar
    $(document).ready(function () {
      if ($("#sidebar-menu").length > 0 && $("#sidebar-menu .mm-active .active").length > 0) {
        var activeMenu = $("#sidebar-menu .mm-active .active").offset().top;

        if (activeMenu > 300) {
          activeMenu = activeMenu - 300;
          $(".vertical-menu .simplebar-content-wrapper").animate({
            scrollTop: activeMenu
          }, "slow");
        }
      }
    });
  }

  function initHoriMenuActive() {
    $(".navbar-nav a").each(function () {
      var pageUrl = window.location.href.split(/[?#]/)[0];

      if (this.href == pageUrl) {
        $(this).addClass("active");
        $(this).parent().addClass("active");
        $(this).parent().parent().addClass("active");
        $(this).parent().parent().parent().addClass("active");
        $(this).parent().parent().parent().parent().addClass("active");
        $(this).parent().parent().parent().parent().parent().addClass("active");
        $(this).parent().parent().parent().parent().parent().parent().addClass("active");
      }
    });
  }

  function initFullScreen() {
    $('[data-bs-toggle="fullscreen"]').on("click", function (e) {
      e.preventDefault();
      $('body').toggleClass('fullscreen-enable');

      if (!document.fullscreenElement &&
      /* alternative standard method */
      !document.mozFullScreenElement && !document.webkitFullscreenElement) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    });
    document.addEventListener('fullscreenchange', exitHandler);
    document.addEventListener("webkitfullscreenchange", exitHandler);
    document.addEventListener("mozfullscreenchange", exitHandler);

    function exitHandler() {
      if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        console.log('pressed');
        $('body').removeClass('fullscreen-enable');
      }
    }
  }

  function initRightSidebar() {
    // right side-bar toggle
    $('.right-bar-toggle').on('click', function (e) {
      $('body').toggleClass('right-bar-enabled');
    });
    $(document).on('click', 'body', function (e) {
      if ($(e.target).closest('.right-bar-toggle, .right-bar').length > 0) {
        return;
      }

      $('body').removeClass('right-bar-enabled');
      return;
    });
  }

  function initDropdownMenu() {
    if (document.getElementById("topnav-menu-content")) {
      var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");

      for (var i = 0, len = elements.length; i < len; i++) {
        elements[i].onclick = function (elem) {
          if (elem.target.getAttribute("href") === "#") {
            elem.target.parentElement.classList.toggle("active");
            elem.target.nextElementSibling.classList.toggle("show");
          }
        };
      }

      window.addEventListener("resize", updateMenu);
    }
  }

  function updateMenu() {
    var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");

    for (var i = 0, len = elements.length; i < len; i++) {
      if (elements[i].parentElement.getAttribute("class") === "nav-item dropdown active") {
        elements[i].parentElement.classList.remove("active");

        if (elements[i].nextElementSibling !== null) {
          elements[i].nextElementSibling.classList.remove("show");
        }
      }
    }
  }

  function initComponents() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl);
    });
    var offcanvasElementList = [].slice.call(document.querySelectorAll('.offcanvas'));
    var offcanvasList = offcanvasElementList.map(function (offcanvasEl) {
      return new bootstrap.Offcanvas(offcanvasEl);
    });
  }

  function initPreloader() {
    $(window).on('load', function () {
      $('#status').fadeOut();
      $('#preloader').delay(350).fadeOut('slow');
    });
  }

  function initSettings() {
    if (window.sessionStorage) {
      var alreadyVisited = sessionStorage.getItem("is_visited");

      if (!alreadyVisited) {
        sessionStorage.setItem("is_visited", "light-mode-switch");
      } else {
        $(".right-bar input:checkbox").prop('checked', false);
        $("#" + alreadyVisited).prop('checked', true);
        updateThemeSetting(alreadyVisited);
      }
    }

    $("#light-mode-switch, #dark-mode-switch, #rtl-mode-switch, #dark-rtl-mode-switch").on("change", function (e) {
      updateThemeSetting(e.target.id);
    }); // show password input value

    $("#password-addon").on('click', function () {
      if ($(this).siblings('input').length > 0) {
        $(this).siblings('input').attr('type') == "password" ? $(this).siblings('input').attr('type', 'input') : $(this).siblings('input').attr('type', 'password');
      }
    });
  }

  function updateThemeSetting(id) {
    if ($("#light-mode-switch").prop("checked") == true && id === "light-mode-switch") {
      $("html").removeAttr("dir");
      $("#dark-mode-switch").prop("checked", false);
      $("#rtl-mode-switch").prop("checked", false);
      $("#dark-rtl-mode-switch").prop("checked", false);
      $("#bootstrap-style").attr('href', 'assets/css/bootstrap.min.css');
      $("#app-style").attr('href', 'assets/css/app.min.css');
      sessionStorage.setItem("is_visited", "light-mode-switch");
    } else if ($("#dark-mode-switch").prop("checked") == true && id === "dark-mode-switch") {
      $("html").removeAttr("dir");
      $("#light-mode-switch").prop("checked", false);
      $("#rtl-mode-switch").prop("checked", false);
      $("#dark-rtl-mode-switch").prop("checked", false);
      $("#bootstrap-style").attr('href', 'assets/css/bootstrap-dark.min.css');
      $("#app-style").attr('href', 'assets/css/app-dark.min.css');
      sessionStorage.setItem("is_visited", "dark-mode-switch");
    } else if ($("#rtl-mode-switch").prop("checked") == true && id === "rtl-mode-switch") {
      $("#light-mode-switch").prop("checked", false);
      $("#dark-mode-switch").prop("checked", false);
      $("#dark-rtl-mode-switch").prop("checked", false);
      $("#bootstrap-style").attr('href', 'assets/css/bootstrap.rtl.css');
      $("#app-style").attr('href', 'assets/css/app.rtl.css');
      $("html").attr("dir", 'rtl');
      sessionStorage.setItem("is_visited", "rtl-mode-switch");
    } else if ($("#dark-rtl-mode-switch").prop("checked") == true && id === "dark-rtl-mode-switch") {
      $("#light-mode-switch").prop("checked", false);
      $("#rtl-mode-switch").prop("checked", false);
      $("#dark-mode-switch").prop("checked", false);
      $("#bootstrap-style").attr('href', 'assets/css/bootstrap-dark.rtl.css');
      $("#app-style").attr('href', 'assets/css/app-dark.rtl.css');
      $("html").attr("dir", 'rtl');
      sessionStorage.setItem("is_visited", "dark-rtl-mode-switch");
    }
  }

  function initCheckAll() {
    $('#checkAll').on('change', function () {
      $('.table-check .form-check-input').prop('checked', $(this).prop("checked"));
    });
    $('.table-check .form-check-input').change(function () {
      if ($('.table-check .form-check-input:checked').length == $('.table-check .form-check-input').length) {
        $('#checkAll').prop('checked', true);
      } else {
        $('#checkAll').prop('checked', false);
      }
    });
  }

  function init() {
    initMetisMenu();
    initLeftMenuCollapse();
    initActiveMenu();
    initMenuItemScroll();
    initHoriMenuActive();
    initFullScreen();
    initRightSidebar();
    initDropdownMenu();
    initComponents();
    initSettings();
    initPreloader();
    Waves.init();
    initCheckAll();
  }

  init();
})(jQuery);

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/scss/bootstrap.scss":
/*!***************************************!*\
  !*** ./resources/scss/bootstrap.scss ***!
  \***************************************/
/***/ (function() {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\nSassError: Duplicate key.\n    ╷\n165 │     \"pink\":       $pink,\r\n    │     ━━━━━━ first key\n... │\n168 │     \"pink\":       $pink,\r\n    │     ^^^^^^ second key\n    ╵\n  resources\\scss\\_variables.scss 168:5  @import\n  C:\\laragon\\www\\aptp-airport-main\\resources\\scss\\bootstrap.scss 14:9                            root stylesheet\n    at processResult (C:\\laragon\\www\\aptp-airport-main\\node_modules\\webpack\\lib\\NormalModule.js:748:19)\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\webpack\\lib\\NormalModule.js:850:5\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:399:11\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:251:18\n    at context.callback (C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:124:13)\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass-loader\\dist\\index.js:73:7\n    at Function.call$2 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:127323:16)\n    at render_closure1.call$2 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:108455:12)\n    at _RootZone.runBinary$3$3 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:39833:18)\n    at _FutureListener.handleError$1 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:38321:21)");

/***/ }),

/***/ "./resources/scss/icons.scss":
/*!***********************************!*\
  !*** ./resources/scss/icons.scss ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/scss/app.scss":
/*!*********************************!*\
  !*** ./resources/scss/app.scss ***!
  \*********************************/
/***/ (function() {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\nSassError: Duplicate key.\n    ╷\n165 │     \"pink\":       $pink,\r\n    │     ━━━━━━ first key\n... │\n168 │     \"pink\":       $pink,\r\n    │     ^^^^^^ second key\n    ╵\n  resources\\scss\\_variables.scss 168:5  @import\n  C:\\laragon\\www\\aptp-airport-main\\resources\\scss\\app.scss 16:9                            root stylesheet\n    at processResult (C:\\laragon\\www\\aptp-airport-main\\node_modules\\webpack\\lib\\NormalModule.js:748:19)\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\webpack\\lib\\NormalModule.js:850:5\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:399:11\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:251:18\n    at context.callback (C:\\laragon\\www\\aptp-airport-main\\node_modules\\loader-runner\\lib\\LoaderRunner.js:124:13)\n    at C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass-loader\\dist\\index.js:73:7\n    at Function.call$2 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:127323:16)\n    at render_closure1.call$2 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:108455:12)\n    at _RootZone.runBinary$3$3 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:39833:18)\n    at _FutureListener.handleError$1 (C:\\laragon\\www\\aptp-airport-main\\node_modules\\sass\\sass.dart.js:38321:21)");

/***/ }),

/***/ "./resources/scss/bootstrap-dark.scss":
/*!********************************************!*\
  !*** ./resources/scss/bootstrap-dark.scss ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/scss/app-dark.scss":
/*!**************************************!*\
  !*** ./resources/scss/app-dark.scss ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"assets/css/app-dark": 0,
/******/ 			"css/app": 0,
/******/ 			"assets/css/bootstrap-dark": 0,
/******/ 			"assets/css/icons": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkskote"] = self["webpackChunkskote"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/js/app.js"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/scss/bootstrap.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/scss/icons.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/scss/app.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/scss/bootstrap-dark.scss"); })
/******/ 	__webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/scss/app-dark.scss"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/app-dark","css/app","assets/css/bootstrap-dark","assets/css/icons"], function() { return __webpack_require__("./resources/css/app.css"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;