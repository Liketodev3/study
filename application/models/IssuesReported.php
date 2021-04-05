<?php

class IssuesReported extends MyAppModel
{

    const DB_TBL = 'tbl_issues_reported';
    const DB_TBL_PREFIX = 'issrep_';
    const STATUS_OPEN = 0;
    const STATUS_PROGRESS = 1;
    const STATUS_RESOLVED = 2;
    const RESOLVE_TYPE_LESSON_UNSCHEDULED = 1;
    const RESOLVE_TYPE_LESSON_COMPLETED = 2;
    const RESOLVE_TYPE_LESSON_COMPLETED_HALF_REFUND = 3;
    const RESOLVE_TYPE_LESSON_COMPLETED_FULL_REFUND = 4;
    const ISSUE_REPORTED_NOTIFICATION = 1;
    const ISSUE_RESOLVE_NOTIFICATION = 2;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getResolveTypeArray(int $langId = 0): array
    {
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return [
            self::RESOLVE_TYPE_LESSON_UNSCHEDULED => Label::getLabel('LBL_Reset_Lesson_to:_Unscheduled'),
            self::RESOLVE_TYPE_LESSON_COMPLETED => Label::getLabel('LBL_Mark_Lesson_as:_Completed'),
            self::RESOLVE_TYPE_LESSON_COMPLETED_HALF_REFUND => Label::getLabel('LBL_Mark_Lesson_as:_Completed_and_issue_student_a_50%_refund'),
            self::RESOLVE_TYPE_LESSON_COMPLETED_FULL_REFUND => Label::getLabel('LBL_Mark_Lesson_as:_Completed_and_issue_student_a_100%_refund')
        ];
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'i');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'i.issrep_slesson_id = sl.slesson_id', 'sl');
        $srch->joinTable(ScheduledLessonDetails::DB_TBL, 'INNER JOIN', 'sld.sldetail_slesson_id = sl.slesson_id', 'sld');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'o.order_id = sld.sldetail_order_id', 'o');
        $srch->joinTable('tbl_order_products', 'INNER JOIN', 'op.op_order_id = o.order_id', 'op');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'i.issrep_reported_by = u.user_id', 'u');
        return $srch;
    }

    public function getIssueDetails()
    {
        $srch = static::getSearchObject();
        $srch->addCondition('issrep_id', '=', $this->mainTableRecordId);
        return $srch;
    }

    public static function getStatusArr($langId = 0, $filter = false)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        if (!$filter) {
            return [
                static::STATUS_OPEN => Label::getLabel('LBL_Open', $langId),
                static::STATUS_PROGRESS => Label::getLabel('LBL_In_Progress', $langId),
                static::STATUS_RESOLVED => Label::getLabel('LBL_Resolved', $langId),
            ];
        } else {
            return [static::STATUS_RESOLVED => Label::getLabel('LBL_Resolved', $langId)];
        }
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
