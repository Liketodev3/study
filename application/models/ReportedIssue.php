<?php

class ReportedIssue extends MyAppModel
{

    const DB_TBL = 'tbl_reported_issues';
    const DB_TBL_PREFIX = 'repiss_';
    const DB_TBL_LOG = 'tbl_reported_issues_log';
    const DB_TBL_LOG_PREFIX = 'reislo_';
    /* Issue Status */
    const STATUS_PROGRESS = 1;
    const STATUS_RESOLVED = 2;
    const STATUS_ESCLATED = 3;
    const STATUS_CLOSED = 4;
    /* Issue Actions */
    const ACTION_UNSCHEDULED = 1;
    const ACTION_COMPLETE_NO_REFUND = 2;
    const ACTION_COMPLETE_HALF_REFUND = 3;
    const ACTION_COMPLETE_FULL_REFUND = 4;
    const ACTION_ESCLATE_TO_ADMIN = 5;
    /* Reported/Esclated by */
    const REPORT_BY_LEARNER = 1;
    const REPORT_BY_TEACHER = 2;
    const ESCLATE_BY_LEARNER = 1;
    const ESCLATE_BY_TEACHER = 2;
    const ISSUE_REPORTED_NOTIFICATION = 1;
    const ISSUE_RESOLVE_NOTIFICATION = 2;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->objMainTableRecord->setSensitiveFields(['repiss_status']);
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'repiss');
        $srch->joinTable(static::DB_TBL_LOG, 'LEFT JOIN', 'reislo.reislo_repiss_id = repiss.repiss_id', 'reislo');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'slesson.slesson_id=repiss.repiss_slesson_id', 'slesson');
        $srch->joinTable(ScheduledLessonDetails::DB_TBL, 'INNER JOIN', 'sldetail.sldetail_slesson_id=slesson.slesson_id', 'sldetail');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id=sldetail.sldetail_order_id', 'orders');
        $srch->joinTable('tbl_order_products', 'INNER JOIN', 'op.op_order_id=orders.order_id', 'op');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'repiss.repiss_reported_by=user.user_id', 'user');
        return $srch;
    }

    public static function getStatusFromActions($key)
    {
        $arr = [
            static::ACTION_UNSCHEDULED => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_NO_REFUND => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_HALF_REFUND => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_FULL_REFUND => static::STATUS_RESOLVED,
            static::ACTION_ESCLATE_TO_ADMIN => static::STATUS_ESCLATED
        ];
        return $arr[$key] ?? 0;
    }

    public static function getStatusArr($key = null)
    {
        $arr = [
            static::STATUS_PROGRESS => Label::getLabel('STATUS_PROGRESS'),
            static::STATUS_RESOLVED => Label::getLabel('STATUS_RESOLVED'),
            static::STATUS_ESCLATED => Label::getLabel('STATUS_ESCLATED'),
            static::STATUS_CLOSED => Label::getLabel('STATUS_CLOSED')
        ];
        if ($key === null) {
            return $arr;
        }
        return $arr[$key] ?? Label::getLabel('LBL_NA');
    }

    public static function getActionsArr($key = null)
    {
        $arr = [
            static::ACTION_UNSCHEDULED => Label::getLabel('LBL_RESET_LESSON_TO_UNSCHEDULED'),
            static::ACTION_COMPLETE_NO_REFUND => Label::getLabel('LBL_COMPLETE_AND_NO_REFUND'),
            static::ACTION_COMPLETE_HALF_REFUND => Label::getLabel('LBL_COMPLETE_AND_50%_REFUND'),
            static::ACTION_COMPLETE_FULL_REFUND => Label::getLabel('LBL_COMPLETE_AND_100%_REFUND'),
            static::ACTION_ESCLATE_TO_ADMIN => Label::getLabel('LBL_ESCLATE_TO_SUPPORT_TEAM')
        ];
        if ($key === null) {
            return $arr;
        }
        return $arr[$key] ?? Label::getLabel('LBL_NA');
    }

    public function getIssueDetails()
    {
        $srch = static::getSearchObject();
        $srch->addCondition('issrep_id', '=', $this->mainTableRecordId);
        return $srch;
    }

    public static function getIssueStatus($issueId)
    {
        $status = IssuesReported::getAttributesById($issueId, ['issrep_status']);
        return $status['issrep_status'];
    }

    public static function getCallHistory($uid)
    {
        $data_string = ['UID' => $uid];
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'https://api.cometondemand.net/api/v2/getCallHistory');
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, ["api-key: " . FatApp::getConfig('CONF_COMET_CHAT_API_KEY')]);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl_handle);
        curl_close($curl_handle);
        $callHistory = json_decode($json);
        if (isset($callHistory->success->data)) {
            return $callHistory->success->data;
        } else {
            return [];
        }
    }

    public static function getIssueStatusByLessonId($lessonId, $reportedBy)
    {
        $srch = static::getSearchObject();
        $srch->addCondition('issrep_slesson_id', '=', $lessonId);
        $srch->addCondition('issrep_reported_by', '=', $reportedBy);
        return $srch;
    }

    public static function isAlreadyReported($lessonId, $userType)
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition('issrep_slesson_id', ' = ', $lessonId);
        $srch->addCondition('issrep_reported_by', ' = ', $userType);
        $issueRow = FatApp::getDb()->fetch($srch->getResultSet());
        if ($issueRow) {
            return true;
        }
        return false;
    }

    public static function isAlreadyResolved($issrep_id)
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition('issrep_id', ' = ', $issrep_id);
        $srch->addCondition('issrep_status', ' IN ', [self::STATUS_PROGRESS, self::STATUS_RESOLVED]);
        $issueRow = FatApp::getDb()->fetch($srch->getResultSet());
        if ($issueRow) {
            return true;
        }
        return false;
    }

    public function getIssuesByLessonId($lessonId): array
    {
        $srch = new SearchBase(self::DB_TBL, 'i');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'i.issrep_reported_by = u.user_id', 'u');
        $srch->addCondition('issrep_slesson_id', '=', $lessonId);
        $srch->addMultipleFields([
            'issrep_id',
            'issrep_slesson_id',
            'issrep_comment',
            'issrep_reported_by',
            'issrep_status',
            'issrep_issues_resolve',
            'issrep_issues_resolve_type',
            'issrep_resolve_comments',
            'issrep_issues_to_report',
            'issrep_is_for_admin',
            'issrep_added_on',
            'issrep_updated_on',
            'user_id',
            'user_first_name',
            'user_last_name',
            'CONCAT(user_first_name," ", user_last_name) as user_full_name',
        ]);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        $issueRows = FatApp::getDb()->fetchAll($rs);
        if (empty($issueRows)) {
            return [];
        }
        return $issueRows;
    }

}
