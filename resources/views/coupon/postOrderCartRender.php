<style>
  .coupon-list-item > label {
    vertical-align: middle;
  }

  .coupon-list-item .bm-angle-right {
    right: -15px;
  }

  .coupon-select-container {
    position: relative;
  }

  .coupon-select-container .order-form-select-fake {
    right: 10px;
  }
</style>

<li class="list-item order-form-group coupon-list-item has-feedback">
  <label for="userCouponId">优惠券</label>
  <div class="order-form-col">
    <div class="coupon-select-container">
      <select class="js-user-coupon order-form-select" name="userCouponId" id="userCouponId">
      </select>
      <div class="js-user-coupon-name order-form-select-fake"></div>
      <i class="bm-angle-right list-feedback"></i>
    </div>
    <div class="js-coupon-container text-right"></div>
  </div>
</li>

<script type="text/html" id="coupon-tpl">
  <option value="" selected><%= data.length ? '请选择优惠券' : '暂无可用优惠券' %></option>
  <% $.each(data, function (i, userCoupon) { %>
  <option data-amount-off="<%= userCoupon.reduceCost %>"
    value="<%= userCoupon.id %>"><%= userCoupon.name %>
  </option>
  <% }) %>
</script>

<?= $block->js() ?>
<script>
  var $userCoupon = $('.js-user-coupon');
  $userCoupon.change(function () {
    var selected = $(this).find('option:selected');
    var fee = selected.data('amount-off');
    $('.js-user-coupon-name').html(selected.html())
    orders.setAmountRule('coupon', {name: '优惠券', amountOff: fee});
    orders.applyAmountRule();
  });

  require(['comps/artTemplate/template.min'], function (template) {
    var showNewOrderCoupons = <?= json_encode(wei()->coupon->showNewOrderCoupons) ?>;

    function loadUserCoupon(receiveCoupon) {
      var cartIds = <?= json_encode($carts->getAll('id')) ?>;
      $.getJSON($.url('user-coupons/get-by-carts', {cartIds: cartIds})).then(function (ret) {
        if (ret.code !== 1) {
          $.msg(ret);
          return;
        }

        $userCoupon.html(template.render('coupon-tpl', ret));

        // 自动选择首个
        if (ret.data.length) {
          $userCoupon.val(ret.data[0].id).change();
        } else {
          $('.js-user-coupon-name').html('暂无可用优惠券')
        }

        if (showNewOrderCoupons && ret.data.length === 0 && receiveCoupon) {
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
