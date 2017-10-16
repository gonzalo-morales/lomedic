@if(!empty($label))
<label class="text-center w-100">{{$label}}</label>
@endif
<label class="switch">
	{{ Form::checkbox($name, 1, old($name), ['id' => $name,'class' => 'inputSlider']) }}
    <span class="slider round"></span>
</label>
    <p class="switch-text text-danger h4">Inactivo</p>
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}