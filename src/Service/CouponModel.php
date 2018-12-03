<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Cart\Service\Cart;
use Miaoxing\Coupon\Metadata\CouponTrait;
use Miaoxing\Plugin\BaseModelV2;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\SoftDeleteTrait;
use Miaoxing\Product\Service\Product;

class CouponModel extends BaseModelV2
{
    use CouponTrait;
    use HasAppIdTrait;
    use SoftDeleteTrait;

    protected $code;

    protected $message;

    protected $products;

    protected $data = [
        'sort' => 50,
        'enable' => true,
        'listing' => true,
        'styles' => [],
        'redirect_link_to' => [],
        'product_ids' => [],
        'category_ids' => [],
    ];

    protected $defaultCasts = [
        'styles' => 'json',
        'redirect_link_to' => 'json',
        'category_ids' => 'json',
        'product_ids' => 'json',
    ];

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 根据多个购物车,拉取可用的优惠券
     *
     * @param Cart|\Miaoxing\Cart\Service\Cart[] $carts
     * @return UserCouponModel|UserCouponModel[]
     * @throws \Exception
     */
    public function getAvailableCouponsByCarts(Cart $carts)
    {
        $now = date('Y-m-d H:i:s');

        /** @var $userCoupons UserCouponModel[]|UserCouponModel */
        $userCoupons = wei()->userCouponModel()
            ->mine()
            ->andWhere('used = 0')
            ->andWhere('started_at < ?', $now)
            ->andWhere('ended_at > ?', $now)
            ->findAll();

        $amount = $carts->getProductAmount();

        foreach ($userCoupons as $key => $userCoupon) {
            $coupon = $userCoupon->coupon;

            if ($coupon->enable == 0 || $amount < $coupon->limitAmount
                || wei()->productFilter->filterCarts($carts, $coupon)->length() <= 0
            ) {
                $userCoupons->remove($key);
            }
        }

        return $userCoupons;
    }

    public function isAvailable()
    {
        if (!$this->enable) {
            $this->code = -1010;
            $this->message = '该优惠券已经无效';

            return false;
        }

        return true;
    }

    /**
     * 发放优惠券
     *
     * @param int $couponId
     * @param int $userId
     * @param int $count
     * @return array
     */
    public function sendCoupon($couponId, $userId, $count = 1)
    {
        $coupon = wei()->couponModel()->findById($couponId);
        if (!$coupon) {
            return $this->err('不存在该优惠券');
        }

        if ($coupon->quantity <= 0) {
            return ['code' => -1, 'message' => '优惠券库存为0，无法获得优惠券'];
        }

        if ($coupon->quantity < $count) {
            return ['code' => -1, 'message' => '优惠券库存不足，无法获得优惠券'];
        }

        $user = wei()->user()->findOneById($userId);
        if ($coupon->getLimit && wei()->userCouponModel->getUserCouponCount($user, $coupon) >= $coupon->getLimit) {
            return ['code' => -1, 'message' => '超过领取数量，无法获得优惠券'];
        }

        if ($coupon->startedAt && strtotime($coupon->startedAt) > time()) {
            return ['code' => -1, 'message' => '还未到开始领取时间，请耐心等候'];
        }

        if ($coupon->endedAt && strtotime($coupon->endedAt) < time()) {
            return ['code' => -1, 'message' => '已过了最后领取的时间，请关注下次活动时间'];
        }

        $couponData = [
            'userId' => $userId,
            'couponId' => $couponId,
            'startAt' => date('Y-m-d H:i:s'),
            'endedAt' => $this->getEndTime($coupon),
        ];

        for ($i = 0; $i < $count; ++$i) {
            wei()->userCouponModel()->save($couponData);
        }

        $coupon->decr('quantity', $count)->save();

        // 记录领取日志
        wei()->statV2->log(wei()->couponLogModel(), [
            'userId' => $userId,
            'couponId' => $couponId,
            'action' => CouponLogModel::ACTION_RECEIVE,
        ]);

        return $this->suc();
    }

    protected function getEndTime(CouponModel $coupon)
    {
        return date('Y-m-d H:i:s', time() + $coupon->validDay * 86400);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->event->trigger('postImageDataLoad', [&$this, ['pic']]);
    }

    public function beforeSave()
    {
        parent::beforeSave();
        $this->event->trigger('preImageDataSave', [&$this, ['pic']]);
    }

    /**
     * Record: 获取指定参加活动的商品
     *
     * @return Product|Product[]
     */
    public function getProducts()
    {
        if (!$this->products) {
            $this->products = wei()->product()->beColl();
            if ($this['productIds']) {
                // 按原来的顺序排列
                $this->products->orderBy('FIELD(id, ' . implode(', ', $this['productIds']) . ')')
                    ->findAll(['id' => $this['productIds']]);
            }
        }

        return $this->products;
    }

    public function getRedirectUrl()
    {
        if ($this->redirectLinkTo['type']) {
            return wei()->linkTo->getUrl($this->redirectLinkTo);
        }
        return '';
    }
}
