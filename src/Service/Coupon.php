<?php

namespace Miaoxing\Coupon\Service;

use Miaoxing\Cart\Service\Cart;

class Coupon extends \miaoxing\plugin\BaseModel
{
    protected $code;

    protected $message;

    protected $data = [
        'sort' => 50,
        'enable' => 1,
        'styles' => [],
        'redirectLinkTo' => [],
        'productIds' => [],
        'categoryIds' => [],
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
     * @return Coupon[]
     */
    public function getAvailableCouponsByCarts(Cart $carts)
    {
        $now = date('Y-m-d H:i:s');

        /** @var $userCoupons UserCoupon[]|UserCoupon */
        $userCoupons = wei()->userCoupon()
            ->mine()
            ->andWhere('used = 0')
            ->andWhere('startTime < ?', $now)
            ->andWhere('endTime > ?', $now)
            ->findAll();

        $amount = $carts->getProductAmount();

        foreach ($userCoupons as $key => $userCoupon) {
            $coupon = $userCoupon->getCoupon();

            if ($coupon['enable'] == 0 || $amount < $coupon['limitAmount']
                || wei()->productFilter->filterCarts($carts, $coupon)->length() <= 0
            ) {
                $userCoupons->remove($key);
            }
        }

        return $userCoupons;
    }

    /**
     * 获取可用的优惠券
     *
     * @param string $userId
     * @param null $type
     * @return array
     */
    public function getAvailableCoupon($userId, $type = null)
    {
        $data = [];
        $userCoupon = wei()->userCoupon()
            ->where('userId=?', $userId)
            ->andWhere('used=?', 0)
            ->andWhere('startTime<?', date('Y-m-d H:i:s'))
            ->andWhere('endTime>?', date('Y-m-d H:i:s'))
            ->findAll();

        foreach ($userCoupon as $key => $value) {
            $coupon = wei()->coupon()
                ->where('id=?', $value['couponId'])
                ->andWhere('enable=?', 1);
            if ($type) {
                $coupon->andWhere('useScene = 0 OR useScene = ?', $type);
            }
            $result = $coupon->find();
            if ($result) {
                $data[] = $result->toArray() + [
                        'startTime' => $value['startTime'],
                        'endTime' => $value['endTime'],
                        'userCouponId' => $value['id'],
                    ];
            }
        }

        return $data;
    }

    /**
     * 获取未使用过的优惠券(含过期)
     *
     * @param string $userId
     * @param null $type
     * @return array
     */
    public function getNotUseCoupon($userId, $type = null)
    {
        $data = [];
        $userCoupon = wei()->userCoupon()->where(['userId' => $userId])->andWhere(['used' => 0])->findAll();

        foreach ($userCoupon as $key => $value) {
            $coupon = wei()->coupon()->where(['id' => $value['couponId']])->andWhere(['enable' => 1]);
            if ($type) {
                $coupon->andWhere('useScene = 0 OR useScene = ?', $type);
            }

            $result = $coupon->find();
            if ($result) {
                $data[] = $result->toArray() + [
                        'startTime' => $value['startTime'],
                        'endTime' => $value['endTime'],
                    ];
            }
        }

        return $data;
    }

    /**
     * 获取使用过的优惠券
     *
     * @param string $userId
     * @return array
     */
    public function getUsedCoupon($userId)
    {
        $data = [];
        $userCoupon = wei()->userCoupon()->where(['userId' => $userId])->andWhere(['used' => 1])->findAll();
        if ($userCoupon) {
            foreach ($userCoupon as $key => $value) {
                $result = wei()->coupon()->where(['id' => $value['couponId']])->andWhere(['enable' => 1])->find();
                if ($result) {
                    $data[] = $result->toArray() + [
                            'useTime' => $value['useTime'],
                            'startTime' => $value['startTime'],
                            'endTime' => $value['endTime'],
                        ];
                }
            }
        }

        return $data;
    }

    /**
     * 获取用户优惠券信息
     *
     * @param string $couponId
     * @param string $userId
     * @return array
     */
    public function getCouponInfo($couponId, $userId)
    {
        $result = wei()->coupon()->where('id=?', $couponId)->fetch();
        $userCoupon = wei()->userCoupon()
            ->where('couponId=?', $couponId)
            ->andWhere('userId=?', $userId)
            ->fetch();
        $result['startTime'] = $userCoupon['startTime'];
        $result['endTime'] = $userCoupon['endTime'];

        return $result;
    }

    /**
     * 获取优惠券信息
     *
     * @param string $couponId
     * @return array
     */
    public function couponInfo($couponId)
    {
        $result = wei()->coupon()->where('id=?', $couponId)->fetch();

        return $result;
    }

    /**
     * 使用优惠券
     *
     * @param string $userId
     * @param string $couponId
     * @return bool
     */
    public function useCoupon($userId, $couponId)
    {
        $couponInfo = $this->getCouponInfo($couponId, $userId);

        if ($couponInfo['enable'] == 0) {
            return ['code' => -1, 'msg' => '该优惠券已经无效'];
        } elseif ($couponInfo['startTime'] > date('Y-m-d H:i:s')) {
            return ['code' => -2, 'msg' => '该优惠券还没到使用时间'];
        } elseif ($couponInfo['endTime'] < date('Y-m-d H:i:s')) {
            return ['code' => -3, 'msg' => '该优惠券已经过了最后使用时间'];
        }
        $ret = wei()->db->update(
            'userCoupon',
            ['used' => 1, 'useTime' => date('Y-m-d H:i:s')],
            ['userId' => $userId, 'couponId' => $couponId, 'used' => 0]
        );
        if ($ret) {
            return ['code' => 1, 'msg' => '该优惠券使用成功'];
        }

        return ['code' => -4, 'msg' => '该优惠券被使用过'];
    }

    public function isAvailable()
    {
        if (!$this['enable']) {
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
     * @return bool
     */
    public function sendCoupon($couponId, $userId, $count = 1)
    {
        $coupon = wei()->coupon()->findOneById($couponId);
        if (!$coupon) {
            return ['code' => -1, 'message' => '不存在该优惠券'];
        }

        if ($coupon['quantity'] <= 0) {
            return ['code' => -1, 'message' => '优惠券库存为0，无法获得优惠券'];
        }

        if ($coupon['quantity'] < $count) {
            return ['code' => -1, 'message' => '优惠券库存不足，无法获得优惠券'];
        }

        $user = wei()->user()->findOneById($userId);
        if ($coupon['getLimit'] && wei()->userCoupon->getUserCouponCount($user, $coupon) >= $coupon['getLimit']) {
            return ['code' => -1, 'message' => '超过领取数量，无法获得优惠券'];
        }

        if ($coupon['startTime'] && strtotime($coupon['startTime']) > time()) {
            return ['code' => -1, 'message' => '还未到开始领取时间，请耐心等候'];
        }

        if ($coupon['endTime'] && strtotime($coupon['endTime']) < time()) {
            return ['code' => -1, 'message' => '已过了最后领取的时间，请关注下次活动时间'];
        }

        $endTime = date('Y-m-d H:i:s', time() + $coupon['validDay'] * 86400);
        $couponData = [
            'userId' => $userId,
            'couponId' => $couponId,
            'startTime' => date('Y-m-d H:i:s'),
            'endTime' => $endTime,
            'createTime' => date('Y-m-d H:i:s'),
        ];

        for ($i = 0; $i < $count; ++$i) {
            wei()->db->insert('userCoupon', $couponData);
        }

        $coupon->decr('quantity', $count)->save();

        // 记录领取日志
        wei()->stat->log('couponLogs', [
            'userId' => $userId,
            'couponId' => $couponId,
            'action' => CouponLog::ACTION_RECEIVE,
        ]);

        return ['code' => 1, 'message' => '操作成功'];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this['styles'] = (array) json_decode($this['styles'], true);
        $this['redirectLinkTo'] = (array) json_decode($this['redirectLinkTo'], true);
        $this['productIds'] = (array) json_decode($this['productIds'], true);
        $this['categoryIds'] = (array) json_decode($this['categoryIds'], true);

        if ($this['startTime'] == '0000-00-00 00:00:00') {
            $this['startTime'] = '';
        }

        if ($this['endTime'] == '0000-00-00 00:00:00') {
            $this['endTime'] = '';
        }

        $this->event->trigger('postImageDataLoad', [&$this, ['pic']]);
    }

    public function beforeSave()
    {
        parent::beforeSave();
        $this['styles'] = json_encode($this['styles'], JSON_UNESCAPED_UNICODE);
        $this['redirectLinkTo'] = json_encode($this['redirectLinkTo'], JSON_UNESCAPED_UNICODE);
        $this['productIds'] = json_encode((array) $this['productIds']);
        $this['categoryIds'] = json_encode((array) $this['categoryIds']);
        $this->event->trigger('preImageDataSave', [&$this, ['pic']]);
    }
}
