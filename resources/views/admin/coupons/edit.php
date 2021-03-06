<?php $view->layout() ?>

<?= $block('header-actions') ?>
<a class="btn btn-secondary" href="<?= $url('admin/coupons') ?>">返回列表</a>
<?= $block->end() ?>

<div class="row">
  <div class="col-12">
    <!-- PAGE CONTENT BEGINS -->
    <form id="coupon-form" class="form-horizontal" method="post" role="form">

      <div class="form-group">
        <label class="col-lg-2 control-label" for="name">
          <span class="text-warning">*</span>
          名称
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="name" id="name">
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="pic">
          <span class="text-warning">*</span>
          图片
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-pic" id="pic" name="pic" required>
        </div>

        <label for="pic" class="col-lg-6 help-text">
          图片长宽比例不限<br>建议所有图片长宽比一致，宽度大于等于200像素
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="money">
          <span class="text-warning">*</span>
          金额
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="money" id="money">
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="quantity">
          <span class="text-warning">*</span>
          库存数量
        </label>

        <div class="col-lg-4">
          <div class="input-group">
            <input type="text" class="form-control" name="quantity" id="quantity" data-rule-required="true">
            <span class="input-group-append"><span class="input-group-text">份</span></span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="get-limit">
          <span class="text-warning">*</span>
          领券限制
        </label>

        <div class="col-lg-4">
          <div class="input-group">
            <input type="text" class="form-control" name="getLimit" id="get-limit" value="1" data-rule-required="true">
            <span class="input-group-append"><span class="input-group-text">份</span></span>
          </div>
        </div>
        <label for="get-limit" class="col-lg-6 help-text">每个用户领券上限，0为不限次数</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="dateType">
          <span class="text-warning">*</span>
          有效期
        </label>

        <div class="col-lg-4">
          <label class="radio-inline">
            <input type="radio" class="js-toggle-display" name="dateType" value="1"
              data-target=".js-date-type-fixed-date"
              data-reverse-target=".js-date-type-fixed-time"
              data-value=":checked"> 固定天数
          </label>
          <label class="radio-inline">
            <input type="radio" class="js-toggle-display" name="dateType" value="2"
              data-target=".js-date-type-fixed-time"
              data-reverse-target=".js-date-type-fixed-date"
              data-value=":checked"> 固定日期
          </label>
        </div>
      </div>

      <div class="js-date-type-fixed-date form-group">
        <label class="col-lg-2 control-label" for="valid-day">
          <span class="text-warning">*</span>
          固定天数
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="validDay" id="valid-day" data-rule-required="true">
        </div>
      </div>

      <div class="js-date-type-fixed-time">
        <div class="form-group">
          <label class="col-lg-2 control-label" for="startedUseAt">
            <span class="text-warning">*</span>
            开始使用时间
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control js-start-use-time" name="startedUseAt">
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="endedUseAt">
            <span class="text-warning">*</span>
            结束使用时间
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control js-end-use-time" name="endedUseAt">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="startedAt">
          开始领取时间
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-start-time" name="startedAt">
        </div>

        <label for="startTime" class="col-lg-6 help-text">不填代表不限制，长期有效</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="endedAt">
          结束领取时间
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-end-time" name="endedAt">
        </div>

        <label for="endTime" class="col-lg-6 help-text">不填代表不限制，长期有效</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="limit-amount">
          最低使用金额
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="limitAmount" id="limit-amount">
        </div>
        <label for="limit-amount" class="col-lg-6 help-text">不填、0都代表不限制</label>
      </div>

      <?php require $this->getFile('@product/admin/products/chooseProduct.php') ?>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="rule">
          规则
        </label>

        <div class="col-lg-4">
          <textarea class="form-control" name="rule" id="rule"></textarea>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="remark">
          备注
        </label>

        <div class="col-lg-4">
          <textarea class="form-control" name="remark" id="remark"></textarea>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="redirect-link-to">
          领取后跳转链接
        </label>

        <div class="col-lg-4">
          <p class="form-control-plaintext" id="redirect-link-to"></p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="styles-bg-color">
          优惠券颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-bg-color" name="styles[bgColor]" id="styles-bg-color"
            value="<?= $coupon['styles']['bgColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="styles-bg-color">
          留空使用默认颜色
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="styles-btn-color">
          按钮颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-btn-color" name="styles[btnColor]" id="styles-btn-color"
            value="<?= $coupon['styles']['btnColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="styles-btn-color">
          留空使用默认颜色
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="styles-btn-font-color">
          按钮文字颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-btn-font-color" name="styles[btnFontColor]"
            id="styles-btn-font-color" value="<?= $coupon['styles']['btnFontColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="styles-btn-font-color">
          留空使用默认颜色
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="sort">
          顺序
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="sort" id="sort">
        </div>

        <label class="col-lg-6 help-text" for="sort">
          大的显示在前面,按从大到小排列.
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="enable">
          状态
        </label>

        <div class="col-lg-4">
          <label class="radio-inline">
            <input type="radio" name="enable" value="1"> 启用
          </label>
          <label class="radio-inline">
            <input type="radio" name="enable" value="0"> 禁用
          </label>

        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="listing">
          列表显示
        </label>

        <div class="col-lg-4">
          <label class="radio-inline">
            <input type="radio" name="listing" value="1"> 显示
          </label>
          <label class="radio-inline">
            <input type="radio" name="listing" value="0"> 不显示
          </label>

        </div>
      </div>

      <?php $event->trigger('adminCouponsEditRender', [$coupon]) ?>

      <input type="hidden" name="id" id="id"/>

      <div class="clearfix form-actions form-group">
        <div class="offset-sm-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check"></i>
            提交
          </button>
          &nbsp; &nbsp; &nbsp;
          <a class="btn btn-secondary" href="<?= $url('admin/coupons') ?>">
            <i class="fa fa-undo"></i>
            返回列表
          </a>
        </div>
      </div>
    </form>
  </div>
  <!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<?= $block->js() ?>
<script>
  require([
    'plugins/link-to/js/link-to', 'plugins/admin/js/form', 'ueditor', 'plugins/app/js/validation', 'plugins/admin/js/spectrum', 'plugins/admin/js/range-date-time-picker',
    'plugins/admin/js/image-upload',
    'plugins/app/libs/jquery-toggle-display/jquery-toggle-display',
  ], function (linkTo) {
    var coupon = <?= $coupon->toJson() ?>;
    // TODO populate
    coupon.enable = coupon.enable ? '1' : '0';
    coupon.listing = coupon.listing ? '1' : '0';
    $('#coupon-form')
      .loadJSON(coupon)
      .ajaxForm({
        url:  $.url('admin/coupons/update'),
        dataType: 'json',
        beforeSubmit: function (arr, $form) {
          return $form.valid();
        },
        success: function (result) {
          $.msg(result, function () {
            if (result.code === 1) {
              window.location = $.url('admin/coupons');
            }
          });
        }
      });

    // 初始化商品选择器
    $.initChooseProduct(coupon.categoryIds);

    // 选择颜色
    $('.js-bg-color').spectrum();
    $('.js-btn-color').spectrum();
    $('.js-btn-font-color').spectrum();

    // 选择时间
    // 开始结束时间使用日期时间范围选择器
    $('.js-start-time, .js-end-time').rangeDateTimePicker({
      showSecond: true,
      dateFormat: 'yy-mm-dd',
      timeFormat: 'HH:mm:ss'
    });

    // 开始结束时间使用日期时间范围选择器
    $('.js-start-use-time, .js-end-use-time').rangeDateTimePicker({
      showSecond: true,
      dateFormat: 'yy-mm-dd',
      timeFormat: 'HH:mm:ss'
    });

    // 跳转后直接跳转的链接
    linkTo.init({
      $el: $('#redirect-link-to'),
      name: 'redirectLinkTo',
      data: coupon.redirectLinkTo,
      hide: {
        keyword: true
      }
    });

    // 点击选择图片
    $('.js-pic').imageUpload();

    $('.js-toggle-display').toggleDisplay();
  });
</script>
<?= $block->end() ?>
<?php require $view->getFile('@link-to/link-to/link-to.php') ?>
