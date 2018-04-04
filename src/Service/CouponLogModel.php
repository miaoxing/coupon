<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Coupon\Metadata\CouponLogTrait;
use Miaoxing\Plugin\BaseModelV2;

/**
 * 注意,不使用 HasAppIdTrait, 统计查询才不会包含 app_id
 */
class CouponLogModel extends BaseModelV2
{
    use CouponLogTrait;

    const ACTION_RECEIVE = 1;

    const ACTION_USE = 2;

    /**
     * @var array
     */
    protected $statFields = ['app_id', 'coupon_id'];

    /**
     * @var array
     */
    protected $statActions = [
        self::ACTION_RECEIVE => 'receive',
        self::ACTION_USE => 'use',
    ];

    /**
     * @var bool
     */
    protected $statTotal = true;

    /**
     * @var array
     */
    protected $statSums = [];
}
