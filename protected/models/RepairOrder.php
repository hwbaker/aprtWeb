<?php

/**
 * This is the model class for table "aprt_repair_order".
 *
 * The followings are the available columns in table 'aprt_repair_order':
 * @property string $repair_order_id
 * @property string $customer_name
 * @property string $customer_mobile
 * @property string $customer_address
 * @property string $date_in_factory
 * @property string $date_out_factory
 * @property string $car_number
 * @property string $car_type
 * @property string $car_mileage
 * @property string $car_repaired_main
 * @property string $create_time
 * @property string $update_time
 */
class RepairOrder extends CActiveRecord
{
    public $create_time_start = '';
    public $create_time_end = '';

    const TYPE_CASH = 'cash'; //现金
    const TYPE_UN_PAYMENT = 'un_payment'; //挂账
    const TYPE_TRANSFER = 'transfer'; //转账
    const TYPE_BAND_CARD = 'band_card'; //银行卡
    const TYPE_CREDIT_CARD = 'credit_card'; //信用卡

    static $payTypeOption = array(
        self::TYPE_CASH => '现金',
        self::TYPE_TRANSFER => '转账',
        self::TYPE_UN_PAYMENT => '挂账',
        self::TYPE_BAND_CARD => '银行卡',
        self::TYPE_CREDIT_CARD => '信用卡',
    );

    const INVOICE_TYPE_NORMAL_INVOICE = 'normal_invoice'; //普通发票
    const INVOICE_TYPE_ADD_TAX_SPECIAL = 'added_tax_special'; //增值税专用发票
    const INVOICE_TYPE_ADD_TAX_NORMAL = 'added_tax_normal'; //增值税普通发票

    static $invoiceTypeOptions = array(
        self::INVOICE_TYPE_NORMAL_INVOICE => '普通发票',
        self::INVOICE_TYPE_ADD_TAX_SPECIAL => '增值税专用发票',
        self::INVOICE_TYPE_ADD_TAX_NORMAL => '增值税普通发票',
    );

    const STATUS_NEW = 'new';
    const STATUS_FINISHED = 'finished';
    static $statusOptions = array(
        self::STATUS_NEW => '新建',
        self::STATUS_FINISHED => '完成',
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'aprt_repair_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_in_factory, date_out_factory, car_number, car_type', 'required'),
			array('repair_order_id, customer_mobile, car_number, car_type, car_mileage, car_repaired_main', 'length', 'max'=>45),
			array('customer_name', 'length', 'max'=>100),
			array('customer_address', 'length', 'max'=>200),
			array('date_in_factory, date_out_factory, create_time, update_time, pay_type, invoice_type,
			       need_receive_amount, real_receive_amount, check_man, settlement_man, status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('repair_order_id, customer_name, customer_mobile, customer_address, date_in_factory, date_out_factory, 
			car_number, car_type, car_mileage, car_repaired_main, create_time, update_time,status', 'safe', 'on'=>'search'),
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
            'repair_project' => array(
                self::HAS_MANY,
                'RepairProject',
                'repair_order_id'
            ),
            'repair_material' => array(
                self::HAS_MANY,
                'RepairMaterial',
                'repair_order_id'
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'repair_order_id' => '维修单号',
			'customer_name' => '客户名称',
			'customer_mobile' => '客户联系电话',
			'customer_address' => '客户联系地址',
			'date_in_factory' => '进厂时间',
			'date_out_factory' => '出厂时间',
			'car_number' => '车牌号码',
			'car_type' => '车型',
			'car_mileage' => '进厂里程',
			'car_repaired_main' => '送修人',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
            'pay_type' => '付款类型',
            'invoice_type' => '发票类型',
            'need_receive_amount' => '应收金额',
            'real_receive_amount' => '结算金额',
            'check_man' => '检验员',
            'settlement_man' => '结算员',
            'status' => '状态'
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
     * @param integer $pageSize
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($pageSize = 20)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('repair_order_id',$this->repair_order_id,true);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
		$criteria->compare('customer_address',$this->customer_address,true);
		$criteria->compare('date_in_factory',$this->date_in_factory,true);
		$criteria->compare('date_out_factory',$this->date_out_factory,true);
		$criteria->compare('car_number',$this->car_number,true);
		$criteria->compare('car_type',$this->car_type,true);
		$criteria->compare('car_mileage',$this->car_mileage,true);
		$criteria->compare('car_repaired_main',$this->car_repaired_main,true);
		$criteria->compare('update_time',$this->update_time,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('pay_type',$this->pay_type);

        if (isset($_GET['RepairOrder']['create_time_start']) && isset($_GET['RepairOrder']['create_time_end'])
            && !empty($_GET['RepairOrder']['create_time_start']) && !empty($_GET['RepairOrder']['create_time_end'])) {
            $minDangerLifeEnd = trim($_GET['RepairOrder']['create_time_end']);
            $minDangerLifeStart = trim($_GET['RepairOrder']['create_time_start']);
            $criteria->addBetweenCondition('create_time', $minDangerLifeStart, $minDangerLifeEnd);
        }

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            )
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RepairOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @desc   获取维修单号
     * @return string
     */
    public function generateRepairOrderId()
    {
        $this->repair_order_id = IdGenerator::getId('aprt');
        return $this->repair_order_id;
    }

    /**
     * @return string
     */
    public function getActionLink()
    {
        $links = array();
        $links[] = CHtml::link('查看单据', array('/RepairOrder/View/', 'id' => $this->repair_order_id), array('target' => '_blank'));

        if ($this->status == self::STATUS_NEW) {
            $links[] = CHtml::link('修改单据', array('/RepairOrder/Update/', 'id' => $this->repair_order_id), array('target' => '_blank'));
            $links[] = CHtml::link('录入结算', array('/RepairOrder/RealReceiveAmount/', 'id' => $this->repair_order_id), array('target' => '_blank'));
            if ($this->pay_type != self::TYPE_UN_PAYMENT) {
                $links[] = CHtml::link('标记完成', array('/RepairOrder/Finish/', 'id' => $this->repair_order_id));
            }
            $links[] = CHtml::link('删除单据', array('/RepairOrder/Delete/', 'id' => $this->repair_order_id));
        }
        return implode(' ', $links);
    }

    /**
     * @desc 获取对账汇总
     * @return mixed
     */
    public static function getAmountReport()
    {
        $minDangerLifeEnd = $minDangerLifeStart = '';
        $sql = 'select 
                    sum(need_receive_amount) need_receive_amount, sum(real_receive_amount) real_receive_amount 
                from 
                    aprt_repair_order
                where 
                    create_time >=:minDangerLifeStart and create_time<=:minDangerLifeEnd';
        if (isset($_GET['create_time_start']) && isset($_GET['create_time_end'])
            && !empty($_GET['create_time_start']) && !empty($_GET['create_time_end'])) {
            $minDangerLifeEnd = trim($_GET['create_time_end']) . ' 23:59:59';
            $minDangerLifeStart = trim($_GET['create_time_start']) . ' 00:00:00';
        }
//        print_r(array('minDangerLifeStart' => $minDangerLifeStart,'minDangerLifeEnd' => $minDangerLifeEnd));

        $result = Yii::app()->db->createCommand($sql)->queryRow(true, array(
                'minDangerLifeStart' => $minDangerLifeStart,
                'minDangerLifeEnd' => $minDangerLifeEnd)
        );

        return $result;
    }
}
