$(document).ready(function () {
    $.fn.select2.defaults.set("theme", "bootstrap");

    $('.select2').select2({minimumResultsForSearch: Infinity})
    $('.select2-search').select2()

    $('.select2-container').addClass('w-100')
});
