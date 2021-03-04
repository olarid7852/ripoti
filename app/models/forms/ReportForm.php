<?php

namespace app\models\forms;

use app\constants\EmailTemplates;
use app\constants\MigrationConstants;
use app\jobs\executors\EmailExecutor;
use app\models\BaseModel;
use app\models\Country;
use app\models\State;
use app\models\ViolationTypes;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property mixed email
 * @property mixed fullName
 * @property mixed first_name
 * @property mixed last_name
 * @property State $state
 * @property Country $country
 */
class ReportForm extends BaseModel
{
    public $contact;
    public $violation_type_id;
    public $search_btn;
    public $start_date;
    public $date_range;
    public $end_date;
    public $reply_report;
    public $datetime_min;
    public $datetime_max;
    public $assignee_role;
    public $assignee_id;
    public $assignee_name;
    public $case_summary;
    public $case_label;
    public $status;
    public $data_consent;
    public $violation_type;

    public static $genderType = ['others' => 'Rather not say', 'male' => 'Male', 'female' => 'Female'];
    public static $ageRange = ['Below 18' => 'Below 18', '18-39' => '18-39', '40 and above' => '40 and above'];
    public static $reporterType = ['myself' => 'Myself', 'third_party' => 'Third Party', 'witness' => 'A witness'];
    public static $contactType =  ['phone-contact' => 'Phone Number', 'email-contact' => 'Email Address',
        'other-contact' => 'Other'];

    public static function tableName()
    {
        return MigrationConstants::TABLE_FORM_REPORTS;
    }

    public function rules()
    {
        return [
            [[
                'reporting_as', 'age', 'violation_type_id', 'occurred_when', 'case_subject',
                'case_description', 'country_id', 'occurred_when', 'state_id',
                'last_name', 'first_name'], 'safe'],
            [['gender','phone_number', 'email', 'other_means_of_contact','report_reply', 'contact'], 'safe'],
            ['email', 'email'],
            ['phone_number', 'integer'],
            [['case_subject'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'reporting_as' => 'I am reporting as',
            'country_id' => 'Country',
            'state_id' => 'State',
            'violation_type_id' => 'What type of violation?',
            'age' => 'Age',
            'occurred_when' => 'When did it occur?',
            'contact' => 'How would you like to be contacted?',
            'other_means_of_contact' => 'Preferred means of contact',
        ];
    }

    public function getViolation()
    {
        return $this->hasOne(ViolationTypes::class, ['id' => 'violation_type_id']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getState()
    {
        return $this->hasOne(State::class, ['id' => 'state_id']);
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getLocation()
    {
        return $this->state->name . ', ' . $this->country->name;
    }

    public static function sendReportReplyEmail($recipient, $userName, $reportReply)
    {
        $emailParams = [
            'emailTemplate' => EmailTemplates::REPORT_REPLY_TEMPLATE,
            'recipient' => $recipient,
            'subject' => EmailTemplates::SUBJECT_REPORT_REPLY,
            'emailTemplateParams' => [
                'name' => $userName,
                'reportReply' => $reportReply,
            ]
        ];
        EmailExecutor::trigger($emailParams);
    }
}
