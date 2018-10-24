<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/22
 * Time: 11:04
 */

/**
 * @param string $url
 * @param string $requestType
 * @param string $data
 * @param string $postType
 * @param array $addHeaders
 * @param bool $isGetCookie
 * @return mixed|string
 */
function curl($url = '', $requestType = 'get', $data = '', $postType = '', $addHeaders = [], $isGetCookie = false)
{
    if (empty($url)) {
        return '';
    }
    //容错处理
    $Headers = array(
        'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
        'Referer:' . $url
    );

    if (strtolower($postType) == 'json' && $requestType != 'get') {
        $Headers[] = 'Content-Type: application/json; charset=utf-8';
        $data      = is_array($data) ? json_encode($data) : $data;
    }

    if (!empty($addHeaders)) {
        $Headers = array_merge($Headers, $addHeaders);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, !$isGetCookie);
    //设置允许302转跳
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //add ssl
    if ($requestType == 'get') {
        curl_setopt($ch, CURLOPT_HEADER, false);
    } else if ($requestType == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($requestType));
    }
    //处理类型
    if ($requestType != 'get') {
        if (is_array($data)) {
            $temp = '';
            foreach ($data as $key => $value) {
                $temp .= urlencode($key) . '=' . urlencode($value) . '&';
            }
            $data = substr($temp, 0, strlen($temp) - 1);
        }
        $Headers = array_merge($Headers, ['Content-Length:' . strlen($data)]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //只要不是get姿势都塞东西给他post
    curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);

    if ($isGetCookie)
        curl_setopt($ch, CURLOPT_HEADER, true);
    //get header
    $result = curl_exec($ch);

    curl_close($ch);

    if ($isGetCookie) {
        preg_match_all('/Set-Cookie:(.*)\r\n/isU', $result, $str);
        if (count($str[1]) % 2 != 0 || empty($str[1])) {
            return $result;
        } else {
            $forCount = count($str[1]) / 2;
            $temp     = [];
            for ($i = 0; $i < $forCount; $i++) {
                $temp[] = $str[1][$i * 2 + 1];
            }
            return $temp;
        }
    }

    return $result;
}

/**
 * @param $config
 * @return Mysqli
 */
function getMysqli($config)
{
    $databaseConfig = $config['database'];
    $mysqli         = new Mysqli($databaseConfig['host'], $databaseConfig['username'], $databaseConfig['password'], $databaseConfig['dbName'], $databaseConfig['port']);
    if ($mysqli->connect_errno)
        exit('[DANGER] mysql connect error tips => ' . $mysqli->connect_error);
    $mysqli->set_charset('utf-8');
    return $mysqli;
}

/**
 * @param $mysqli Mysqli
 * @param $title
 * @return boolean
 */
function selectTitleExits($mysqli, $title)
{
    $hash   = hash('sha256', $title);
    $result = $mysqli->query('select id from newstable where titleHash = "' . $hash . '" limit 1;');
    return $result->fetch_row() != 0;
}

/**
 * @param $mysqli Mysqli
 * @param $title
 * @return boolean
 */
function insertTitle($mysqli, $title)
{
    $hash   = hash('sha256', $title);
    $result = $mysqli->query('insert into newstable(titleHash) values("' . $hash . '")');
    return $result === true;
}