<?php

/**
 * This is the model class for table "aprt_repair_material".
 *
 * The followings are the available columns in table 'aprt_repair_material':
 * @property integer $id
 * @property string $repair_order_id
 * @property string $material_name
 * @property string $material_count
 * @property string $material_unit
 * @property string $material_amount
 */
class RepairMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'aprt_repair_material';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('repair_order_id', 'length', 'max'=>45),
			array('material_name', 'length', 'max'=>100),
			array('material_count, material_amount', 'length', 'max'=>14),
			array('material_unit', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, repair_order_id, material_name, material_count, material_unit, material_amount', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'repair_order_id' => '维修单号:APRT201704120001',
			'material_name' => '材料名称',
			'material_count' => '材料数量',
			'material_unit' => '材料单位:个;桶',
			'material_amount' => '材料单价',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('repair_order_id',$this->repair_order_id,true);
		$criteria->compare('material_name',$this->material_name,true);
		$criteria->compare('material_count',$this->material_count,true);
		$criteria->compare('material_unit',$this->material_unit,true);
		$criteria->compare('material_amount',$this->material_amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RepairMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
