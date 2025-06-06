/******/ (function() { // webpackBootstrap
/*!****************************************************!*\
  !*** ./resources/js/pages/session-timeout.init.js ***!
  \****************************************************/
$.sessionTimeout({
  keepAliveUrl: "pages-starter.html",
  logoutButton: "Logout",
  logoutUrl: "auth-login.html",
  redirUrl: "auth-lock-screen.html",
  warnAfter: 3e3,
  redirAfter: 3e4,
  countdownMessage: "Redirecting in {timer} seconds."
}), $("#session-timeout-dialog  [data-dismiss=modal]").attr("data-bs-dismiss", "modal");
/******/ })()
;