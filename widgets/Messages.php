<?php

namespace nanson\messenger\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;
use nanson\messenger\models\Message;

/**
 * Class Messages
 * @package nanson\messenger\widgets\messenger
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Messages extends Widget
{

	/**
	 * @var integer contact ID
	 */
	public $contactId;

	/**
	 * @var integer user ID
	 */
	public $userId;

	/**
	 * @var string widget template
	 */
	public $tpl = 'messages';

	/**
	 * @var array The HTML attributes for the widget wrapper tag.
	 */
	public $options = [];

	/**
	 * @var int messages per page;
	 */
	public $pageSize = 10;

	/**
	 * @var array default contacts order
	 */
	public $defaultOrder = ['created_at' => SORT_DESC];

	/**
	 * @var array options for data provider
	 */
	public $dataProviderOptions = [];

	/**
	 * @var callable function to modify query;
	 */
	public $queryModifier;

	/**
	 * @var string skin AssetBundle
	 */
	public $skinAsset;

	/**
	 * @var ActiveDataProvider contacts data provider
	 */
	protected $_dataProvider;

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function init()
	{
		parent::init();

		if (is_null($this->contactId)) {
			throw new InvalidConfigException('Property "contactId" is required');
		}

		if (is_null($this->userId)) {
			$this->userId = Yii::$app->user->id;
		}

		$skin = $this->skinAsset;

		if ($skin) {
			$skin::register($this->view);
		}

	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		Message::markRead($this->userId, $this->contactId);

		$options = ArrayHelper::merge($this->options, ['id' => $this->id]);

		return $this->render($this->tpl, [
			'id' => $this->id,
			'options' => $options,
			'contactId' => $this->contactId,
			'dataProvider' => $this->dataProvider,
		]);
	}

	/**
	 * Returns data provider for messages
	 * @return object|ActiveDataProvider
	 * @throws \yii\base\InvalidConfigException
	 */
	public function getDataProvider()
	{
		if (is_null($this->_dataProvider)) {

			$query = Message::find()->byContact($this->contactId, $this->userId)->with(['author', 'recipient']);

			if (is_callable($this->queryModifier)) {
				$func = $this->queryModifier;
				$func($query);
			}

			$this->_dataProvider = Yii::createObject(ArrayHelper::merge([
				'class' => ActiveDataProvider::className(),
				'query' => $query,
			], $this->dataProviderOptions));

			$this->_dataProvider->sort->defaultOrder = $this->defaultOrder;

			$this->_dataProvider->pagination->pageSize = $this->pageSize;

		}

		return $this->_dataProvider;
	}

}