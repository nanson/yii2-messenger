<?php
/**
 * @var \yii\base\View $this
 */

$counter = \nanson\messenger\widgets\Counter::widget();

echo \yii\helpers\Html::tag('h1', Yii::t('messenger/app', 'Contacts') . ' ' . $counter);

echo \nanson\messenger\widgets\Contacts::widget();