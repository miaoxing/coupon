<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20180403220609CreateUserCouponsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('user_coupons')
            ->id()
            ->int('app_id', 10)
            ->int('user_id', 10)
            ->int('coupon_id')->comment('优惠券id')
            ->tinyInt('used', 1)->comment('是否已用')
            ->timestamp('created_at')->comment('发送时间')
            ->tinyInt('remind', 1)
            ->timestamp('started_at')->comment('开始时间')
            ->timestamp('ended_at')->comment('结束时间')
            ->timestamp('used_at')->comment('使用时间')
            ->timestamps()
            ->userstamps()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('user_coupons');
    }
}
