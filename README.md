# Yii2 Messenger

Private messages module for yii2

## Installing

The preferred way to install this extension is through Composer.

```json
{
  "require": {
    "nanson/yii2-messenger": "*"
  }
}
```

Migration

```
./yii migrate --migrationPath=@vendor/nanson/yii2-messenger/migrations/
```

Configure module:

```
    'modules' => [
        'messenger' => [
            'class' => \nanson\messenger\Messenger::className(),
        ],
    ],
```

## Usage

Url for contacts list: `/messenger/contacts/`.

Url for messages list: `/messenger/contacts/messages/?id={contact_id}`

### Contacts widget

Display contacts list for user with last message.

```php
<?php
echo \nanson\messenger\widgets\Contacts::widget();
?>
```

| Option                | Type      | Description |
|-----------------------|-----------|-------------|
| userId                | integer   | User ID. _Default:_ `\Yii::$app->user->id ` |
| tpl                   | string    | Widget template. _Default:_ `create` |
| options               | array     | The HTML attributes for the widget wrapper tag. _Default:_ `[]` |
| pageSize              | integer   | Contacts per page. _Default:_ `10` |
| defaultOrder          | array     | Default contacts order _Default:_ `['last_message_id' => SORT_DESC]` |
| viewRoute             | string    | Route to messages. _Default:_ `/messenger/contacts/messages` |
| dataProviderOptions   | array     | Options for `yii\data\ActiveDataProvider`. _Default:_ `[]` |
| queryModifier         | callable  | Function to modify ActiveDataProvider query. _Default:_ `null` |
| skinAsset             | string    | Skin Asset Bundles class. _Default:_ `null` |

### Messages widget

Display user messages with current contact

```php
<?php
echo \nanson\messenger\widgets\Messages::widget([
    'contactId' => $contactId,
]);
?>
```

| Option                | Type      | Description |
|-----------------------|-----------|-------------|
| contactId             | integer   | Contact ID. |
| userId                | integer   | User ID. _Default:_ `\Yii::$app->user->id ` |
| tpl                   | string    | Widget template. _Default:_ `messages` |
| options               | array     | The HTML attributes for the widget wrapper tag. _Default:_ `[]` |
| pageSize              | integer   | Messages per page. _Default:_ `10` |
| defaultOrder          | array     | Default contacts order _Default:_ `['created_at' => SORT_DESC]` |
| dataProviderOptions   | array     | Options for `yii\data\ActiveDataProvider`. _Default:_ `[]` |
| queryModifier         | callable  | Function to modify ActiveDataProvider query. _Default:_ `null` |
| skinAsset             | string    | Skin Asset Bundles class. _Default:_ `null` |

### Add message widget

Display form to message creation.

```php
echo \nanson\messenger\widgets\AddMessage::widget([
	'contactId' => $contactId,
	'pjaxId' => "pjaxMessages",
]);
```

| Option        | Type      | Description |
|---------------|-----------|-------------|
| contactId     | integer   | Contact ID. |
| userId        | integer   | User ID. _Default:_ `\Yii::$app->user->id ` |
| tpl           | string    | Widget template. _Default:_ `create` |
| options       | array     | The HTML attributes for the widget wrapper tag. _Default:_ `[]` |
| formOptions   | array     | The HTML attributes for the widget form. _Default:_ `[]` |
| route         | string    | Route to create message action. _Default:_ `/messenger/rest/create`
| skinAsset     | string    | Skin Asset Bundles class. _Default:_ `null` |
| pjaxId        | string    | Pjax widget Id. If specified, pjax will be reloaded after message creation. _Default:_ `null` |
| fancySelector | string    | Fancybox selector. If specified, widget will be rendered as Fancybox. https://github.com/newerton/yii2-fancybox _Default:_ `null` |
| fancyOptions  | array     | Fancybox widget options. _Default:_ `null` |

### Messages counter widget

Display count unreaded messages for user.

```php
echo \nanson\messenger\widgets\Counter::widget();
```

| Option    | Type      | Description |
|-----------|-----------|-------------|
| route     | string    | Route to action. _Default:_ `/messenger/rest/count` |
| timeout   | integer   | Update timout. _Default:_ `30` |
| tag       | string    | Counter html tag. _Default:_ `span` |
| options   | array     | The HTML attributes for the counter tag. _Default:_ `['class' => 'badge']` |