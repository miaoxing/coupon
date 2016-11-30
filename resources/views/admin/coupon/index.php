<?php $view->layout() ?>

<div class="page-header">
  <div class="pull-right">
    <form id="shop-upload-form" class="form-horizontal" method="post" role="form">
      <a class="btn btn-success" href="<?= $url('admin/coupon/new') ?>">增加优惠券</a>
    </form>
  </div>
  <h1>
    微商城
    <small>
      <i class="fa fa-angle-double-right"></i>
      优惠券列表
    </small>
  </h1>
</div>
<!-- /.page-header -->
<div class="row">

  <div class="col-xs-12">
    <div class="table-responsive">
      <table id="coupon-list" class="table table-bordered table-hover table-center">
        <thead>
        <tr>
          <?php if ($req['userlist'] || $req['groupId']) : ?>
            <th></th>
          <?php endif; ?>
          <th class="t-3">编号</th>
          <th class="t-5">图片</th>
          <th class="t-10">名称</th>
          <th class="t-3">金额</th>
          <th class="t-5">限制金额</th>
          <th class="t-5">有效期(日)</th>
          <th class="t-10">规则</th>
          <th class="t-10">备注</th>
          <th class="t-3">启用</th>
          <th class="t-3">顺序</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
      </table>
      <?php if ($req['userlist'] || $req['groupId']) : ?>
        <div class="well form-well">
          <form class="form-inline" role="form">
            <div class="form-group">
              <a class="btn btn-info" href="javascript:sendUserCoupon();">发送优惠券</a>
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- PAGE detail ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<script id="table-actions" type="text/html">
  <a href="<%= $.url('coupon/%s', id) %>">
    领取
  </a>
  <a href="<%= $.url('admin/coupon-stats/show', {couponId: id}) %>">
    统计
  </a>
  <a href="javascript:sendAll(<%= id %>);" title="发送">
    发送
  </a>
  <a href="<?= wei()->url('admin/coupon/edit') ?>?id=<%= id %>">
    编辑
  </a>
  <a class="text-danger delete-record" href="javascript:;" data-href="<%= $.url('admin/coupon/delete', {id: id}) %>" title="删除">
    删除
  </a>
</script>

<?php require $view->getFile('admin:admin/checkboxCol.php') ?>

<?= $block('js') ?>
<script>
  require(['dataTable'], function () {
    getCouponList();
  });

  function getCouponList() {
    var recordTable = $('#coupon-list').dataTable({
      "ajax": {
        url: $.url('admin/coupon.json')
      },
      columns: [
        <?php if ($req['userlist'] || $req['groupId']) :?>
        {
          data: 'id',
          render: function (data) {
            return '<input type="checkbox" name="c_id" value="' + data + '"/>';
          }
        },
        <?php endif; ?>
        {
          data: 'id'
        },
        {
          data: 'pic',
          render: function (data) {
            return "<img width='50' src=" + data + ">";
          }
        },
        {
          data: 'name'
        },
        {
          data: 'money'
        },
        {
          data: 'limitAmount'
        },
        {
          data: 'validDay'
        },
        {
          data: 'rule'
        },
        {
          data: 'remark'
        },
        {
          data: 'enable',
          render: function (data, type, full) {
            return template.render('checkbox-col-tpl', {
              id: full.id,
              name: 'enable',
              value: data
            });
          }
        },
        {
          data: 'sort'
        },
        {
          data: 'id',
          render: function (data, type, full) {
            return template.render('table-actions', full);
          }
        }
      ]
    });

    // 切换状态
    recordTable.on('click', '.toggle-status', function(){
      var $this = $(this);
      var data = {};
      data['id'] = $this.data('id');
      data[$this.attr('name')] = +!$this.data('value');
      $.post($.url('admin/coupon/update-enable'), data, function(result){
        $.msg(result);
        recordTable.reload();
      }, 'json');
    });

    // 点击删除标签
    recordTable.on('click', '.delete-record', function () {
      var link = $(this);
      $.confirm('删除后将无法还原,确认删除?', function () {
        $.post(link.data('href'), function (result) {
          $.msg(result, function(){
            recordTable.reload();
          });
        }, 'json');
      });
    });
  }
</script>

<script>
  function sendUserCoupon() {
    var couponList = [];
    $('input[name="c_id"]:checked').each(function () {
      couponList.push($(this).val());
    });
    if (couponList == '') {
      alert('请选择优惠券');
      return;
    }
    $.ajax({
      url: '<?= wei()->url('admin/coupon/sendUser')?>',
      type: 'post',
      data: {couponlist: couponList.toString(), userlist: '<?= $req['userlist']; ?>', groupId: '<?=$req['groupId']?>'},
      dataType: 'json',
      success: function (r) {
        alert(r.message);
        if (r.code == 1) {
          window.location.href = '<?= wei()->url('admin/user/index')?>';
        }
      }
    });
  }

  function sendAll(couponId) {
    if (confirm("确定全部用户发送？")) {
      $.ajax({
        url: '<?= wei()->url('admin/coupon/sendAll')?>',
        type: 'post',
        data: {couponId: couponId},
        dataType: 'json',
        success: function (r) {
          alert(r.message);
        }
      });
    }
  }
</script>
<?= $block->end() ?>
