<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/5/12
 * Time: 0:51
 */

namespace Royal\Util;


class Time
{
    private function getThisDayFlow($port)
    {
        //获取当前开始的时刻和最后的时刻
        $startTime = date('Y-m-d 00:00:00', time());
        $endTime = date('Y-m-d 23:59:59', time());
        return array('start'=>$startTime,'end'=>$endTime);
    }

    private function getThisWeekFlow($port)
    {
        //获取一周的第一天和最后一天
        $date = new \DateTime();
        $date->modify('this week');
        $first_day_of_week = $date->format('Y-m-d 00:00:00');
        $date->modify('this week +6 days');
        $end_day_of_week = $date->format('Y-m-d 23:59:59');
        return array('start'=>$first_day_of_week,'end'=>$end_day_of_week);

    }

    private function getThisMonthFlow($port)
    {
        //获取当月第一天和最后一天
        $first_date = date('Y-m-01 00:00:00', strtotime(date("Y-m-d")));
        $last_date = date('Y-m-d 23:59:59', strtotime("$first_date +1 month -1 day"));
        return array('start'=>$first_date,'end'=>$last_date);

    }
}