<button type="submit" class="btn btn-primary me-2">
    <i class="mdi mdi mdi-floppy btn-icon-prepend"></i> Submit
</button>
<a href="{{route(str_replace('edit','index',request()->route()->getName()))}}" class="btn btn-outline-light">
    <i class="mdi mdi mdi-close btn-icon-prepend"></i> Cancel
</a>
