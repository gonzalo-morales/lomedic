<?php

Form::component('cText', 'components.form.text', ['text' , 'name', 'attributes']);
Form::component('cSelect', 'components.form.select', ['text' , 'name', 'options', 'attributes']);
Form::component('cSelectWithDisabled', 'components.form.select_disabled', ['text' , 'name', 'options', 'attributes']);
Form::component('cCheckbox', 'components.form.checkbox', ['text' , 'name']);
Form::component('cCheckboxYesOrNo', 'components.form.checkbox_yes_or_no', ['text' , 'name']);
Form::component('cCheckboxBtn', 'components.form.checkbox_btn', ['text' , 'name', 'value']);