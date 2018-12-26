<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20181226112924AddUserTagIdsToCouponsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('coupons')
            ->string('user_tag_ids')->defaults('[]')
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->table('coupons')
            ->dropColumn('user_tag_ids')
            ->exec();
    }
}
