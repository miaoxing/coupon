<?php

namespace MiaoxingDoc\Coupon {

    /**
     * @property    \Miaoxing\Coupon\Service\CouponLog $couponLog
     * @method      \Miaoxing\Coupon\Service\CouponLog|\Miaoxing\Coupon\Service\CouponLog[] couponLog()
     *
     * @property    \Miaoxing\Coupon\Service\CouponModel $couponModel
     * @method      \Miaoxing\Coupon\Service\CouponModel|\Miaoxing\Coupon\Service\CouponModel[] couponModel()
     *
     * @property    \Miaoxing\Coupon\Service\CouponStat $couponStat
     * @method      \Miaoxing\Coupon\Service\CouponStat|\Miaoxing\Coupon\Service\CouponStat[] couponStat()
     *
     * @property    \Miaoxing\Coupon\Service\UserCouponModel $userCouponModel
     * @method      \Miaoxing\Coupon\Service\UserCouponModel|\Miaoxing\Coupon\Service\UserCouponModel[] userCouponModel()
     */
    class AutoComplete
    {
    }
}

namespace {

    /**
     * @return MiaoxingDoc\Coupon\AutoComplete
     */
    function wei()
    {
    }

    /** @var Miaoxing\Coupon\Service\CouponLog $couponLog */
    $couponLog = wei()->couponLog;

    /** @var Miaoxing\Coupon\Service\CouponModel $couponModel */
    $coupon = wei()->couponModel();

    /** @var Miaoxing\Coupon\Service\CouponModel|Miaoxing\Coupon\Service\CouponModel[] $couponModels */
    $coupons = wei()->couponModel();

    /** @var Miaoxing\Coupon\Service\CouponStat $couponStat */
    $couponStat = wei()->couponStat;

    /** @var Miaoxing\Coupon\Service\UserCouponModel $userCouponModel */
    $userCoupon = wei()->userCouponModel();

    /** @var Miaoxing\Coupon\Service\UserCouponModel|Miaoxing\Coupon\Service\UserCouponModel[] $userCouponModels */
    $userCoupons = wei()->userCouponModel();
}
