<?php

namespace Miaoxing\Coupon\Controller\cli;

class Coupons extends \miaoxing\plugin\BaseController
{
    public function statAction($req)
    {
        // 1. 获取统计的时间范围
        $today = date('Y-m-d');
        list($startDate, $endDate) = explode('~', (string)$req['date']);
        if (!$startDate) {
            $startDate = $today;
        }
        if (!$endDate) {
            $endDate = $startDate;
        }

        $stat = wei()->stat;

        // 2. 读取优惠券各天统计数据,按日期,卡券编号和操作类型分组
        $logs = $stat->createQuery('couponLog', $startDate, $endDate);
        $logs = $logs->fetchAll();

        // 3. 按日期,优惠券编号格式化
        $data = $stat->format('couponLog', $logs);

        // 4. 记录到统计表中
        $stat->save('couponLog', $data, 'couponStats');

        return $this->suc();
    }
}
