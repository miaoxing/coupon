<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;

class ProductCoupons extends BaseController
{
    public function indexAction($req)
    {
        $coupons = wei()->couponModel()
            ->enabled()
            ->andWhere(['listing' => true])
            ->desc('sort')
            ->desc('id')
            ->findAll();

        $this->event->trigger('afterCouponsIndexFind', $coupons);

        $carts = wei()->cart()->beColl();
        $carts[] = wei()->cart()->fromArray(['productId' => $req['productId']]);
        foreach ($coupons as $i => $coupon) {
            if (wei()->productFilter->filterCarts($carts, $coupon)->length() === 0) {
                unset($coupons[$i]);
            }
        }

        return get_defined_vars();
    }
}
