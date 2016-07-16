<?php

namespace nanson\messenger\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use nanson\messenger\db\MessageQuery;
use nanson\messenger\components\MessengerTrait;

/**
 * Class Message
 * @property integer $id
 * @property datetime $created_at
 * @property integer $author_id
 * @property integer $recipient_id
 * @property boolean $unread
 * @property string $message
 * @property ActiveRecord $author message author
 * @property ActiveRecord $recipient message recipient
 * @property-read ActiveRecord $contact message contact
 * @package nanson\messenger\models
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Message extends ActiveRecord
{

    use MessengerTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'recipient_id', 'message'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('messenger/app', 'Created At'),
            'author_id' => Yii::t('messenger/app', 'Author'),
            'recipient_id' => Yii::t('messenger/app', 'Recipient'),
            'unread' => Yii::t('messenger/app', 'Unread'),
            'message' => Yii::t('messenger/app', 'Message'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nanson_messenger_messages';
    }

    /**
     * @inheritdoc
     * @return MessageQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(MessageQuery::className(), [get_called_class()]);
    }

    /**
     * Relation with message Author
     * @return ActiveRecord
     */
    public function getAuthor()
    {
        return $this->hasOne($this->userClassName, ['id' => 'author_id'])->from(['author' => $this->userTableName]);
    }

    /**
     * Relation with message Recipient
     * @return ActiveRecord
     */
    public function getRecipient()
    {
        return $this->hasOne($this->userClassName, ['id' => 'recipient_id'])->from(['recipient' => $this->userTableName]);
    }

    /**
     * Return contact from message
     * @return ActiveRecord
     */
    public function getContact()
    {
        $userId = Yii::$app->user->id;

        if ($userId == $this->author_id) {
            return $this->recipient;
        } elseif ($userId == $this->recipient_id) {
            return $this->author;
        }

    }

    /**
     * Mark messages as readed
     * @param $userId
     * @param $contactId
     */
    public static function markRead($userId, $contactId)
    {

        self::updateAll(['unread' => false], [
            'and',
            ['recipient_id' => $userId],
            ['author_id' => $contactId],
        ]);

    }

}