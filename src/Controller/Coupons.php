<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;
use Miaoxing\Plugin\Service\Request;

class Coupons extends BaseController
{
    public function indexAction($req)
    {
        $coupons = $this->getCoupons();

        $this->page->setTitle('优惠券');
        return get_defined_vars();
    }

    public function showAction(Request $req)
    {
        if (!$req->json()) {
            return [];
        }

        $coupon = wei()->couponModel()->findOneById($req['id']);

        return $this->suc([
            'data' => $coupon->toArray() + ['redirectUrl' => $coupon->getRedirectUrl()],
            'receiveRet' => $coupon->checkReceive(),
        ]);
    }

    public function productsAction($req)
    {
        $coupon = wei()->couponModel()->findOneById($req['id']);

        $products = wei()->product()
            ->notDeleted()
            ->andWhere(['visible' => 1])
            ->andWhere(['listing' => 1]);

        switch ($coupon->scope) {
            case 'category':
                $products->andWhere(['categoryId' => $coupon->categoryIds]);
                break;

            case 'product':
                $products->orderBy('FIELD(id, ' . implode(', ', $coupon->productIds) . ')')
                    ->andWhere(['id' => $coupon->productIds]);
                break;

            case 'all':
                break;
        }
        $products->desc('sort')
            ->desc('id')
            ->page($req['page'])
            ->findAll();

        return $this->suc(['data' => $products]);
    }

    public function getCouponAction($req)
    {
        $ret = wei()->couponModel->sendCoupon($req['id'], wei()->curUser['id']);
        if ($ret['code'] == 1) {
            $ret['message'] = '领取成功';
        }

        return $ret;
    }

    public function getAllCouponAction($req)
    {
        $coupons = wei()->couponModel()->enabled()->andWhere(['listing' => true])->findAll();
        $isGet = false;

        foreach ($coupons as $coupon) {
            $ret = wei()->couponModel->sendCoupon($coupon->id, wei()->curUser['id']);
            if ($ret['code'] !== 1) {
                return $ret;
            }
            $isGet = true;
        }

        if (!$isGet) {
            return $this->err('已经没有可领取的优惠券了');
        }

        return $this->suc('领取成功');
    }

    public function getByProductAction($req)
    {
        $coupons = $this->getCoupons();

        $carts = wei()->cart()->beColl();
        $carts[] = wei()->cart()->fromArray(['productId' => $req['productId']]);
        foreach ($coupons as $i => $coupon) {
            if (wei()->productFilter->filterCarts($carts, $coupon)->length() === 0) {
                unset($coupons[$i]);
            }
        }

        return get_defined_vars();
    }

    public function getByCartsAction($req)
    {
        $coupons = $this->getCoupons();
        $carts = wei()->cart()->findAllByIds($req['cartIds']);
        $amount = $carts->getProductAmount();

        foreach ($coupons as $key => $coupon) {
            if ($amount < $coupon->limitAmount || wei()->productFilter->filterCarts($carts, $coupon)->length() === 0) {
                $coupons->remove($key);
            }
        }

        return get_defined_vars();
    }

    protected function getCoupons()
    {
        $coupons = wei()->couponModel()
            ->enabled()
            ->andWhere(['listing' => true])
            ->desc('sort')
            ->desc('id')
            ->findAll();

        $this->event->trigger('afterCouponsIndexFind', $coupons);

        return $coupons;
    }
}
