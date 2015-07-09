<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-widgets
 * @subpackage yii2-widget-depdrop
 * @version 1.0.0
 */

namespace backend\modules\timetable\widgets;

use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use kartik\base\Config;
use kartik\select2\Select2;
use kartik\depdrop\DepDropAsset;

/**
 * 
 */
class DepDrop extends \kartik\base\InputWidget
{
    const TYPE_DEFAULT = 1;
    const TYPE_SELECT2 = 2;
    
    public $typename = 'dropdownList';

    /**
     * @var int the type of the dropdown element.
     * - 1 or [[DepDrop::TYPE_DEFAULT]] will render using \yii\helpers\Html::dropDownList
     * - 2 or [[DepDrop::TYPE_SELECT2]] will render using \kartik\widgets\Select2 widget
     */
    public $type = self::TYPE_DEFAULT;

    /**
     * @var array the configuration options for the Select2 widget. Applicable
     * only if the `type` property is set to [[DepDrop::TYPE_SELECT2]].
     */
    public $select2Options = [];

    /**
     * @var \yii\web\View instance
     */
    private $_view;

    /**
     * @inherit doc
     * @throw InvalidConfigException
     */
    public function init()
    {
        if (empty($this->pluginOptions['url'])) {
            throw new InvalidConfigException("The 'pluginOptions[\"url\"]' property has not been set.");
        }
        if (empty($this->pluginOptions['depends']) || !is_array($this->pluginOptions['depends'])) {
            throw new InvalidConfigException("The 'pluginOptions[\"depends\"]' property must be set and must be an array of dependent dropdown element ID.");
        }
        if (empty($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        if ($this->type === self::TYPE_SELECT2) {
            Config::checkDependency('select2\Select2', 'yii2-widget-select2', 'for dependent dropdown for TYPE_SELECT2');
        }
        parent::init();
        if ($this->type !== self::TYPE_SELECT2 && !empty($this->options['placeholder'])) {
            $this->data = ['' => $this->options['placeholder']] + $this->data;
        }
        if ($this->type === self::TYPE_SELECT2 &&
            (!empty($this->options['placeholder']) || !empty($this->select2Options['options']['placeholder']))
        ) {
            $this->pluginOptions['placeholder'] = '';
        } elseif ($this->type === self::TYPE_SELECT2 && !empty($this->pluginOptions['placeholder']) && $this->pluginOptions['placeholder'] !== false) {
            $this->options['placeholder'] = $this->pluginOptions['placeholder'];
            $this->pluginOptions['placeholder'] = '';
        }
        $this->_view = $this->getView();
        $this->registerAssets();
        if ($this->type === self::TYPE_SELECT2) {
            if (empty($this->data)) {
                $this->data = ['' => ''];
            }
            if ($this->hasModel()) {
                $settings = ArrayHelper::merge($this->select2Options, [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'data' => $this->data,
                    'options' => $this->options
                ]);
            } else {
                $settings = ArrayHelper::merge($this->select2Options, [
                    'name' => $this->name,
                    'value' => $this->value,
                    'data' => $this->data,
                    'options' => $this->options
                ]);
            }
            echo Select2::widget($settings);

            $id = 'jQuery("#' . $this->options['id'] . '")';
            $text = ArrayHelper::getValue($this->pluginOptions, 'loadingText', 'Loading ...');
            $this->_view->registerJs("{$id}.on('depdrop.beforeChange',function(e,i,v){{$id}.select2('data',{text: '{$text}'});});");
            $this->_view->registerJs("{$id}.on('depdrop.change',function(e,i,v,c){{$id}.select2('val',{$id}.val());});");
        } else {
            echo $this->getInput(!empty($this->typename)? $this->typename:'dropdownList', true);
        }
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        DepDropAsset::register($this->_view);
        $this->registerPlugin('depdrop');
    }

}