<?php $view->layout('plugin:layouts/jqm.php') ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('assets/mall/coupon.css') ?>">
<?= $block->end() ?>

<div data-role="navbar">
  <ul class="nav-tab">
    <li><a href="<?= $wei->url('coupon/my-coupon', ['used' => 'no', 'v' => rand(1, 100000)]) ?>"
      <?php if ($used == 'no') : ?>
        class="ui-btn-active"
      <?php endif; ?>>
        未使用优惠券
      </a>
    </li>
    <li><a href="<?= $wei->url('coupon/my-coupon', ['used' => 'yes', 'v' => rand(1, 100000)]) ?>"
      <?php if ($used == 'yes') : ?>
        class="ui-btn-active"
      <?php endif; ?>>
        已用优惠券
      </a></li>
  </ul>
</div><!-- /navbar -->
<div data-role="content">
  <div>
    <ul data-role="listview" class="common-list">
      <?php if ($couponList) : ?>
        <?php foreach ($couponList as $key => $value) : ?>
          <li data-icon="false">
            <a href="#">
              <img src="<?= $value['pic'] ?>"/>
              <h3><?= $value['name'] ?></h3>
              <p>
                <span class="coupon-list price">￥ <?= $value['money'] ?></span>
                <span class="coupon-list">有效期:
                  <?= substr($value['startTime'], 0, 10) ?> - <?= substr($value['endTime'], 0, 10) ?>
                  <?php if ($value['endTime'] && ($value['endTime'] < date('Y-m-d H:i:s')) && ($used == 'no')) : ?>
                    <span style="color:red;">(已过期)</span>
                  <?php endif; ?>
                </span>
                <?php if ($used == 'no') : ?>
                  <span class="coupon-list">使用规则: <?= $value['rule'] ? $value['rule'] : '无'; ?></span>
                <?php elseif ($used == 'yes') : ?>
                  <span class="coupon-list">使用时间: <?= substr($value['useTime'], 0, 10) ?></span>
                <?php endif; ?>
              </p>
            </a>
          </li>
        <?php endforeach; ?>
      <?php else : ?>
        <li data-icon="false" style="text-align:center;">暂无优惠券</li>
      <?php endif; ?>
    </ul>
  </div>
</div>
