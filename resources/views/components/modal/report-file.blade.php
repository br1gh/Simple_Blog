<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="{{$id}}-label"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-white border-warning" style="background-color: #282A36">
            <div class="modal-header" style="background-color: #272934; border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                <h5 class="modal-title" id="{{$id}}-label">Report {{$type}}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body border-bottom-0">
                <form id="report-{{$type}}-form">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="mb-3">
                        <label for="report-{{$type}}-description" class="col-form-label">Description:</label>
                        <textarea class="form-control text-white" id="report-{{$type}}-description" name="description"
                                  required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="background-color: #272934; border-top: 1px solid rgba(0, 0, 0, 0.125);">
                <button id="report-{{$type}}-submit" type="button" class="btn btn-warning" data-id="">Report</button>
            </div>
        </div>
    </div>
</div>
