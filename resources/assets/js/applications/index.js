$(function() {
    if ('object' == typeof window.modules) {
        $.each(window.modules, function(name, options) {
            if ('undefined' != typeof customModules[name]) {
                customModules[name].run($.extend({}, true, options));
            }
        });
    }
});