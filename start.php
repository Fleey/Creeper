<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/22
 * Time: 9:38
 */


include_once './common.php';
include_once './api/Dog.php';
include_once './api/Spider.php';
//load function
$config = include_once './config.php';
//load config

init($config);
start($config);

function init($config)
{
    printf('[INFO] 正在初始化程序...' . PHP_EOL);
    ini_set('max_execution_time', '0');
    //set page time out time;
    foreach ($config['sites'] as $key => $value) {
        if (!file_exists('./article/' . $value['savePath'])) {
            mkdir('./article/' . $value['savePath'], 0777, true);
        }
    }
    //遍历创建所需文件夹
    $mysqli = getMysqli($config);
    foreach ($config['table'] as $key => $value) {
        $mysqli->query($value);
    }
    //遍历创建所需数据表
    $mysqli->close();
    printf('[INFO] 初始化结束...' . PHP_EOL);
}

//初始化数据

function start($config)
{
    printf('[INFO] 正在开始爬取新闻文章...' . PHP_EOL);
    $mysqli                   = getMysqli($config);
    $dogApi                   = new DogApi($config['dogApi']['username'], $config['dogApi']['password'], $config['dogApi']['domain']);
    $footerAppendContent      = explode('|', $config['footerAppend']);
    $footerAppendContentCount = count($footerAppendContent) - 1;
    $successCount             = 0;
    foreach ($config['sites'] as $value) {
        $articleList = SpiderApi::getNewsList($value['url'], $value['index']['content'], $value['index']['url']);
        foreach ($articleList as $value1) {
            $articleInfo = SpiderApi::getNewsContent($value1, $value['article']['title'], $value['article']['body']);
            if (empty($articleInfo))
                continue;
            $title   = $articleInfo[0];
            $content = $articleInfo[1];
            if (selectTitleExits($mysqli, $title))
                continue;
            //文章重复
            insertTitle($mysqli, $title);
            //插入标题避免重复
            $title   = $dogApi->convertContent($title, true, true);
            $content = $dogApi->convertContent($content, true, true);
            //convert data
            $path = './article/' . $value['savePath'] . '/' . uniqid() . '.html';
            $html = '<html><head><title>' . $title . '</title></head>标题:<h1>' . $title . '</h1><br><p>内容：' . $content . '</p>' . $footerAppendContent[rand(0, $footerAppendContentCount)] . '</html>';
            file_put_contents($path, $html);
            //save file
            $indexHtml = '<a href="./' . substr($path, 10, strlen($path)) . '">' . $title . '</a>' . PHP_EOL;
            file_put_contents('./article/index.html', $indexHtml, FILE_APPEND);
            printf('[INFO] 成功抓取第' . $successCount . '次' . PHP_EOL);
        }
    }
    //save append index html
    $mysqli->close();
    printf('[INFO] 结束本次爬取任务，等待下次程序唤醒...' . PHP_EOL);
}

