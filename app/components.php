<?php
Form::component('cText', 'components.form.text', ['text', 'name', 'attributes','value']);
Form::component('cNumber', 'components.form.number', ['text', 'name', 'attributes', 'value', 'decimal', 'separator']);
Form::component('cPassword', 'components.form.password', ['text', 'name', 'attributes']);
Form::component('cDate', 'components.form.date', ['text', 'name', 'attributes']);
Form::component('cTextArea', 'components.form.textarea', ['text', 'name', 'attributes']);
Form::component('cSelect', 'components.form.select', ['text', 'name', 'options', 'selectAttributes','optionsAttributes']);
Form::component('cSelectWithDisabled', 'components.form.select_disabled', ['text', 'name', 'options', 'selectAttributes','optionsAttributes','default']);
Form::component('cCheckbox', 'components.form.checkbox', ['text', 'name', 'attributes', 'value']);
Form::component('cCheckboxYesOrNo', 'components.form.checkbox_yes_or_no', ['text', 'name', 'attributes']);
Form::component('cCheckboxBtn', 'components.form.checkbox_btn', ['label', 'text', 'name', 'value', 'textoff']);
Form::component('cCheckboxSwitch', 'components.form.checkbox_switch', ['label', 'name', 'value']);
Form::component('cRadio', 'components.form.radio', ['text', 'name', 'options', 'attributes']);
Form::component('cFile', 'components.form.file', ['text', 'name', 'attributes']);