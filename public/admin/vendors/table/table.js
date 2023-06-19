$(document).ready(function () {
    fetch();
});

$('#column, #order, #custom-page, #limit, .filters select').on('change', fetch);

let searchTimeout;
$('#search').on('keyup', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
        fetch();
    }, 350);
});

$('#next-page').on('click', function () {
    fetch(parseInt($('#custom-page').val()) + 1);
});

$('#previous-page').on('click', function () {
    fetch(parseInt($('#custom-page').val()) - 1);
});

$('.first-last-button').on('click', function () {
    fetch($(this).data('page'));
});

function fetch(page = 0) {
    $('.table-body').html(
        '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
    )
    let customPage = $('#custom-page');
    page = page > 0 ? page : customPage.val();

    let filter = {}
    $(".filters select").each(function () {
        let type = $(this).data('type')

        if (!filter[type]) {
            filter[type] = {};
        }

        filter[type][$(this).attr('id').substring(7)] = $(this).val();
    });

    $.ajax({
        url: tableUrl,
        type: "GET",
        data: {
            "_token": token,
            "column": $("#column").val(),
            "order": $("#order").val(),
            "search": $("#search").val(),
            "limit": $("#limit").val(),
            "filter": filter,
            "page": page,
        },
        dataType: 'json',
        contentType: 'application/json',
        success: function (res) {
            $('.table-body').html(res.html);
            customPage.html('');
            for ($i = 1; $i <= res.lastPage; $i++) {
                customPage.append(
                    '<option value="' + $i + '" ' + ($i === res.currentPage ? 'selected' : '') + '>' + $i + '</option>'
                );
            }
            customPage.select2();
            customPage.val(res.currentPage)

            let isFirstPage = (res.currentPage === 1);
            let isLastPage = (res.currentPage === res.lastPage);

            $('#first-page').attr('disabled', isFirstPage)
            $('#previous-page').attr('disabled', isFirstPage)
            $('#next-page').attr('disabled', isLastPage)
            $('#last-page').attr('disabled', isLastPage).attr('data-page', res.lastPage);
            $('.page-item button').removeClass('dropdown-toggle')
        },
    });
}
