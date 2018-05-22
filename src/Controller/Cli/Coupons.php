<?php

namespace Miaoxing\Coupon\Controller\cli;

use Miaoxing\Coupon\Service\CouponStatModel;

class Coupons extends \Miaoxing\Plugin\BaseController
{
    public function statAction($req)
    {
        // 1. 获取统计的时间范围
        $stat = wei()->statV2;
        $today = wei()->time->today();
        list($startDate, $endDate) = explode('~', (string) $req['date']);
        if (!$startDate) {
            $startDate = $today;
        }
        if (!$endDate) {
            $endDate = $startDate;
        }

        // 2. 读取各天统计数据
        $logs = $stat->createQuery('couponLogModel', $startDate, $endDate);
        $logs = $logs->fetchAll();

        // 3. 按日期,编号格式化
        $data = $stat->format('couponLogModel', $logs);

        // 4. 记录到统计表中
        $stat->save('couponLogModel', $data, 'couponStatModel');

        // 5. 更新到总表中
        foreach ($data as $row) {
            /** @var CouponStatModel $last */
            $last = wei()->couponStatModel()
                ->desc('stat_date')
                ->find(['coupon_id' => $row['coupon_id']]);
            $coupon = wei()->couponModel()->unscoped()->findById($row['coupon_id']);
            $coupon->save([
                'receiveCount' => $last->totalReceiveCount,
                'receiveUser' => $last->totalReceiveUser,
                'useCount' => $last->totalUseCount,
                'useUser' => $last->totalUseUser,
            ]);
        }

        return $this->suc();
    }
}
