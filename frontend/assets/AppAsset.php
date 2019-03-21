<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css =
        [
            'css/site.css',
            'css/font-awesome-4.4.0/css/font-awesome.min.css',
            'template/font-awesome/css/font-awesome.min.css',
            'template/css/bootstrap.min.css',
            'template/css/style.css',
        ];
    public $js =
        [
            //'template/js/bootstrap.min.js',
            //'template/js/jquery-2.1.1.js',//这个文件和验证码冲突
            //'template/js/photo-gallery.js',
        ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
