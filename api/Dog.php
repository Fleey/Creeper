<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/22
 * Time: 16:34
 */

class DogApi
{

    private $username;
    private $password;
    private $domain;
    private $cookie;
    private $uid;

    public function __construct($username, $password, $domain)
    {
        $this->username = $username;
        $this->password = $password;
        $this->domain   = $domain;
        if (empty($this->cookie))
            $this->cookie = $this->getCookie();
        if (empty($this->uid))
            $this->uid = $this->getUID();
    }

    public function convertContent($str, $isAi = false, $isAutoTypeset = false)
    {
        $url    = $this->domain . '/dog.php';
        $cookie = '';
        foreach ($this->cookie as $value) {
            $cookie .= $value . ';';
        }
        $cookie = substr($cookie, 0, strlen($cookie) - 1);
        $result = curl($url, 'post', [
            'contents'     => $str,
            'xfm_uid'      => $this->uid,
            'bd_ai'        => $isAi ? 'on' : 'no',
            'auto_typeset' => $isAutoTypeset ? 'on' : 'no',
            'agreement'    => 'on'
        ], '', ['Cookie:' . $cookie]);
        preg_match_all('/<textarea[^>].*?>([\s\S]*?)<\/textarea>/',$result,$str);
        return $str[1][1];
    }

    private function getUID()
    {
        $url    = $this->domain;
        $cookie = '';
        foreach ($this->cookie as $value) {
            $cookie .= $value . ';';
        }
        $cookie = substr($cookie, 0, strlen($cookie) - 1);
        $result = curl($url, 'get', '', '', [
            'Cookie:' . $cookie
        ]);
        preg_match('/name=\'xfm_uid\'.+value=\'(.+)\'/isU', $result, $str);
        return $str[1];
    }


    private function getCookie()
    {
        $url    = $this->domain . '/index.php?action=login';
        $result = curl($url, 'post', [
            'username' => $this->username,
            'password' => $this->password,
            'submit'   => '登陆'
        ], '', [], true);
        return $result;
    }
}