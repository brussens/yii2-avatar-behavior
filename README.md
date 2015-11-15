#Yii2 avatar behavior
Behavior for the transparent operations of avatar thumbnails

[![Latest Stable Version](https://poser.pugx.org/brussens/yii2-avatar-behavior/v/stable)](https://packagist.org/packages/brussens/yii2-avatar-behavior)
[![Total Downloads](https://poser.pugx.org/brussens/yii2-avatar-behavior/downloads)](https://packagist.org/packages/brussens/yii2-avatar-behavior)
[![License](https://poser.pugx.org/brussens/yii2-avatar-behavior/license)](https://packagist.org/packages/brussens/yii2-avatar-behavior)

##Install
Either run
```
php composer.phar require --prefer-dist brussens/yii2-avatar-behavior "*"
```

or add

```
"brussens/yii2-avatar-behavior": "*"
```

to the require section of your `composer.json` file.

##Model configurations
Add a new attribute to the user's model, such as "userpic"

Add to your user model:
```php
namespace common\models;

use yii\db\ActiveRecord;
use brussens\behaviors\AvatarBehavior;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            'avatarBehavior' => [
                'class' => AvatarBehavior::className(),
                'attribute' => 'userpic'
            ]
        ];
    }
}
```

## Use
```php
//Returns user avatar as Html::img()
echo Yii::$app->getUser()->getIdentity()->getThumb(30, 30, [
    'class' => 'img-thumbnail'
]);

//Returns user avatar url
echo Html::img(Yii::$app->getUser()->getIdentity()->getThumbUrl(30, 30));

//Some user
$user = User::findOne(1);
echo $user->getThumb(20, 20);
```
