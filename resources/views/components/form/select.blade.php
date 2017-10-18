{{ Form::label($name, $text) }}
{{ Form::select($name, $options ?? [], null, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
    if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
    return array_merge($acc, [$key => $item]);
}, ['id' => $name, 'class' => 'form-control custom-select'])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}

