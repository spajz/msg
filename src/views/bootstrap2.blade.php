<div class="alert alert-{{ $key }}">
    <button type="button" class="close" data-dismiss="alert">×</button>
    @foreach ($messages as $message)
        <p>{{ $message }}</p>
    @endforeach
</div>