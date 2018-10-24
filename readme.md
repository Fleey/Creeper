# Creeper

一个爬虫项目，程序由PHP编写

使用方法 直接CGI访问又可以，使用CLI也行

PHP CGI
```
http://你的地址：你的端口/start.php
```

PHP CLI
```php
php start.php
```

使用CLI模式体验更佳，CGI模式会页面长时间卡死的。。。

并且注意配置config.php

并且使用了小狗API进行同义词转换，从而使百度爬虫认为文章原创率高


推荐使用`PHP7`和 `Mysql` 如果需要使用到定时任务。。。记得Cli 和 Unix

#### 本来这个项目是打算用`pcntl`进行定时自动爬的，但是某人说还是方便点好些

> 配置文件解释 

    'sites'  => [
        [
            'url'      => 'http://games.sina.com.cn/',
            //网站地址
            'savePath' => './sina/',
            //保存目录地址 article/保存目录地址/
            'index'    => [
                'content' => '/(<a href="")/isU',
                //匹配列表文章容器
                'url'     => '/<a href="(.+)"/isU'
                //匹配文章链接
            ],
            'article'  => [
                'title' => '/#dedede;height:auto;">(.+)<\/h1>/isU',
                //匹配文章标题
                'body'  => '/<div id="artibody">([\s\S]+)<div class=\'mianze\'><b>/'
                //匹配文章内容
            ]
        ],  
        
