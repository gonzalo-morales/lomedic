<div class="text-center" style="display:inline">
@if(!empty($label))
<label class="text-center mb-0 w-100">{{$label}}</label>
@endif
	{{ Form::hidden($name, 0) }}
    <label class="btn form-check-label {{ (old($name) ?? $value ) ? 'btn-success':'btn-secondary'}}"> 
        {{ Form::checkbox($name, 1, old($name), [
            'id' => $name,
            'class' => 'd-none toggeable',
			'data-toggle-off' => $textoff ?? $text,
			'data-toggle-on' => $text
        ]) }}
        <span>{{(old($name) ?? $value ) ? $text : $textoff ?? $text}}</span>
    </label>
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}