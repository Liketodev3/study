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
    const ACTION_COMPLETE_ZERO_REFUND = 2;
    const ACTION_COMPLETE_HALF_REFUND = 3;
    const ACTION_COMPLETE_FULL_REFUND = 4;
    const ACTION_ESCLATE_TO_ADMIN = 5;
    /* Action User Types */
    const USER_TYPE_LEARNER = 1;
    const USER_TYPE_TEACHER = 2;
    const USER_TYPE_SUPPORT = 3;
    const ISSUE_REPORTED_NOTIFICATION = 1;
    const ISSUE_RESOLVE_NOTIFICATION = 2;

    private $userId;
    private $userType;

    public function __construct($id = 0, $userId = 0, $userType = 0)
    {
        $this->userId = FatUtility::int($userId);
        $this->userType = FatUtility::int($userType);
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->objMainTableRecord->setSensitiveFields(['repiss_status']);
    }

    public function setupIssue(int $sldetailId, int $titleId, string $comment): bool
    {
        $options = IssueReportOptions::getOptionsArray($this->commonLangId, User::USER_TYPE_LEANER);
        $this->setFldValue('repiss_status', static::STATUS_PROGRESS);
        $this->assignValues([
            'repiss_comment' => $comment,
            'repiss_sldetail_id' => $sldetailId,
            'repiss_title' => $options[$titleId] ?? 'NA',
            'repiss_reported_by_type' => static::USER_TYPE_LEARNER,
            'repiss_reported_on' => date('Y-m-d H:i:s'),
            'repiss_reported_by' => $this->userId
        ]);
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    public function setupIssueAction(int $action, string $comment, bool $closed = false): bool
    {
        $db = FatApp::getDb();
        if (!$db->startTransaction()) {
            $this->error = Label::getLabel('LBL_PLEASE_TRY_AGAIN');
            return false;
        }
        $issueId = $this->getMainTableRecordId();
        $record = new TableRecord(ReportedIssue::DB_TBL_LOG);
        $record->assignValues([
            'reislo_action' => $action,
            'reislo_comment' => $comment,
            'reislo_repiss_id' => $issueId,
            'reislo_added_by' => $this->userId,
            'reislo_added_on' => date('Y-m-d H:i:s'),
            'reislo_added_by_type' => $this->userType
        ]);
        if (!$record->addNew()) {
            $this->error = $record->getError();
            $db->rollbackTransaction();
            return false;
        }
        switch ($action) {
            case static::ACTION_UNSCHEDULED:
                $status = static::STATUS_RESOLVED;
                break;
            case static::ACTION_COMPLETE_ZERO_REFUND:
                $status = static::STATUS_RESOLVED;
                break;
            case static::ACTION_COMPLETE_HALF_REFUND:
                $status = static::STATUS_RESOLVED;
                if ($closed && !$this->executeLessonTransactions($issueId, 50)) {
                    $db->rollbackTransaction();
                    return false;
                }
                break;
            case static::ACTION_COMPLETE_FULL_REFUND:
                $status = static::STATUS_RESOLVED;
                if ($closed && !$this->executeLessonTransactions($issueId, 100)) {
                    $db->rollbackTransaction();
                    return false;
                }
                break;
            case static::ACTION_ESCLATE_TO_ADMIN:
                $status = static::STATUS_ESCLATED;
                break;
            default:
                $this->error = Label::getLabel('LBL_INVALID_ACTION_PERFORMED');
                $db->rollbackTransaction();
                return false;
        };
        if ($closed && $this->userType == static::USER_TYPE_SUPPORT) {
            $status = static::STATUS_CLOSED;
        }
        $this->setFldValue('repiss_status', $status);
        $this->setFldValue('repiss_updated_on', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        if (!$db->commitTransaction()) {
            $this->error = Label::getLabel('LBL_PLEASE_TRY_AGAIN');
            $db->rollbackTransaction();
            return false;
        }
        return true;
    }

    private function executeLessonTransactions(int $issueId, int $percent): bool
    {
        $issue = static::getIssueById($issueId);
        if (empty($issue)) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }
        if ($percent == 100) {
            $txn = new Transaction($issue['sldetail_learner_id']);
            $data = [
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_credit' => $issue['order_net_amount'],
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_type' => Transaction::TYPE_ISSUE_REFUND,
                'utxn_slesson_id' => $issue['repiss_sldetail_id'],
                'utxn_comments' => 'Refund of order ' . $issue['order_id']
            ];
            if (!$txn->addTransaction($data)) {
                $this->error = $txn->getError();
                return false;
            }
            return true;
        }
        if ($percent == 50) {
            $txn = new Transaction($issue['sldetail_learner_id']);
            $data = [
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_credit' => $issue['order_net_amount'] / 2,
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_type' => Transaction::TYPE_ISSUE_REFUND,
                'utxn_slesson_id' => $issue['repiss_sldetail_id'],
                'utxn_comments' => 'Refund of order ' . $issue['order_id']
            ];
            if (!$txn->addTransaction($data)) {
                $this->error = $txn->getError();
                return false;
            }
            $txn = new Transaction($issue['slesson_teacher_id']);
            $data = [
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_credit' => $issue['order_net_amount'] / 2,
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_type' => Transaction::TYPE_LESSON_BOOKING,
                'utxn_slesson_id' => $issue['repiss_sldetail_id'],
                'utxn_comments' => 'Payment of order ' . $issue['order_id']
            ];
            if (!$txn->addTransaction($data)) {
                $this->error = $txn->getError();
                return false;
            }
        }
        return true;
    }

    public static function getIssueById($issueId)
    {
        $adminLangId = CommonHelper::getLangId();
        $srch = ReportedIssue::getSearchObject();
        $srch->joinTable(UserSetting::DB_TBL, 'INNER JOIN', 'slesson.slesson_teacher_id = us.us_user_id', 'us');
        $srch->joinTable(TeachingLanguage::DB_TBL, 'INNER JOIN', 'slesson.slesson_slanguage_id = tlang.tlanguage_id', 'tlang');
        $srch->joinTable(TeachingLanguage::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sll.tlanguagelang_tlanguage_id = tlang.tlanguage_id AND sll.tlanguagelang_lang_id = ' . $adminLangId, 'sll');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'sldetail.sldetail_learner_id = ul.user_id', 'ul');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'slesson.slesson_teacher_id = ut.user_id', 'ut');
        $srch->addMultipleFields(['repiss.repiss_id', 'repiss.repiss_title', 'repiss.repiss_sldetail_id',
            'repiss.repiss_reported_on', 'repiss.repiss_reported_by', 'repiss.repiss_reported_by_type',
            'repiss.repiss_status', 'repiss.repiss_comment', 'repiss.repiss_updated_on', "us.*",
            'sldetail.sldetail_order_id', 'sldetail.sldetail_learner_id', 'sldetail.sldetail_learner_join_time',
            'slesson.slesson_teacher_join_time', 'sldetail.sldetail_learner_end_time', 'slesson.slesson_teacher_id',
            'slesson.slesson_teacher_end_time', 'slesson.slesson_ended_by', 'slesson.slesson_ended_on',
            'IFNULL(sll.tlanguage_name, tlang.tlanguage_identifier) as tlanguage_name',
            'CONCAT(user.user_first_name, " ", user.user_last_name) AS reporter_username',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) AS learner_username',
            'CONCAT(ut.user_first_name, " ", ut.user_last_name) AS teacher_username',
            'order_net_amount', 'order_id', 'order_discount_total',
            'op_qty', 'op_lpackage_is_free_trial', 'op_unit_price',
        ]);
        $srch->addCondition('repiss.repiss_id', '=', FatUtility::int($issueId));
        $srch->addGroupBy('repiss.repiss_id');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    public static function getIssueLogsById($issueId)
    {
        $srch = new SearchBase(ReportedIssue::DB_TBL_LOG, 'reislo');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'user.user_id=reislo.reislo_added_by', 'user');
        $srch->addCondition('reislo_repiss_id', '=', FatUtility::int($issueId));
        $srch->addMultipleFields([
            'CONCAT(user.user_first_name, " ", user.user_last_name) AS user_fullname', 'reislo_repiss_id',
            'reislo_action', 'reislo_comment', 'reislo_added_on', 'reislo_added_by', 'reislo_added_by_type']);
        $srch->addOrder('reislo.reislo_id', 'ASC');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'repiss');
        $srch->joinTable(ScheduledLessonDetails::DB_TBL, 'INNER JOIN', 'sldetail.sldetail_id=repiss.repiss_sldetail_id', 'sldetail');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'slesson.slesson_id=sldetail.sldetail_slesson_id', 'slesson');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id=sldetail.sldetail_order_id', 'orders');
        $srch->joinTable('tbl_order_products', 'INNER JOIN', 'op.op_order_id=orders.order_id', 'op');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'repiss.repiss_reported_by=user.user_id', 'user');
        return $srch;
    }

    public function getLessonToReport(int $sldetailId)
    {
        $srch = new SearchBase('tbl_scheduled_lesson_details', 'sldetail');
        $srch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'slesson.slesson_id=sldetail.sldetail_slesson_id', 'slesson');
        $srch->addMultipleFields(['slesson_end_date', 'slesson_end_time']);
        $srch->addCondition('sldetail.sldetail_learner_id', '=', $this->userId);
        $srch->addCondition('sldetail.sldetail_id', '=', $sldetailId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $lesson = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($lesson)) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }
        $reportHours = FatApp::getConfig('CONF_REPORT_ISSUE_HOURS_AFTER_COMPLETION');
        $lessonEnddate = $lesson['slesson_end_date'] . ' ' . $lesson['slesson_end_time'];
        $lessonReportDate = strtotime($lessonEnddate . " +" . $reportHours . " hour");
        if ($lessonReportDate <= strtotime(date('Y-m-d H:i:s'))) {
            $this->error = Label::getLabel('LBL_REPORT_TIME_EXPIRED');
            return false;
        }
        $srch = new SearchBase('tbl_reported_issues');
        $srch->addCondition('repiss_reported_by', '=', $this->userId);
        $srch->addCondition('repiss_sldetail_id', '=', $sldetailId);
        $srch->setPageSize(1);
        $srch->getResultSet();
        if ($srch->recordCount() > 0) {
            $this->error = Label::getLabel('LBL_ALREADY_REPORTED_ISSUE');
            return false;
        }
        return $lesson;
    }

    public static function getStatusFromActions($key)
    {
        $arr = [
            static::ACTION_UNSCHEDULED => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_ZERO_REFUND => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_HALF_REFUND => static::STATUS_RESOLVED,
            static::ACTION_COMPLETE_FULL_REFUND => static::STATUS_RESOLVED,
            static::ACTION_ESCLATE_TO_ADMIN => static::STATUS_ESCLATED
        ];
        return $arr[$key] ?? 0;
    }

    public static function getUserTypeArr($key = null)
    {
        $arr = [
            static::USER_TYPE_LEARNER => Label::getLabel('USER_LEARNER'),
            static::USER_TYPE_TEACHER => Label::getLabel('USER_TEACHER'),
            static::USER_TYPE_SUPPORT => Label::getLabel('USER_SUPPORT')
        ];
        if ($key === null) {
            return $arr;
        }
        return $arr[$key] ?? Label::getLabel('LBL_NA');
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
            static::ACTION_COMPLETE_ZERO_REFUND => Label::getLabel('LBL_COMPLETE_AND_ZERO_REFUND'),
            static::ACTION_COMPLETE_HALF_REFUND => Label::getLabel('LBL_COMPLETE_AND_50%_REFUND'),
            static::ACTION_COMPLETE_FULL_REFUND => Label::getLabel('LBL_COMPLETE_AND_100%_REFUND'),
            static::ACTION_ESCLATE_TO_ADMIN => Label::getLabel('LBL_ESCLATE_TO_SUPPORT_TEAM')
        ];
        if ($key === null) {
            return $arr;
        }
        return $arr[$key] ?? Label::getLabel('LBL_NA');
    }

}
