<?php
/**
 * @var \yii\base\View $this
 * @var string $id widget id
 * @var array $options
 * @var string $viewRoute
 * @var \yii\data\ActiveDataProvider $dataProvider contacts
 */

use yii\helpers\Html;
use yii\widgets\Pjax;

echo Html::beginTag('div', $options);

Pjax::begin(['id' => "$id-pjax"]);

echo \yii\grid\GridView::widget([
	'id' => "$id-contacts-list",
	'dataProvider' => $dataProvider,
	'rowOptions' => function ($model, $key, $index, $grid) {
		if ($model->lastMessage->unread) {
			return ['class' => 'info'];
		}
	},
	'columns' => [
		[
			'label' => Yii::t('messenger/app', 'Contact'),
			'format' => 'html',
			'value' => function($model, $key, $index, $column) use ($viewRoute) {
				return Html::a($model->username, [$viewRoute, 'id' => $model->id]);
			},
		],
		'lastMessage.created_at:datetime',
		[
			'value' => 'lastMessageAuthor.username',
			'label' => Yii::t('messenger/app', 'Author'),
		],
		'lastMessage.message',
	]
]);

Pjax::end();

echo Html::endTag('div');