<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;

class Coupons extends BaseController
{
    public function indexAction($req)
    {
        $coupons = wei()->couponModel()->enabled()->findAll();
        $data = [];

        $curTime = time();
        foreach ($coupons as $i => $coupon) {
            $beforeStartTime = $coupon->startedAt && strtotime($coupon->startedAt) > $curTime;
            $afterEndTime = $coupon->endedAt && strtotime($coupon->endedAt) <= $curTime;
            $overLimit = $coupon['getLimit']
                && wei()->userCouponModel->getUserCouponCount(wei()->curUser, $coupon) >= $coupon['getLimit'];
            $lowQuantity = $coupon['quantity'] <= 0;
            if ($beforeStartTime || $afterEndTime || $overLimit || $lowQuantity) {
                $data[] = $coupon->toArray() + [
                        'canGet' => false,
                    ];
            } else {
                $data[] = $coupon->toArray() + [
                        'canGet' => true,
                    ];
            }
        }
        $coupons = $data;

        $headerTitle = '优惠券';

        return get_defined_vars();
    }

    public function showAction($req)
    {
        $coupon = wei()->couponModel()->findOneById($req['id']);
        $canGet = true;

        if (!$coupon->enable) {
            $canGet = false;
        }

        if ($coupon->startedAt && strtotime($coupon->startedAt) > time()) {
            $canGet = false;
        }

        if ($coupon->endedAt && strtotime($coupon->endedAt) < time()) {
            $canGet = false;
        }

        if ($coupon->quantity <= 0) {
            $canGet = false;
        }

        if ($coupon->getLimit
            && wei()->userCouponModel->getUserCouponCount(wei()->curUser, $coupon) >= $coupon->getLimit
        ) {
            $canGet = false;
        }

        return get_defined_vars();
    }

    public function getCouponAction($req)
    {
        $ret = wei()->couponModel->sendCoupon($req['id'], wei()->curUser['id']);
        if ($ret['code'] == 1) {
            $ret['message'] = '领取成功';
        }

        return $this->ret($ret);
    }

    public function getAllCouponAction($req)
    {
        $coupons = wei()->couponModel()->enabled()->findAll();
        $isGet = false;

        foreach ($coupons as $i => $coupon) {
            $afterStartTime = !$coupon->startedAt || strtotime($coupon->startedAt) <= time();
            $beforeEndTime = !$coupon->endedAt || strtotime($coupon->endedAt) > time();
            $inLimit = !$coupon->getLimit
                || wei()->userCouponModel->getUserCouponCount(wei()->curUser, $coupon) < $coupon->getLimit;
            $muchQuantity = $coupon->quantity > 0;
            if ($afterStartTime && $beforeEndTime && $inLimit && $muchQuantity) {
                $ret = wei()->couponModel->sendCoupon($coupon->id, wei()->curUser['id']);
                if ($ret['code'] !== 1) {
                    return $ret;
                }

                $isGet = true;
            }
        }

        if (!$isGet) {
            return $this->err('已经没有可领取的优惠券了');
        }

        return $this->ret($ret);
    }
}
