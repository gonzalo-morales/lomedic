{{ Form::label($name, $text) }}
{{ Form::select($name, $options ?? [], $selected, ['id' => $name, 'class' => 'form-control select']) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}