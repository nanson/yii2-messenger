<?php

namespace nanson\messenger\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use nanson\messenger\models\Message;
use nanson\messenger\web\AddMessageAsset;
use yii\helpers\Html;

/**
 * Class AddMessage
 * @package nanson\messenger\widgets
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class AddMessage extends Widget
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
    public $tpl = 'create';

    /**
     * @var array The HTML attributes for the widget wrapper tag.
     */
    public $options = [];

    /**
     * @var array The HTML attributes for the widget form.
     */
    public $formOptions = [];

    /**
     * @var string route to create message action
     */
    public $route = '/messenger/rest/create';

    /**
     * @var string PJAX widget id
     */
    public $pjaxId;

    /**
     * @var string skin AssetBundle
     */
    public $skinAsset;

    /**
     * @var string fancybox selector
     */

    public $fancySelector;

    /**
     * @var array fancybox options
     * @link http://fancybox.net/api
     */
    public $fancyOptions = [];

    /**
     * @inheritdoc
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

        AddMessageAsset::register($this->view);
        $this->view->registerJs("$('#$this->id').messengerAddMessage()");

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
        $model = Yii::createObject([
            'class' => Message::className(),
            'author_id' => $this->userId,
            'recipient_id' => $this->contactId,
        ]);

        $options = ArrayHelper::merge($this->options, [
            'id' => $this->id,
            'data' => [
                'pjax' => $this->pjaxId,
                'fancy' => $this->fancySelector,
            ],
        ]);

        $formOptions = ArrayHelper::merge([
            'id' => "$this->id-form",
            'action' => Yii::$app->urlManager->createUrl($this->route),
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
        ], $this->formOptions);

        if ($this->fancySelector) {

            echo \newerton\fancybox\FancyBox::widget([
                'target' => $this->fancySelector,
                'config' => array_merge([
                    'href' => '#' . $this->id,
                    'autoDimensions' => false,
                    'autoSize' => false,
                    'width' => 500,
                    'height' => 200,
                    'type' => 'inline',
                ], $this->fancyOptions),
            ]);

            Html::addCssStyle($options, 'display:none');

        }

        return $this->render($this->tpl, [
            'id' => $this->id,
            'model' => $model,
            'options' => $options,
            'formOptions' => $formOptions,
        ]);
    }

}