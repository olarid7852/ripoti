<?php

namespace app\controllers;

use app\constants\Messages;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\models\BaseModel;
use app\models\ContactForm;
use app\models\Country;
use app\models\forms\ReportForm;
use app\models\forms\ReportTypesForm;
use app\models\State;
use app\models\ViolationTypes;
use app\services\DummyServiceInterface;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class SiteController extends BaseController
{
    /* @var  DummyServiceInterface */

    protected $dummyService;
    public $layout = 'site';


    public function __construct($id, $module, DummyServiceInterface $dummyService, $config = [])
    {
        $this->dummyService = $dummyService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
       return $this->render('index');
    }

    public function actionReportSuccess()
    {
      return $this->render('report-success');
    }

    public function actionServiceTest()
    {
        return $this->dummyService->shout('Hello World');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionReportForm()
    {
        $model = new ReportForm();
        return $this->render('report-form', [
            'model' => $model,
        ]);
    }

    public function actionCreateReportForm()
    {
        $postData = $this->getRequest()->post();
        $dataConsent = ArrayHelper::getValue($postData, 'ReportForm.data_consent');
        $violation = ArrayHelper::getValue($postData, 'ReportForm.violation_type_id');
        $formReport = new ReportForm();
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $formReport->saveData($postData);
            $report = new ReportTypesForm([
                'violation_type_id' => $violation,
                'source' => 'form',
                'data_consent' => $dataConsent,
                'form_report_id' => $formReport->id
            ]);
            $report->save();
            $transaction->commit();
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::UPDATE_REPORT,
                    'saved'
                ),
                'report-success'
            );
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError(
                $ex->getMessage(),
                'report-form'
            );
        }
    }

    public function actionStates($countryId)
    {
        if (!$this->getRequest()->isAjax) {
            $this->redirect($this->getRequest()->getReferrer());
        }
        $states = State::getStatesFromCountry($countryId);
        return $this->sendSuccessResponse($states);
    }

    /**@handle what can be reported view page
     * @return string
     */
    public function actionViolations()
    {
        $model = new ReportForm();
        return $this->render('violations', ['model' => $model]);
    }

    /**
     * @return string
     * @throws \Exception
     * @author Adaa Mgbede <adaa@cottacush.com>
     */
    public function actionCases()
    {
        $model = new ReportForm();
        $data = $this->getRequest()->get();
        $query = ReportTypesForm::getReportQuery($data);
        $reports = $query->count();
        $cases = $query->where(['!=', ReportTypesForm::tableName() . '.status', Status::STATUS_UNVERIFIED])->count();
        $countryResult = ReportTypesForm::getCountryPieChartData();
        $violationResult = ReportTypesForm::getViolationsPieChartData();
        return $this->render('cases', [
            'model' => $model,
            'cases' => $cases,
            'reports' => $reports,
            'violationResult' => $violationResult,
            'countryResult' => $countryResult
        ]);
    }
}
