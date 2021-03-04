<?php

namespace app\controllers;

use app\constants\Messages;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\models\Permissions;
use CottaCush\Yii2\Date\DateUtils;
use app\libs\Utils;
use app\models\BaseModel;
use app\models\ViolationTypes;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;

class ViolationTypeController extends BaseController
{
    public function beforeAction($action)
    {
        $this->canAccess(Permissions::MANAGE_VIOLATION_TYPE);
        return parent::beforeAction($action);
    }
    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles loading violations to view
     */
    public function actionIndex()
    {
        $this->layout = 'dashboard';
        $model = new ViolationTypes();
        $query = ViolationTypes::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['created_at', 'status']
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider, 'model' => $model
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @handles creating violation types
     */
    public function actionCreateViolationTypes()
    {
        $postData = $this->getRequest()->post();
        $violations = ArrayHelper::getValue($postData, 'ViolationTypes.names');
        $violationsArray = explode(',', $violations);
        $now = DateUtils::getMysqlNow();
        $violationList = [];

        foreach ($violationsArray as $violation) {
            if (!empty($violation)) {
                if (ViolationTypes::checkDuplicateViolation($violation)) {
                    return $this->returnError(Messages::VIOLATION_EXIST);
                }
                $key = BaseInflector::slug($violation);
                $violationList[] = [$violation, $now, $key];
            }
            if (ViolationTypes::checkDuplicateViolation($violation)) {
                return $this->returnError(Messages::VIOLATION_EXIST);
            }
            if (empty($violation)) {
                return $this->returnError(Messages::EMPTY_VIOLATION);
            }
        }

        try {
            BaseModel::batchInsert(
                ViolationTypes::tableName(),
                ['names', 'created_at', 'key'],
                $violationList,
                $violations
            );
            return $this->returnSuccess(Messages::VIOLATION_ADDED);
        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles updating violation status
     */
    public function actionUpdateViolationStatus($violationId)
    {
        try {
            $this->updateModelStatus($violationId, ViolationTypes::class, Messages::UPDATE_VIOLATION);
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::UPDATE_VIOLATION . ' status',
                    'changed'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles editing violations
     */
    public function actionEditViolation($violationId)
    {
        try {
            $this->editModelData($violationId, ViolationTypes::class, [
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Violation', 'updated'));
    }
}
