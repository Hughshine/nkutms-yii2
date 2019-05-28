<?php
//定义组织者的分类常量
define('ORG_CATEGORY',
    [
        0=>'学生组织',1=>'社团组织',2=>'学校组织',3=>'其他'
    ]);
//定义用户的分类常量
define('USER_CATEGORY',
    [
        0=>'普通用户',1=>'学生',2=>'教职工',3=>'其他'
    ]);
//定义活动的分类常量
define('ACT_CATEGORY',
    [
        0=>'ACT_0',1=>'ACT_1',2=>'ACT_2',3=>'ACT_3'
    ]);

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
];
