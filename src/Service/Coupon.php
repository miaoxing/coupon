<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Config\ConfigTrait;
use Miaoxing\Plugin\BaseService;

/**
 * @property bool showProductCoupons
 */
class Coupon extends BaseService
{
    use ConfigTrait;

    protected $configs = [
        'showProductCoupons' => [
            'default' => false,
        ],
    ];
}
