<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单List'=>array('index'),
	'维修单管理项',
);

$this->menu=array(
	array('label'=>'维修单管理项', 'url'=>array('index')),
	array('label'=>'新建维修单', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#repair-order-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>维修单管理项</h1>

<p>
你可以使用查询操作符 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 进行搜索
</p>

<?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
));
?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'repair-order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'repair_order_id',
//		'customer_name',
//		'customer_mobile',
//		'customer_address',
        array(
            'name' => 'date_in_factory',
            'value' => 'date("Y-m-d", strtotime($data->date_in_factory))',
        ),
        array(
            'name' => 'date_out_factory',
            'value' => '$data->date_out_factory != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($data->date_out_factory)) : ""',
        ),
		'car_number',
		'car_type',
//		'car_mileage',
//		'car_repaired_main',
        array(
            'name' => 'pay_type',
            'value' => 'isset(RepairOrder::$payTypeOption[$data->pay_type]) ? RepairOrder::$payTypeOption[$data->pay_type] : $data->pay_type',
            'filter' => RepairOrder::$payTypeOption,
        ),
//        array(
//            'name' => 'invoice_type',
//            'value' => 'isset(RepairOrder::$invoiceTypeOptions[$data->invoice_type]) ? RepairOrder::$invoiceTypeOptions[$data->invoice_type] : $data->invoice_type',
//            'filter' => RepairOrder::$invoiceTypeOptions,
//        ),
		'need_receive_amount',
		'real_receive_amount',
        array(
            'name' => 'create_time',
            'value' => 'date("Y-m-d", strtotime($data->create_time))',
        ),
        array(
            'name' => 'status',
            'value' => 'isset(RepairOrder::$statusOptions[$data->status]) ? RepairOrder::$statusOptions[$data->status] : $data->status',
            'filter' => RepairOrder::$statusOptions,
        ),
//		array(
//			'class'=>'CButtonColumn',
//		),
        array(
            'class' => 'AprtPHPColumn',
            'header' => '操作',
            'content' => '$data->getActionLink()'
        ),
	),
)); ?>
