/******/ (function() { // webpackBootstrap
/*!**************************************************!*\
  !*** ./resources/js/pages/form-advanced.init.js ***!
  \**************************************************/
!function (s) {
  "use strict";

  function e() {}
  e.prototype.init = function () {
    s(".select2").select2(), s(".select2-limiting").select2({
      maximumSelectionLength: 2
    }), s(".select2-search-disable").select2({
      minimumResultsForSearch: 1 / 0
    }), s(".select2-ajax").select2({
      ajax: {
        url: "https://api.github.com/search/repositories",
        dataType: "json",
        delay: 250,
        data: function data(e) {
          return {
            q: e.term,
            page: e.page
          };
        },
        processResults: function processResults(e, t) {
          return t.page = t.page || 1, {
            results: e.items,
            pagination: {
              more: 30 * t.page < e.total_count
            }
          };
        },
        cache: !0
      },
      placeholder: "Search for a repository",
      minimumInputLength: 1,
      templateResult: function templateResult(e) {
        if (e.loading) return e.text;
        var t = s("<div class='select2-result-repository clearfix'><div class='select2-result-repository__avatar'><img src='" + e.owner.avatar_url + "' /></div><div class='select2-result-repository__meta'><div class='select2-result-repository__title'></div><div class='select2-result-repository__description'></div><div class='select2-result-repository__statistics'><div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div><div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div><div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div></div></div></div>");
        return t.find(".select2-result-repository__title").text(e.full_name), t.find(".select2-result-repository__description").text(e.description), t.find(".select2-result-repository__forks").append(e.forks_count + " Forks"), t.find(".select2-result-repository__stargazers").append(e.stargazers_count + " Stars"), t.find(".select2-result-repository__watchers").append(e.watchers_count + " Watchers"), t;
      },
      templateSelection: function templateSelection(e) {
        return e.full_name || e.text;
      }
    }), s(".select2-templating").select2({
      templateResult: function templateResult(e) {
        return e.id ? s('<span><img src="assets/images/flags/select2/' + e.element.value.toLowerCase() + '.png" class="img-flag" /> ' + e.text + "</span>") : e.text;
      }
    }), s("#colorpicker-default").spectrum(), s("#colorpicker-showalpha").spectrum({
      showAlpha: !0
    }), s("#colorpicker-showpaletteonly").spectrum({
      showPaletteOnly: !0,
      showPalette: !0,
      color: "#34c38f",
      palette: [["#556ee6", "white", "#34c38f", "rgb(255, 128, 0);", "#50a5f1"], ["red", "yellow", "green", "blue", "violet"]]
    }), s("#colorpicker-togglepaletteonly").spectrum({
      showPaletteOnly: !0,
      togglePaletteOnly: !0,
      togglePaletteMoreText: "more",
      togglePaletteLessText: "less",
      color: "#556ee6",
      palette: [["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"], ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"], ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"], ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"], ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"], ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"], ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"], ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]]
    }), s("#colorpicker-showintial").spectrum({
      showInitial: !0
    }), s("#colorpicker-showinput-intial").spectrum({
      showInitial: !0,
      showInput: !0
    }), s("#timepicker").timepicker({
      icons: {
        up: "mdi mdi-chevron-up",
        down: "mdi mdi-chevron-down"
      },
      appendWidgetTo: "#timepicker-input-group1"
    }), s("#timepicker2").timepicker({
      showMeridian: !1,
      icons: {
        up: "mdi mdi-chevron-up",
        down: "mdi mdi-chevron-down"
      },
      appendWidgetTo: "#timepicker-input-group2"
    }), s("#timepicker3").timepicker({
      minuteStep: 15,
      icons: {
        up: "mdi mdi-chevron-up",
        down: "mdi mdi-chevron-down"
      },
      appendWidgetTo: "#timepicker-input-group3"
    });
    var i = {};
    s('[data-toggle="touchspin"]').each(function (e, t) {
      var a = s.extend({}, i, s(t).data());
      s(t).TouchSpin(a);
    }), s("input[name='demo3_21']").TouchSpin({
      initval: 40,
      buttondown_class: "btn btn-primary",
      buttonup_class: "btn btn-primary"
    }), s("input[name='demo3_22']").TouchSpin({
      initval: 40,
      buttondown_class: "btn btn-primary",
      buttonup_class: "btn btn-primary"
    }), s("input[name='demo_vertical']").TouchSpin({
      verticalbuttons: !0
    }), s("input#defaultconfig").maxlength({
      warningClass: "badge bg-info",
      limitReachedClass: "badge bg-warning"
    }), s("input#thresholdconfig").maxlength({
      threshold: 20,
      warningClass: "badge bg-info",
      limitReachedClass: "badge bg-warning"
    }), s("input#moreoptions").maxlength({
      alwaysShow: !0,
      warningClass: "badge bg-success",
      limitReachedClass: "badge bg-danger"
    }), s("input#alloptions").maxlength({
      alwaysShow: !0,
      warningClass: "badge bg-success",
      limitReachedClass: "badge bg-danger",
      separator: " out of ",
      preText: "You typed ",
      postText: " chars available.",
      validate: !0
    }), s("textarea#textarea").maxlength({
      alwaysShow: !0,
      warningClass: "badge bg-info",
      limitReachedClass: "badge bg-warning"
    }), s("input#placement").maxlength({
      alwaysShow: !0,
      placement: "top-left",
      warningClass: "badge bg-info",
      limitReachedClass: "badge bg-warning"
    });
  }, s.AdvancedForm = new e(), s.AdvancedForm.Constructor = e;
}(window.jQuery), function () {
  "use strict";

  window.jQuery.AdvancedForm.init();
}(), $(function () {
  "use strict";

  var c = $(".docs-date"),
    o = $(".docs-datepicker-container"),
    n = $(".docs-datepicker-trigger"),
    r = {
      show: function show(e) {
        console.log(e.type, e.namespace);
      },
      hide: function hide(e) {
        console.log(e.type, e.namespace);
      },
      pick: function pick(e) {
        console.log(e.type, e.namespace, e.view);
      }
    };
  c.on({
    "show.datepicker": function showDatepicker(e) {
      console.log(e.type, e.namespace);
    },
    "hide.datepicker": function hideDatepicker(e) {
      console.log(e.type, e.namespace);
    },
    "pick.datepicker": function pickDatepicker(e) {
      console.log(e.type, e.namespace, e.view);
    }
  }).datepicker(r), $(".docs-options, .docs-toggles").on("change", function (e) {
    var t,
      a = e.target,
      i = $(a),
      e = i.attr("name"),
      s = "checkbox" === a.type ? a.checked : i.val();
    switch (e) {
      case "container":
        s ? (s = o).show() : o.hide();
        break;
      case "trigger":
        s ? (s = n).prop("disabled", !1) : n.prop("disabled", !0);
        break;
      case "inline":
        (t = $('input[name="container"]')).prop("checked") || t.click();
        break;
      case "language":
        $('input[name="format"]').val($.fn.datepicker.languages[s].format);
    }
    r[e] = s, c.datepicker("reset").datepicker("destroy").datepicker(r);
  }), $(".docs-actions").on("click", "button", function (e) {
    var t = $(this).data(),
      a = t.arguments || [];
    e.stopPropagation(), t.method && (t.source ? c.datepicker(t.method, $(t.source).val()) : (a = c.datepicker(t.method, a[0], a[1], a[2])) && t.target && $(t.target).val(a));
  });
});
/******/ })()
;