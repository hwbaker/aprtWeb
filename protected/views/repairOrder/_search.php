<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

    <div class="row">
        <label >按时间搜索</label>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'value' => date('Y-m-d', time()), //设置默认值
            'name' => 'create_time_start',
            'language' => 'zh_cn',
            'model' => $model,
            'attribute' => 'create_time_start',
            'flat' => false,
            'options' => array(
                'showAnim' => 'fadeIn',
                'showOn' => 'focus',
                'changeYear' => true,
                'changeMonth' => true,
                'buttonImageOnly' => true,
                'dateFormat' => 'yy-mm-dd',
                'yearRange' => '-30:+30',
            ),
            'htmlOptions' => array(
                'placeholder' => '开始时间',
                'readonly'=>'readonly',
                'size'=> 45,
            ),
        ));?>

        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'value' => date('Y-m-d', time()), //设置默认值
            'name' => 'create_time_end',
            'language' => 'zh_cn',
            'model' => $model,
            'attribute' => 'create_time_end',
            'flat' => false,
            'options' => array(
                'showAnim' => 'fadeIn',
                'showOn' => 'focus',
                'changeYear' => true,
                'changeMonth' => true,
                'buttonImageOnly' => true,
                'dateFormat' => 'yy-mm-dd',
                'yearRange' => '-30:+30',
            ),
            'htmlOptions' => array(
                'placeholder' => '结束时间',
                'readonly'=>'readonly',
                'size'=> 45,
            ),
        ));?>
    </div>

	<div class="row">
		<?php echo $form->label($model,'customer_name'); ?>
		<?php echo $form->textField($model,'customer_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'customer_mobile'); ?>
		<?php echo $form->textField($model,'customer_mobile',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'customer_address'); ?>
		<?php echo $form->textField($model,'customer_address',array('size'=>45,'maxlength'=>45)); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'car_mileage'); ?>
		<?php echo $form->textField($model,'car_mileage',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'car_repaired_main'); ?>
		<?php echo $form->textField($model,'car_repaired_main',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('搜索'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->