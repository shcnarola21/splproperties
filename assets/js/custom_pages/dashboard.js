$('[data-popup=popover-custom]').popover({
    html: true,
    template: '<div class="popover border-primary-600"><div class="arrow"></div><h3 class="popover-title bg-primary-600"></h3><div class="popover-content"></div></div>',
    trigger: 'hover'

}).on('shown.bs.popover', function () {
    $('body .popover .rating').rating();
});
