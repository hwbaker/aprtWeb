<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单List'=>array('index'),
	'新建',
);

$this->menu=array(
	array('label'=>'维修单List', 'url'=>array('index')),
	array('label'=>'维修单管理项', 'url'=>array('admin')),
);
?>

<h1>新建维修单</h1>

<?php $this->renderPartial('_form', array('model'=>$model,
                                                'contentProject' => $contentProject,
                                                'contentMaterial' => $contentMaterial
                                            )
); ?>