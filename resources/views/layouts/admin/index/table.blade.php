<div class="table-responsive">
    <table class="table table-bordered tablesorter " id="">
        <thead class=" text-primary">
        <tr>
            @if($actions)
                <th>Actions</th>
            @endif
            @foreach($headers as $header)
                <th {{$loop->last ? 'class="text-center"' : '' }}>
                    {{$header}}
                </th>
            @endforeach
            @if($tableName === 'reports')
                <th>Reported by</th>
                <th>Status</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if (sizeof($items->items()) == 0)
            <tr>
                <td colspan="{{sizeof($headers) + ($tableName == 'reports' ? 3 : 1)}}" class="text-center">
                    No records found
                </td>
            </tr>
        @else
            @foreach($items->items() as $item)
                @php
                    $showRouteParameters = [];

                    if ($tableName === 'posts'){
                        $showRouteParameters = ['post' => $item->slug];
                    }

                    if ($tableName === 'users'){
                        $showRouteParameters = ['user' => $item->username];
                    }
                @endphp
                <tr>
                    @if($actions)
                        <td>
                            @if(in_array('enforce', $actions))
                                <button type="button" class="btn btn-warning show-modal" data-toggle="modal"
                                        data-target="#exampleModal"
                                        data-id="{{$item->id}}"
                                        data-object-id="{{$item->object_id}}"
                                        data-object-type="{{$item->object_type}}"
                                        data-url="{{route('admin.reports.fetch', ['id' => $item->id])}}"
                                    {{$item->status == 0 ? '' : 'disabled'}}>
                                    <i class="mdi mdi-gavel m-0"></i>
                                </button>
                            @endif
                            @if(in_array('show', $actions))
                                <a href="{{route($showRouteName, $showRouteParameters)}}"
                                   class="btn btn-info"
                                   target="_blank"
                                >
                                    <i class="mdi mdi-eye m-0"></i>
                                </a>
                            @endif
                            @if(in_array('edit', $actions))
                                <a href="{{route('admin.'.$tableName.'.edit', ['id' => $item->id])}}"
                                   class="btn btn-primary">
                                    <i class="mdi mdi-pencil m-0"></i>
                                </a>
                            @endif
                            @if(in_array('ban', $actions))
                                <a href="#" class="btn btn-warning">
                                    <i class="mdi mdi-block-helper m-0"></i>
                                </a>
                            @endif
                            @if(in_array('restore', $actions) && Auth::user()->isSuperAdmin() && $item->deleted_at)
                                <a href="{{route('admin.'.$tableName.'.restore', ['id' => $item->id])}}"
                                   class="btn btn-success">
                                    <i class="mdi mdi-backup-restore m-0"></i>
                                </a>
                            @endif
                            @if(in_array('delete', $actions) && !$item->deleted_at)
                                <a href="{{route('admin.'.$tableName.'.delete', ['id' => $item->id])}}"
                                   class="btn btn-dribbble">
                                    <i class="mdi mdi-trash-can m-0"></i>
                                </a>
                            @endif
                            @if(in_array('forceDelete', $actions) && Auth::user()->isSuperAdmin())
                                <a href="{{route('admin.'.$tableName.'.force-delete', ['id' => $item->id])}}"
                                   class="btn btn-danger">
                                    <i class="mdi mdi-fire m-0"></i>
                                </a>
                            @endif
                        </td>
                    @endif
                    @foreach($fields as $field)
                        <td {{$loop->last ? 'class="text-center"' : '' }}>
                            {{ $item->{$field} }}
                        </td>
                    @endforeach
                    @if($tableName === 'reports')
                        <td>
                            <a href="/user/{{$item->username}}" class="text-info" target="_blank">
                                {{$item->username}}
                            </a>
                        </td>
                        <td>
                            @switch($item->status)
                                @case(-1)
                                    <label class="badge badge-danger">Rejected</label>
                                    @break
                                @case(1)
                                    <label class="badge badge-success">Enforced</label>
                                    @if($item->penalty)
                                        <label class="badge badge-warning">
                                            @switch($item->penalty)
                                                @case(\App\Enums\PenaltyType::BAN)
                                                    User Banned
                                                    @break
                                                @case(\App\Enums\PenaltyType::DELETE_COMMENT)
                                                    Comment Deleted
                                                    @break
                                                @case(\App\Enums\PenaltyType::DELETE_POST)
                                                    Post Deleted
                                                    @break
                                                @case(\App\Enums\PenaltyType::DELETE_USER)
                                                    User Deleted
                                                    @break
                                                @default
                                                    @break
                                            @endswitch
                                        </label>
                                    @endif
                                    @break
                                @default
                                    <label class="badge badge-info">Pending</label>
                            @endswitch
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="modal fade" id="enforceModal" tabindex="-1" role="dialog" aria-labelledby="enforceModalLabel"
     aria-hidden="true" data-id="0">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enforceModalLabel">Enforce</h5>
                <button type="button" class="hide-modal btn btn-outline-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="enforce-form" method='POST'>
                <div class="modal-body">
                    <p id="report-reason">
                    </p>
                    <p id="report-body">
                    </p>
                </div>
                <div class="modal-footer">
                    <div class="input-group w-auto">
                        <div id="enforce-date" class="input-group date w-auto" data-provide="datepicker">
                        </div>
                        <div class="input-group-append">
                            <button id="ban-user" type="button" class="btn btn-warning h-100 enforce-button"
                                    data-penalty="1"
                                    data-status="1">
                                <i class="mdi mdi-gavel btn-icon-append"></i> Ban User
                            </button>
                        </div>
                    </div>
                    <div class="vr"></div>
                    <button id="delete-comment" type="button" class="btn btn-warning enforce-button" data-penalty="2"
                            data-status="1">
                        <i class="mdi mdi-gavel btn-icon-prepend"></i> Delete Comment
                    </button>
                    <button id="delete-post" type="button" class="btn btn-warning enforce-button" data-penalty="3"
                            data-status="1">
                        <i class="mdi mdi-gavel btn-icon-prepend"></i> Delete Post
                    </button>
                    <button id="delete-user" type="button" class="btn btn-warning enforce-button" data-penalty="4"
                            data-status="1">
                        <i class="mdi mdi-gavel btn-icon-prepend"></i> Delete User
                    </button>
                    <button id="reject" type="button" class="btn btn-danger enforce-button" data-status="-1">
                        <i class="mdi mdi mdi-shredder btn-icon-prepend"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#enforce-date").datepicker().on('changeDate change keyup paste', function () {
        $('#ban-user').prop('disabled', $(this).children().first().val() === '')
    });

    $('.show-modal').on('click', function () {
        $('#ban-user').prop('disabled', true)
        let url = $(this).data('url')
        let enforceDate = $('#enforce-date')
        enforceDate.datepicker('destroy');
        $('#report-reason').html(
            '<div class="d-flex justify-content-center">' +
                '<div class="spinner-border" role="status"></div>' +
            '</div>'
        )
        let reportBody = $('#report-body')
        reportBody.html('')
        reportBody.hide()
        enforceDate.html('')
        $.get(url)
            .done(res => {
                $('#report-reason').html(res.description)
                if (res.object_type === 'comment') {
                    $('#report-body').html(
                        "<a href='/post/" + res.object.post.slug + "' class='btn btn-info me-2 mb-3' target='_blank'>" +
                            "<i class='mdi mdi-newspaper btn-icon-prepend'></i> Show post" +
                        "</a>" +
                        "<a href='/user/" + res.object.user.username + "' class='btn btn-info mb-3' target='_blank'>" +
                            "<i class='mdi mdi-account btn-icon-prepend'></i> Show user profile" +
                        "</a>" + res.object.body
                    )
                }
                if (res.object_type === 'post') {
                    $('#report-body').html(
                        "<a href='/post/" + res.object.slug + "' class='btn btn-info' target='_blank'>" +
                            "<i class='mdi mdi-newspaper btn-icon-prepend'></i> Show post" +
                        "</a>"
                    )
                }
                if (res.object_type === 'user') {
                    $('#report-body').html(
                        "<a href='/user/" + res.object.username + "' class='btn btn-info' target='_blank'>" +
                            "<i class='mdi mdi-account btn-icon-prepend'></i> Show user profile" +
                        "</a>"
                    )
                }
                reportBody.show()
                enforceDate.html(
                    "<input type='text' class='form-control'>" +
                    "<div class='input-group-addon'>" +
                        "<span class='glyphicon glyphicon-th'></span>" +
                    "</div>"
                )
                enforceDate.datepicker({
                    format: "yyyy-mm-dd",
                    startDate: 'now',
                    todayBtn: "linked",
                });
                $('#enforce-date input').attr("placeholder", "Ban until");
            })
            .fail(res => {
                console.log(res)
            })

        let type = $(this).attr('data-object-type')

        if (type === 'comment') {
            $('#delete-comment').show()
        } else {
            $('#delete-comment').hide()
        }

        if (type === 'user') {
            $('#delete-post').hide()
        } else {
            $('#delete-post').show()
        }

        let enforceModal = $('#enforceModal')
        enforceModal.attr('data-id', $(this).attr('data-id'))
        enforceModal.attr('data-object-id', $(this).attr('data-object-id'))
        enforceModal.attr('data-object-type', type)
        enforceModal.modal('show');
    });

    $('.hide-modal').on('click', function () {
        $('#enforceModal').modal('hide');
    });

    $(document).on('click', '.enforce-button', function (e) {
        e.preventDefault();
        let url = "{{route('admin.reports.enforce')}}"
        let enforceModal = $('#enforceModal')
        $.post(url, {
            _token: '{{ csrf_token() }}',
            reportId: enforceModal.attr('data-id'),
            status: $(this).attr('data-status'),
            objectId: enforceModal.attr('data-object-id'),
            objectType: enforceModal.attr('data-object-type'),
            penalty: $(this).attr('data-penalty'),
            date: $('#enforce-date input').first().val()
        })
            .done(res => {
                window.location.reload();
            })
            .fail(res => {
                window.location.reload();
            })
    });
</script>
