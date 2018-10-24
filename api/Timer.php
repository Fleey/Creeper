<?php
/**
 * Created by Fleey.
 * User: Fleey
 * Date: 2018/10/23
 * Time: 15:27
 */

class Timer
{
    public static $task = [];
    //记录缓存任务
    public static $time = 1;

    //定时间隔
    public static function run($time = null)
    {
        if ($time)
            self::$time = $time;

        self::installHandler();
        pcntl_alarm(1);
    }

    /**
     * 注册信号函数
     */
    public static function installHandler()
    {
        pcntl_signal(SIGALRM, ['Timer', 'signalHandler']);
    }

    /**
     *  信号函数
     */
    public static function signalHandler()
    {
        self::task();
        pcntl_alarm(self::$time);
        //一次信号事件执行完成后,再触发下一次
    }

    /**
     * 执行回调
     */
    public static function task()
    {
        if (empty(self::$task))
            return;

        foreach (self::$task as $time => $arr) {
            $current = time();
            foreach ($arr as $k => $job) {
                $func     = $job['func'];
                $argv     = $job['argv'];
                $interval = $job['interval'];
                $persist  = $job['persist'];
                if ($current == $time) {
                    call_user_func_array($func, $argv);
                    //调用回调函数并且置入参数
                    unset(self::$task[$time][$k]);
                    //删除该任务
                }
                //如果当前时间有执行任务
                if ($persist)
                    self::$task[$current + $interval][] = $job;
                //如果是持久化则下次唤醒
            }
            //遍历任务

            if (empty(self::$task[$time]))
                unset(self::$task[$time]);
        }
    }

    /**
     * 新增任务
     * @param $interval
     * @param $func
     * @param array $argv
     * @param bool $persist
     */
    public static function add($interval, $func, $argv = [], $persist = false)
    {
        if (is_null($interval))
            return;

        $time                = time() + $interval;
        self::$task[$time][] = [
            'func'     => $func,
            'argv'     => $argv,
            'interval' => $interval,
            'persist'  => $persist
        ];

    }

    /**
     *  删除所有函数
     */
    public function dellAll(){
        self::$task = [];
    }
}