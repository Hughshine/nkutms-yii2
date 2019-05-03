<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/4/30
 * Time: 10:54
 */

namespace common\exceptions;

class ValidateException extends ProjectException
{
    public $errors=null;//用于记录save或者validate后的error数组

    /**
     * 重写父类的获取信息方法
     * @return string
     */
    public function getExceptionMsg()
    {
        $res=$this->getMessage();
        $res=$res.'ValidateErrors:'.$this->getErrors();
        if($this->preException!=null)
            $res=$res.'<='.$this->preException->getExceptionMsg().';';
        return $res;
    }

    /**
     * 返回errors里的内容
     * @return string
     */
    public function getErrors()
    {
        if($this->errors==null)return '';
        $res='';
        foreach ( $this->errors as $k=>$each)
            foreach ($each as $v)
                $res=$res.$k.'=>'.$v.';';
        return $res;
    }
}