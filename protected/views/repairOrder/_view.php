<?php
/* @var $this RepairOrderController */
/* @var $data RepairOrder */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('repair_order_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->repair_order_id), array('view', 'id'=>$data->repair_order_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_name')); ?>:</b>
	<?php echo CHtml::encode($data->customer_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_mobile')); ?>:</b>
	<?php echo CHtml::encode($data->customer_mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_address')); ?>:</b>
	<?php echo CHtml::encode($data->customer_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_in_factory')); ?>:</b>
	<?php echo CHtml::encode($data->date_in_factory); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_out_factory')); ?>:</b>
	<?php echo CHtml::encode($data->date_out_factory); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('car_number')); ?>:</b>
	<?php echo CHtml::encode($data->car_number); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('car_type')); ?>:</b>
	<?php echo CHtml::encode($data->car_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('car_mileage')); ?>:</b>
	<?php echo CHtml::encode($data->car_mileage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('car_repaired_main')); ?>:</b>
	<?php echo CHtml::encode($data->car_repaired_main); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	*/ ?>

</div>