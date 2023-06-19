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
                <td colspan="{{sizeof($headers) + 1}}" class="text-center">
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
                            @if(in_array('block', $actions))
                                <a href="#"
                                   class="btn btn-warning">
                                    <i class="mdi mdi-block-helper m-0"></i>
                                </a>
                            @endif
                            @if(in_array('restore', $actions) && Auth::user()->id === 1 && $item->deleted_at)
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
                            @if(in_array('forceDelete', $actions) && Auth::user()->id === 1)
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
                            <a href="/user/{{$item->username}}" class="text-primary">{{$item->username}}</a>
                        </td>
                        <td>
                            @switch($item->status)
                                @case(-1)
                                    <label class="badge badge-danger">Rejected</label>
                                    @break
                                @case(1)
                                    <label class="badge badge-success">Enforced</label>
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
