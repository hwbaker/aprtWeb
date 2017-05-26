<?php
/* @var $this RepairOrderController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'维修单List',
);

$this->menu=array(
	array('label'=>'新建维修单', 'url'=>array('create')),
	array('label'=>'维修单管理项', 'url'=>array('admin')),
);
?>

<h1>维修单List</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
