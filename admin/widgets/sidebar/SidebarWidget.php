<?php
namespace admin\widgets\sidebar;

/**
 * 后台siderbar插件：左边的
 */
use Yii;
use yii\base\Widget;
use yii\widgets\Menu;

class SidebarWidget extends Menu
{    
    public $submenuTemplate = "\n<ul class=\"children\">\n{items}\n</ul>\n";
    
    public $options = ['class'=>'nav nav-pills nav-stacked nav-quirk'];
    
    public $activateParents = true;
    
    public function init()
    {
        $this->items = [
            //['label' =>'<i class="fa fa-dashboard"></i><span>仪表盘</span>','url'=>['site/index']],
            ['label' =>'<a href=""><i class="fa fa-th-list"></i><span>事务管理</span></a>','options'=>['class'=>'nav-parent'],'items'=>[
                    ['label'=>'活动管理','url'=>['activity/index'],],
                    ['label'=>'票务管理','url'=>['ticket/index'],],
                    ['label'=>'通知管理','url'=>['notice/index'],],
                ]
            ],
            ['label' =>'<a href=""><i class="fa fa-user"></i><span>用户管理</span></a>','options'=>['class'=>'nav-parent'],'items'=>[
                    ['label'=>'普通用户管理','url'=>['user/index']],
                    ['label'=>'组织者管理','url'=>['organizer/index']],
                ]
            ],

        ];
    }
}