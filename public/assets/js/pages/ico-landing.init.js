/******/ (function() { // webpackBootstrap
/*!************************************************!*\
  !*** ./resources/js/pages/ico-landing.init.js ***!
  \************************************************/
$(window).scroll(function () {
  50 <= $(window).scrollTop() ? $(".sticky").addClass("nav-sticky") : $(".sticky").removeClass("nav-sticky");
}), $("[data-countdown]").each(function () {
  var s = $(this),
    i = $(this).data("countdown");
  s.countdown(i, function (s) {
    $(this).html(s.strftime('<div class="coming-box">%D <span>Days</span></div> <div class="coming-box">%H <span>Hours</span></div> <div class="coming-box">%M <span>Minutes</span></div> <div class="coming-box">%S <span>Seconds</span></div> '));
  });
}), $("#clients-carousel, #team-carousel").owlCarousel({
  items: 1,
  loop: !1,
  margin: 24,
  nav: !1,
  dots: !1,
  responsive: {
    576: {
      items: 2
    },
    768: {
      items: 3
    },
    992: {
      items: 4
    }
  }
}), $("#timeline-carousel").owlCarousel({
  items: 1,
  loop: !1,
  margin: 0,
  nav: !0,
  navText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"],
  dots: !1,
  responsive: {
    576: {
      items: 2
    },
    768: {
      items: 3
    },
    992: {
      items: 4
    }
  }
});
/******/ })()
;