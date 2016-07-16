<?php
/**
 * @var \yii\base\View $this
 * @var string $id widget id
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

echo Html::beginTag('div', $options);

$form = ActiveForm::begin($formOptions);

echo Html::tag('div', Yii::t('messenger/app', 'Success Message'), [
    'class' => 'message-success alert alert-success',
    'style' => 'display:none;',
]);
echo Html::tag('div', Yii::t('messenger/app', 'Error Message'), [
    'class' => 'message-error alert alert-danger',
    'style' => 'display:none;',
]);

echo $form->field($model, 'message')->textarea(['name' => 'message']);
echo $form->field($model, 'author_id')->hiddenInput(['name' => 'author_id'])->label(false);
echo $form->field($model, 'recipient_id')->hiddenInput(['name' => 'recipient_id'])->label(false);

echo Html::beginTag('div', ['class' => 'form-group']);
echo Html::submitButton(Yii::t('messenger/app', 'Send Message'), ['class' => 'message-add btn btn-primary']);
echo Html::endTag('div');

ActiveForm::end();

echo Html::endTag('div');