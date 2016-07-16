<?php

namespace nanson\messenger\models;

use Yii;
use yii\db\ActiveRecord;
use nanson\messenger\db\ContactQuery;
use nanson\messenger\components\MessengerTrait;

/**
 * Class Contact
 * @property-read integer last_message_id
 * @property-read Message lastMessage
 * @property-read ActiveRecord lastMessageAuthor
 * @property-read ActiveRecord userModel
 * @package nanson\messenger\models
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Contact extends ActiveRecord
{
    use MessengerTrait;

    /**
     * @var null|ActiveRecord User model object
     */
    protected $_userModel = null;

    /**
     * Returns User model object
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserModel()
    {
        if (is_null($this->_userModel) or $this->primaryKey != $this->_userModel->primaryKey) {

            $userClass = self::getUserClassName();

            if (empty($this->primaryKey)) {
                $this->_userModel = Yii::createObject(['class' => $userClass]);
            } else {
                $this->_userModel = $userClass::findOne($this->primaryKey);
            }
        }

        return $this->_userModel;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::getUserTableName();
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes[] = 'last_message_id';
        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = $this->userModel->fields();
        $fields['last_message_id'] = 'last_message_id';
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return $this->userModel->behaviors();
    }

    /**
     * @inheritdoc
     * @return ContactQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(ContactQuery::className(), [get_called_class()]);
    }

    /**
     * Relation with last message
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(Message::className(), ['id' => 'last_message_id']);
    }

    /**
     * Relation with last message author
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessageAuthor()
    {
        return $this->hasOne($this->userClassName, ['id' => 'author_id'])->via('lastMessage');
    }

}