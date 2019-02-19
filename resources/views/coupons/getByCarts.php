<a href="javascript:;" class="js-coupon-modal-show text-sm">有可用优惠券领取</a>

<div class="js-coupon-modal modal-bottom modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title text-center">
          领券
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-fluid">
        <div class="coupon-container">
          <?php require $view->getFile('@coupon/coupons/_list.php') ?>
        </div>
      </div>
      <div class="modal-footer modal-footer-fluid flex">
        <button type="button" class="btn btn-primary btn-fluid flex-grow-1" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<?= $block->get('css') ?>
<?= $block->get('js') ?>
<script>
  $('.js-coupon-modal-show').click(function () {
    $('.js-coupon-modal').modal('show');
  });
</script>
