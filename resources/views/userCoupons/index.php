<?php

$view->layout();
?>

<ul class="header-nav nav nav-underline">
  <li class="nav-item">
    <a class="nav-link <?= !$req['used'] ? 'active' : '' ?> text-active-primary border-active-primary" href="<?= $url->query('user-coupons', ['used' => 0]) ?>">
      未使用
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $req['used'] ? 'active' : '' ?> text-active-primary border-active-primary" href="<?= $url->query('user-coupons', ['used' => 1]) ?>">
      已使用
    </a>
  </li>
</ul>

<ul class="list">
  <?php
  foreach ($userCoupons as $userCoupon) {
    $coupon = $userCoupon->coupon;
    ?>
    <li class="list-item">
      <div class="list-col" style="width: 65px">
        <img src="<?= $coupon->pic ?>">
      </div>
      <div class="list-col">
        <h4 class="list-title">
          <?= $coupon->name ?>
        </h4>
        <div class="list-text">
          <div class="text-primary">￥ <?= $coupon->money ?></div>
          <div>有效期:
            <?= substr($userCoupon->startedAt, 0, 10) ?> - <?= substr($userCoupon->endedAt, 0, 10) ?>
            <?php if ($userCoupon->endedAt && ($userCoupon->endedAt < date('Y-m-d H:i:s'))) { ?>
              <span class="text-danger">(已过期)</span>
            <?php } ?>
          </div>
          <div>使用规则: <?= $coupon->rule ?: '无'; ?></div>
          <?php if ($userCoupon->used) { ?>
            <span class="coupon-list">使用时间: <?= $userCoupon->usedAt ?></span>
          <?php } ?>
          <?php if ($userCoupon->checkToUse()['code'] === 1) { ?>
            <div>
              <a href="<?= $url('coupons/%s', $userCoupon->couponId) ?>"
                class="btn btn-outline-primary btn-sm float-right">去使用</a>
            </div>
          <?php } ?>
        </div>
      </div>
    </li>
  <?php } ?>
  <?php if (!$userCoupons->length()) { ?>
    <li class="list-empty">暂无记录</li>
  <?php } ?>
</ul>
