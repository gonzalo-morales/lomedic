@if(!empty($text))
	{{ Form::label($name, $text,['class'=>'w-100']) }}
@endif
<label class="custom-file w-100">
    {{ Form::file($name, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
            if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
            return array_merge($acc, [$key => $item]);
        }, ['id'=>$name, 'class'=>'custom-file-input', 'data-target'=>'#'.$name.'-span', 'data-toggle'=>'custom-file'])) }}
    <span id="{{$name}}-span" class="custom-file-control custom-file-name" data-content="Seleccionar archivo(s)..."></span>
</label>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}