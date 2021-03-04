<?php

namespace app\controllers;

use app\constants\Messages;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\models\BaseModel;
use app\models\cases\CaseAssignment;
use Lukasoppermann\Httpstatus\Httpstatus;
use Yii;
use CottaCush\Yii2\Controller\BaseController as UtilsController;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Class BaseController
 * @package app\controllers
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class BaseController extends UtilsController
{
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function beforeAction($action)
    {
        $this->loginRequireBeforeAction();
        return parent::beforeAction($action);
    }

    /**
     * @var Httpstatus $httpStatuses
     */
    protected $httpStatuses;

    public function init()
    {
        parent::init();
        $this->httpStatuses = new Httpstatus();
    }

    /**
     * Executed after action
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $this->setSecurityHeaders();

        /**
         * Set Current Transaction in New Relic
         * @author Adegoke Obasa <goke@cottacush.com>
         */
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($action->controller->id . '/' . $action->id);
        }
        return $result;
    }

    /**
     * Set Headers to prevent Click-jacking and XSS
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    private function setSecurityHeaders()
    {
        $headers = Yii::$app->response->headers;
        $headers->add('X-Frame-Options', 'DENY');
        $headers->add('X-XSS-Protection', '1');
    }

    /**
     * Allow sending success response
     * @param $data
     * @return array
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function sendSuccessResponse($data)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->setStatusCode(200, $this->httpStatuses->getReasonPhrase(200));

        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public function getCaseId($caseId)
    {
        $case = CaseAssignment::findOne(['case_id' => $caseId]);
        return $case->id;
    }

    /**
     * Allows sending error response
     * @param $message
     * @param $code
     * @param $httpStatusCode
     * @param null $data
     * @return array
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     */
    public function sendErrorResponse($message, $code, $httpStatusCode, $data = null)
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->setStatusCode($httpStatusCode, $this->httpStatuses->getReasonPhrase($httpStatusCode));

        $response = [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];

        if (!is_null($data)) {
            $response["data"] = $data;
        }

        return $response;
    }

    /**
     * Sends fail response
     * @param $data
     * @param int $httpStatusCode
     * @return array
     * @author Oluwarotimi Akintewe <akintewe.rotimi@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function sendFailResponse($data, $httpStatusCode = 500)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->setStatusCode($httpStatusCode, $this->httpStatuses->getReasonPhrase($httpStatusCode));

        return [
            'status' => 'fail',
            'data' => $data
        ];
    }

    /**
     * This flashes error message and sends to the view
     * @param $message
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function flashError($message)
    {
        \Yii::$app->session->setFlash('danger', $message);
    }

    /**
     * This flashes success message and sends to the view
     * @param $message
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function flashSuccess($message)
    {
        \Yii::$app->session->setFlash('success', $message);
    }

    /**
     * Get Yii2 request object
     * @return \yii\console\Request|\yii\web\Request
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getRequest()
    {
        return Yii::$app->request;
    }

    /**
     * Get Yii2 response object
     * @return \yii\console\Request|\yii\web\Response
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getResponse()
    {
        return Yii::$app->response;
    }

    /**
     * Get Yii2 session object
     * @return mixed|\yii\web\Session
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getSession()
    {
        return Yii::$app->session;
    }

    /**
     * Get Yii2 security object
     * @return \yii\base\Security
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getSecurity()
    {
        return Yii::$app->security;
    }

    /**
     * Get web user
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getUser()
    {
        return Yii::$app->user;
    }


    public function getSenderId()
    {
        return $this->getUser()->identity->id;
    }

    /**
     * show flash messages
     * @param bool $sticky
     * @return string
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function showFlashMessages($sticky = false)
    {
        $timeout = $sticky ? 0 : 5000;
        $flashMessages = [];
        $allMessages = $this->getSession()->getAllFlashes();
        foreach ($allMessages as $key => $message) {
            if (is_array($message)) {
                $message = $this->mergeFlashMessages($message);
            }
            $flashMessages[] = [
                'message' => $message,
                'type' => $key === 'danger' ? 'error' : $key,
                'timeout' => $timeout
            ];
        }
        $this->getSession()->removeAllFlashes();
        return Html::script('var notifications =' . json_encode($flashMessages));
    }

    /**
     * Returns the user for the current module
     * @return \yii\web\User null|object
     * @throws \yii\base\InvalidConfigException
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function getModuleUser()
    {
        return $this->module->get('user');
    }

    /**
     * @return int|string
     * @throws \yii\base\InvalidConfigException
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public function getUserId()
    {
        return $this->getUser()->id;
    }

    /**
     * @param $modelClass
     * @param $modelId
     * @return bool
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function loadModelData($modelClass, $modelId)
    {
        $model = $modelClass::findOne($modelId);
        if (!$model) {
            return false;
        }
        $postData = $this->getRequest()->post();
        $model->load($postData);
        return $model;
    }

    /**
     * @param $id
     * @param $modelClass
     * @param $entity
     * @param null $scenario
     * @return bool|Response
     * @throws EntityNotUpdatedException
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function updateModelStatus($id, $modelClass, $entity, $scenario = null)
    {
        $model = $this->loadModelData($modelClass, $id);
        if (!$model) {
            throw new EntityNotUpdatedException(Messages::getNotExistWarning($entity));
        }
        if ($scenario) {
            $model->scenario = $scenario;
        }
        if (!$model->save()) {
            throw new EntityNotUpdatedException($model->getFirstError());
        }
        return true;
    }

    /**
     * @param $id
     * @param $modelClass
     * @param null $updateOptions
     * @return bool
     * @throws EntityNotSavedException
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function editModelData($id, $modelClass, $updateOptions = null, $scenario = null)
    {
        $model = $this->loadModelData($modelClass, $id);
        if ($updateOptions) {
            foreach ($updateOptions as $optionKey => $optionValue) {
                $model[$optionKey] = $optionValue;
            }
        }
        if ($scenario) {
            $model->scenario = $scenario;
        }
        if (!$model->save()) {
            throw new EntityNotSavedException($model->getFirstError());
        }
        return true;
    }

    public function canAccess(
        $permissionKeys,
        $redirect = true,
        $fullAccessKey = null,
        $errorMsg = null,
        $defaultUrl = null
    ) {
        if ($this->getModuleUser()->isGuest) {
            $this->getModuleUser()->loginRequired();
        }
        if (!is_array($permissionKeys)) {
            $permissionKeys = [$permissionKeys];
        }
        foreach ($permissionKeys as $permissionKey) {
            if ($this->getPermissionManager()->canAccess($permissionKey)) {
                return true;
            }
        }
        if ($redirect) {
            $this->flashError(Messages::PERMISSION_NOT_GRANTED);
            $request = $this->getRequest();
            $referrerUrl = $defaultUrl ?: $request->referrer;
            $this->redirect($referrerUrl)->send();
            Yii::$app->end(); //this enforces the redirect
        }
        return false;
    }
}
