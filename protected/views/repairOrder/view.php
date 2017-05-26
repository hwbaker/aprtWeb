<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单List'=>array('index'),
	$model->repair_order_id,
);

$this->menu=array(
	array('label'=>'维修单List', 'url'=>array('index')),
	array('label'=>'新建维修单', 'url'=>array('create')),
	array('label'=>'更新维修单', 'url'=>array('update', 'id'=>$model->repair_order_id)),
//	array('label'=>'删除维修单', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->repair_order_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'维修单管理项', 'url'=>array('admin')),
    array('label'=>'录入结算', 'url'=>array('realReceiveAmount', 'id'=>$model->repair_order_id)),
    array('label'=>'标记完成', 'url'=>array('finish', 'id'=>$model->repair_order_id)),
);
?>

<h1>#<?php echo $model->repair_order_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'repair_order_id',
        array(
            'name' => 'date_in_factory',
            'value' => date("Y-m-d", strtotime($model->date_in_factory))
        ),
        array(
            'name' => 'date_out_factory',
            'value' => $model->date_out_factory != '0000-00-00 00:00:00' ? date("Y-m-d", strtotime($model->date_out_factory)) : ''
        ),
		'car_number',
		'car_type',
		'car_mileage',
		'car_repaired_main',
        'customer_name',
        'customer_mobile',
        'customer_address',
        array(
            'name' => 'pay_type',
            'value' => isset(RepairOrder::$payTypeOption[$model->pay_type]) ? RepairOrder::$payTypeOption[$model->pay_type] : $model->pay_type
        ),
        array(
            'name' => 'invoice_type',
            'value' => isset(RepairOrder::$invoiceTypeOptions[$model->invoice_type]) ? RepairOrder::$invoiceTypeOptions[$model->invoice_type] : $model->invoice_type
        ),
        'create_time',
        'update_time',
        'need_receive_amount',
        'real_receive_amount',
        'check_man',
        'settlement_man',
        array(
            'name' => 'status',
            'value' => isset(RepairOrder::$statusOptions[$model->status]) ? RepairOrder::$statusOptions[$model->status] : $model->status
        ),
	),
)); ?>

<br><br>
<div id="repair-order-grid" class="grid-view">
    <table class="detail-view">
        <thead>
        <tr>
            <th style="text-align: center">维修项目</th>
            <th style="text-align: center">金额(CNY)</a></th>
        </tr>

        </thead>
        <tbody>
            <?php
            $sumAmount = 0;
            foreach ($repairProject as $pItem) {
                $sumAmount += $pItem['project_amount'];
                echo "<tr><td>{$pItem['project_name']}</td><td>{$pItem['project_amount']}</td></tr>";
            }
            ?>
            <td colspan="2" style="font-size: 12px; font-weight:bold; color: red; text-align: right">维修项目合计:  <?php echo $sumAmount;?></td>
        </tbody>
    </table>
</div>

<br><br>
<div id="repair-order-grid" class="grid-view">
    <table class="detail-view">
        <thead>
        <tr>
            <th style="text-align: center">维修材料</th>
            <th style="text-align: center">数量</a></th>
            <th style="text-align: center">单位</a></th>
            <th style="text-align: center">单价</a></th>
            <th style="text-align: center">金额(CNY)</a></th>
        </tr>

        </thead>
        <tbody>
        <?php
        $sumAmount = 0;
        foreach ($repairMaterial as $mItem) {
            $amount = $mItem['material_count'] * $mItem['material_amount'];
            $sumAmount += $amount;
            echo "<tr><td>{$mItem['material_name']}</td>
                      <td>{$mItem['material_count']}</td>
                      <td>{$mItem['material_unit']}</td>
                      <td>{$mItem['material_amount']}</td>
                      <td>{$amount}</td>
                  </tr>";
        }
        ?>
        <td colspan="5" style="font-size: 12px; font-weight:bold; color: red; text-align: right">维修材料合计:  <?php echo $sumAmount;?></td>
        </tbody>
    </table>
</div>
