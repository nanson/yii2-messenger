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
 * @package nanson\messenger\models
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class Contact extends ActiveRecord
{
	use MessengerTrait;

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