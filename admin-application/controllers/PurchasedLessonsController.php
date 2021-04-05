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
        $frmSearch = $this->getOrderPurchasedLessonsForm();
        $data      = FatApp::getPostedData();
        if ($data) {
            $frmSearch->fill($data);
        }
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    public function view(string $orderId = '')
    {
        if (empty($orderId)) {
            FatApp::redirectUser(CommonHelper::generateUrl('PurchasedLessons'));
        }

        $orderSearch = new OrderSearch();
        $orderSearch->addMultipleFields(array(
            'order_id', 'order_user_id', 'order_date_added', 'order_is_paid', 'order_net_amount',
            'order_wallet_amount_charge', 'order_discount_total', 'order_date_added', 'op_invoice_number',
            'slesson_date', 'slesson_start_time', 'slesson_end_date', 'slesson_end_time', 'op_teacher_id',
            'op_grpcls_id', 'op_qty', 'op_unit_price', 'op_commission_charged', 'op_commission_percentage',
            'op_refund_qty', 'op_total_refund_amount', 'op_lpackage_is_free_trial', 'op_lesson_duration',
            'CONCAT(u.user_first_name, " ", u.user_last_name) as userFullName', 'CONCAT(t.user_first_name, " ", t.user_last_name) as teacherFullName',
            't.user_timezone as teacherTimezone', 'u.user_timezone as userTimezone', 'tcred.credential_email as teacherEmail',
            'cred.credential_email as userEmail', 'grpcls_title', 'grpcls_status',
            'IFNULL(tlanguage_name, tlanguage_identifier) as teachLang',
            'IFNULL(uCountryLang.country_name, " ") as uCountryName',
            'IFNULL(tCountryLang.country_name, " ") as tCountryName',
        ));
        $orderSearch->joinOrderProduct($this->adminLangId);
        $orderSearch->joinUser();
        $orderSearch->joinTable(Country::DB_TBL, 'LEFT JOIN', 'uCountry.country_id = u.user_country_id', 'uCountry');
        $orderSearch->joinTable(Country::DB_TBL_LANG, 'LEFT JOIN', 'uCountry.country_id = uCountryLang.countrylang_country_id', 'uCountryLang');
        $orderSearch->joinUserCredentials();
        $orderSearch->joinTeacherLessonLanguage($this->adminLangId);
        $orderSearch->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 't.user_id = tcred.credential_user_id', 'tcred');
        $orderSearch->joinTable(Country::DB_TBL, 'LEFT JOIN', 'tCountry.country_id = t.user_country_id', 'tCountry');
        $orderSearch->joinTable(Country::DB_TBL_LANG, 'LEFT JOIN', 'tCountry.country_id = tCountryLang.countrylang_country_id', 'tCountryLang');
        $orderSearch->joinTable(TeacherGroupClasses::DB_TBL, 'LEFT OUTER JOIN', 'grpcls.grpcls_id = op_grpcls_id', 'grpcls');
        $orderSearch->addCondition('order_id', '=', $orderId);
        $orderSearch->addCondition('order_type', '=', Order::TYPE_LESSON_BOOKING);

        $resultSet = $orderSearch->getResultSet();
        $orderDeatils = FatApp::getDb()->fetch($resultSet);
        if (empty($orderDeatils)) {
            Message::addErrorMessage(Label::getLabel('LBL_INVALID_REQUEST.'));
            FatApp::redirectUser(CommonHelper::generateUrl('PurchasedLessons'));
        }

        $order =  new Order($orderId);
        $orderPayments = $order->getOrderPayments(array("order_id" => $orderId));

        $form = $this->getPaymentForm($orderId);

        $this->set('yesNoArr', applicationConstants::getYesNoArr($this->adminLangId));
        $this->set('order', $orderDeatils);
        $this->set('orderPayments', $orderPayments);
        $this->set('adminLangId', $this->adminLangId);
        $this->set('form', $form);

        $this->_template->render();
    }



    private function getPaymentForm(string $orderId)
    {
        $form = new Form('frmPayment');
        $form->addHiddenField('', 'opayment_order_id', $orderId);
        $form->addTextArea(Label::getLabel('LBL_Comments', $this->adminLangId), 'opayment_comments', '')->requirements()->setRequired();
        $form->addRequiredField(Label::getLabel('LBL_Payment_Method', $this->adminLangId), 'opayment_method');
        $form->addRequiredField(Label::getLabel('LBL_Txn_ID', $this->adminLangId), 'opayment_gateway_txn_id');
        $form->addRequiredField(Label::getLabel('LBL_Amount', $this->adminLangId), 'opayment_amount')->requirements()->setFloatPositive(true);
        $form->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $form;
    }

    protected function getOrderPurchasedLessonsForm()
    {
        $frm          = new Form('orderPurchasedLessonsSearchForm');
        $arr_options  = array(
            '-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + applicationConstants::getYesNoArr($this->adminLangId);
        $arr_options1  = array(
            '-2' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)
        ) + Order::getPaymentStatusArr($this->adminLangId);
        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array(
            'id' => 'teacher',
            'autocomplete' => 'off'
        ));

        $keyword      = $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array(
            'id' => 'learner',
            'autocomplete' => 'off'
        ));
        $frm->addSelectBox(Label::getLabel('LBL_Free_Trial', $this->adminLangId), 'op_lpackage_is_free_trial', $arr_options, -1, array(), '');
        $frm->addSelectBox(Label::getLabel('Payment Status', $this->adminLangId), 'order_is_paid', $arr_options1, -2, array(), '');
        $frm->addSelectBox(Label::getLabel('LBL_Class_Type', $this->adminLangId), 'class_type', ApplicationConstants::getClassTypes($this->adminLangId));

        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'order_user_id', '');
        $frm->addHiddenField('', 'op_teacher_id', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getPurchasedLessonsSearchForm($status = "all", $orderId = null)
    {
        $frm = new Form('purchasedLessonsSearchForm');
        $isFreeTrialOption  = array('-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)) + applicationConstants::getYesNoArr($this->adminLangId);
        $lessonStatusOption = array('-1' => Label::getLabel('LBL_Does_Not_Matter', $this->adminLangId)) + ScheduledLesson::getStatusArr();
        $frm->addTextBox(Label::getLabel('LBL_Teacher', $this->adminLangId), 'teacher', '', array('id' => 'teacher', 'autocomplete' => 'off'));

        $frm->addTextBox(Label::getLabel('LBL_Learner', $this->adminLangId), 'learner', '', array('id' => 'learner', 'autocomplete' => 'off'));

        $frm->addSelectBox(Label::getLabel('LBL_Free_Trial', $this->adminLangId), 'op_lpackage_is_free_trial', $isFreeTrialOption, -1, array(), '');

        $statusFld = $frm->addSelectBox(Label::getLabel('Lesson_Status', $this->adminLangId), 'slesson_status', $lessonStatusOption, -1, array(), '');
        if ($status != "all" && array_key_exists($status, $lessonStatusOption)) {
            $statusFld->value = $status;
        }

        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'slesson_teacher_id', '');
        $frm->addHiddenField('', 'sldetail_learner_id', '');
        $orderIdFld =  $frm->addHiddenField('', 'sldetail_order_id', '');
        if (!empty($orderId)) {
            $orderIdFld->value = $orderId;
        }

        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function purchasedLessonsSearch()
    {
        $searchFrm = $this->getPurchasedLessonsSearchForm();
        $postData = FatApp::getPostedData();
        $data = $searchFrm->getFormDataFromArray($postData);
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $scheduledLessonSearchObj = new ScheduledLessonSearch(false);
        $scheduledLessonSearchObj->joinTeacher();
        $scheduledLessonSearchObj->joinLearner();
        $scheduledLessonSearchObj->joinIssueReported();
        $scheduledLessonSearchObj->joinOrder();
        $scheduledLessonSearchObj->joinOrderProducts();
        $scheduledLessonSearchObj->joinTeacherTeachLanguage();
        $scheduledLessonSearchObj->addMultipleFields(array(
            'sldetail_id',
            'slesson_id',
            'slesson_grpcls_id',
            'op_lpackage_is_free_trial',
            'slesson_status',
            'slesson_ended_by',
            'slesson_date',
            'slesson_end_date',
            'slesson_ended_on',
            'slesson_start_time',
            'slesson_end_time',
            'slesson_teacher_join_time',
            'sldetail_learner_join_time',
            'slesson_teacher_end_time',
            'sldetail_learner_end_time',
            'slesson_added_on',
            'order_is_paid',
            'IFNULL(iss.issrep_status,0) AS issrep_status',
            'IFNULL(iss.issrep_id,0) AS issrep_id',
            'CONCAT(ul.user_first_name, " " , ul.user_last_name) AS learner_name',
            'CONCAT(ut.user_first_name, " " , ut.user_last_name) AS teacher_name',
            'IFNULL(tl_l.tlanguage_name, t_t_lang.tlanguage_identifier) as teacherTeachLanguageName',
        ));
        if (!empty($data['slesson_teacher_id'])) {
            $teacherId = FatUtility::int($data['slesson_teacher_id']);
            $scheduledLessonSearchObj->addCondition('slesson_teacher_id', '=', $teacherId);
        }
        if (!empty($data['sldetail_learner_id'])) {
            $learnerId = FatUtility::int($data['sldetail_learner_id']);
            $scheduledLessonSearchObj->addCondition('sldetail_learner_id', '=', $learnerId);
        }
        if (!empty($data['sldetail_order_id'])) {
            $scheduledLessonSearchObj->addCondition('sldetail_order_id', '=', $data['sldetail_order_id']);
        }

        // if ($data['op_lpackage_is_free_trial'] >= 0) {
        //     $learnerId = FatUtility::int($data['op_lpackage_is_free_trial']);
        //     $scheduledLessonSearchObj->addCondition('op_lpackage_is_free_trial', '=', $learnerId);
        // }

        // $scheduledLessonSearchObj->addCondition('slesson_grpcls_id', '=', 0);

        if ($data['slesson_status'] > 0) {
            $status = FatUtility::int($data['slesson_status']);
            switch ($status) {
                case ScheduledLesson::STATUS_ISSUE_REPORTED:
                    $scheduledLessonSearchObj->addCondition('issrep_id', '>', 0);
                    break;
                case ScheduledLesson::STATUS_UPCOMING:
                    $scheduledLessonSearchObj->addCondition('mysql_func_CONCAT(slns.slesson_date, " ", slns.slesson_start_time )', '>=', date('Y-m-d H:i:s'), 'AND', true);
                    // $scheduledLessonSearchObj->addCondition('slns.slesson_date', '>=', date('Y-m-d'));
                    $scheduledLessonSearchObj->addCondition('slns.slesson_status', '=', ScheduledLesson::STATUS_SCHEDULED);
                    break;
                case ScheduledLesson::STATUS_SCHEDULED:
                    $scheduledLessonSearchObj->addCondition('slns.slesson_status', '=', $status);
                    break;
                default:
                    $scheduledLessonSearchObj->addCondition('slns.slesson_status', '=', $status);
                    break;
            }
        }
        $scheduledLessonSearchObj->addOrder('slesson_date', 'desc');
        $scheduledLessonSearchObj->setPageNumber($page);
        $scheduledLessonSearchObj->setPageSize($pagesize);
        $resultSet = $scheduledLessonSearchObj->getResultSet();

        $records = FatApp::getDb()->fetchAll($resultSet);
        // echo $scheduledLessonSearchObj->getQuery();
        // die;
        $this->set("arr_listing", $records);
        $this->set('pageCount', $scheduledLessonSearchObj->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $data);
        $this->set('recordCount', $scheduledLessonSearchObj->recordCount());
        $this->_template->render(false, false, null, false, false);
    }

    public function search()
    {
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $frmSearch = $this->getOrderPurchasedLessonsForm();
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
        $srch->joinUserCredentials();
        $srch->joinTeacherLessonLanguage($this->adminLangId);
        $srch->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'op_teacher_id = tCredentials.credential_user_id', 'tCredentials');
        $srch->joinGroupClass($this->adminLangId);
        $srch->addMultipleFields(
            array(
                'order_id',
                'grpcls_id',
                'op_qty',
                'grpcls.grpcls_title',
                'order_user_id',
                'op_teacher_id',
                'op_lpackage_is_free_trial',
                'order_is_paid',
                'order_net_amount',
                'CONCAT(u.user_first_name, " " , u.user_last_name) AS learner_username',
                'CONCAT(t.user_first_name, " " , t.user_last_name) AS teacher_username',
                'tCredentials.credential_email as teacherEmail',
                'cred.credential_email as userEmail',
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
        if (!empty($post['class_type'])) {
            if ($post['class_type'] == applicationConstants::CLASS_TYPE_GROUP) {
                $srch->addCondition('grpcls_id', '>', 0);
            } else {
                $srch->addDirectCondition('grpcls_id IS NULL');
            }
        }
        $srch->addOrder('order_date_added', 'desc');
        //$srch->addCondition('order_is_paid', '!=', Order::ORDER_IS_PENDING);

        // $srch->addCondition('slesson_grpcls_id', '=', 0);

        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        $rs      = $srch->getResultSet();

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

    public function viewSchedules($status = "all", $orderId = null)
    {
        if (empty($status)) {
            $status = "all";
        }
        $searchForm =  $this->getPurchasedLessonsSearchForm($status, $orderId);
        $this->set('searchForm', $searchForm);
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
        $srch->joinGroupClass($this->adminLangId);
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
            'IFNULL(grpclslang_grpcls_title,grpcls_title) as grpcls_title',
            'grpcls.grpcls_description',
            'sld.sldetail_learner_id as learnerId',
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

    public function updateStatusSetup()
    {
        if (!$this->canEdit) {
            FatUtility::dieJsonError($this->unAuthorizeAccess);
        }

        $sldetailId = FatApp::getPostedData('sldetail_id', FatUtility::VAR_INT, 0);
        // $slesson_id = FatApp::getPostedData('slesson_id', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('slesson_status', FatUtility::VAR_INT, 0);
        $statusArr = ScheduledLesson::getStatusArr();
        unset($statusArr[ScheduledLesson::STATUS_RESCHEDULED]);
        if (1 > $sldetailId ||  !array_key_exists($status, $statusArr)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $srch = new ScheduledLessonSearch(false);
        // $srch->addCondition('slesson_id', '=', $slesson_id);
        $srch->addCondition('sldetail_id', '=', $sldetailId);
        $rs = $srch->getResultSet();
        $lessonRow = FatApp::getDb()->fetch($rs);

        if (empty($lessonRow)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_Request'));
        }

        $slesson_id = $lessonRow['slesson_id'];

        // echo "<pre>"; print_r($lessonRow); echo "</pre>"; exit;

        if ($lessonRow['slesson_status'] == ScheduledLesson::STATUS_CANCELLED) {
            $this->error = Label::getLabel("LBL_You_can_not_change_status_of_cancelled_lesson", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        if ($status == ScheduledLesson::STATUS_CANCELLED && $lessonRow['slesson_status'] != ScheduledLesson::STATUS_NEED_SCHEDULING) {
            // Message::addErrorMessage(Label::getLabel('LBL_You_are_not_cancelled_the_lesson'));
            FatUtility::dieJsonError(Label::getLabel('LBL_You_are_not_cancelled_this_lesson'));
        }
        /*[ pay for completed lesson*/
        $db = FatApp::getDb();
        $db->startTransaction();

        $sLessonObj = new ScheduledLesson($lessonRow['slesson_id']);
        $sLessonObj->loadFromDb();

        if ($status == ScheduledLesson::STATUS_COMPLETED) {
            if ($lessonRow['slesson_is_teacher_paid'] == 0) {
                $sLessonObj->setFldValue('slesson_ended_on', date('Y-m-d H:i:s'));

                $lessonObj = new ScheduledLesson($slesson_id);
                if ($lessonObj->payTeacherCommission()) {
                    $userNotification = new UserNotifications($lessonRow['slesson_teacher_id']);
                    $userNotification->sendWalletCreditNotification($lessonRow['slesson_id']);
                    $sLessonObj->setFldValue('slesson_is_teacher_paid', 1);
                }
            } else {
                $trnObj = new Transaction($lessonRow['slesson_teacher_id']);
                if (!$trnObj->changeStatusByLessonId($slesson_id, Transaction::STATUS_COMPLETED)) {
                    FatUtility::dieJsonError($trnObj->getError());
                }
            }
        }
        /* elseif ($status == ScheduledLesson::STATUS_CANCELLED) {
            $lessonDetailsObj = new ScheduledLessonDetails($sldetailId);
            if(!$lessonDetailsObj->refundToLearner()){
                $db->rollbackTransaction();
                FatUtility::dieJsonError($lessonDetailsObj->getError());
            }
        } */

        /*]*/

        if ($status == ScheduledLesson::STATUS_CANCELLED) {
            if (!$sLessonObj->cancelLessonByTeacher('')) {
                $db->rollbackTransaction();
                FatUtility::dieJsonError($sLessonObj->getError());
            }

            // remove from teacher google calendar
            $token = current(UserSetting::getUserSettings($sLessonObj->getFldValue('slesson_teacher_id')))['us_google_access_token'];
            if ($token) {
                $oldCalId = $sLessonObj->getFldValue('slesson_teacher_google_calendar_id');

                if ($oldCalId) {
                    SocialMedia::deleteEventOnGoogleCalendar($token, $oldCalId);
                }
                $sLessonObj->setFldValue('slesson_teacher_google_calendar_id', '');
            }
        }
        $sLessonObj->setFldValue('slesson_status', $status);
        if (!$sLessonObj->save()) {
            $db->rollbackTransaction();
            Message::addErrorMessage($sLessonObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $db->commitTransaction();
        /*[ notifications to users */
        $userNotification = new UserNotifications($lessonRow['sldetail_learner_id']);
        $userNotification->sendSchLessonUpdateNotificationByAdmin($sldetailId, $lessonRow['sldetail_learner_id'], $status, User::USER_TYPE_TEACHER);

        //$userNotification = new UserNotifications($lessonRow['sldetail_learner_id']);
        //$userNotification->sendSchLessonUpdateNotificationByAdmin($slesson_id, $lessonRow['sldetail_learner_id '],  $status, User::USER_TYPE_LEANER);
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
        $orderSearch->joinScheduledLessonDetail();
        $orderSearch->joinScheduledLesson();
        $orderSearch->addMultipleFields([
            'order_id',
            'order_is_paid',
            'order_user_id',
            'order_net_amount',
            'sldetail_order_id',
            'slesson_id',
            'slesson_grpcls_id',
            'count(sld.sldetail_order_id) as totalLessons',
            'SUM(CASE WHEN sld.sldetail_learner_status = ' . ScheduledLesson::STATUS_SCHEDULED . ' THEN 1 ELSE 0 END) scheduledLessonsCount',
            'SUM(CASE WHEN sld.sldetail_learner_status = ' . ScheduledLesson::STATUS_NEED_SCHEDULING . ' THEN 1 ELSE 0 END) needToscheduledLessonsCount',
        ]);
        //$orderSearch->joinTable(ScheduledLesson::DB_TBL, 'INNER JOIN', 'sld.sldetail_order_id = o.order_id', 'sl');
        $orderSearch->addCondition('o.order_id', '=', FatApp::getPostedData('order_id', FatUtility::VAR_STRING, ''));
        $orderSearch->addGroupBy('sld.sldetail_order_id');
        $resultSet = $orderSearch->getResultSet();
        $orderInfo =  $db->fetch($resultSet);
        // print_r($orderInfo);
        // die;
        if (empty($orderInfo)) {
            $this->error = Label::getLabel("LBL_Invalid_Request", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        if ($orderInfo['order_is_paid'] == Order::ORDER_IS_CANCELLED) {
            $this->error = Label::getLabel("LBL_You_can_not_change_status_of_cancelled_order", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        $orderInfo['order_net_amount'] =  FatUtility::float($orderInfo['order_net_amount']);

        if ($orderInfo['slesson_grpcls_id'] == 0 && $data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['needToscheduledLessonsCount'] != $orderInfo['totalLessons']) {
            $this->error = Label::getLabel("LBL_You_can_not_cancel_the_order", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        if ($orderInfo['slesson_grpcls_id'] == 0 && $data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['scheduledLessonsCount'] > 0) {
            $this->error = Label::getLabel("LBL_You_can_not_cancel_the_order_because_some_lesson_are_scheduled", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        if ($data['order_is_paid'] == Order::ORDER_IS_PENDING && $orderInfo['order_is_paid'] == Order::ORDER_IS_CANCELLED) {
            $this->error = Label::getLabel("LBL_Order_already_cancelled", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }

        $assignValues = array('order_is_paid' => $data['order_is_paid']);
        if (!$db->updateFromArray(Order::DB_TBL, $assignValues, array('smt' => 'order_id = ?', 'vals' => array($data['order_id'])))) {
            $db->rollbackTransaction();
            $this->error = Label::getLabel("LBL_SYSTEM_ERROR", CommonHelper::getLangId());
            if (FatUtility::isAjaxCall()) {
                // Message::addErrorMessage($this->error);
                FatUtility::dieJsonError($this->error);
            }
            return false;
        }
        /* [ */
        if ($data['order_is_paid'] == Order::ORDER_IS_CANCELLED && $orderInfo['order_net_amount'] > 0) {
            $scheduledLessonSrch = new ScheduledLessonSearch();
            $scheduledLessonSrch->addMultipleFields(array(
                'sldetail_id',
                'slesson_id',
                'slesson_grpcls_id',
                'slesson_status',
                'sldetail_learner_status',
            ));
            $scheduledLessonSrch->addCondition('sldetail_order_id', '=', $orderInfo['order_id']);
            $scheduledLessonSrch->addCondition('sldetail_learner_status', '!=', ScheduledLesson::STATUS_CANCELLED);
            $scheduledLessonSrch->addCondition('slesson_status', '!=', ScheduledLesson::STATUS_CANCELLED);
            $orderLessons = FatApp::getDb()->fetchAll($scheduledLessonSrch->getResultSet());
            
        
            foreach ($orderLessons as $orderLesson) {
                
                if ($orderLesson['slesson_grpcls_id'] == 0 &&
                    !$db->updateFromArray(ScheduledLesson::DB_TBL, ['slesson_status' => ScheduledLesson::STATUS_CANCELLED],
                        ['smt' => 'slesson_id = ?', 'vals' => [$orderLesson['slesson_id']]])) {
                    $db->rollbackTransaction();
                    $this->error = $db->getError();
                    if (FatUtility::isAjaxCall()) {
                        FatUtility::dieJsonError($this->error);
                    }
                    return false;
                }

                $schLesDetObj = new ScheduledLessonDetails($orderLesson['sldetail_id']);
                if (!$schLesDetObj->refundToLearner()) {
                    $db->rollbackTransaction();
                    FatUtility::dieJsonError($db->getError());
                }

                
                if (!$db->updateFromArray(ScheduledLessonDetails::DB_TBL, ['sldetail_learner_status' => ScheduledLesson::STATUS_CANCELLED],
                    ['smt' => 'sldetail_order_id = ?', 'vals' => [$data['order_id']]])) {
                    $db->rollbackTransaction();
                    $this->error = $db->getError();
                    if (FatUtility::isAjaxCall()) {
                        FatUtility::dieJsonError($this->error);
                    }
                    return false;
                }
            }
        }
        $db->commitTransaction();

        if (FatUtility::isAjaxCall()) {
            // Message::addMessage(Label::getLabel('LBL_Updated_Successfully.'));
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_Updated_Successfully.'));
        }
        $this->set('msg', Label::getLabel('LBL_Updated_Successfully.'));
        $this->_template->render(false, false, 'json-success.php');
    }


    public function updatePayment()
    {
        if (!$this->canEdit) {
            FatUtility::dieJsonError($this->unAuthorizeAccess);
        }

        $orderId = FatApp::getPostedData('opayment_order_id', FatUtility::VAR_STRING, '');
        if ($orderId == '' || $orderId == null) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $form = $this->getPaymentForm($orderId);
        $post = $form->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($form->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $orderSearch = new OrderSearch();
        $orderSearch->addMultipleFields(array('order_id'));
        $orderSearch->joinOrderProduct();
        $orderSearch->joinUser();
        $orderSearch->joinTable(User::DB_TBL, 'INNER JOIN', 'op.op_teacher_id = t.user_id', 't');
        $orderSearch->addCondition('order_id', '=', $orderId);
        $orderSearch->addCondition('order_type', '=', Order::TYPE_LESSON_BOOKING);
        $orderSearch->addCondition('order_is_paid', '=', Order::ORDER_IS_PENDING);
        $resultSet = $orderSearch->getResultSet();
        $orderDeatils = FatApp::getDb()->fetch($resultSet);
        if (empty($orderDeatils)) {
            Message::addErrorMessage(Label::getLabel('LBL_INVALID_REQUEST.'));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->adminLangId);
        if (!$orderPaymentObj->addOrderPayment($post["opayment_method"], $post['opayment_gateway_txn_id'], $post["opayment_amount"], $post["opayment_comments"])) {
            Message::addErrorMessage($orderPaymentObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $giftcardObj = new Giftcard();
        if (!$giftcardObj->addGiftcardDetails($orderId)) {
            Message::addErrorMessage($giftcardObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', Label::getLabel('LBL_Payment_Details_Added_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
