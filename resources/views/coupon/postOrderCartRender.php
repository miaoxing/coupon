<style>
  .coupon-list-item > label {
    vertical-align: middle;
  }

  .coupon-list-item .bm-angle-right {
    right: -15px;
  }

  .coupon-list-item .order-form-select {
    text-align-last: right;
    text-indent: 0;
  }

  .coupon-select-container {
    position: relative;
  }
</style>

<li class="list-item order-form-group coupon-list-item has-feedback">
  <label for="userCouponId">优惠券</label>
  <div class="order-form-col">
    <div class="coupon-select-container">
      <select class="js-user-coupon order-form-select" name="userCouponId" id="userCouponId" dir="rtl">
      </select>
      <i class="bm-angle-right list-feedback"></i>
    </div>
    <div class="js-coupon-container text-right"></div>
  </div>
</li>

<script type="text/html" id="coupon-tpl">
  <option value=""><%= data.length ? '选择优惠券' : '暂无可用优惠券' %></option>
  <% $.each(data, function (i, userCoupon) { %>
  <option data-amount-off="<%= userCoupon.reduceCost %>"
    value="<%= userCoupon.id %>"><%= userCoupon.name %>
  </option>
  <% }) %>
</script>

<?= $block->js() ?>
<script>
  $('.js-user-coupon').change(function () {
    var selected = $(this).find('option:selected');
    var fee = selected.data('amount-off');
    orders.setAmountRule('coupon', {name: '优惠券', amountOff: fee});
    orders.applyAmountRule();
  });

  require(['comps/artTemplate/template.min'], function (template) {
    function loadUserCoupon(receiveCoupon) {
      var cartIds = <?= json_encode($carts->getAll('id')) ?>;
      $.getJSON($.url('user-coupons/get-by-carts', {cartIds: cartIds})).then(function (ret) {
        if (ret.code !== 1) {
          $.msg(ret);
          return;
        }

        $('.js-user-coupon').html(template.render('coupon-tpl', ret));

        // 自动选择首个
        if (ret.data.length) {
          $('.js-user-coupon').val(ret.data[0].id).change();
        }

        if (ret.data.length === 0 && receiveCoupon) {
          $.get($.url('coupons/get-by-carts', {cartIds: cartIds})).then(function (res) {
            $('.js-coupon-container').html(res);
          });
          $(document).on('afterGetCoupon', function () {
            loadUserCoupon(false);
          });
        }
      });
    }
    loadUserCoupon(true);
  });
</script>
<?= $block->end() ?>
