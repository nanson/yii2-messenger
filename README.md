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

Configure module:

```
    'modules' => [
        'messenger' => [
            'class' => \nanson\messenger\Messenger::className(),
        ],
    ],
```