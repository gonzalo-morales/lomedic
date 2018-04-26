@if(!empty($text))
	{{ Form::label($name, ucwords(cTrans('forms.'.$name,$text))) }}
@endif
{{ Form::textarea($name, null, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
    if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
    return array_merge($acc, [$key => $item]);
}, ['id' => $name, 'class' => 'form-control','rows'=>4])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}