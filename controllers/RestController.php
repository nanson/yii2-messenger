<?php

namespace nanson\messenger\controllers;

use yii\rest\ActiveController;
use nanson\messenger\models\Message;

/**
 * Class RestController
 * @package nanson\messenger\controllers
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class RestController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'nanson\messenger\models\Message';

    /**
     * Returns vount unreaded messages for user
     * @return int
     */
    public function actionCount()
    {
        return Message::find()->byRecipient()->unread()->count();
    }
}