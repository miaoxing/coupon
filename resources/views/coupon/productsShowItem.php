<div class="js-coupon-container"></div>

<?= $block->js() ?>
<script>
  $.get($.url('coupons/get-by-product', {productId: <?= $product['id'] ?>})).then(function (res) {
    $('.js-coupon-container').html(res);
  });
</script>
<?= $block->end() ?>
