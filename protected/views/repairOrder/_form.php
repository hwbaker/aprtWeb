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

	<p class="note">带 <span class="required">*</span> 必填.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <?php echo $form->labelEx($model,'date_in_factory'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'value' => date('Y-m-d', time()), //设置默认值
            'name' => 'date_in_factory',
            'language' => 'zh_cn',
            'model' => $model,
            'attribute' => 'date_in_factory',
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
                'placeholder' => '进厂日期',
                'readonly'=>'readonly',
                'size'=> 45,
            ),
        ));?>

		<?php echo $form->error($model,'date_in_factory'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'date_out_factory'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'value' => date('Y-m-d', time()), //设置默认值
            'name' => 'date_out_factory',
            'language' => 'zh_cn',
            'model' => $model,
            'attribute' => 'date_out_factory',
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
                'placeholder' => '出厂日期',
                'readonly'=>'readonly',
                'size'=> 45,
            ),
        ));?>

        <?php echo $form->error($model,'date_out_factory'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'car_number'); ?>
		<?php echo $form->textField($model,'car_number',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'car_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'car_type'); ?>
		<?php echo $form->textField($model,'car_type',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'car_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'car_mileage'); ?>
		<?php echo $form->textField($model,'car_mileage',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'car_mileage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'car_repaired_main'); ?>
		<?php echo $form->textField($model,'car_repaired_main',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'car_repaired_main'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'customer_name'); ?>
        <?php echo $form->textField($model,'customer_name',array('size'=>45,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'customer_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'customer_mobile'); ?>
        <?php echo $form->textField($model,'customer_mobile',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'customer_mobile'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'customer_address'); ?>
        <?php echo $form->textField($model,'customer_address',array('size'=>45,'maxlength'=>200)); ?>
        <?php echo $form->error($model,'customer_address'); ?>
    </div>

    <div class="row">
        <label>维修项目</label>
        <h1 style="font-size:0.5em; color: red; ">输入格式:项目名称,金额.每行一条数据,用英文逗号分隔
            <br>例如:四轮定位,200</h1>
        <textarea cols="43" rows="6" name="repairProject[content]" id="repairProject_content" ><?php echo $contentProject;?></textarea>
    </div>

    <div class="row">
        <label>维修材料</label>
        <h1 style="font-size:0.5em; color: red; ">输入格式:材料名称,数量,单位,单价.每行一条数据,用英文逗号分隔
            <br>例如:空调管,1,根,1800</h1>
        <textarea cols="43" rows="6" name="repairMaterial[content]" id="repairMaterial_content"><?php echo $contentMaterial;?></textarea>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'pay_type'); ?>
        <?php echo $form->dropDownList($model, 'pay_type', array_merge(array('' => '请选择'), RepairOrder::$payTypeOption), array('style'=>'width:280px')) ?>
        <?php echo $form->error($model,'pay_type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'invoice_type'); ?>
        <?php echo $form->dropDownList($model, 'invoice_type', array_merge(array('' => '请选择'), RepairOrder::$invoiceTypeOptions), array('style'=>'width:280px')) ?>
        <?php echo $form->error($model,'invoice_type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'check_man'); ?>
        <?php echo $form->textField($model,'check_man',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'check_man'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'settlement_man'); ?>
        <?php echo $form->textField($model,'settlement_man',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'settlement_man'); ?>
    </div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '新建' : '保存',
            array("id" => "repairOrderCommit")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    $('#repair-order-form').submit(function() {
        var projectReturn = projectContentCheck();
        var materialReturn = materialContentCheck();
        if (projectReturn && materialReturn) {
            return true;
        } else {
            return false;
        }
    });

    // 项目检测
    function projectContentCheck() {
        var repairProjectContentVal = $('#repairProject_content').val().trim(); // 维修项目
        var submitVal = true;
        if (repairProjectContentVal) {
            var projectArr = repairProjectContentVal.split('\n');
            // 判断行是否重复
            var doubleData = new Array();
            // 判断项目是否重复
            var doubleSku = new Array();
            $.each(projectArr, function (index, projectItem) {
                var doubleNum = $.inArray(projectItem, doubleData);
                if (doubleNum == -1){
                    doubleData.push(projectItem);
                    if (projectItem.indexOf('\t') != -1) {
                        var caseVal=projectItem.split('\t');
                    } else if (projectItem.indexOf(',') != -1) {
                        var caseVal=projectItem.split(',');
                    } else {
                        alert('维修项目 第'+(index+1)+'行分隔符有误,请查看');
                        submitVal = false;
                        return false;
                    }
                    var projectName = $.trim(caseVal[0]);
                    var doubleSkuNum = $.inArray(projectName,doubleSku);
                    if( caseVal.length != 2){
                        submitVal = false;
                        alert('维修项目 第'+(index+1)+'行格式有误,请查看');
                        return false;
                    }

                    if (doubleSkuNum == -1) {
                        doubleSku.push(projectName);
                    } else {
                        submitVal=false;
                        alert('维修项目 第'+(index+1)+'行和第'+(doubleSkuNum+1)+'行项目名称重复,请查看');
                        return false;
                    }
                } else {
                    submitVal=false;
                    alert('维修项目 第'+(index+1)+'行和第'+(doubleNum+1)+'行重复,请查看');
                    return false;
                }
            });
        }

        return submitVal;
    }

    // 材料检测
    function materialContentCheck() {
        var materialContentVal = $('#repairMaterial_content').val().trim(); // 维修材料
        var submitVal = true;
        if (materialContentVal) {
            var materialArr = materialContentVal.split('\n');
            // 判断行是否重复
            var doubleData = new Array();
            // 判断材料是否重复
            var doubleSku = new Array();
            $.each(materialArr, function (index, materialItem) {
                var doubleNum = $.inArray(materialItem, doubleData);
                if (doubleNum == -1) {
                    doubleData.push(materialItem);
                    if (materialItem.indexOf('\t') != -1) {
                        var caseVal=materialItem.split('\t');
                    } else if (materialItem.indexOf(',') != -1) {
                        var caseVal=materialItem.split(',');
                    } else {
                        alert('维修材料 第'+(index+1)+'行分隔符有误,请查看');
                        submitVal = false;
                        return false;
                    }
                    var materialName = $.trim(caseVal[0]);
                    var doubleSkuNum = $.inArray(materialName, doubleSku);
                    if(caseVal.length != 4){
                        submitVal = false;
                        alert('维修材料 第'+(index+1)+'行格式有误,请查看');
                        return false;
                    }

                    if (doubleSkuNum == -1) {
                        doubleSku.push(materialName);
                    } else {
                        submitVal=false;
                        alert('维修材料 第'+(index+1)+'行和第'+(doubleSkuNum+1)+'行材料名称重复,请查看');
                        return false;
                    }
                } else {
                    submitVal=false;
                    alert('维修材料 第'+(index+1)+'行和第'+(doubleNum+1)+'行重复,请查看');
                    return false;
                }
            });
        }

        return submitVal;
    }

    
</script>