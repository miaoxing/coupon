<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/coupon/css/coupon.css') ?>">
<style>
  <?php foreach ($coupons as $key => $coupon) : ?>
  .stamp<?= $key ?> {
    display: block;
    background: <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?>;
    background: radial-gradient(rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0) 4px,
    <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?> 4px);
    background-size: 12px 8px;
    background-position: -5px 10px;
  }

  .stamp<?= $key ?>:before {
    background-color: <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?>;
  }

  .stamp<?= $key ?> .copy .submit {
    background-color: <?= $coupon['styles']['btnColor'] ?: '#fff' ?>;
    color: <?= $coupon['styles']['btnFontColor'] ?: '#000' ?>;
  }
  <?php endforeach ?>
</style>
<?= $block->end() ?>

<div class="coupon-container">
  <?php require $view->getFile('@coupon/coupons/index-title.php') ?>

  <div class="m-x-sm">
    <a class="btn btn-primary hairline js-get-all-coupon" href="javascript:;">一键领取</a>
  </div>

  <?php foreach ($coupons as $key => $coupon) : ?>
    <a href="<?= $url('coupons/%s', $coupon['id']) ?>" class="stamp stamp<?= $key ?>">
      <i></i>

      <div class="par">
        <p class="f-16"><?= $coupon['name'] ?></p>
        <sub class="sign f-20">￥</sub>
        <span class="f-24"><?= sprintf('%.2f', $coupon['money']) ?></span>
        <sub>优惠券</sub>
        <p class="f-16">订单满<?= $coupon['limitAmount'] ?>元可使用</p>
      </div>

      <div class="copy f-20">
        <p class="f-12">
          <?php if ($coupon['dateType'] == \Miaoxing\Coupon\Service\CouponModel::DATE_TYPE_FIXED_DATE) { ?>
            领取后<?= $coupon['validDay'] ?>天有效
          <?php } else { ?>
            <?= substr($coupon['startedUseAt'], 0, 10) ?> ~ <?= substr($coupon['endedUseAt'], 0, 10) ?> 有效
          <?php } ?>
        </p>

        <?php if ($coupon['canGet']) : ?>
          <span class="js-get-coupon submit f-14" data-id="<?= $coupon['id'] ?>">点击领取</span>
        <?php else : ?>
          <span class="non-submit f-14"><?= $coupon['canGetMsg'] ?: '不可领取' ?></span>
        <?php endif; ?>
      </div>
    </a>

    <div class="coupon-remark">
    <span class="text-primary">
      备注: <?= $coupon['remark'] ?>
    </span>
    </div>
  <?php endforeach; ?>
</div>

<?= $block->js() ?>
<script>
  <?php $needPerfect = wei()->event->until('preGetCoupon') ?>
  var perfectInformation = function() {
    var setimeout;
    setimeout = setTimeout(function () {
      clearTimeout(setimeout);
      window.location.href = $.url('users/edit');
    }, 3000);

    $.alert('完善信息才能领取优惠券，马上跳转完善信息页面......', function () {
      clearTimeout(setimeout);
      window.location.href = $.url('users/edit');
    });
  };

  $('.js-get-coupon').click(function (e) {
    e.preventDefault();

    <?php if ($needPerfect) : ?>
      perfectInformation();

    <?php else : ?>
      var id = $(this).data('id');
      $.ajax({
        type: 'post',
        url: $.url('coupons/get-coupon'),
        data: {
          id: id
        },
        dataType: 'json',
        success: function (ret) {
          $.msg(ret, function() {
            window.location.reload();
          });
        }
      });
    <?php endif; ?>
  });

  $('.js-get-all-coupon').click(function () {
    <?php if ($needPerfect) : ?>
      perfectInformation();

    <?php else : ?>
      $.ajax({
        type: 'post',
        url: $.url('coupons/get-all-coupon'),
        dataType: 'json',
        success: function (ret) {
          $.msg(ret, function() {
            window.location.reload();
          });
        }
      });
    <?php endif; ?>
  });
</script>
<?= $block->end() ?>
