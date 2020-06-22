<?php
class PurchasedLessonsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPurchasedLessons($this->admin_id, true);
        $this->canView = $this->objPrivilege->canViewPurchasedLessons($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditPurchasedLessons($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $frmSearch = $this->getPurchasedLessonsForm();
        $data      = FatApp::getPostedData();
        if ($data) {
            $frmSearch->fill($data);
        }
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    protected function getPurchasedLessonsForm()
    {
        $frm          = new Form('frmPurchasedLessonsSearch');
        $arr_options  = array(
            '-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + applicationConstants::getYesNoArr($this->adminLangId);
        $arr_options1  = array(
            '-2' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + Order::getPaymentStatusArr($this->adminLangId);
        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array(
            'id' => 'keyword',
            'autocomplete' => 'off'
        ));

        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array(
            'id' => 'keyword',
            'autocomplete' => 'off'
        ));
        $frm->addSelectBox(Label::getLabel('LBL_Free_Trial', $this->adminLangId), 'op_lpackage_is_free_trial', $arr_options, -1, array(), '');
        $frm->addSelectBox(Label::getLabel('Payment Status', $this->adminLangId), 'order_is_paid', $arr_options1, -2, array(), '');

        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'order_user_id', '');
        $frm->addHiddenField('', 'op_teacher_id', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function search()
    {
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $frmSearch = $this->getPurchasedLessonsForm();
        $data      = FatApp::getPostedData();
        $post      = $frmSearch->getFormDataFromArray($data);

        $page      = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $srch = new OrderSearch(false, false);
        $srch->addGroupBy('order_id');
        $srch->joinOrderProduct();
        $srch->joinUser();
        $srch->joinTeacherLessonLanguage($this->adminLangId);
        $srch->addMultipleFields(
            array(
            'order_id',
            'order_user_id',
            'op_teacher_id',
            'op_lpackage_is_free_trial',
            'order_is_paid',
            'order_net_amount',
            'CONCAT(u.user_first_name, " " , u.user_last_name) AS learner_username',
            'CONCAT(t.user_first_name, " " , t.user_last_name) AS teacher_username',
            'COALESCE(NULLIF(sl.tlanguage_name, ""), tlang.tlanguage_identifier) AS language',
            'order_currency_code'
            )
        );
        if (isset($post['op_teacher_id']) and $post['op_teacher_id'] > 0) {
            $user_is_teacher = FatUtility::int($post['op_teacher_id']);
            $srch->addCondition('op_teacher_id', '=', $user_is_teacher);
        }
        if (isset($post['order_user_id']) and $post['order_user_id'] > 0) {
            $user_is_learner = FatUtility::int($post['order_user_id']);
            $srch->addCondition('order_user_id', '=', $user_is_learner);
        }
        if (isset($post['order_is_paid']) and $post['order_is_paid'] > -2) {
            $is_paid = FatUtility::int($post['order_is_paid']);
            $srch->addCondition('order_is_paid', '=', $is_paid);
        }
        if (isset($post['op_lpackage_is_free_trial']) and $post['op_lpackage_is_free_trial'] > -1) {
            $is_trial = FatUtility::int($post['op_lpackage_is_free_trial']);
            $srch->addCondition('op_lpackage_is_free_trial', '=', $is_trial);
        }
        $srch->addOrder('order_date_added', 'desc');
        //$srch->addCondition('order_is_paid', '!=', Order::ORDER_IS_PENDING);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs      = $srch->getResultSet();
        //echo $srch->getQuery();
        //die();

        $records = FatApp::getDb()->fetchAll($rs);
        $adminId = AdminAuthentication::getLoggedAdminId();
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false, null, false, false);
    }

    public function viewSchedules($orderId)
    {
        $srch = new ScheduledLessonSearch();
        $srch->joinTeacher();
        $srch->joinTeacherSettings();
        $srch->joinLessonLanguage($this->adminLangId);
        $srch->addCondition('slns.slesson_order_id', ' = ', $orderId);
        //echo $srch->getQuery(); die();
        $srch->addMultipleFields(
            array(
            'slns.slesson_id',
            'slns.slesson_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_ended_by',
            'slns.slesson_ended_on',
            'slns.slesson_status',
            'IFNULL(sl.tlanguage_name, tlang.tlanguage_identifier) as teacherTeachLanguageName',
            )
        );
        //$srch->addCondition( 'slns.slesson_status',' = ', ScheduledLesson::STATUS_SCHEDULED );
        $rs = $srch->getResultSet();
        //echo $srch->getQuery();
        //die();

        $data = FatApp::getDb()->fetchAll($rs);
        if ($data == false || $orderId == null) {
            Message::addErrorMessage('Error: Lessons not allocated yet.');
            FatApp::redirectUser(FatUtility::generateUrl("PurchasedLessons"));
        }
        $statusArr = ScheduledLesson::getStatusArr();
        $this->set('arr_listing', $data);
        $this->set('status_arr', $statusArr);
        $this->_template->render();
    }

    public function viewDetail($slesson_id)
    {
        $slesson_id = FatUtility::int($slesson_id);
        $statusArr = ScheduledLesson::getStatusArr();
        if (1 > $slesson_id) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        /* [ */
        $srch = new ScheduledLessonSearch(false);
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->joinTeacher();
        $srch->joinLearner();
        $srch->joinLearnerCountry($this->adminLangId);
        $srch->addCondition('slns.slesson_id', ' = ', $slesson_id);
        $srch->joinTeacherSettings();
        $srch->joinLessonLanguage($this->adminLangId);

        $srch->addMultipleFields(array(
            'slns.slesson_id',
            'slns.slesson_learner_id as learnerId',
            'ul.user_first_name as learnerFname',
            'ul.user_last_name as learnerLname',
            'CONCAT(ul.user_first_name, " ", ul.user_last_name) as learnerFullName',
            /*'ul.user_timezone as learnerTimeZone',*/
            'IFNULL(learnercountry_lang.country_name, learnercountry.country_code) as learnerCountryName',
            'slns.slesson_date',
            'slns.slesson_start_time',
            'slns.slesson_end_time',
            'slns.slesson_status',
            //'IFNULL(t_sl_l.slanguage_name, t_sl.slanguage_identifier) as teacherTeachLanguageName',
            'IFNULL(sl.tlanguage_name, tlang.tlanguage_identifier) as teacherTeachLanguageName',
            'op_lpackage_is_free_trial as is_trial',
            'op_lesson_duration'
        ));
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);

        if (!$lessonRow) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        /* ] */

        $this->set("statusArr", $statusArr);
        $this->set("lessonRow", $lessonRow);
        $this->_template->render(false, false);
    }

    public function updateStatusSetup($slesson_id = 0)
    {
        if (!$this->canEdit) {
            FatUtility::dieJsonError($this->unAuthorizeAccess);
        }
        $slesson_id = FatApp::getPostedData('slesson_id', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('slesson_status', FatUtility::VAR_INT, 0);

        if (1 > $slesson_id || 1 > $status) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $srch = new ScheduledLessonSearch(false);
        $srch->addCondition('slesson_id', '=', $slesson_id);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);
        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

		//echo "<pre>"; print_r($lessonRow); echo "</pre>"; exit;

        /*$data = ScheduledLesson::getAttributesById($slesson_id, array('slesson_id', 'slesson_status'));
        if (false == $data) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }*/
        /*[ pay for completed lesson*/
        if ($status == ScheduledLesson::STATUS_COMPLETED) {
            if ($lessonRow['slesson_is_teacher_paid'] == 0) {
                $db = FatApp::getDb();
                $db->startTransaction();
                $dataUpdateArr = array(
                    'slesson_ended_on'	=>	date('Y-m-d H:i:s')
                );

                $lessonObj = new ScheduledLesson($slesson_id);
                if ($lessonObj->payTeacherCommission()) {
                    $userNotification = new UserNotifications($lessonRow['slesson_teacher_id']);
                    $userNotification->sendWalletCreditNotification($lessonRow['slesson_id']);
                    $dataUpdateArr['slesson_is_teacher_paid'] = 1;
                }
                $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
                $sLessonObj->assignValues($dataUpdateArr);
                if (!$sLessonObj->save()) {
                    $db->rollbackTransaction();
                    FatUtility::dieJsonError($sLessonObj->getError());
                }
                $db->commitTransaction();
            } else {
                $trnObj = new Transaction($lessonRow['slesson_teacher_id']);
                if (!$trnObj->changeStatusByLessonId($slesson_id, Transaction::STATUS_COMPLETED)) {
                    FatUtility::dieJsonError($trnObj->getError());
                }
            }
        } elseif ($status == ScheduledLesson::STATUS_CANCELLED) {
            $trnObj = new Transaction($lessonRow['slesson_teacher_id']);
            if (!$trnObj->changeStatusByLessonId($slesson_id, Transaction::STATUS_DECLINED)) {
                FatUtility::dieJsonError($trnObj->getError());
            }
        }

        /*]*/

        $assignValues = array('slesson_status' => $status);
        $record = new ScheduledLesson($slesson_id);
        $record->assignValues($assignValues);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
		/*[ notifications to users */
		$userNotification = new UserNotifications($lessonRow['slesson_teacher_id']);
        $userNotification->sendSchLessonUpdateNotificationByAdmin($slesson_id, $lessonRow['slesson_learner_id'], $status, User::USER_TYPE_TEACHER);

		$userNotification = new UserNotifications($lessonRow['slesson_learner_id']);
        $userNotification->sendSchLessonUpdateNotificationByAdmin($slesson_id, $lessonRow['slesson_teacher_id'],  $status, User::USER_TYPE_LEANER);
		/*]*/

        $this->set('msg', 'Updated Successfully.');
        $this->set('slessonId', $slesson_id);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function updateOrderStatus()
    {
        if (!$this->canEdit) {
            FatUtility::dieJsonError($this->unAuthorizeAccess);
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $data = FatApp::getPostedData();
        $orderSearch =  new OrderSearch();
        $orderSearch->addMultipleFields([
            'order_id',
            'order_is_paid',
            'order_user_id',
            'order_net_amount',
            'count(sl.slesson_order_id) as totalLessons',
            'SUM(CASE WHEN sl.slesson_status = '.ScheduledLesson::STATUS_SCHEDULED.' THEN 1 ELSE 0 END) scheduledLessonsCount',
            'SUM(CASE WHEN sl.slesson_status = '.ScheduledLesson::STATUS_NEED_SCHEDULING.' THEN 1 ELSE 0 END) needToscheduledLessonsCount',
        ]);
        $orderSearch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'sl.slesson_order_id = o.order_id', 'sl');
        $orderSearch->addCondition('o.order_id', '=', FatApp::getPostedData('order_id',FatUtility::VAR_STRING,''));
        $orderSearch->addGroupBy('sl.slesson_order_id');
        $resultSet = $orderSearch->getResultSet();
        $orderInfo =  $db->fetch($resultSet);
        if(empty($orderInfo)) {
            $this->error = Label::getLabel("LBL_Invalid_Request", CommonHelper::getLangId());
            if(FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }
        if($data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['needToscheduledLessonsCount'] != $orderInfo['totalLessons']){
            $this->error = Label::getLabel("LBL_You_are_not_cancelled_the_order", CommonHelper::getLangId());
            if(FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }
        if($data['order_is_paid'] == Order::ORDER_IS_PENDING && $orderInfo['order_is_paid'] == Order::ORDER_IS_CANCELLED) {
            $this->error = Label::getLabel("LBL_Order_already_cancelled", CommonHelper::getLangId());
            if(FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        if($data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['scheduledLessonsCount'] > 0) {
            $this->error = Label::getLabel("LBL_You_are_not_cancelled_the_order_because_some_lesson_are_scheduled", CommonHelper::getLangId());
            if(FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }
        $assignValues = array('order_is_paid' => $data['order_is_paid']);
        if (!$db->updateFromArray(Order::DB_TBL, $assignValues, array('smt' => 'order_id = ?', 'vals' => array($data['order_id'])))) {
            $db->rollbackTransaction();
            $this->error = Label::getLabel("LBL_SYSTEM_ERROR", CommonHelper::getLangId());
            if(FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }
        /* [ */
        if($data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['order_net_amount'] > 0) {
            $assignValues = array('slesson_status' => ScheduledLesson::STATUS_CANCELLED);
            if (!$db->updateFromArray(ScheduledLesson::DB_TBL, $assignValues, array('smt' => 'slesson_order_id = ?', 'vals' => array($data['order_id'])))) {
                $db->rollbackTransaction();
                $this->error = $db->getError();
                if(FatUtility::isAjaxCall()) {
                    FatUtility::dieJsonError($this->error);
                }
                return false;
            }

            $transObj = new Transaction($orderInfo["order_user_id"]);
            $formattedOrderId = "#".$orderInfo["order_id"];

            $utxn_comments = Label::getLabel('LBL_Order_Refund:_{order-id}');
            $utxn_comments = str_replace("{order-id}", $formattedOrderId, $utxn_comments);

            $txnDataArr = array(
                'utxn_user_id'	=>	$orderInfo["order_user_id"],
                'utxn_date' => date('Y-m-d H:i:s'),
                'utxn_credit'	=>	$orderInfo['order_net_amount'],
                'utxn_status'	=>	Transaction::STATUS_COMPLETED,
                'utxn_order_id'	=>	$orderInfo["order_id"],
                'utxn_comments'	=>	$utxn_comments,
                'utxn_type'		=>	Transaction::TYPE_ORDER_CANCELLED_REFUND
            );
            if (!$transObj->addTransaction($txnDataArr)) {
                    $db->rollbackTransaction();
                    $this->error = $transObj->getError();
                    if(FatUtility::isAjaxCall()) {
                        // Message::addErrorMessage($this->error);
                        FatUtility::dieJsonError($this->error);
                    }
                    return false;
            }
        }
        $db->commitTransaction();

        if(FatUtility::isAjaxCall()) {
            // Message::addMessage(Label::getLabel('LBL_Updated_Successfully.'));
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Updated_Successfully.'));
        }
        $this->set('msg', Label::getLabel('LBL_Updated_Successfully.'));
        $this->_template->render(false, false, 'json-success.php');
    }
}
