{{ Form::label($name, $text) }}
@foreach($options as $key=>$value)
	<label class="custom-control custom-radio w-100">
    	{{ Form::radio($name,$key,Null, collect($attributes ?? [])->reduceWithKeys(function($acc, $item, $key) {
            if (isset($acc['class']) && $key == 'class') $item = "{$acc['class']} $item";
            return array_merge($acc, [$key => $item]);
        }, ['id' => $name.' '.$key, 'class' => 'custom-control-input'])) }}
        <span class="custom-control-indicator"></span>
    	<span class="custom-control-description">{{ $value }}</span>
    </label>
@endforeach
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}