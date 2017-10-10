{{ Form::label($name, $text) }}
{{ Form::select($name, $options ?? [], null, ['id' => $name, 'class' => 'form-control custom-select'] + ($attributes ?? [])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}