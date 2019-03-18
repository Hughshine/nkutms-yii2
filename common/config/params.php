<?php
//定义组织者的分类常量
define('ORG_CATEGORY',
    [
        0=>'ORG_0',1=>'ORG_1',2=>'ORG_2',3=>'ORG_3'
    ]);
//定义用户的分类常量
define('USER_CATEGORY',
    [
        0=>'USER_0',1=>'USER_1',2=>'USER_2',3=>'USER_3'
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
