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
    z-index: 1;
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


<?php foreach ($coupons as $key => $coupon) { ?>
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

      <?php
      $ret = $coupon->checkReceive();
      if ($ret['code'] === 1) {
        ?>
        <span class="js-get-coupon btn btn-default hairline" data-id="<?= $coupon['id'] ?>">点击领取</span>
      <?php } else { ?>
        <span class="btn btn-default hairline disabled"><?= $ret['shortMessage'] ?></span>
      <?php } ?>
    </div>
  </a>

  <div class="coupon-remark p-l-0">
    <span class="text-primary">
      备注: <?= $coupon['remark'] ?>
    </span>
  </div>
<?php } ?>

<?= $block->js() ?>
<script>
  var event = $.Event('afterGetCoupon');

  $('.js-get-coupon').click(function (e) {
    e.preventDefault();

    var $btn = $(this);
    var id = $btn.data('id');
    $.ajax({
      type: 'post',
      url: $.url('coupons/get-coupon'),
      data: {
        id: id
      },
      dataType: 'json',
      success: function (ret) {
        $.msg(ret);
        if (ret.code === 1) {
          $btn.html('已领取').addClass('disabled');
          $(document).trigger(event);
        }
      }
    });
  });

  $('.js-get-all-coupon').click(function () {
    $.ajax({
      type: 'post',
      url: $.url('coupons/get-all-coupon'),
      dataType: 'json',
      success: function (ret) {
        if (ret.code === 1) {
          $('.js-get-coupon').html('已领取').addClass('disabled');
        }
        $.msg(ret);
        $(document).trigger(event);
      }
    });
  });
</script>
<?= $block->end() ?>
