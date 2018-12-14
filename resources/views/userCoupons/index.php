<?php

$view->layout();
?>

<ul class="header-tab nav tab-underline border-bottom">
  <li class="border-primary <?= !$req['used'] ? 'active' : '' ?>">
    <a class="text-active-primary" href="<?= $url->query('user-coupons', ['used' => 0]) ?>">
      未使用
    </a>
  </li>
  <li class="border-primary <?= $req['used'] ? 'active' : '' ?>">
    <a class="text-active-primary" href="<?= $url->query('user-coupons', ['used' => 1]) ?>">
      已使用
    </a>
  </li>
</ul>

<ul class="list">
  <?php
  foreach ($userCoupons as $userCoupon) :
    $coupon = $userCoupon->coupon;
    ?>
    <li class="list-item">
      <div class="list-col" style="width: 65px">
        <img src="<?= $coupon->pic ?>">
      </div>
      <div class="list-col">
        <h4 class="list-heading">
          <?= $coupon->name ?>
        </h4>
        <div class="list-body">
          <div class="text-primary">￥ <?= $coupon->money ?></div>
          <div>有效期:
            <?= substr($userCoupon->startedAt, 0, 10) ?> - <?= substr($userCoupon->endedAt, 0, 10) ?>
            <?php if ($userCoupon->endedAt && ($userCoupon->endedAt < date('Y-m-d H:i:s'))) : ?>
              <span class="text-danger">(已过期)</span>
            <?php endif ?>
          </div>
          <div>使用规则: <?= $coupon->rule ?: '无'; ?></div>
          <?php if ($userCoupon->used) : ?>
            <span class="coupon-list">使用时间: <?= $userCoupon->usedAt ?></span>
          <?php endif ?>
        </div>
      </div>
    </li>
  <?php endforeach ?>
  <?php if (!$userCoupons->length()) : ?>
    <li class="list-empty">暂无记录</li>
  <?php endif ?>
</ul>
