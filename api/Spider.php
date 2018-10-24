<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/22
 * Time: 16:35
 */

class SpiderApi
{
    public static function getNewsList($url, $patterContent, $patterUrl)
    {
        $result = curl($url);
        preg_match_all($patterContent, $result, $contentResult);
        $result = $contentResult[1];
        $temp   = [];
        foreach ($result as $value) {
            preg_match($patterUrl, $value, $urlResult);
            if (empty($urlResult))
                continue;

            $temp[] = $urlResult[1];
        }
        return $temp;
    }

    public static function getNewsContent($url,$patterTitle ,$patterContent)
    {
        $result = curl($url);
        preg_match($patterContent, $result, $str);
        preg_match($patterTitle, $result, $str1);
        if(empty($str[1]) || empty($str1[1])){
            return [];
        }
        return [self::removeDom($str1[1]),self::removeDom($str[1]) ];
    }

    private static function removeDom($str){
        return mb_convert_encoding(trim(strip_tags($str)),'utf-8','GBK,UTF-8,ASCII');
    }
}