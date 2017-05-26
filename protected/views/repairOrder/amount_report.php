<?php
/* @var $this RepairOrderController */
/* @var $model RepairOrder */

$this->breadcrumbs=array(
	'维修单管理项'=>array('admin'),
	'对账汇总',
);

$this->menu=array(
	array('label'=>'维修单管理项', 'url'=>array('index')),
	array('label'=>'新建维修单', 'url'=>array('create')),
);
?>

<div class="search-form" >
    <div class="wide form">

        <?php $form=$this->beginWidget('CActiveForm', array(
            'action'=>Yii::app()->createUrl($this->route),
            'method'=>'get',
        )); ?>

        <div class="row">
            <label >按创建时间搜索</label>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'value' => isset($_GET['create_time_start']) ? $_GET['create_time_start'] : date('Y-m-d', time()), //设置默认值
                'name' => 'create_time_start',
                'language' => 'zh_cn',
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
                'value' => isset($_GET['create_time_end']) ? $_GET['create_time_end'] : date('Y-m-d', time()), //设置默认值
                'name' => 'create_time_end',
                'language' => 'zh_cn',
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

        <div class="row buttons">
            <?php echo CHtml::submitButton('搜索'); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div>
</div><!-- search-form -->

<div id="repair-order-grid">
    <table class="items">
        <thead>
        <tr>
            <th id="repair-order-grid_c0">
                应收金额
            </th>
            <th id="repair-order-grid_c1">
                结算金额
            </th>
            <th id="repair-order-grid_c2">
                差额(结算-应收)
            </th>
        </tr>

        </thead>
        <tbody>
        <tr class="odd">
            <td><?php echo $result['need_receive_amount']?></td>
            <td><?php echo $result['real_receive_amount']?></td>
            <td style="color: red"><?php echo $result['real_receive_amount'] - $result['need_receive_amount']?></td>
        </tr>
        </tbody>
    </table>
</div>


