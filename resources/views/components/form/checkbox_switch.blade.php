@if(!empty($label))
	{{ Form::label($name, ucwords(cTrans('forms.'.$name,$text)),['class'=>'w-100']) }}
@endif
<label class="switch">
	{{ Form::checkbox($name, 1, old($name), ['id' => $name,'class' => 'inputSlider']) }}
    <span class="slider round"></span>
    <p class="switch-text text-danger d-none">Inactivo</p>
</label>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}