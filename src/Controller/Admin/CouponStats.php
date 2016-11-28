<?php

namespace Miaoxing\Coupon\Controller\admin;

use DateTime;

class CouponStats extends \miaoxing\plugin\BaseController
{
    protected $controllerName = '优惠券统计';

    protected $actionPermissions = [
        'show' => '查看',
    ];

    public function showAction($req)
    {
        $coupon = wei()->coupon()->findOneById($req['couponId']);

        // 获取查询的日期范围
        $startDate = $req['startDate'] ?: date('Y-m-d', strtotime('-7 days'));
        $endDate = $req['endDate'] ?: date('Y-m-d');

        // 获取最后更新时间
        $lastUpdateTime = wei()->couponStat()->select('updateTime')->desc('id')->fetchColumn();

        switch ($req['_format']) {
            case 'json':
                // 1. 读出统计数据
                $stats = wei()->couponStat()
                    ->curApp()
                    ->andWhere(['couponId' => $coupon['id']])
                    ->andWhere('statDate BETWEEN ? AND ? ', [$startDate, $endDate])
                    ->fetchAll();

                // 2. 如果取出的数据和日期不一致,补上缺少的数据
                $date1 = new DateTime($startDate);
                $date2 = new DateTime($endDate);
                $dateCount = $date1->diff($date2)->days + 1;
                if (count($stats) != $dateCount) {
                    // 找到最后一个有数据的日期
                    $lastStat = wei()->couponStat()
                        ->curApp()
                        ->andWhere('statDate < ?', $startDate)
                        ->desc('id')
                        ->findOrInit(['couponId' => $coupon['id']])
                        ->toArray();

                    $defaults = wei()->couponStat->getData();

                    $stats = wei()->stat->normalize('couponLog', $stats, $defaults, $lastStat, $startDate, $endDate);
                }

                // 3. 转换为数字
                $stats = wei()->chart->convertNumbers($stats);

                return $this->suc([
                    'data' => $stats,
                ]);

            default:
                return get_defined_vars();
        }
    }
}
