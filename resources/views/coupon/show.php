<?php $view->layout() ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('plugins/coupon/css/coupon.css') ?>">
<style>
  .stamp01 {
    background: <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?>;
    background: radial-gradient(rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0) 4px,
    <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?> 4px);
    background-size: 12px 8px;
    background-position: -5px 10px;
  }

  .stamp01:before {
    background-color: <?= $coupon['styles']['bgColor'] ?: '#50ADD3' ?>;
  }

  .stamp01 .copy .submit {
    background-color: <?= $coupon['styles']['btnColor'] ?: '#fff' ?>;
    color: <?= $coupon['styles']['btnFontColor'] ?: '#000' ?>;
  }
</style>
<?= $block->end() ?>

<div class="coupon-container">
  <div class="stamp stamp01">
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
        领取后<?= $coupon['validDay'] ?>天有效
      </p>

      <?php if ($canGet) : ?>
      <a href="javascript:;" class="js-get-coupon submit f-14" data-id="<?= $coupon['id'] ?>">点击领取</a>
      <?php else : ?>
      <span class="non-submit f-14">不可领取</span>
      <?php endif; ?>

    </div>
  </div>
</div>

<div class="coupon-remark">
  <span class="text-primary">
    备注: <?= $coupon['remark'] ?>
  </span>
</div>

<?= $block('js') ?>
  <script>
    <?php $needPerfect = wei()->event->until('preGetCoupon'); ?>
    var perfectInformation = function() {
      var setimeout;
      setimeout = setTimeout(function () {
        clearTimeout(setimeout);
        window.location.href = $.url('users/info');
      }, 3000);

      $.alert('完善信息才能领取优惠券，马上跳转完善信息页面......', function () {
        clearTimeout(setimeout);
        window.location.href = $.url('users/info');
      });
    };

    $('.js-get-coupon').click(function () {
      <?php if ($needPerfect) : ?>
        perfectInformation();

      <?php else : ?>
        var id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: $.url('coupon/get-coupon'),
          data: {
            id: id
          },
          dataType: 'json',
          success: function (ret) {
            $.msg(ret);
            // 领取成功，跳转首页
            if(ret.code == 1) {
              setInterval("window.location.href = $.url('')", 3000);
            }
          }
        });

      <?php endif; ?>
    });

    <?php if (!$canGet) : ?>
      // 不可领取，跳转首页
      setInterval("window.location.href = $.url('')", 3000);
    <?php endif; ?>

  </script>
<?= $block->end() ?>
