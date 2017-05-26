<?php

/**
 * This is the model class for table "aprt_id_generator".
 *
 * The followings are the available columns in table 'aprt_id_generator':
 * @property string $id
 * @property string $idType
 * @property string $id_name
 * @property string $id_prefix
 * @property string $id_separator
 * @property string $current_id
 * @property integer $id_length
 * @property integer $need_reset
 * @property string $update_time
 */
class IdGenerator extends CActiveRecord
{
    const NEED_RESET=1;
    static $ERROR_LIST = array(
        'E01'=>'单据类型不能为空',
        'E02'=>'单据类型不存在',
        'E03'=>'系统繁忙，请重试',
        'E04'=>'单据不合法，请配置单据类型数组'
    );
    //合法单据类型
    static $ID_TYPE_LIST = array(
        'aprt'=>array(
            'id_type'=>'aprt',
            'id_name'=>'维修单',
            'id_prefix'=>'APRT',
            'id_separator'=>'date',
            'current_id'=>'2000',
            'id_length'=>'4',
            'need_reset'=>'1',
            'update_time'=>'now()'
        ),
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'aprt_id_generator';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_length, need_reset', 'numerical', 'integerOnly'=>true),
			array('id_type, id_name', 'length', 'max'=>45),
			array('id_prefix', 'length', 'max'=>20),
			array('id_separator', 'length', 'max'=>10),
			array('current_id', 'length', 'max'=>11),
			array('update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_type, id_name, id_prefix, id_separator, current_id, id_length, need_reset, update_time', 'safe', 'on'=>'search'),
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
            'id_type' => '单据类型',
            'id_name' => '单据名称',
            'id_prefix' => 'ID前缀',
            'id_separator' => 'ID分割符',
            'current_id' => '当前ID',
            'id_length' => 'ID长度',
            'need_reset' => '重置',
            'update_time' => '最近更新时间',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_type',$this->id_type,true);
		$criteria->compare('id_name',$this->id_name,true);
		$criteria->compare('id_prefix',$this->id_prefix,true);
		$criteria->compare('id_separator',$this->id_separator,true);
		$criteria->compare('current_id',$this->current_id,true);
		$criteria->compare('id_length',$this->id_length);
		$criteria->compare('need_reset',$this->need_reset);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IdGenerator the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @desc   获取维修单号
     * @param  string $idType
     * @return string
     * @throws Exception
     */
    public static function getId($idType = '')
    {
        global $idGntConn;
        if (strlen($idType) == 0 || !array_key_exists($idType, self::$ID_TYPE_LIST)) {
            throw new Exception('单据类型错误！请确认单据类型是否存在。');
        }
        if (!isset($idGntConn) || !($idGntConn instanceof CDbConnection)) {
            $idGntConn = new APRTCDbConnection(Yii::app()->db->connectionString, Yii::app()->db->username, Yii::app()->db->password);
            $idGntConn->createCommand("set session wait_timeout=28800")->execute();
        }

        $transaction = $idGntConn->beginTransaction();
        try {
            $sql = 'select 
                        id_prefix,id_separator,current_id,id_length,need_reset,update_time,now() as cur_time
    		        from 
    		            aprt_id_generator 
    		        where 
    		            id_type=:id_type for update';
            $row = $idGntConn->createCommand($sql)->queryRow(true, array('id_type' => $idType));
            if ($row) {
                $id = $row['id_prefix'];
                $row['current_id'] += 1;
                //设置 可以重置，且上次更新时间和本次更新时间不在同一天，重设current_id
                if ($row['id_separator'] == 'date' && $row['need_reset'] == self::NEED_RESET) {
                    if (substr($row['cur_time'], 0, 10) != substr($row['update_time'], 0, 10)) {
                        $row['current_id'] = 1;
                    }
                }
                $sql = 'update aprt_id_generator set update_time=now(),current_id=:current_id where id_type=:id_type';
                $idGntConn->createCommand($sql)->execute(array('current_id' => $row['current_id'], 'id_type' => $idType));
                if ($row['id_separator'] == 'date') {
                    $row['id_separator'] = date('ymd');
                }
                $id .= $row['id_separator'];
                if ($row['id_length'] > 0) {
                    $row['current_id'] = substr(str_pad($row['current_id'], $row['id_length'], '0', STR_PAD_LEFT), -1 * $row['id_length']);
                }
                $id .= $row['current_id'];
            } else {
                $transaction->rollback();
                $model = new IdGenerator();
                if (self::$ID_TYPE_LIST[$idType]['update_time'] == 'now()') {
                    self::$ID_TYPE_LIST[$idType]['update_time'] = date('Y-m-d H:i:s');
                }
                $idGntConn->createCommand()->insert($model->tableName(), self::$ID_TYPE_LIST[$idType]);
                return IdGenerator::getId($idType);
            }
            $transaction->commit();
            return $id;
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('IdGenerator:'.$e->getMessage());
        }
// 		error_log($idType.'|'.date('Y-m-d H:i:s').':'.$id."\r\n",3,"E:/err_log.txt");
    }
}
