<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20161128152348CreateCouponTables extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('couponLogs')
            ->id()
            ->int('appId')
            ->int('userId')
            ->int('couponId')
            ->tinyInt('action', 1)
            ->date('createDate')
            ->timestamp('createTime')
            ->exec();

        $this->schema->table('couponStats')
            ->id()
            ->int('appId')
            ->int('couponId')
            ->date('statDate')->comment('统计的日期')
            ->int('receiveCount')->comment('领取的数量')
            ->int('receiveUser')->comment('领取的人数')
            ->int('useCount')->comment('使用次数')
            ->int('useUser')->comment('使用人数')
            ->int('totalReceiveCount')->comment('总共领取优惠券的数量')
            ->int('totalReceiveUser')->comment('总共领取优惠券的人数')
            ->int('totalUseCount')->comment('总共使用优惠券的数量')
            ->int('totalUseUser')->comment('总共使用优惠券的人数')
            ->timestampsV1()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('coupon');
        $this->schema->dropIfExists('userCoupon');
        $this->schema->dropIfExists('couponLogs');
        $this->schema->dropIfExists('couponStats');
    }
}
