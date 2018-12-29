<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Config\ConfigTrait;
use Miaoxing\Plugin\BaseService;

/**
 * @property bool showProductCoupons
 * @property bool showNewOrderCoupons
 */
class Coupon extends BaseService
{
    use ConfigTrait;

    protected $configs = [
        'showProductCoupons' => [
            'default' => false,
        ],
        'showNewOrderCoupons' => [
            'default' => false,
        ],
    ];
}
