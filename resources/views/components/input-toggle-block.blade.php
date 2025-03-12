@props(['name', 'label' => '', 'checked' => false])

<div class="form-check">
    <input type="hidden" name="{{ $name }}" value="0"> <!-- Để gửi giá trị mặc định nếu không checked -->
    <input type="checkbox" class="form-check-input" id="{{ $name }}" name="{{ $name }}" value="1" {{ $checked ? 'checked' : '' }}>
    <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
</div>
