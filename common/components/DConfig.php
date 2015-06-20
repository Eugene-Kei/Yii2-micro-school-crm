<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Eugene
 */
namespace common\components;

use common\models\Config;
use yii\base\Component;
use yii\base\Exception;

class DConfig extends Component{
    protected $data = [];
 
    public function init()
    {
        
        $items = Config::find()->all();
//        $items = Config::findAll($condition);
        foreach ($items as $item){
            if ($item->param) 
                $this->data[$item->param] = $item->value === '' ?  $item->default : $item->value;
        }
    }
 
    public function get($key)
    {
        if (array_key_exists($key, $this->data)){
            return $this->data[$key];
        } else {
            throw new Exception('Undefined parameter '.$key);
        }
    }
 
    public function set($key, $value)
    {              
        $model = Config::findOne(['param'=>$key]);
        if (!$model) 
            throw new Exception('Undefined parameter '.$key);
 
        $this->data[$key] = $value;
        $model->value = $value;
        $model->save();
    }
}
