<?php

namespace Miaoxing\Coupon\Service;

class CouponStat extends \miaoxing\plugin\BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $providers = [
        'db' => 'app.db',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'couponStats';

    /**
     * {@inheritdoc}
     */
    protected $data = [
        'receiveCount' => 0,
        'receiveUser' => 0,
        'useCount' => 0,
        'useUser' => 0,
        'totalReceiveCount' => 0,
        'totalReceiveUser' => 0,
        'totalUseCount' => 0,
        'totalUseUser' => 0,
    ];
}
