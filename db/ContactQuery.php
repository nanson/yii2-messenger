<?php

namespace nanson\messenger\db;

use yii\db\ActiveQuery;
use yii\db\Expression;
use nanson\messenger\models\Message;

/**
 * Class ContactQuery
 * @property-read string tableName contacts table name
 * @property-read string msgTableName messages table name
 * @package nanson\messenger\db
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class ContactQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $expr = new Expression('0');
        $this->select(["$this->tableName.*", 'last_message_id' => $expr]);

        parent::init();
    }

    /**
     * Selection contacts for user
     * @param null|integer $userId
     * @return $this
     */
    public function byUser($userId = null)
    {
        if (is_null($userId)) {
            $userId = \Yii::$app->user->id;
        }

        $this->withLastMessage();

        $this->andOnCondition([
            'or',
            ["$this->msgTableName.[[author_id]]" => $userId],
            ["$this->msgTableName.[[recipient_id]]" => $userId],
        ]);

        $this->andOnCondition(['!=', "$this->tableName.[[id]]", $userId]);

        return $this;
    }

    /**
     * Join last message
     * @return $this
     */
    public function withLastMessage()
    {

        $this->addSelect(['last_message_id' => "max($this->msgTableName.[[id]])"]);

        $this->innerJoin("$this->msgTableName", [
            'or',
            "$this->tableName.[[id]] = $this->msgTableName.[[author_id]]",
            "$this->tableName.[[id]] = $this->msgTableName.[[recipient_id]]"
        ]);

        $this->groupBy("$this->tableName.[[id]]");

        return $this;
    }

    /**
     * Returns table name of AR model
     * @return string
     */
    public function getTableName()
    {
        $class = $this->modelClass;
        return $class::tableName();
    }

    /**
     * Returns messages table name
     * @return string
     */
    public function getMsgTableName()
    {
        return Message::tableName();
    }

}