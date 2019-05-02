<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-right info">
                <p>欢迎回来:</p>
                <p><?=Yii::$app->user->identity->admin_name ?></p>

                <!--<p><i class="fa fa-circle text-success"></i> 在线</p>-->
            </div>
            <div class="pull-left image" style="height:35px;width:35px">
                <img src="/statics/images/admin.jpg" class="img-circle" alt="Admin Image"/>
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
                        'label' => '事务管理',
                        'icon' => 'server',
                        'url' => '#',
                        'items' => [
                            ['label' => '活动管理', 'icon' => 'grav', 'url' => ['activity/index']],
                            ['label' => '票务管理', 'icon' => 'ticket', 'url' => ['ticket/index']],
                            ['label' => '通知管理', 'icon' => 'info-circle', 'url' => ['notice/index']],
                        ],
                    ],
                    [
                        'label' => '用户管理',
                        'icon' => 'server',
                        'url' => '#',
                        'items' => [
                            ['label' => '普通用户管理', 'icon' => 'user-circle', 'url' => ['user/index']],
                            ['label' => '组织者管理', 'icon' => 'group', 'url' => ['organizer/index']],
                        ],
                    ],
                    ['label' => '修改密码', 'icon' => 'user-secret', 'url' => ['site/repassword'],],
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
