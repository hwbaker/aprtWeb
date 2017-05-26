<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单List'=>array('index'),
	$model->repair_order_id=>array('view','id'=>$model->repair_order_id),
	'更新',
);

$this->menu=array(
	array('label'=>'维修单List', 'url'=>array('index')),
	array('label'=>'新建维修单', 'url'=>array('create')),
	array('label'=>'查看', 'url'=>array('view', 'id'=>$model->repair_order_id)),
	array('label'=>'维修单管理项', 'url'=>array('admin')),
);
?>

<h1>更新维修单 <?php echo $model->repair_order_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,
                                                'contentProject' => $contentProject,
                                                'contentMaterial' => $contentMaterial
                                            )
); ?>