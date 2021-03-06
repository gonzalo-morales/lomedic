@if(!empty($text))
	{{ Form::label($name, ucwords(cTrans('forms.'.$name,$text))) }}
@endif
{{ Form::number($name, !empty($value) ? number_format($value ,$decimal ?? 2, '.',$separator ?? '') : null, ['id' => $name, 'class' => 'form-control'] + ($attributes ?? [])) }}
{{ $errors->has($name) ? HTML::tag('span', $errors->first($name), ['class'=>'help-block error-help-block']) : '' }}