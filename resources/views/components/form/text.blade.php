{{ Form::label($name, $text) }}
{{ Form::text($name, null, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
    if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
    return array_merge($acc, [$key => $item]);
}, ['id' => $name, 'class' => 'form-control'])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}