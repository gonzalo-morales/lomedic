<label>{{$text}}</label>
<div class="form-check">
	{{ Form::hidden($name, 0) }}
	<label class="form-check-label">
		{{ Form::checkbox($name, null, old($name), [
			'id' => $name,
			'class' => 'form-check-input toggeable',
			'data-toggle-off' => 'No',
			'data-toggle-on' => 'Si'
		]) }}
	</label>
</div>
