<?php

class RepairOrderController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','realReceiveAmount','finish','amountReport'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	    $model = $this->loadModel($id);
        // 维修项目
        $repairProject = isset($model->repair_project) ? $model->repair_project : array();
        // 维修材料
        $repairMaterial = isset($model->repair_material) ? $model->repair_material : array();

		$this->render('view',array(
			'model' => $model,
            'repairProject' => $repairProject,
            'repairMaterial' => $repairMaterial
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RepairOrder;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RepairOrder']))
		{
            $transaction = Yii::app()->db->beginTransaction();
		    try {
                $projectSumAmount = $materialSumAmount = 0;
                $model->attributes=$_POST['RepairOrder'];
                $model->create_time = $model->update_time = date('Y-m-d H:i:s', time());
                $model->date_out_factory = $_POST['RepairOrder']['date_out_factory'] == '0000-00-00 00:00:00' ?
                   $_POST['RepairOrder']['date_out_factory'] : $_POST['RepairOrder']['date_out_factory'] . " 23:59:59";

                // 维修单号
                $model->repair_order_id = $model->generateRepairOrderId();

                // 维修项目
                if (isset($_POST['repairProject']['content']) && $_POST['repairProject']['content']) {
                    $rightProjectArr = array();
                    $batchRepairProject = explode("\n", trim($_POST['repairProject']['content']));
                    // 处理数据 基本格式验证
                    foreach ($batchRepairProject as $key => $line) {
                        if (strstr($line, ',')) {
                            $item = explode(',', $line);
                        } elseif (strstr($line, "\t")) {
                            $item = explode("\t", $line);
                        } else {
                            $errorMsg[] = '维修项目 第' . ($key + 1) . '行分隔符有误!';
                            continue;
                        }

                        $count = count($item);
                        if( $count != 2){
                            throw new Exception ('维修项目 第' . ($key+1) .'行格式有误,请查看');
                        }
                        $projectName = trim($item[0]);
                        $projectAmount = floatval(trim($item[1]));

                        $rightProjectArr[$key]['project_name'] = $projectName;
                        $rightProjectArr[$key]['project_amount'] = $projectAmount;
                    }

                    // 保存 维修项目
                    if ($rightProjectArr) {
                        foreach ($rightProjectArr as $projectItem) {
                            $repairProject = new RepairProject;
                            $repairProject->repair_order_id = $model->repair_order_id;
                            $repairProject->project_name = $projectItem['project_name'];
                            $repairProject->project_amount = $projectItem['project_amount'];
                            $projectSumAmount += $projectItem['project_amount'];
                            if (!$repairProject->save()) {
                                throw new Exception('维修项目创建失败:' . print_r($repairProject->getErrors(), true));
                            }
                        }
                    }
                    unset($rightProjectArr);
                }

                // 维修材料
                if (isset($_POST['repairMaterial']['content']) && $_POST['repairMaterial']['content']) {
                    $rightMaterialArr = array();
                    $batchRepairMaterial = explode("\n", trim($_POST['repairMaterial']['content']));
                    // 处理数据 基本格式验证
                    foreach ($batchRepairMaterial as $key => $line) {
                        if (strstr($line, ',')) {
                            $item = explode(',', $line);
                        } elseif (strstr($line, "\t")) {
                            $item = explode("\t", $line);
                        } else {
                            $errorMsg[] = '维修材料 第' . ($key + 1) . '行分隔符有误!';
                            continue;
                        }

                        $count = count($item);
                        if( $count != 4){
                            throw new Exception ('维修材料 第' . ($key+1) .'行格式有误,请查看');
                        }
                        $materialName = trim($item[0]);
                        $materialCount = floatval(trim($item[1]));
                        $materialUnit = trim($item[2]);
                        $materialAmount = floatval(trim($item[3]));

                        $rightMaterialArr[$key]['material_name'] = $materialName;
                        $rightMaterialArr[$key]['material_count'] = $materialCount;
                        $rightMaterialArr[$key]['material_unit'] = $materialUnit;
                        $rightMaterialArr[$key]['material_amount'] = $materialAmount;
                    }

                    // 保存 维修材料
                    if ($rightMaterialArr) {
                        foreach ($rightMaterialArr as $materialItem) {
                            $repairMaterial = new RepairMaterial;
                            $repairMaterial->repair_order_id = $model->repair_order_id;
                            $repairMaterial->material_name = $materialItem['material_name'];
                            $repairMaterial->material_count = $materialItem['material_count'];
                            $repairMaterial->material_unit = $materialItem['material_unit'];
                            $repairMaterial->material_amount = $materialItem['material_amount'];
                            $materialSumAmount += $materialItem['material_count'] * $materialItem['material_amount'];
                            if (!$repairMaterial->save()) {
                                throw new Exception('维修材料创建失败:' . print_r($repairMaterial->getErrors(), true));
                            }
                        }
                    }
                    unset($rightMaterialArr);
                }

                // 应收金额
                $needReceiveAmount = $projectSumAmount + $materialSumAmount;
                $model->need_receive_amount = $needReceiveAmount;
                if (!$model->save()) {
                    throw new Exception('维修单创建失败:' . print_r($model->getErrors(), true));
                }

                $transaction->commit();
                $this->redirect(array('view','id' => $model->repair_order_id));
            } catch (Exception $e) {
                $transaction->rollback();
		        throw $e;
            }

		}

		$this->render('create',array(
			'model'=>$model,
            'contentProject' => '',
            'contentMaterial' => ''
		));
	}

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws Exception
     */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        if ($model->status == RepairOrder::STATUS_FINISHED) {
            throw new Exception("标记'完成'的维修单不能修改");
        }

        $contentProject = $contentMaterial = '';
        // 维修项目
        $existRepairProject = isset($model->repair_project) ? $model->repair_project : array();
        // 维修材料
        $existMaterial = isset($model->repair_material) ? $model->repair_material : array();
        foreach ($existRepairProject as $project) {
            $contentProject .= $project->project_name . ',' . $project->project_amount . "\n";
        }
        foreach ($existMaterial as $material) {
            $contentMaterial .= $material->material_name . ',' . $material->material_count
                             . ',' . $material->material_unit . ',' . $material->material_amount . "\n";
        }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RepairOrder']))
		{
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $projectSumAmount = $materialSumAmount = 0;
                $model->attributes = $_POST['RepairOrder'];
                $model->update_time = date('Y-m-d H:i:s', time());
//                $model->date_out_factory = $_POST['RepairOrder']['date_out_factory'] . " 23:59:59";

                // 维修项目
                if (isset($_POST['repairProject']['content']) && $_POST['repairProject']['content']) {
                    $rightProjectArr = array();
                    $batchRepairProject = explode("\n", trim($_POST['repairProject']['content']));
                    // 处理数据 基本格式验证
                    foreach ($batchRepairProject as $key => $line) {
                        if (strstr($line, ',')) {
                            $item = explode(',', $line);
                        } elseif (strstr($line, "\t")) {
                            $item = explode("\t", $line);
                        } else {
                            $errorMsg[] = '维修项目 第' . ($key + 1) . '行分隔符有误!';
                            continue;
                        }

                        $count = count($item);
                        if( $count != 2){
                            throw new Exception ('维修项目 第' . ($key+1) .'行格式有误,请查看');
                        }
                        $projectName = trim($item[0]);
                        $projectAmount = floatval(trim($item[1]));

                        $rightProjectArr[$key]['project_name'] = $projectName;
                        $rightProjectArr[$key]['project_amount'] = $projectAmount;
                    }

                    // 保存 维修项目
                    if ($rightProjectArr) {
                        $existRepairProject = isset($model->repair_project) ? $model->repair_project : array();
                        //RepairProject::model()-> findByAttributes(array('repair_order_id' => $model->repair_order_id));
                        if ($existRepairProject) {
                            $deleteRepairProject = RepairProject::model()->deleteAllByAttributes(array('repair_order_id' => $model->repair_order_id));
                            if (!$deleteRepairProject) {
                                throw new Exception('删除维修项目旧明细失败');
                            }
                        }

                        foreach ($rightProjectArr as $projectItem) {
                            $repairProject = new RepairProject;
                            $repairProject->repair_order_id = $model->repair_order_id;
                            $repairProject->project_name = $projectItem['project_name'];
                            $repairProject->project_amount = $projectItem['project_amount'];
                            $projectSumAmount += $projectItem['project_amount'];
                            if (!$repairProject->save()) {
                                throw new Exception('维修项目创建失败:' . print_r($repairProject->getErrors(), true));
                            }
                        }
                    }
                    unset($rightProjectArr);
                }

                // 维修材料
                if (isset($_POST['repairMaterial']['content']) && $_POST['repairMaterial']['content']) {
                    $rightMaterialArr = array();
                    $batchRepairMaterial = explode("\n", trim($_POST['repairMaterial']['content']));
                    // 处理数据 基本格式验证
                    foreach ($batchRepairMaterial as $key => $line) {
                        if (strstr($line, ',')) {
                            $item = explode(',', $line);
                        } elseif (strstr($line, "\t")) {
                            $item = explode("\t", $line);
                        } else {
                            $errorMsg[] = '维修材料 第' . ($key + 1) . '行分隔符有误!';
                            continue;
                        }

                        $count = count($item);
                        if( $count != 4){
                            throw new Exception ('维修材料 第' . ($key+1) .'行格式有误,请查看');
                        }
                        $materialName = trim($item[0]);
                        $materialCount = floatval(trim($item[1]));
                        $materialUnit = trim($item[2]);
                        $materialAmount = floatval(trim($item[3]));

                        $rightMaterialArr[$key]['material_name'] = $materialName;
                        $rightMaterialArr[$key]['material_count'] = $materialCount;
                        $rightMaterialArr[$key]['material_unit'] = $materialUnit;
                        $rightMaterialArr[$key]['material_amount'] = $materialAmount;
                    }

                    // 保存 维修材料
                    if ($rightMaterialArr) {
                        print_r($rightMaterialArr);
                        $existMaterialProject = isset($model->repair_material) ? $model->repair_material : array();
                        if ($existMaterialProject) {
                            $deleteMaterialProject = RepairMaterial::model()->deleteAllByAttributes(array('repair_order_id' => $model->repair_order_id));
                            if (!$deleteMaterialProject) {
                                throw new Exception('删除维修材料旧明细失败');
                            }
                        }

                        foreach ($rightMaterialArr as $materialItem) {
                            $repairMaterial = new RepairMaterial;
                            $repairMaterial->repair_order_id = $model->repair_order_id;
                            $repairMaterial->material_name = $materialItem['material_name'];
                            $repairMaterial->material_count = $materialItem['material_count'];
                            $repairMaterial->material_unit = $materialItem['material_unit'];
                            $repairMaterial->material_amount = $materialItem['material_amount'];
                            $materialSumAmount += $materialItem['material_count'] * $materialItem['material_amount'];
                            if (!$repairMaterial->save()) {
                                throw new Exception('维修材料创建失败:' . print_r($repairMaterial->getErrors(), true));
                            }
                        }
                    }
                    unset($rightMaterialArr);
                }
                // 应收金额
                $needReceiveAmount = $projectSumAmount + $materialSumAmount;
                $model->need_receive_amount = $needReceiveAmount;
                if (!$model->save()) {
                    throw new Exception('维修单更新失败:' . print_r($model->getErrors(), true));
                }

                $transaction->commit();
                $this->redirect(array('view','id' => $model->repair_order_id));
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }

		}

		$this->render('update',array(
			'model' => $model,
            'contentProject' => $contentProject,
            'contentMaterial' => $contentMaterial
		));
	}

    /**
     * @desc 录入实际结算金额
     * @param $id
     * @throws Exception
     */
	public function actionRealReceiveAmount($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == RepairOrder::STATUS_FINISHED) {
            throw new Exception('标记完成的维修单的不能修改结算金额');
        }
        if (isset($_POST['RepairOrder']['real_receive_amount']) && $_POST['RepairOrder']['real_receive_amount']) {
            $model->need_receive_amount = floatval(trim($_POST['RepairOrder']['need_receive_amount']));
            $model->real_receive_amount = floatval(trim($_POST['RepairOrder']['real_receive_amount']));
            if (!$model->saveAttributes(array('need_receive_amount', 'real_receive_amount'))) {
                throw new Exception('录入结算金额失败:' . print_r($model->getErrors(), true));
            }
            $this->redirect(array('view','id' => $model->repair_order_id));
        }
        $this->render('real_receive_amount',array(
            'model' => $model,
        ));
    }

    /**
     * @desc 标记单据完成
     * @param $id
     * @throws Exception
     */
    public function actionFinish($id)
    {
        $model = $this->loadModel($id);
        if ($model->pay_type == RepairOrder::TYPE_UN_PAYMENT) {
            throw new Exception("付款类型'挂账'的维修单不能标记完成");
        }
        $model->status = RepairOrder::STATUS_FINISHED;
        if (!$model->saveAttributes(array('status'))) {
            throw new Exception('标记完成失败:' . print_r($model->getErrors(), true));
        }
        $this->redirect(array('view','id' => $model->repair_order_id));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws Exception
     */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
        if ($model->status == RepairOrder::STATUS_FINISHED) {
            throw new Exception("标记'完成'的维修单不能删除");
        }
        $transaction = Yii::app()->db->beginTransaction();
		try {
            $existRepairProject = isset($model->repair_project) ? $model->repair_project : array();
            if ($existRepairProject) {
                $deleteRepairProject = RepairProject::model()->deleteAllByAttributes(array('repair_order_id' => $model->repair_order_id));
                if (!$deleteRepairProject) {
                    throw new Exception('删除维修项目明细失败');
                }
            }

            $existMaterialProject = isset($model->repair_material) ? $model->repair_material : array();
            if ($existMaterialProject) {
                $deleteMaterialProject = RepairMaterial::model()->deleteAllByAttributes(array('repair_order_id' => $model->repair_order_id));
                if (!$deleteMaterialProject) {
                    throw new Exception('删除维修材料明细失败');
                }
            }

            if (!$model->delete()) {
                throw new Exception('删除维修单失败');
            }

            $transaction->commit();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('RepairOrder');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RepairOrder('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RepairOrder']))
			$model->attributes=$_GET['RepairOrder'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RepairOrder the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=RepairOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param RepairOrder $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='repair-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * @desc 对账汇总
     */
	public function actionAmountReport()
    {

        $result = RepairOrder::getAmountReport();

        $this->render('amount_report',array(
            'result' => $result
        ));
    }
}
