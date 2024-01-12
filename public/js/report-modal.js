$('.report-post').click(function () {
    $('#report-post-submit').attr('data-id', $(this).attr('data-id'));
});

$('#report-post-submit').click(function () {
    let id = $(this).attr('data-id');
    let description = $('#report-post-description').val();
    report('post', id, description, url, token);
});

$('.report-comment').click(function () {
    $('#report-comment-submit').attr('data-id', $(this).attr('data-id'));
});

$('#report-comment-submit').click(function () {
    let id = $(this).attr('data-id');
    let description = $('#report-comment-description').val();
    report('comment', id, description, url, token);
});

function report(type, id, description, url, token) {
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            type: type,
            id: id,
            description: description
        },
        success: () => {
            $('#report-' + type + '-modal').modal('hide');
            $('#report-' + type + '-description').val('');
            $('#report-' + type + '-submit').attr('data-id', '');
            toastr.success('Report submitted successfully.');
        },
        error: () => {
            toastr.error('Something went wrong');
        }
    });
}
