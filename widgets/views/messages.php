<?php
/**
 * @var \yii\base\View $this
 * @var string $id widget id
 * @var array $options
 * @var integer $contactId contact ID
 * @var \yii\data\ActiveDataProvider $dataProvider messages
 */

use yii\helpers\Html;
use yii\widgets\Pjax;

echo Html::beginTag('div', $options);

Pjax::begin(["id" => "$id-pjax"]);

echo \yii\grid\GridView::widget([
	'dataProvider' => $dataProvider,
	'rowOptions' => function ($model, $key, $index, $grid) {

		$options = [
			'id' => "message-item-$key",
			'class' => 'message-item',
		];

		if ($model->unread) {
			Html::addCssClass($options, 'info');
		}

		return $options;
	},
	'columns' => [
		'created_at:datetime',
		[
			'value' => 'author.username',
			'label' => Yii::t('messenger/app', 'Author'),
		],
		[
			'value' => 'recipient.username',
			'label' => Yii::t('messenger/app', 'Recipient'),
		],
		'message'
	]
]);

Pjax::end();

echo \nanson\messenger\widgets\AddMessage::widget([
	'contactId' => $contactId,
	'pjaxId' => "$id-pjax",
]);

echo Html::endTag('div');