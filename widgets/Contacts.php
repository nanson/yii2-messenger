<?php

namespace nanson\messenger\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use nanson\messenger\models\Contact;

/**
 * Class Contacts
 * @property-read ActiveDataProvider $dataProvider data provider for contacts
 * @package nanson\messenger\widgets
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Contacts extends Widget
{

    /**
     * @var integer user ID
     */
    public $userId;

    /**
     * @var string widget template
     */
    public $tpl = 'contacts';

    /**
     * @var array The HTML attributes for the widget wrapper tag.
     */
    public $options = [];

    /**
     * @var int contacts per page;
     */
    public $pageSize = 10;

    /**
     * @var string route to messages
     */
    public $viewRoute = '/messenger/contacts/messages';

    /**
     * @var array default contacts order
     */
    public $defaultOrder = ['last_message_id' => SORT_DESC];

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
     */
    public function init()
    {
        parent::init();

        $skin = $this->skinAsset;

        if (is_null($this->userId)) {
            $this->userId = Yii::$app->user->id;
        }

        if ($skin) {
            $skin::register($this->view);
        }

    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        $options = ArrayHelper::merge($this->options, [
            'id' => $this->id,
        ]);

        return $this->render($this->tpl, [
            'id' => $this->id,
            'options' => $options,
            'viewRoute' => $this->viewRoute,
            'dataProvider' => $this->dataProvider,
        ]);
    }

    /**
     * Returns data provider for contacts
     * @return object|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function getDataProvider()
    {
        if (is_null($this->_dataProvider)) {

            $query = Contact::find()->byUser($this->userId)->with(['lastMessage', 'lastMessageAuthor']);

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