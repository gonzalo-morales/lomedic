{{ Form::label($name, $text) }}
{{ Form::textarea($name, null, ['id' => $name, 'class' => 'form-control'] + ($attributes ?? [])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}