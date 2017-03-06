<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Plugin\Service\User;
use Miaoxing\Cart\Service\Cart;

class UserCoupon extends \miaoxing\plugin\BaseModel
{
    protected $code;

    protected $message;

    /**
     * @var Coupon
     */
    protected $coupon;

    public function getCoupon()
    {
        $this->coupon || $this->coupon = wei()->coupon()->findOrInitById($this['couponId']);

        return $this->coupon;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResult()
    {
        return ['code' => $this->code, 'message' => $this->message];
    }

    /**
     * @param Cart|\Miaoxing\Cart\Service\Cart[] $carts
     * @return array|bool
     * @throws \Exception
     */
    public function isAvailable(Cart $carts)
    {
        if ($this['used']) {
            $this->code = -1000;
            $this->message = '该优惠券已经使用过';

            return false;
        }

        if ($this['startTime'] >= date('Y-m-d H:i:s')) {
            $this->code = -1001;
            $this->message = '该优惠券还没到使用时间';

            return false;
        }

        if ($this['endTime'] <= date('Y-m-d H:i:s')) {
            $this->code = -1002;
            $this->message = '该优惠券已经过了最后使用时间';

            return false;
        }

        if (wei()->productFilter->filterCarts($carts, $this->getCoupon())->length() <= 0) {
            $this->code = -1003;
            $this->message = '该购物车没有包括优惠券可使用的商品,请从新选择优惠券';

            return false;
        }

        $coupon = $this->getCoupon();
        if (!$coupon->isAvailable()) {
            $this->code = $coupon->getCode();
            $this->message = $coupon->getMessage();

            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取用户领取优惠券的数量
     * @param User $user
     * @param Coupon $coupon
     * @return int
     */
    public function getUserCouponCount(User $user, Coupon $coupon)
    {
        $count = wei()->userCoupon()->findAll(['userId' => $user['id'], 'couponId' => $coupon['id']])->count();

        return $count;
    }

    public function getReduceCost()
    {
        return $this->getCoupon()->get('money');
    }

    public function getName()
    {
        return $this->getCoupon()->get('name');
    }
}
