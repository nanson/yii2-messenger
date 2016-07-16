<?php

namespace nanson\messenger\db;

use nanson\messenger\components\MessengerTrait;
use nanson\messenger\Messenger;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class MessageQuery
 * @property-read string tableName table name of AR model
 * @package nanson\messenger\db
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class MessageQuery extends ActiveQuery
{
    use MessengerTrait;

    /**
     * Selection unread messages
     * @param bool|false $state
     * @return $this
     */
    public function unread($state = true)
    {
        $this->andWhere(["{{%$this->tableName}}.{{%unread}}" => $state]);

        return $this;
    }

    /**
     * Selection by user id
     * @param null $userId
     * @return $this
     */
    public function byUser($userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        $this->andWhere([
            'or',
            ["{{%$this->tableName}}.{{%author_id}}" => $userId],
            ["{{%$this->tableName}}.{{%recipient_id}}" => $userId]
        ]);

        return $this;
    }

    /**
     * Returns user messages for contact
     * @param $contactId
     * @param null $userId
     * @return $this
     */
    public function byContact($contactId, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        $this->byUser($contactId);
        $this->byUser($userId);

        return $this;
    }

    /**
     * Selection bu author id
     * @param null $userId
     * @return $this
     */
    public function byAuthor($userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        $this->andWhere(["{{%$this->tableName}}.{{%author_id}}" => $userId]);

        return $this;
    }

    /**
     * Selection by recipient id
     * @param null $userId
     * @return $this
     */
    public function byRecipient($userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        $this->andWhere(["{{%$this->tableName}}.{{%recipient_id}}" => $userId]);

        return $this;
    }

    /**
     * Selection latest messages
     * @return $this
     */
    public function latest()
    {
        $class = $this->modelClass;

        $msgIds = $query = $class::find()
            ->select(["max({{%$this->tableName}}.{{%id}})"])
            ->withContact()
            ->groupBy("{{%$this->userTableName}}.{{%id}}");

        $this->andWhere(["{{%$this->tableName}}.{{%id}}" => $msgIds]);

        return $this;
    }

    /**
     * Join contact to user messages
     * @return $this
     */
    public function withContact()
    {
        $this->leftJoin("{{%$this->userTableName}}", [
            'or',
            "{{%$this->tableName}}.{{%author_id}} = {{%$this->userTableName}}.{{%id}}",
            "{{%$this->tableName}}.{{%recipient_id}} = {{%$this->userTableName}}.{{%id}}"
        ]);

        $this->byUser();
        $this->andOnCondition(["!=", "{{%$this->userTableName}}.{{%id}}", Yii::$app->user->id]);

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

}