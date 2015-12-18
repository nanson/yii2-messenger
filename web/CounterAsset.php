<?php

namespace nanson\messenger\web;

use yii\web\AssetBundle;

/**
 * Class CounterAsset
 * @package nanson\messenger\web
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class CounterAsset extends AssetBundle
{

	/**
	 * @inheritdoc
	 */
	public $js = [
		'messenger.counter.js'
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [
		'yii\web\JqueryAsset',
	];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->sourcePath = __DIR__ . "/../assets";
		parent::init();
	}

}