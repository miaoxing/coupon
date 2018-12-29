<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;

class UserCoupons extends BaseController
{
    public function indexAction($req)
    {
        $userCoupons = wei()->userCouponModel()
            ->mine()
            ->andWhere(['used' => (bool) $req['used']])
            ->desc('id')
            ->findAll()
            ->load('coupon');

        $this->page->setTitle('优惠券');

        return get_defined_vars();
    }

    public function getByCartsAction($req)
    {
        $carts = wei()->cart()->findAllByIds($req['cartIds']);
        $userCoupons = wei()->couponModel->getAvailableCouponsByCarts($carts);

        $data = [];
        foreach ($userCoupons as $userCoupon) {
            $data[] = $userCoupon->toArray() + [
                    'name' => $userCoupon->getName(),
                    'reduceCost' => $userCoupon->getReduceCost(),
                ];
        }

        return $this->suc(['data' => $data]);
    }
}
