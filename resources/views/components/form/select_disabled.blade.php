@if(!empty($text))
	{{ Form::label($name, $text) }}
@endif
{{ Form::select($name, collect($options ?? [])->prepend('...','0'), null, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
    if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
    return array_merge($acc, [$key => $item]);
}, ['id' => $name, 'class' => 'form-control custom-select']), ['0' => ['disabled','selected']]) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}