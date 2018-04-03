<?php

namespace Miaoxing\Coupon\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20180403220239CreateCouponsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $table = $this->schema->table('coupons')
            ->id()
            ->int('app_id')
            ->string('name', 32)->comment('名称')
            ->string('money', 8)->comment('金额')
            ->string('pic')->comment('图')
            ->mediumText('rule')->comment('规则')
            ->string('valid_day', 16)->comment('有效日期')
            ->string('remark')->comment('备注')
            ->text('styles')
            ->int('get_limit', 4)->comment('领取次数，0为无限领取')
            ->int('quantity')->comment('库存数量')
            ->decimal('limit_amount', 10)->comment('限制金额')
            ->tinyInt('use_scene', 1)->comment('使用场景 0不限 1特价商品 2换购商品')
            ->string('scope', 16)
            ->text('product_ids')
            ->text('category_ids')
            ->int('sort')
            ->text('redirect_link_to')->comment('领取后跳转链接')
            ->tinyInt('enable', 1)->comment('状态')
            ->timestamp('started_at')
            ->timestamp('ended_at')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('coupons');
    }
}
