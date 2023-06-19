@props([
    'group' => 'form',
    'name' => null,
    'label' => null,
])

<div class="form-group @error($name) mb-0 @enderror">
    <label for="{{$group}}_{{$name}}">{{$label}}</label>
    <input
        id="{{$group}}_{{$name}}"
        name="{{$group}}[{{$name}}]"
        type="password"
        class="form-control @error($name) is-invalid @enderror"
    >
</div>
@error($name)
<div class="invalid-feedback d-block" style="margin-bottom: 1rem" role="alert">{{ $message }}</div>
@enderror
