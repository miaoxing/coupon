<?php

namespace Miaoxing\Coupon\Metadata;

/**
 * UserCouponTrait
 *
 * @property int $id
 * @property int $appId
 * @property int $userId
 * @property int $couponId 优惠券id
 * @property bool $used 是否已用
 * @property string $createdAt
 * @property bool $remind
 * @property string $startedAt 开始时间
 * @property string $endedAt 结束时间
 * @property string $usedAt 使用时间
 * @property string $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 */
trait UserCouponTrait
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
        'used' => 'bool',
        'created_at' => 'datetime',
        'remind' => 'bool',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'used_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'int',
        'updated_by' => 'int',
    ];
}
