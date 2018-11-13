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
}
