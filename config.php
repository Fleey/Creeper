<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/22
 * Time: 9:24
 */

return [
    'database' => [
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'port'     => '3306',
        'dbName'   => 'test2'
    ],

    'table' => [
        'CREATE TABLE IF NOT EXISTS `newstable`  (  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'文章主键\',  `titleHash` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT \'标题hash避免重复\',  PRIMARY KEY (`id`) USING BTREE,  INDEX `hash`(`titleHash`) USING BTREE) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;'
    ],

    'footerAppend' => 'test|huaji|6666',
    // | 分割字符  随机文章底部插入内容

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
        [
            'url'      => 'http://news.17173.com/',
            'savePath' => './17173/',
            'index'    => [
                'content' => '/<h2 class="tit">(<a href="http:\/\/news.17173.com\/content\/.+")/isU',
                'url'     => '/href="(http:\/\/news.17173.com\/content\/.+)"/isU',
            ],
            'article'  => [
                'title' => '/tit-article">(.+)</isU',
                'body'  => '/id="mod_article">([\s\S]+)<div align="right">/isU'
            ]
        ],
        [
            'url'      => 'https://new.qq.com/ch/games/',
            'savePath' => './qq/',
            'index'    => [
                'content' => '/href=\"(http:\/\/new.qq.com\/omn.+")/isU',
                'url'     => '/(.+)"/isU'
            ],
            'article'  => [
                'title' => '/<h1>(.+)<\/h1>/isU',
                'body'  => '/<div class="content clearfix">([\s\S]*)<div id="Comment">/isU'
            ]
        ],
        [
            'url'      => 'https://www.gamersky.com/news/pc/zx/',
            'savePath' => './gamersky/',
            'index'    => [
                'content' => '/href=\"(https:\/\/www.gamersky.com\/news\/[0-9]{6}.+")/isU',
                'url'     => '/(.+)"/isU'
            ],
            'article'  => [
                'title' => '/<h1>(.+)<\/h1>/isU',
                'body'  => '/<div class="Mid2L_con">([\s\S]*)结束-->/isU'
            ]
        ]
    ],
    'dogApi' => [
        'domain'   => 'http://www.7pyx.com/',
        'username' => 'username',
        'password' => 'password'
    ]

];