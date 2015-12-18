<?php

namespace nanson\messenger\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use nanson\messenger\web\CounterAsset;

/**
 * Class Counter
 * @package nanson\messenger\widgets
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Counter extends Widget
{

	public $actionRoute = '/messenger/rest/count';

	/**
	 * @var int timeout counter update
	 */
	public $timeout = 30;

	/**
	 * @var string counter html tag
	 */
	public $tag = 'span';

	/**
	 * @var array The HTML attributes for the counter tag.
	 */
	public $options = [
		'class' => 'badge',
	];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		CounterAsset::register($this->view);
		$this->view->registerJs("$('#$this->id').messengerCounter()");

		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$options = ArrayHelper::merge($this->options, [
			'id' => $this->id,
			'data' => [
				'url' => Yii::$app->urlManager->createUrl($this->actionRoute),
				'timeout' => $this->timeout,
			],
		]);

		return Html::tag($this->tag, '0', $options);
	}

}