<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <input class="form-control" id="{{ $name }}" name="{{ $name }}" value="{{ setting($name) }}" placeholder="{{ $default }}">
</div>
