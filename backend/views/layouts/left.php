<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left info">
                <p>欢迎回来:</p>
                <p><?=Yii::$app->user->identity->org_name ?></p>

                <!--<p><i class="fa fa-circle text-success"></i> 在线</p>-->
            </div>
            <div class="pull-right image" style="height:40px;width:40px">

                <!--<img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>-->
            </div>

        </div>

        <!-- search form -->
        <!--这里放搜索框<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '菜单', 'options' => ['class' => 'header']],
                    [
                        'label' => '活动',
                        'icon' => 'server',
                        'url' => '#',
                        'items' => [
                            ['label' => '活动列表', 'icon' => 'file-excel-o', 'url' => ['activity/index']],
                            [
                                'label' => '我的',
                                'icon' => 'user-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => '发布活动记录', 'icon' => 'file-excel-o', 'url' => ['activity/mine'],],
                                    ['label' => '发布一个活动', 'icon' => 'file-word-o', 'url' => ['activity/create'],]
                                    /*[
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],*/
                                ],
                            ],
                        ],
                    ],
                    ['label' => '我的资料', 'icon' => 'user', 'url' => ['site/view'],],
                    ['label' => '修改密码', 'icon' => 'user-secret', 'url' => ['site/password'],],
                    /*['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                ],
            ]
        ) ?>

    </section>

</aside>
