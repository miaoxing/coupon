<?php

namespace Miaoxing\Coupon;

use Miaoxing\Address\Service\Address;
use Miaoxing\Coupon\Service\CouponLog;
use plugins\mall\services\Order;

class Plugin extends \miaoxing\plugin\BasePlugin
{
    protected $name = '优惠券';

    protected $description = '';

    protected $adminNavId = 'marketing';

    public function onAdminNavGetNavs(&$navs, &$categories, &$subCategories)
    {
        $navs[] = [
            'parentId' => 'marketing-activities',
            'url' => 'admin/coupon/index',
            'name' => '优惠券管理',
        ];
    }

    public function onLinkToGetLinks(&$links, &$types)
    {
        $links[] = [
            'typeId' => 'marketing',
            'name' => '我的优惠券',
            'url' => 'coupon/index',
        ];

        $links[] = [
            'typeId' => 'marketing',
            'name' => '优惠券领取列表',
            'url' => 'coupon/list',
        ];
    }

    public function onPostOrderCartRender(Order $order)
    {
        // 检查商品是否可用优惠券
        $carts = $order->getCarts();
        $allowCoupon = true;
        foreach ($carts as $cart) {
            if (!$cart->getProduct()->get('allowCoupon')) {
                $allowCoupon = false;
                break;
            }
        }

        if (!$allowCoupon) {
            return;
        }

        $userCoupons = wei()->coupon->getAvailableCouponsByCarts($carts);
        $this->view->display('coupon:coupon/postOrderCartRender.php', get_defined_vars());
    }

    public function onPreOrderCreate(Order $order, Address $address = null, $data)
    {
        if (!$data['userCouponId']) {
            return;
        }

        $carts = $order->getCarts();
        $userCoupon = wei()->userCoupon()->mine()->findOneById($data['userCouponId']);
        if (!$userCoupon->isAvailable($carts)) {
            return $userCoupon->getResult();
        }

        // 如果可用,记录减免的金额
        $order['userCouponId'] = $userCoupon['id'];
        $order->setAmountRule('userCoupon', ['name' => '优惠券', 'amountOff' => $userCoupon->getReduceCost()]);
    }

    public function onPostOrderCreate(Order $order)
    {
        if (!$order['userCouponId']) {
            return;
        }

        // 设置优惠券已使用
        $userCoupon = wei()->userCoupon()->findOneById($order['userCouponId']);
        $userCoupon->save([
            'used' => true,
            'useTime' => date('Y-m-d H:i:s'),
        ]);

        // 记录核销日志
        wei()->stat->log('couponLogs', [
            'userId' => $order['userId'],
            'couponId' => $userCoupon['couponId'],
            'action' => CouponLog::ACTION_USE,
        ]);
    }

    public function onOrdersShowItem(Order $order)
    {
        if ($order['userCouponId']) {
            $this->view->display('coupon:coupon/ordersShowItem.php', get_defined_vars());
        }
    }

    public function onAdminOrdersShow(Order $order, &$data)
    {
        if ($order['userCouponId']) {
            $data['couponName'] = $order->getUserCoupon()->getCoupon()->get('name');
        } else {
            $data['couponName'] = '';
        }
    }

    public function onAdminOrdersShowItem()
    {
        $this->view->display('coupon:coupon/adminOrdersShowItem.php');
    }

    /**
     * 订单导出事件
     *
     * @param array $order
     * @param array $cart
     * @param array $rowData
     * @param array $outputData
     */
    public function onRenderOrder(array $order, array $cart, array &$rowData, array &$outputData)
    {
        if (!in_array('优惠券信息', $outputData[0])) {
            $outputData[0][] = '优惠券信息';
        }
        $couponName = $order['userCouponId'] ? wei()->userCoupon()->findById($order['userCouponId'])->getName() : '-';
        $rowData[] = $couponName;
    }
}
