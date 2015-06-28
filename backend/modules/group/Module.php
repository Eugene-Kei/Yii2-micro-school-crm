<?php

namespace backend\modules\group;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\group\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Font-Awesome icon css class
     * @return string
     */
    public static function getIcon() {
        return 'fa-users';
    }

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     *
     * The translation will be conducted according to the message category and the target language will be used.
     *
     * You can add parameters to a translation message that will be substituted with the corresponding value after
     * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
     *
     * ```php
     * $username = 'Alexander';
     * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     *
     * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
     * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        $path = str_replace('\\', '/', __NAMESPACE__);
        // Add module I18N category.
        if (!isset(Yii::$app->i18n->translations[$category])) {
            Yii::$app->i18n->translations[$category] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@' . $path . '/messages',
                'fileMap' => [
                    $path => $category . '.php',
                ]
            ];
        }

        return Yii::t($category, $message, $params, $language);
    }
}
