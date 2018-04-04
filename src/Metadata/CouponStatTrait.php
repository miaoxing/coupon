<?php

namespace Miaoxing\Coupon\Metadata;

/**
 * CouponStatTrait
 *
 * @property int $id
 * @property int $appId
 * @property int $couponId
 * @property string $statDate 统计的日期
 * @property int $receiveCount 领取的数量
 * @property int $receiveUser 领取的人数
 * @property int $useCount 使用次数
 * @property int $useUser 使用人数
 * @property int $totalReceiveCount 总共领取优惠券的数量
 * @property int $totalReceiveUser 总共领取优惠券的人数
 * @property int $totalUseCount 总共使用优惠券的数量
 * @property int $totalUseUser 总共使用优惠券的人数
 * @property string $createdAt
 * @property string $updatedAt
 */
trait CouponStatTrait
{
    /**
     * @var array
     * @see CastTrait::$casts
     */
    protected $casts = [
        'id' => 'int',
        'app_id' => 'int',
        'coupon_id' => 'int',
        'stat_date' => 'date',
        'receive_count' => 'int',
        'receive_user' => 'int',
        'use_count' => 'int',
        'use_user' => 'int',
        'total_receive_count' => 'int',
        'total_receive_user' => 'int',
        'total_use_count' => 'int',
        'total_use_user' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
