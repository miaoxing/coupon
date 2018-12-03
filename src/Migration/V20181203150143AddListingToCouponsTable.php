<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20181203150143AddListingToCouponsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('coupons')
            ->bool('listing')->defaults(true)
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->table('coupons')
            ->dropColumn('listing')
            ->exec();
    }
}
