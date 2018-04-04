<?php

namespace MiaoxingDoc\Coupon {

    /**
     * @property    \Miaoxing\Coupon\Service\CouponLogModel $couponLogModel
     * @method      \Miaoxing\Coupon\Service\CouponLogModel|\Miaoxing\Coupon\Service\CouponLogModel[] couponLogModel()
     *
     * @property    \Miaoxing\Coupon\Service\CouponModel $couponModel
     * @method      \Miaoxing\Coupon\Service\CouponModel|\Miaoxing\Coupon\Service\CouponModel[] couponModel()
     *
     * @property    \Miaoxing\Coupon\Service\CouponStatModel $couponStatModel
     * @method      \Miaoxing\Coupon\Service\CouponStatModel|\Miaoxing\Coupon\Service\CouponStatModel[] couponStatModel()
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

    /** @var Miaoxing\Coupon\Service\CouponLogModel $couponLogModel */
    $couponLog = wei()->couponLogModel();

    /** @var Miaoxing\Coupon\Service\CouponLogModel|Miaoxing\Coupon\Service\CouponLogModel[] $couponLogModels */
    $couponLogs = wei()->couponLogModel();

    /** @var Miaoxing\Coupon\Service\CouponModel $couponModel */
    $coupon = wei()->couponModel();

    /** @var Miaoxing\Coupon\Service\CouponModel|Miaoxing\Coupon\Service\CouponModel[] $couponModels */
    $coupons = wei()->couponModel();

    /** @var Miaoxing\Coupon\Service\CouponStatModel $couponStatModel */
    $couponStat = wei()->couponStatModel();

    /** @var Miaoxing\Coupon\Service\CouponStatModel|Miaoxing\Coupon\Service\CouponStatModel[] $couponStatModels */
    $couponStats = wei()->couponStatModel();

    /** @var Miaoxing\Coupon\Service\UserCouponModel $userCouponModel */
    $userCoupon = wei()->userCouponModel();

    /** @var Miaoxing\Coupon\Service\UserCouponModel|Miaoxing\Coupon\Service\UserCouponModel[] $userCouponModels */
    $userCoupons = wei()->userCouponModel();
}
