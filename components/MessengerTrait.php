<?php


namespace nanson\messenger\components;

use Yii;
use nanson\messenger\Messenger;

/**
 * Class MessengerTrait
 * @property-read string userClassName User class name
 * @property-read string userTableName User table name
 * @package nanson\messenger\components
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
trait MessengerTrait
{

    /**
     * Returns User class name
     * @return string
     */
    public static function getUserClassName()
    {
        return Yii::$app->getModule(Messenger::MODULE)->userClass;
    }

    /**
     * Returns User model table name
     * @return string
     */
    public static function getUserTableName()
    {
        $userClass = self::getUserClassName();
        $tableName = trim($userClass::tableName(), "{%}");
        return "{{%$tableName}}";
    }

}