<?php

namespace app\controllers;

use app\constants\Status;
use app\models\Admin;
use app\models\forms\CasesTypesForm;
use app\models\forms\ReportTypesForm;
use app\models\ViolationTypes;
use yii\data\ActiveDataProvider;

class DashboardController extends BaseController
{
    /**
     * @param null $dateRange
     * @param null $country
     * @return string
     * @throws \Exception
     * @handles data for dashboard
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionIndex($dateRange = null, $country = null)
    {
        $this->layout = 'dashboard';
        $model = new ReportTypesForm();
        $data = $this->getRequest()->get();
        $query = ReportTypesForm::getReportQuery($data);
        $reports = $query->count();
        $verifiedReports = $query
            ->andWhere(['!=', ReportTypesForm::tableName() . '.status', Status::STATUS_UNVERIFIED])
            ->count();
        $unVerifiedReports = $query->filterWhere(['status' => Status::STATUS_UNVERIFIED])->count();
        $users = Admin::find()->where(['status' => Status::STATUS_ACTIVE])->count();
        $caseQuery = CasesTypesForm::find();
        $cases = $caseQuery->count();
        $withdrawnCases = $caseQuery->where(['status' => Status::STATUS_WITHDRAW])->count();
        $pendingCases = $caseQuery
            ->where(['!=', CasesTypesForm::tableName() . '.status', Status::STATUS_WITHDRAW && Status::STATUS_RESOLVED])
            ->count();
        $resolvedCases = $caseQuery->where(['status' => Status::STATUS_RESOLVED])->count();
        $violation = ViolationTypes::find()->joinWith(['report']);
        $reportResult = ReportTypesForm::getMonthlyReportsCount();
        $caseResult = CasesTypesForm::getMonthlyCasesCount();

        $reportData = new ActiveDataProvider(
            [
                'query' => $violation,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]
        );
        return $this->render('/default/index', [
            'model' => $model,
            'reports' => $reports,
            'reportResult' => $reportResult,
            'caseResult' => $caseResult,
            'cases' => $cases,
            'users' => $users,
            'unVerifiedReports' => $unVerifiedReports,
            'verifiedReports' => $verifiedReports,
            'withdrawnCases' => $withdrawnCases,
            'pendingCases' => $pendingCases,
            'resolvedCases' => $resolvedCases,
            'reportData' => $reportData,
            'dateRange' => $dateRange,
            'country' => $country,
        ]);
    }
}
