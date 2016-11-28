<?php $view->layout() ?>
<div class="page-header">
  <div class="pull-right">
    <a class="btn" href="<?= $url('admin/coupon/index') ?>">返回列表</a>
  </div>
  <h1>
    微官网
    <small>
      <i class="fa fa-angle-double-right"></i>
      优惠券列表
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
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
          <div class="input-group">
            <input type="text" class="form-control js-pic" id="pic" name="pic">
                        <span class="input-group-btn">
                            <button id="select-thumb" class="btn btn-white" type="button">
                <i class="fa fa-calendar bigger-110"></i>
                选择图片
              </button>
                        </span>
          </div>
        </div>
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
        <label class="col-lg-2 control-label" for="getLimit">
          <span class="text-warning">*</span>
          领券限制
        </label>

        <div class="col-lg-4">
          <div class="input-group">
            <input type="text" class="form-control" name="getLimit" id="getLimit" value="1" data-rule-required="true">
            <span class="input-group-addon">份</span>
          </div>
        </div>
        <label for="getLimit" class="col-lg-6 help-text">每个用户领券上限，0为不限次数</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="validDay">
          <span class="text-warning">*</span>
          有效期(天)
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="validDay" id="validDay" data-rule-required="true">
        </div>

        <label for="startTime" class="col-lg-6 help-text">领取优惠券后的有效时间</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="startTime">
          开始时间
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-start-time" name="startTime" style="position: relative;">
        </div>

        <label for="startTime" class="col-lg-6 help-text">不填代表不限制，长期有效</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="endTime">
          结束时间
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-end-time" name="endTime" style="position: relative;">
        </div>

        <label for="startTime" class="col-lg-6 help-text">不填代表不限制，长期有效</label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="limitAmount">
          最低使用金额
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="limitAmount" id="limitAmount">
        </div>
        <label for="limitAmount" class="col-lg-6 help-text">不填、0都代表不限制</label>
      </div>

      <?php require $this->getFile('promotion:admin/promotions/chooseProduct.php') ?>

      <div class="form-group" style="display:none;">
        <label class="col-lg-2 control-label" for="useScene">
          使用场景
        </label>

        <div class="col-lg-4">
          <select id="useScene" name="useScene" class="form-control">
            <option value="0">不限</option>
            <option value="1">限时特价</option>
            <option value="2">积分换购</option>
          </select>
        </div>
      </div>

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
        <label class="col-lg-2 control-label" for="stylesBgColor">
          优惠券颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-bg-color" name="styles[bgColor]" id="stylesBgColor"
                 value="<?= $coupon['styles']['bgColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="stylesBgColor">
          留空使用默认颜色
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="stylesBtnColor">
          按钮颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-btn-color" name="styles[btnColor]" id="stylesBtnColor"
                 value="<?= $coupon['styles']['btnColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="stylesBtnColor">
          留空使用默认颜色
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="stylesBtnFontColor">
          按钮文字颜色
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control js-btn-font-color" name="styles[btnFontColor]" id="stylesBtnFontColor" value="<?= $coupon['styles']['btnFontColor'] ?>">
        </div>
        <label class="col-lg-6 help-text" for="stylesBtnFontColor">
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
            <input type="radio" name="enable" class="enable" value="1"> 启用
          </label>
          <label class="radio-inline">
            <input type="radio" name="enable" class="enable" value="0"> 禁用
          </label>

        </div>
      </div>

      <input type="hidden" name="id" id="id"/>

      <div class="clearfix form-actions form-group">
        <div class="col-sm-offset-2">
          <button class="btn btn-info" type="submit">
            <i class="fa fa-check"></i>
            提交
          </button>
          &nbsp; &nbsp; &nbsp;
          <a class="btn" href="<?= $url('admin/coupon/index') ?>">
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

<?= $block('js') ?>
<script>
  require(['form', 'ueditor', 'validator', 'assets/spectrum', 'assets/dateTimePicker'], function () {
    var coupon = <?= $coupon->toJson() ?>;
    $('#coupon-form')
      .loadJSON(coupon)
      .ajaxForm({
        url: '<?= $url('admin/coupon/update') ?>',
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
          return $form.valid();
        },
        success: function (result) {
          $.msg(result, function () {
            if (result.code > 0) {
              window.location = $.url('admin/coupon/index');
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

    // 点击选择图片
    $('.js-pic').imageInput();
  });
</script>
<?= $block->end() ?>
