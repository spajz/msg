<div data-alert class="alert-box {{ $key }} radius">
    @foreach ($messages as $message)
        <p>{{ $message }}</p>
    @endforeach
    <a href="#" class="close">&times;</a>
</div>
 