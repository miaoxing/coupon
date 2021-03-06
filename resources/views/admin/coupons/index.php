<?php

$view->layout();
$wei->page->addAsset('comps/jasny-bootstrap/dist/css/jasny-bootstrap.min.css');
?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/admin/css/filter.css') ?>"/>
<?= $block->end() ?>

<?= $block('header-actions') ?>
<a class="btn btn-success" href="<?= $url('admin/coupons/new') ?>">增加优惠券</a>
<?php if (wei()->setting('coupon.enableBatchSend')) { ?>
  <form class="js-import-form form-horizontal d-inline-block" method="post" role="form">
    <div class="js-excel-fileinput excel-fileinput fileinput fileinput-new" data-provides="fileinput">
        <span class="btn btn-secondary btn-file">
          <span class="fileinput-new">批量发放优惠券</span>
            <input type="file" name="file">
        </span>
      <a href="<?= $asset('plugins/coupon/docs/批量发放优惠券模板.xlsx') ?>" class="btn btn-link">
        下载范例
      </a>
    </div>
  </form>
<?php } ?>
<?= $block->end() ?>

<div class="row">

  <div class="col-12">
    <div class="table-responsive">
      <form class="js-coupon-form form-horizontal filter-form" role="form">
        <div class="well">
          <div class="form-group">
            <label class="col-md-1 control-label" for="created-at">创建时间：</label>

            <div class="col-md-3">
              <input type="text" class="js-range-date form-control" id="created-at">
              <input type="hidden" class="js-start-date" name="start_date">
              <input type="hidden" class="js-end-date" name="end_date">
            </div>

          </div>

          <div class="clearfix form-group">
            <div class="offset-md-1 col-md-6">
              <button class="js-user-filter btn btn-primary" type="submit">
                查询
              </button>
            </div>
          </div>
        </div>
      </form>

      <table id="coupon-list" class="table table-bordered table-hover table-center">
        <thead>
        <tr>
          <?php if ($req['userlist'] || $req['groupId']) : ?>
            <th></th>
          <?php endif ?>
          <th class="t-2">编号</th>
          <th class="t-4">图片</th>
          <th>名称</th>
          <th class="t-3">金额</th>
          <th class="t-4">限制金额</th>
          <th class="t-4">库存数量</th>
          <th class="t-6">有效期</th>
          <th class="t-4">领取人数</th>
          <th class="t-4">核销人数</th>
          <th class="t-2">顺序</th>
          <th class="t-6">创建时间</th>
          <th class="t-2">启用</th>
          <th class="t-7">操作</th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
      </table>
      <?php if ($req['userlist'] || $req['groupId']) : ?>
        <div class="well">
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
  <?php $event->trigger('adminCouponsViewActions') ?>
  <a href="<%= $.url('admin/coupon-stats/show', {couponId: id}) %>">
    统计
  </a>
  <a href="<%= $.url('coupons/%s', id) %>">
    查看
  </a>
  <a href="<%= $.url('admin/coupons/%s/edit', id) %>">
    编辑
  </a>
  <a class="text-danger delete-record" href="javascript:" data-href="<%= $.url('admin/coupons/delete', {id: id}) %>"
    title="删除">
    删除
  </a>
</script>

<?php require $view->getFile('@admin/admin/checkboxCol.php') ?>

<?= $block->js() ?>
<script>
  require(['plugins/admin/js/data-table', 'plugins/excel/js/excel', 'plugins/admin/js/date-range-picker',
    'comps/jasny-bootstrap/dist/js/jasny-bootstrap.min'], function () {
    var recordTable = $('#coupon-list').dataTable({
      ajax: {
        url: $.url('admin/coupons.json')
      },
      sorting: [[0, 'desc']],
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
          data: 'quantity'
        },
        {
          data: 'validDay',
          render: function (data, type, full) {
            if (full.type === 1) {
              return data + '天';
            } else {
              return full.startedUseAt.substr(0, 10) + ' ~ ' + full.endedUseAt.substr(0, 10);
            }
          }
        },
        {
          data: 'receiveUser',
          sortable: true
        },
        {
          data: 'useUser',
          sortable: true
        },
        {
          data: 'sort'
        },
        {
          data: 'createdAt'
        },
        {
          data: 'enable',
          render: function (data, type, full) {
            return template.render('checkbox-col-tpl', {
              id: full.id,
              name: 'enable',
              value: data ? '1' : '0'
            });
          }
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
    recordTable.on('click', '.toggle-status', function () {
      var $this = $(this);
      var data = {};
      data['id'] = $this.data('id');
      data[$this.attr('name')] = +!$this.data('value');
      $.post($.url('admin/coupons/update-enable'), data, function (result) {
        $.msg(result);
        recordTable.reload();
      }, 'json');
    });

    // 点击删除标签
    recordTable.on('click', '.delete-record', function () {
      var link = $(this);
      $.confirm('删除后将无法还原,确定删除?', function (result) {
        if (!result) {
          return;
        }

        $.post(link.data('href'), function (result) {
          $.msg(result, function () {
            recordTable.reload();
          });
        }, 'json');
      });
    });

    // 批量发放优惠券
    $('.js-excel-fileinput').on('change.bs.fileinput', function (event) {
      $('.js-import-form').uploadFile('admin/coupons/upload', 5, function (result) {
        if (result.code == 1) {
          $.msg(result);
          recordTable.reload();

          var $modal = $(template.render('import-suc-tpl', result));
          $modal.modal('show');

        } else {
          var $modal = $(template.render('import-error-tpl', result));
          $modal.modal('show');
        }
      });
      $(this).fileinput('clear');
    });

    $('.js-coupon-form')
      .submit(function (e) {
        recordTable.reload($(this).serialize(), false);
        e.preventDefault();
      });

    // 日期范围选择
    $('.js-range-date').daterangepicker({
      format: 'YYYY-MM-DD',
      separator: ' ~ '
    }, function (start, end) {
      $('.js-start-date').val(start.format(this.format));
      $('.js-end-date').val(end.format(this.format));
      this.element.trigger('change');
    });
  });

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
      url: '<?= wei()->url('admin/coupons/sendUser')?>',
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
</script>
<?= $block->end() ?>
<?php require $this->getFile('@excel/admin/error-modal.php') ?>
<?php require $this->getFile('@excel/admin/suc-modal.php') ?>
