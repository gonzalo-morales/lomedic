@if(!empty($text))
	{{ Form::label($name, $text) }}
@endif
{{ Form::select($name, collect($options ?? [])->prepend('...','0'), $default ?? null, collect($selectAttributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
    if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
    return array_merge($acc, [$key => $item]);
}, ['id' => $name, 'class' => 'form-control custom-select']), array_replace($optionsAttributes ?? [], ['0' => ['disabled','selected']])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}