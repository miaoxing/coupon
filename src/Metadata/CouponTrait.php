<?php

namespace Miaoxing\Coupon\Metadata;

/**
 * CouponTrait
 *
 * @property int $id 主键
 * @property int $appId
 * @property string $name 名称
 * @property string $money 金额
 * @property string $pic 图
 * @property string $rule 规则
 * @property string $validDay 有效日期
 * @property string $remark 备注
 * @property array $styles
 * @property int $getLimit 领取次数，0为无限领取
 * @property int $quantity 库存数量
 * @property float $limitAmount 限制金额
 * @property bool $useScene 使用场景 0不限 1特价商品 2换购商品
 * @property string $scope
 * @property array $productIds
 * @property array $categoryIds
 * @property int $sort
 * @property array $redirectLinkTo 领取后跳转链接
 * @property bool $enable 状态
 * @property string $startedAt
 * @property string $endedAt
 * @property int $createdBy
 * @property string $createdAt 创建时间
 * @property string $deletedAt
 * @property int $deletedBy
 * @property int $updatedBy
 * @property string $updatedAt
 */
trait CouponTrait
{
    /**
     * @var array
     * @see CastTrait::$casts
     */
    protected $casts = [
        'id' => 'int',
        'app_id' => 'int',
        'name' => 'string',
        'money' => 'string',
        'pic' => 'string',
        'rule' => 'string',
        'valid_day' => 'string',
        'remark' => 'string',
        'styles' => 'json',
        'get_limit' => 'int',
        'quantity' => 'int',
        'limit_amount' => 'float',
        'use_scene' => 'bool',
        'scope' => 'string',
        'product_ids' => 'json',
        'category_ids' => 'json',
        'sort' => 'int',
        'redirect_link_to' => 'json',
        'enable' => 'bool',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'created_by' => 'int',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
        'deleted_by' => 'int',
        'updated_by' => 'int',
        'updated_at' => 'datetime',
    ];
}
