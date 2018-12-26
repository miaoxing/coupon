<?php

namespace Miaoxing\Coupon\Controller;

use Miaoxing\Plugin\BaseController;
use Miaoxing\Plugin\Service\Request;

class Coupons extends BaseController
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

        foreach ($coupons as $i => $coupon) {
            $afterStartTime = !$coupon->startedAt || strtotime($coupon->startedAt) <= time();
            $beforeEndTime = !$coupon->endedAt || strtotime($coupon->endedAt) > time();
            $inLimit = !$coupon->getLimit
                || wei()->userCouponModel->getUserCouponCount(wei()->curUser, $coupon) < $coupon->getLimit;
            $muchQuantity = $coupon->quantity > 0;
            if ($afterStartTime && $beforeEndTime && $inLimit && $muchQuantity) {
                $ret = wei()->couponModel->sendCoupon($coupon->id, wei()->curUser['id']);
                if ($ret['code'] !== 1) {
                    return $ret;
                }

                $isGet = true;
            }
        }

        if (!$isGet) {
            return $this->err('已经没有可领取的优惠券了');
        }

        return $this->ret($ret);
    }
}
