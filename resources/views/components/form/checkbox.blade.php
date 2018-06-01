<div class="custom-control custom-checkbox">
    {{-- {{ Form::hidden($name, 0) }} --}}
    {{ Form::checkbox($name, 1, $value ?? old($name), [
        'id' => $name,
        'class' => 'custom-control-input',
    ] + ($attributes ?? [])) }}
	{{ Form::label($name, ucwords(cTrans('forms.'.$name,$text)),['class'=>'custom-control-label','for' => $name]) }}
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}