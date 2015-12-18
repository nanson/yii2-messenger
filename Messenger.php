<?php

namespace nanson\messenger;

use Yii;
use yii\base\Module;

/**
 * Class Messenger
 * @package nanson\messenger
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Messenger extends Module
{

	const MODULE = 'messenger';

	/**
	 * @var string User class
	 */
	public $userClass;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if (is_null($this->userClass)) {
			$this->userClass = Yii::$app->user->identityClass;
		}

		$this->registerTranslations();

		return parent::init();
	}

	public function registerTranslations()
	{
		Yii::$app->i18n->translations['messenger/*'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath' => '@vendor/nanson/yii2-messenger/messages',
			'fileMap' => [
				'messenger/app' => 'app.php',
			],
        ];
    }

}