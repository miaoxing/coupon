<div class="js-coupon-container"></div>

<?= $block->js() ?>
<script>
  $.get($.url('product-coupons')).then(function (res) {
    $('.js-coupon-container').html(res);
  });
</script>
<?= $block->end() ?>
