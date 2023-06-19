@props([
    'group' => 'form',
    'name' => null,
    'label' => null,
    'value' => null,
])

<div class="form-group @error($name) mb-0 @enderror">
    <label for="{{$group}}_{{$name}}">{{$label}}</label>
    <input
        id="{{$group}}_{{$name}}"
        name="{{$group}}[{{$name}}]"
        type="email"
        class="form-control @error($name) is-invalid @enderror"
        value="{{$value}}"
    >
</div>
@error($name)
<div class="invalid-feedback d-block" style="margin-bottom: 1rem" role="alert">{{ $message }}</div>
@enderror
