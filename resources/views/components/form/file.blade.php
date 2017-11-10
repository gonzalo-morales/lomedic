{{ Form::label($name, $text,['class'=>'w-100']) }}
<label class="custom-file w-100">
    {{ Form::file($name, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
            if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
            return array_merge($acc, [$key => $item]);
        }, ['id'=>$name,'multiple', 'class'=>'custom-file-input'])) }}
    <span class="custom-file-control"></span>
</label>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}