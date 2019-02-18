<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('assets/admin/stat.css') ?>"/>
<?= $block->end() ?>

<!-- /.page-header -->
<div class="page-header">
  <div class="pull-right">
    <a class="btn btn-default" href="<?= $url('admin/coupons') ?>">返回列表</a>
  </div>
  <h1>
    优惠券统计
  </h1>
</div>

<div class="row">
  <div class="col-12">

    <div class="well well-sm bigger-110">
      优惠券名称: <?= $e($coupon['name']) ?>
      <span class="pull-right small">新数据每分钟更新,最后更新时间: <?= $lastUpdateTime ?: '暂无' ?></span>
    </div>

    <div class="well well-sm">
      <form class="js-chart-form form-inline">
        <div class="form-group">
          <label class="control-label" for="range-date">日期范围</label>
          <input type="text" class="js-range-date form-control text-center input-large" id="range-date"
            value="<?= $e($startDate . ' ~ ' . $endDate) ?>">
          <input type="hidden" class="js-start-date" name="startDate">
          <input type="hidden" class="js-end-date" name="endDate">
        </div>
      </form>
    </div>

    <h5 class="stat-title">趋势图</h5>

    <ul class="js-chart-tabs nav tab-underline">
      <li role="presentation" class="nav-item">
        <a href="#receive" class="nav-link active" aria-controls="receive" role="tab" data-toggle="tab">新增领取</a>
      </li>
      <li role="presentation" class="nav-item">
        <a href="#total-receive" class="nav-link" aria-controls="receive" role="tab" data-toggle="tab">累积领取</a>
      </li>
      <li class="nav-item">
        <a href="#use" class="nav-link" aria-controls="use" role="tab" data-toggle="tab">新增核销</a>
      </li>
      <li class="nav-item">
        <a href="#total-use" class="nav-link" aria-controls="use" role="tab" data-toggle="tab">累积核销</a>
      </li>
    </ul>
    <div class="tab-content m-t border-0">
      <div role="tabpanel" class="js-chart-pane tab-pane text-center active" id="receive">
        加载中...
      </div>
      <div role="tabpanel" class="js-chart-pane tab-pane" id="total-receive"></div>
      <div role="tabpanel" class="js-chart-pane tab-pane" id="use"></div>
      <div role="tabpanel" class="js-chart-pane tab-pane" id="total-use"></div>
    </div>

    <hr>

    <h5 class="stat-title">详细数据</h5>

    <table class="js-stat-table table table-center table-head-bordered">
      <thead>
      <tr>
        <th rowspan="2">时间</th>
        <th colspan="2">新增领取</th>
        <th colspan="2">累积领取</th>
        <th colspan="2">新增核销</th>
        <th colspan="2">累积核销</th>
      </tr>
      <tr>
        <th>人数</th>
        <th>次数</th>
        <th>人数</th>
        <th>次数</th>
        <th>人数</th>
        <th>次数</th>
        <th>人数</th>
        <th>次数</th>
      </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

  </div>
  <!-- /col -->
</div>
<!-- /row -->

<?= $block->js() ?>
<script>
  require(['plugins/stat/js/stat', 'highcharts',
    'form', 'jquery-deparam', 'dataTable', 'daterangepicker'
  ], function (stat) {
    // 渲染底部表格
    var $statTable = $('.js-stat-table').dataTable({
      dom: 't',
      ajax: null,
      processing: false,
      serverSide: false,
      displayLength: 99999,
      columnDefs: [{
        targets: ['_all'],
        sortable: true
      }],
      columns: [
        {
          data: 'statDate'
        },
        {
          data: 'receiveUser'
        },
        {
          data: 'receiveCount'
        },
        {
          data: 'totalReceiveUser'
        },
        {
          data: 'totalReceiveCount'
        },
        {
          data: 'useUser'
        },
        {
          data: 'useCount'
        },
        {
          data: 'totalUseUser'
        },
        {
          data: 'totalUseCount'
        }
      ]
    });

    // 所有图表的配置
    var charts = [
      {
        id: 'receive',
        series: [
          {
            name: '新增领取人数',
            dataSource: 'receiveUser'
          },
          {
            name: '新增领取次数',
            dataSource: 'receiveCount'
          }
        ]
      },
      {
        id: 'total-receive',
        series: [
          {
            name: '累积领取人数',
            dataSource: 'totalReceiveUser'
          },
          {
            name: '累积领取次数',
            dataSource: 'totalReceiveCount'
          }
        ]
      }, {
        id: 'use',
        series: [
          {
            name: '新增核销人数',
            dataSource: 'useUser'
          },
          {
            name: '新增核销次数',
            dataSource: 'useCount'
          }
        ]
      }, {
        id: 'total-use',
        series: [
          {
            name: '累积核销人数',
            dataSource: 'totalUseUser'
          },
          {
            name: '累积核销次数',
            dataSource: 'totalUseCount'
          }
        ]
      }
    ];

    var $form = $('.js-chart-form');
    function render() {
      $.ajax({
        url: $.queryUrl('admin/coupon-stats/show.json'),
        dataType: 'json',
        data: $form.serializeArray(),
        success: function (ret) {
          if (ret.code !== 1) {
            return $.msg(ret);
          }

          stat.renderChart({
            charts: charts,
            data: ret.data
          });
          $statTable.fnClearTable();
          $statTable.fnAddData(ret.data);
        }
      });
    }
    render();

    // 更新表单时,重新渲染
    $form.update(function () {
      render();
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
</script>
<?= $block->end() ?>
