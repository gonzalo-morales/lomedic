{{ Form::label($name, $text) }}
{{ Form::text($name, $value, ['id' => $name, 'class' => 'form-control']) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}