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
        $this->schema->table('coupon')->tableComment('优惠券')
            ->id()
            ->string('name', 32)->comment('名称')
            ->string('money', 8)->defaults(0)->comment('金额')
            ->string('pic')->comment('图片')
            ->mediumText('rule')->comment('规则')
            ->string('validDay', 16)->comment('有效日期')
            ->string('remark')->comment('备注')
            ->text('styles')
            ->int('getLimit', 4)->comment('领取次数，0为无限领取')
            ->string('limitAmount', 16)->defaults(0)->comment('限制金额')
            ->tinyInt('useScene', 1)->comment('使用场景 0不限 1特价商品 2换购商品')
            ->string('scope', 16)
            ->text('productIds')
            ->text('categoryIds')
            ->int('sort', 4)
            ->bool('enable')
            ->datetime('startTime')
            ->datetime('endTime')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->exec();

        $this->schema->table('userCoupon')
            ->id()
            ->int('userId')
            ->int('couponId')
            ->bool('used')->comment('是否已用')
            ->timestamp('useTime')->comment('使用时间')
            ->timestamp('createTime')->comment('发送时间')
            ->timestamp('startTime')->comment('开始时间')
            ->timestamp('endTime')->comment('结束时间')
            ->bool('remind')
            ->exec();

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
            ->timestamps()
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
