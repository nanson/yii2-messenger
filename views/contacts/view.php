<?php
/**
 * @var \yii\base\View $this
 * @var integer $contactId Contact ID
 */

echo \yii\helpers\Html::tag('h1', Yii::t('messenger/app', 'Messages'));

echo \nanson\messenger\widgets\Messages::widget([
	'contactId' => $contactId,
]);