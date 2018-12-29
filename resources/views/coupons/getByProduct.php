<dl class="product-item border-top">
  <dt>领券：</dt>
  <dd>
    <a class="js-coupon-modal-show" href="javascript:;">
      <?= $coupons[0]->name  ?><?= $coupons->length() > 1 ? '等' : '' ?>
    </a>
  </dd>
</dl>

<div class="js-coupon-modal modal-bottom modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-title text-center">
          领券
        </div>
      </div>
      <div class="modal-body modal-body-fluid">
        <?php require $view->getFile('@coupon/coupons/_list.php') ?>
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