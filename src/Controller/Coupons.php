<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;

class Coupons extends BaseController
{
    protected $guestPages = ['coupon/list'];

    public function myCouponAction()
    {
        $used = $this->request('used', 'no');
        $couponList = [];
        if ($used == 'no') {
            $couponList = wei()->couponModel->getNotUseCoupon($this->curUser['id']);
        } elseif ($used == 'yes') {
            $couponList = wei()->couponModel->getUsedCoupon($this->curUser['id']);
        }

        $headerTitle = '优惠券';

        return get_defined_vars();
    }

    public function indexAction($req)
    {
        $coupons = wei()->couponModel()->notDeleted()->enabled()->findAll();
        $data = [];

        foreach ($coupons as $i => $coupon) {
            $curTime = time();
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
        $coupons = wei()->couponModel()->notDeleted()->enabled()->findAll();
        $isGet = false;

        foreach ($coupons as $i => $coupon) {
            $afterStartTime = !$coupon['startTime'] || strtotime($coupon['startTime']) <= time();
            $beforeEndTime = !$coupon['endTime'] || strtotime($coupon['endTime']) > time();
            $inLimit = !$coupon['getLimit']
                || wei()->userCouponModel->getUserCouponCount(wei()->curUser, $coupon) < $coupon['getLimit'];
            $muchQuantity = $coupon['quantity'] > 0;
            if ($afterStartTime && $beforeEndTime && $inLimit && $muchQuantity) {
                $ret = wei()->couponModel->sendCoupon($coupon['id'], wei()->curUser['id']);
                if ($ret['code'] != 1) {
                    return $this->ret($ret);
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
