<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Coupon\Metadata\CouponStatTrait;
use Miaoxing\Plugin\BaseModelV2;

class CouponStatModel extends BaseModelV2
{
    use CouponStatTrait;

    /**
     * {@inheritdoc}
     */
    protected $data = [
        'receive_count' => 0,
        'receive_user' => 0,
        'use_count' => 0,
        'use_user' => 0,
        'total_receive_count' => 0,
        'total_receive_user' => 0,
        'total_use_count' => 0,
        'total_use_user' => 0,
    ];
}
