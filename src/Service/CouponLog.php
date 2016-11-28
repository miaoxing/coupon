<?php

namespace Miaoxing\Coupon\Service;

class CouponLog extends \miaoxing\plugin\BaseModel
{
    const ACTION_RECEIVE = 1;

    const ACTION_USE = 2;

    /**
     * {@inheritdoc}
     */
    protected $providers = [
        'db' => 'app.db',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'couponLogs';

    /**
     * @var array
     */
    protected $statFields = ['appId', 'couponId'];

    /**
     * @var array
     */
    protected $statActions = [
        1 => 'receive',
        2 => 'use',
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
