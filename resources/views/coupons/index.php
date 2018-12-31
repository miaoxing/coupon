<?php $view->layout() ?>

<div class="coupon-container">
  <?php require $view->getFile('@coupon/coupons/index-title.php') ?>
  <div class="m-x-sm">
    <a class="btn btn-primary hairline js-get-all-coupon" href="javascript:;">一键领取</a>
  </div>
  <?php require $app->getControllerFile('_list') ?>
</div>
