<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Coupon\Metadata\UserCouponTrait;
use Miaoxing\Plugin\BaseModelV2;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Service\User;
use Miaoxing\Cart\Service\Cart;

/**
 * @property CouponModel $coupon
 */
class UserCouponModel extends BaseModelV2
{
    use UserCouponTrait;
    use HasAppIdTrait;

    protected $code;

    protected $message;

    public function coupon()
    {
        return $this->belongsTo(wei()->couponModel()->withDeleted());
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

    public function checkToUse()
    {
        if ($this->used) {
            return $this->err('该优惠券已经使用过');
        }

        if ($this->endedAt <= date('Y-m-d H:i:s')) {
            return $this->err('该优惠券已经过了最后使用时间');
        }

        return $this->suc();
    }

    /**
     * @param Cart|\Miaoxing\Cart\Service\Cart[] $carts
     * @return array|bool
     * @throws \Exception
     */
    public function isAvailable(Cart $carts)
    {
        if ($this->used) {
            $this->code = -1000;
            $this->message = '该优惠券已经使用过';

            return false;
        }

        if ($this->startedAt >= date('Y-m-d H:i:s')) {
            $this->code = -1001;
            $this->message = '该优惠券还没到使用时间';

            return false;
        }

        if ($this->endedAt <= date('Y-m-d H:i:s')) {
            $this->code = -1002;
            $this->message = '该优惠券已经过了最后使用时间';

            return false;
        }

        if (wei()->productFilter->filterCarts($carts, $this->coupon)->length() <= 0) {
            $this->code = -1003;
            $this->message = '该购物车没有包括优惠券可使用的商品,请从新选择优惠券';

            return false;
        }

        $coupon = $this->coupon;
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
     * @param CouponModel $coupon
     * @return int
     */
    public function getUserCouponCount(User $user, CouponModel $coupon)
    {
        $count = wei()->userCouponModel()->count(['user_id' => $user['id'], 'coupon_id' => $coupon['id']]);

        return $count;
    }

    public function getReduceCost()
    {
        return $this->coupon->get('money');
    }

    public function getName()
    {
        return $this->coupon->get('name');
    }
}
