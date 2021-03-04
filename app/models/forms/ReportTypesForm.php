<?php

namespace app\models\forms;

use app\constants\MigrationConstants;
use app\constants\Status;
use app\constants\Source;
use app\models\BaseModel;
use app\models\Country;
use app\models\State;
use app\models\EmailReport;
use app\models\twitter\TwitterReport;
use app\models\ViolationTypes;
use Exception;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * ReportTypesForm Form is the model behind the report types form.
 * @property state $state
 * @property Country $country
 * @property ViolationTypes $violation
 * @property mixed|null first_name
 * @property mixed|null last_name
 * @property mixed source
 * @property ReportForm formReports
 * @property EmailReport emailReports
 * @property TwitterReport twitterReports
 */
class ReportTypesForm extends BaseModel
{

    public $reply_report;
    public $search_btn;
    public $start_date;
    public $date_range;
    public $end_date;
    public $datetime_min;
    public $datetime_max;
    public $assignee_role;
    public $assignee_id;
    public $assignee_name;
    public $attach;
    public $case_summary;
    public $case_label;
    public $report_reply;
    public $violation_type;

    public static $sourceType = ['form' => 'Form', 'email' => 'Email', 'twitter' => 'Twitter'];
    public static $status = [
            Status::STATUS_VERIFIED => 'Verified',
            Status::STATUS_UNVERIFIED => 'Unverified',
            Status::STATUS_CASE => 'Case created',
        ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return MigrationConstants::TABLE_REPORTS;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['source', 'status', 'reply_report', 'data_consent'], 'safe'],
            ['data_consent', 'boolean'],
        ];
    }

    public function getReportSource()
    {
        return strtoupper($this->source);
    }

    public function getFormReports()
    {
        return $this->hasOne(ReportForm::class, ['id' => 'form_report_id']);
    }

    public function getTwitterReports()
    {
        return $this->hasOne(TwitterReport::class, ['id' => 'twitter_report_id']);
    }

    public function getEmailReports()
    {
        return $this->hasOne(EmailReport::class, ['id' => 'email_report_id']);
    }

    public function getViolation()
    {
        return $this->hasOne(ViolationTypes::class, ['id' => 'violation_type_id']);
    }

    public function getReporterName()
    {
        if ($this->source === Source::SOURCE_FORM) {
            return $this->formReports->fullName;
        }
        if ($this->source === Source::SOURCE_TWITTER) {
            return $this->twitterReports->twitter_handle;
        }
        if ($this->source === Source::SOURCE_EMAIL) {
            return $this->emailReports->reporter_email;
        }
    }

    public static function getMonthlyReports()
    {
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $result = [];
        foreach ($months as $month) {
            $reports = self::find()
                ->where("MONTH(created_at) = $month")
                ->count();
            $result[] = [$month => $reports];
        }
        return $result;
    }

    public static function getMonthlyReportsCount()
    {
        $result = self::getMonthlyReports();
        $data = [];
        for ($index = 0; $index <= count($result); $index++) {
            $count = implode(', ', array_column($result, $index));
            array_push($data, $count);
        }
        return $data;
    }

    public static function getViolationsPieChartData()
    {
        $reports = self::find()
            ->select(['violation_types.names', 'COUNT(violation_types.id) AS count'])
            ->leftJoin(ViolationTypes::tableName(), 'violation_types.id = reports.violation_type_id')
            ->where(['!=', 'reports.violation_type_id', ''])
            ->groupBy(['violation_types.id'])
            ->orderBy(['count' => SORT_DESC])->limit('3')
            ->asArray()->all();
        return ArrayHelper::map($reports, 'names', 'count');
    }

    public static function getCountryPieChartData()
    {
        $reportCountry = self::find()
            ->select(['countries.name', 'COUNT(countries.id) AS count'])
            ->leftJoin(ReportForm::tableName(), 'form_reports.id = reports.form_report_id')
            ->leftJoin(TwitterReport::tableName(), 'twitter_reports.id = reports.twitter_report_id')
            ->leftJoin(EmailReport::tableName(), 'email_reports.id = reports.email_report_id')
            ->leftJoin(Country::tableName(), 'form_reports.country_id = countries.id OR '
                . 'twitter_reports.country_id = countries.id OR email_reports.country_id = countries.id')
            ->groupBy(['countries.id'])
            ->orderBy(['count' => SORT_DESC])->limit('7')
            ->asArray()->all();
        return ArrayHelper::map($reportCountry, 'name', 'count');
    }

    public function getReporterEmail()
    {
        if ($this->source === Source::SOURCE_EMAIL) {
            return htmlentities($this->emailReports->reporter_email);
        }
        if ($this->source === Source::SOURCE_FORM) {
            return $this->formReports->email;
        }
    }

    public function getReportType()
    {
        if ($this->source === Source::SOURCE_FORM) {
            return $this->formReports;
        }
        if ($this->source === Source::SOURCE_TWITTER) {
            return $this->twitterReports;
        }
        if ($this->source === Source::SOURCE_EMAIL) {
            return $this->emailReports;
        }
    }

    /**
     * @param $data
     * @return ActiveQuery
     * @throws Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles filtering reports
     */
    public static function getReportQuery($data)
    {
        $dateRange = ArrayHelper::getValue($data, 'date_range');
        $violation = ArrayHelper::getValue($data, 'violation');
        $countryId = ArrayHelper::getValue($data, 'country_id');
        $status = ArrayHelper::getValue($data, 'status');
        $source = ArrayHelper::getValue($data, 'source');
        $query = self::find()
            ->joinWith(['formReports', 'twitterReports', 'emailReports']);
        if ($dateRange) {
            $query = self::getDateRangeFilter($dateRange, $query, self::tableName());
        }
        if ($violation) {
            $query->andFilterWhere([ReportTypesForm::tableName() . '.violation_type_id' => $violation]);
        }
        if ($status) {
            $query->andFilterWhere([ReportTypesForm::tableName() . '.status' => $status]);
        }
        if ($source) {
            $query->andFilterWhere([ReportTypesForm::tableName() . '.source' => $source]);
        }
        if ($countryId) {
            $query->leftJoin(Country::tableName(), 'form_reports.country_id = countries.id OR ' .
                'twitter_reports.country_id = countries.id OR email_reports.country_id = countries.id')
                ->andWhere([Country::tableName() . '.id' => $countryId]);
        }
        return $query;
    }
}
