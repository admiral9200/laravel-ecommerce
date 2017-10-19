<?php


if(!isset($values)) {
    $values = $values;
}

if(isset($attributes)) {
    $attributes['name'] = $name;
    $attributes['type'] = "text";
    if(!isset($attributes['id'])) {
        $attributes['id'] = $name;
    }

} else {
    $attributes['type'] = "text";
    $attributes['class'] = 'form-control';
    $attributes['id'] = $name;
    $attributes['name'] = $name;

}
$attrString = "";

foreach($attributes as $attrKey => $attrValue) {
    $attrString .= "{$attrKey}=\"{$attrValue}\"";
}

//dd($options);
?>


<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <select {!! $attrString !!}>
        @foreach($options as $val => $lab)
        <option
                @if(in_array($val, $values))
                        selected
                @endif
                value="{{ $val }}">{{ $lab }}</option>
        @endforeach
    </select>

</div>