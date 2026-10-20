<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ trans('torns.ai_settings_key') }}</label>
    <input class="form-control" type="password" id="{{ $name }}" name="{{ $name }}" autocomplete="off" placeholder="{{ $ai->masked($name) ?: $placeholder }}">
    <div class="form-hint">{{ $hint }}</div>
</div>
