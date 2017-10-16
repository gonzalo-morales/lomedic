{{ Form::label($name, $text) }}
{{ Form::select($name, collect($options ?? [])->prepend('...','0'), null, ['id' => $name, 'class' => 'form-control custom-select'] + ($attributes ?? []), ['0' => ['disabled','selected']]) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}