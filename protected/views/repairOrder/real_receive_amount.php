<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单List'=>array('index'),
	$model->repair_order_id=>array('view','id'=>$model->repair_order_id),
	'录入结算',
);

$this->menu=array(
	array('label'=>'维修单List', 'url'=>array('index')),
	array('label'=>'新建维修单', 'url'=>array('create')),
	array('label'=>'查看', 'url'=>array('view', 'id'=>$model->repair_order_id)),
	array('label'=>'维修单管理项', 'url'=>array('admin')),
);
?>

<h1>实际结算金额: <?php echo $model->repair_order_id; ?></h1>

<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */
/* @var $form CActiveForm */
?>

<div class="form" xmlns="http://www.w3.org/1999/html">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'repair-order-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->labelEx($model,'need_receive_amount'); ?>
        <?php echo $form->textField($model,'need_receive_amount',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'need_receive_amount'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'real_receive_amount'); ?>
        <?php echo $form->textField($model,'real_receive_amount',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'real_receive_amount'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新建' : '保存',
            array("id" => "repairOrderCommit")); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->