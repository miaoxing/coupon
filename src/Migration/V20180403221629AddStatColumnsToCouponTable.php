<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20180403221629AddStatColumnsToCouponTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('coupons')
            ->int('receive_count')->comment('累积领取次数')
            ->int('receive_user')->comment('累积领取人数')
            ->int('use_count')->comment('累积核销次数')
            ->int('use_user')->comment('累积核销次数')
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->table('coupons')
            ->dropColumn('receive_count')
            ->dropColumn('receive_user')
            ->dropColumn('use_count')
            ->dropColumn('use_user')
            ->exec();
    }
}
