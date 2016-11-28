<li class="list-item order-form-group has-feedback list-over-fix">
  <label for="userCouponId">优惠券</label>

  <div class="order-form-col text-right">
    <select class="js-user-coupon order-form-select" name="userCouponId" id="userCouponId">
      <option value=""><?= $userCoupons->length() ? '选择优惠券' : '暂无可用优惠券' ?></option>
      <?php if ($userCoupons->length()) : ?>
        <?php foreach ($userCoupons as $i => $userCoupon) : ?>
          <option data-amount-off="<?= $userCoupon->getReduceCost() ?>"
                  value="<?= $userCoupon['id'] ?>"><?= $userCoupon->getName() ?></option>
        <?php endforeach ?>
      <?php endif ?>
    </select>

    <div class="js-user-coupon-name order-form-select-fake">
      选择优惠券
    </div>
  </div>
  <i class="bm-angle-right list-feedback"></i>
</li>

<?= $block('js') ?>
<script>
  $('.js-user-coupon').change(function () {
    var selected = $(this).find('option:selected');
    $('.js-user-coupon-name').html(selected.html());
    var fee = selected.data('amount-off');
    orders.setAmountRule('coupon', {name: '优惠券', amountOff: fee});
    orders.applyAmountRule();
  });

  // 在有优惠券的前提下，默认状态自动选择第一个优惠券
  window.onload = function() {
    if ($('.js-user-coupon option').size() > 1) {
      var $selected = $('.js-user-coupon option:eq(1)');
      var selectValue = $selected.val();
      $('.js-user-coupon').val(selectValue);
      $('.js-user-coupon-name').html($selected.html());
      var fee = $selected.data('amount-off');
      orders.setAmountRule('coupon', {name: '优惠券', amountOff: fee});
      orders.applyAmountRule();
    }
  };
</script>
<?= $block->end() ?>
