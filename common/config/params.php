<?php
//定义组织者的分类常量
define('ORG_CATEGORY',
    [
        0=>'校级组织',1=>'院系组织',2=>'学生组织',3=>'其他'
    ]);
//定义用户的分类常量
define('USER_CATEGORY',
    [
        0=>'普通用户',1=>'学生',2=>'教职工',3=>'其他'
    ]);
//定义活动的分类常量
define('ACT_CATEGORY',
    [
        0=>'校级活动',1=>'院系活动',2=>'学生组织活动',3=>'其他'
    ]);

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
];
