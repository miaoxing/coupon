<?php

namespace Miaoxing\Coupon\Metadata;

/**
 * UserCouponTrait
 *
 * @property int $id 主键
 * @property int $appId
 * @property int $userId
 * @property int $couponId 优惠券id
 * @property bool $used 是否已用
 * @property string $usedAt 使用时间
 * @property string $createdAt 发送时间
 * @property string $startedAt 开始时间
 * @property string $endedAt 结束时间
 * @property bool $remind
 * @property int $updatedBy
 * @property string $updatedAt
 * @property int $createdBy
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
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'remind' => 'bool',
        'updated_by' => 'int',
        'updated_at' => 'datetime',
        'created_by' => 'int',
    ];
}
