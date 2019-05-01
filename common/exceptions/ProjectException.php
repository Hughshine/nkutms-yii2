<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/4/30
 * Time: 10:54
 */

namespace common\exceptions;

/**
 * Class ProjectException:
 * 这个项目所用到的所有异常的父类
 * @package common\exceptions
 * @property ProjectException $preException
 */
class ProjectException extends \Exception
{
    //由于不清楚php中异常链的处理,我用$preException来模拟一个异常链
    public $preException=null;

    /**
     * 获取异常链的所有信息
     * @return string
     */
    public function getExceptionMsg()
    {
        $res=parent::getMessage().'\n';
        if($this->preException!=null)
            $res=$res.$this->preException->getExceptionMsg().'\n';
        return $res;
    }
}





