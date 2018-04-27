<div class="text-center" style="display:inline">
@if(!empty($label))
	<label class="text-center mb-0 w-100">{{ucwords(cTrans('forms.'.strtolower($name),$label))}}</label>
@endif
	{{ Form::hidden($name, 0) }}
    <label class="center btn form-check-label {{ (old($name) ?? $value ) ? 'btn-success':'btn-secondary'}}"> 
        {{ Form::checkbox($name, 1, old($name), [
            'id' => $name,
            'class' => 'd-none toggeable',
			'data-toggle-off' => ucwords(cTrans('data.'.strtolower($textoff),$textoff)) ?? ucwords(cTrans('data.'.strtolower($text),$text)),
			'data-toggle-on' => ucwords(cTrans('data.'.strtolower($text),$text))
        ]) }}
        <span>{{(old($name) ?? $value ) ? ucwords(cTrans('data.'.strtolower($text),$text)) : ucwords(cTrans('data.'.strtolower($textoff),$textoff)) ?? ucwords(cTrans('data.'.strtolower($text),$text))}}</span>
    </label>
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}