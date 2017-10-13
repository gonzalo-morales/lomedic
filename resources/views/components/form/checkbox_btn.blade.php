@if(!empty($label))
<label class="text-center w-100">{{$label}}</label>
@endif
<div data-toggle="buttons" class="text-center">
	{{ Form::hidden($name, 0) }}
    <label class="btn btn-secondary form-check-label {{ (old($name) ?? $value ) ? 'active':''}}"> 
        {{ Form::checkbox($name, 1, old($name), [
            'id' => $name,
            'class' => 'form-check-input toggeable',
			'data-toggle-off' => $textoff ?? $text,
			'data-toggle-on' => $text
        ]) }}
        <span>{{(old($name) ?? $value ) ? $text : $textoff ?? $text}}</span>
    </label>
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}