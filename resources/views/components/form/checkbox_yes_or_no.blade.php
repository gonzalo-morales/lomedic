<label>{{ucwords(cTrans('forms.'.strtolower($name),$text))}}</label>
<div class="form-check">
	{{ Form::hidden($name, 0) }}
	<label class="form-check-label">
		{{ Form::checkbox($name, 1, old($name), [
			'id' => $name,
			'class' => 'form-check-input toggeable',
			'data-toggle-off' => cTrans('data.'.'no','No'),
			'data-toggle-on' => cTrans('data.'.'si','Si')
		] + ($attributes ?? [])) }}
		<span></span>
	</label>
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}