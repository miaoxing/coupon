<?php

namespace Miaoxing\Coupon\Controller\admin;

use DateTime;

class CouponStats extends \Miaoxing\Plugin\BaseController
{
    protected $controllerName = '优惠券统计';

    protected $actionPermissions = [
        'show' => '查看',
    ];

    public function showAction($req)
    {
        $coupon = wei()->couponModel()->findOneById($req['couponId']);

        // 获取查询的日期范围
        $startDate = $req['startDate'] ?: date('Y-m-d', strtotime('-7 days'));
        $endDate = $req['endDate'] ?: date('Y-m-d');

        // 获取最后更新时间
        $lastUpdateTime = wei()->couponStatModel()->unscoped()->select('updated_at')->desc('id')->fetchColumn();

        switch ($req['_format']) {
            case 'json':
                // 1. 读出统计数据
                $stats = wei()->couponStatModel()
                    ->unscoped()
                    ->andWhere(['coupon_id' => $coupon['id']])
                    ->andWhere('stat_date BETWEEN ? AND ? ', [$startDate, $endDate])
                    ->findAll()
                    ->toArray();

                // 2. 如果取出的数据和日期不一致,补上缺少的数据
                $date1 = new DateTime($startDate);
                $date2 = new DateTime($endDate);
                $dateCount = $date1->diff($date2)->days + 1;
                if (count($stats) != $dateCount) {
                    // 找到最后一个有数据的日期
                    $lastStat = wei()->couponStatModel()
                        ->unscoped()
                        ->andWhere('stat_date < ?', $startDate)
                        ->desc('id')
                        ->findOrInit(['coupon_id' => $coupon['id']])
                        ->toArray();

                    $defaults = $this->camelArray(wei()->couponStatModel->getData());

                    $stats = wei()->stat->normalize(
                        'couponLogModel',
                        $stats,
                        $defaults,
                        $lastStat,
                        $startDate,
                        $endDate
                    );
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

    protected function camelArray($input)
    {
        $output = [];
        $str = wei()->str;
        foreach ($input as $key => $value) {
            $output[$str->camel($key)] = $value;
        }
        return $output;
    }
}
