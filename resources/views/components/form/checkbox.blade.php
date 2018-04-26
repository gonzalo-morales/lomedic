<div class="form-check">
    {{ Form::hidden($name, 0) }}
    <label class="form-check-label custom-control custom-checkbox">
        {{ Form::checkbox($name, 1, $value ?? old($name), [
            'id' => $name,
            'class' => 'form-check-input custom-control-input',
        ] + ($attributes ?? [])) }} {!! ucwords(cTrans('forms.'.$name,$text)) !!}
        <span class="custom-control-indicator"></span>
    </label>
</div>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}