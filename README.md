<p align="center">
    <a target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">南开大学学生活动抢票系统(管理系统/面向微信小程序的api)</h1>
    <br>
</p>

# 部署方式

- clone 至 web 根目录

- composer update (建议使用镜像/代理)

- php init (linux/mac)

- 更改apache配置文件，开启并配置rewrite规则。（.htaccess）

> 自动部署项目待定

# 项目基本信息

## `Models`
| Basic Class   | Description              |
| ------------- | ------------------------ |
| User          | 抢票者                   |
| Organizer     | 发布者（不同的学生组织） |
| Admin         | 管理员                   |
| Activity      | 活动                     |
| Ticket        | 票                       |
| ActivityEvent | 活动事件                 |
| TicketEvent   | 票务事件                 |

> 数据库设计见db中sql文件

## `模块`

### 1. admin

- 管理员对活动/票务事件进行监控，对用户/组织者信息、活动信息进行修改

### 2. api

- 针对用户（抢票者）设计的api，开放对活动的查询搜索、抢票、退票、绑定/更改个人信息等接口

##### `Controllers`
```
ActivityController => baseurl/activities/...
    actions
        index 
        view 
        search 
        ticketing

TicketController => baseurl/tickets/...
    actions
        my-tickets
        search-by-id
        withdraw

UserController => baseurl/users/...
    actions
        wechat-login
        edit-profile
```

### 3. org-api

- 针对发布者（学生组织）设计的api，开放发布活动、查看参与人员等接口

##### `Controllers`
```
ActivityController => baseurl/activities/...
    actions
        index => GET
        view => GET
        search 
        my-activities
        my-participants
        add-activity
        edit-activity
        cancel-activity

OrganizerController => baseurl/tickets/...
    actions
        login
        edit-profile
        (signup)
```

> 注意事项：
> 1. wechat-login功能暂未检查请求来源，抢票者注册/登录功能不安全
> 2. ...







