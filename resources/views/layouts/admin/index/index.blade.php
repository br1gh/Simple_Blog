@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="column">Sort by</label>
                        <select id="column" class="form-control select2">
                            @foreach($table->orderableColumns as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="order">Order</label>
                        <select id="order" class="form-control select2">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="limit">Limit</label>
                        <select id="limit" class="form-control select2">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search"></label>
                        <input id="search" class="form-control" type="text" placeholder="Search...">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card table-card">
        <div class="row">
            <div class="col-md-6 card-body">
                <div>
                    <nav>
                        <ul class="pagination justify-content-center m-0 admin-table-pagination">
                            <li class="page-item">
                                <a id="first-page" class="page-link first-last-button" data-page="1"><<</a>
                            </li>
                            <li class="page-item">
                                <a id="previous-page" class="page-link"><</a>
                            </li>
                            <li class="page-item" style="width: 4rem; height: 100%">
                                <select id="custom-page" class="form-control" data-live-search="true">
                                </select>
                            </li>
                            <li class="page-item">
                                <a id="next-page" class="page-link">></a>
                            </li>
                            <li class="page-item">
                                <a id="last-page" class="page-link first-last-button">>></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="card-body table-body">
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let tableUrl = "{{ url()->current() }}";
        let token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('admin/vendors/table/table.js')}}"></script>
@endpush

