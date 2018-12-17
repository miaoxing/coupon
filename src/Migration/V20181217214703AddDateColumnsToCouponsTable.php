<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20181217214703AddDateColumnsToCouponsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('coupons')
            ->tinyInt('date_type')->defaults(1)
            ->timestamp('started_use_at')
            ->timestamp('ended_use_at')
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->table('coupons')
            ->dropColumn('date_type')
            ->dropColumn('started_use_at')
            ->dropColumn('ended_use_at')
            ->exec();
    }
}
