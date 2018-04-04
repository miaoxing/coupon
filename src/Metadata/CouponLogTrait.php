<?php

namespace Miaoxing\Coupon\Metadata;

/**
 * CouponLogTrait
 *
 * @property int $id
 * @property int $appId
 * @property int $userId
 * @property int $couponId
 * @property int $action
 * @property string $createdDate
 * @property string $createdAt
 */
trait CouponLogTrait
{
    /**
     * @var array
     * @see CastTrait::$casts
     */
    protected $casts = [
        'id' => 'int',
        'app_id' => 'int',
        'user_id' => 'int',
        'coupon_id' => 'int',
        'action' => 'int',
        'created_date' => 'date',
        'created_at' => 'datetime',
    ];
}
