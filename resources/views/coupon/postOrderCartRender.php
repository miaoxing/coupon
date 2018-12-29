<li class="list-item order-form-group has-feedback list-over-fix">
  <label for="userCouponId">优惠券</label>
  <div class="order-form-col text-right">
    <select class="js-user-coupon order-form-select" name="userCouponId" id="userCouponId">
    </select>
    <div class="js-user-coupon-name order-form-select-fake">
      选择优惠券
    </div>
  </div>
  <i class="bm-angle-right list-feedback"></i>
</li>

<script type="text/html" id="coupon-tpl">
  <option value=""><%= data.length ? '选择优惠券' : '暂无可用优惠券' %></option>
  <% $.each(data, function (i, userCoupon) { %>
  <option data-amount-off="<%= userCoupon.reduceCost %>"
    value="<%= userCoupon.id %>"><%= userCoupon.name %></option>
  <% }) %>
</script>

<!--<li class="js-coupon-container list-item order-form-group has-feedback list-over-fix" style="padding: 11px 30px 11px 0;"></li>-->

<?= $block->js() ?>
<script>
  $('.js-user-coupon').change(function () {
    var selected = $(this).find('option:selected');
    $('.js-user-coupon-name').html(selected.html());
    var fee = selected.data('amount-off');
    orders.setAmountRule('coupon', {name: '优惠券', amountOff: fee});
    orders.applyAmountRule();
  });

  require(['comps/artTemplate/template.min'], function (template) {
    $.getJSON($.url('user-coupons/get-by-carts', {cartIds: <?= json_encode($carts->getAll('id')) ?>}))
      .then(function (ret) {
        if (ret.code !== 1) {
          $.msg(ret);
          return;
        }

        $('.js-user-coupon').html(template.render('coupon-tpl', ret));

        // 自动选择首个
        if (ret.data.length) {
          $('.js-user-coupon').val(ret.data[0].id).change();
        }
      });
  });

  $.get($.url('product-coupons/order')).then(function (res) {
    $('.js-coupon-container').html(res);
  });
</script>
<?= $block->end() ?>
