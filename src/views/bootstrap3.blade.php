<div class="alert alert-{{ $key }} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @foreach ($messages as $message)
        <p>{{ $message }}</p>
    @endforeach
</div>
 