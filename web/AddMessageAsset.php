<?php

namespace nanson\messenger\web;

use yii\web\AssetBundle;

/**
 * Class AddMessageAsset
 * @package nanson\messenger\web
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class AddMessageAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $js = [
        'messenger.message.add.js'
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