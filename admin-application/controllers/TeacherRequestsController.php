<?php
class TeacherRequestsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewTeacherApprovalRequests();
    }

    public function index()
    {
        $frmSrch = $this->getSearchForm();
        $this->set('frmSrch', $frmSrch);
        $this->_template->render();
    }

    public function search()
    {
        $srchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $post = $srchForm->getFormDataFromArray($data);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $srch = new TeacherRequestSearch();
        $srch->joinUserCredentials();
        $srch->addMultipleFields(
            array(
            'utrequest_id',
            'utrequest_user_id',
            'utrequest_status',
            'utrequest_date',
            'utrequest_reference',
            'credential_username',
            'credential_email',
            'user_first_name',
            'user_last_name',
            )
        );
        $srch->addOrder('utrequest_id', 'desc');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        if (!empty($post['keyword'])) {
            $cnd = $srch->addCondition('utrequest_reference', '=', '%' . $post['keyword'] . '%', 'AND');
            $cnd->attachCondition('user_first_name', 'like', '%' . $post['keyword'] . '%', 'OR');
            $cnd->attachCondition('user_last_name', 'like', '%' . $post['keyword'] . '%', 'OR');
            $cnd->attachCondition('credential_email', 'like', '%' . $post['keyword'] . '%', 'OR');
            $cnd->attachCondition('credential_username', 'like', '%' . $post['keyword'] . '%', 'OR');
            $cnd->attachCondition('utrequest_reference', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        if (!empty($post['date_from'])) {
            $srch->addCondition('tr.utrequest_date', '>=', $post['date_from'] . ' 00:00:00');
        }
        if ($post['status'] > -1) {
            $srch->addCondition('tr.utrequest_status', '=', $post['status']);
        }
        if (!empty($post['date_to'])) {
            $srch->addCondition('tr.utrequest_date', '<=', $post['date_to'] . ' 23:59:59');
        }
        $rs	= $srch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);
        $this->set('canEditTeacherApprovalRequests', $this->objPrivilege->canEditTeacherApprovalRequests(AdminAuthentication::getLoggedAdminId(), true));
        $this->set('reqStatusArr', TeacherRequest::getStatusArr($this->adminLangId));
        $this->set('arr_listing', $rows);
        $this->set('postedData', $post);
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $pagingArr = array(
            'page' => $page,
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
            'adminLangId' => $this->adminLangId
        );
        $this->set('pagingArr', $pagingArr);
        $this->_template->render(false, false);
    }

    public function view($utrequest_id)
    {
        $utrequest_id = FatUtility::int($utrequest_id);
        if ($utrequest_id < 1) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $srch = new TeacherRequestSearch();
        $srch->joinTeacherRequestValues();
        $srch->addCondition('utrequest_id', '=', $utrequest_id);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);

        /* echo $srch->getQuery();
        die(); */
        $srch->addMultipleFields(
            array(
                'utrequest_id',
                'utrequest_user_id',
                'utrequest_reference',
                'utrequest_date',
                'utrequest_attempts',
                'utrequest_comments',
                'utrequest_status',
                'utrvalue_user_first_name',
                'utrvalue_user_last_name',
                'utrvalue_user_gender',
                'utrvalue_user_phone',
                'utrvalue_user_video_link',
                'utrvalue_user_profile_info',
                'utrvalue_user_teach_slanguage_id',
                'utrvalue_user_language_speak',
                'utrvalue_user_language_speak_proficiency',
                'count(utrequest_id) as totalRequest'
                )
        );
        $srch->addGroupBy('utrequest_id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        if (!$row) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $row['utrvalue_user_teach_slanguage_id'] = json_decode($row['utrvalue_user_teach_slanguage_id']);
        $row['utrvalue_user_language_speak'] = json_decode($row['utrvalue_user_language_speak']);
        $row['utrvalue_user_language_speak_proficiency'] = json_decode($row['utrvalue_user_language_speak_proficiency']);

        $this->set('row', $row);



        /* photoId Proof row[ */
        $photo_id_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF, $row['utrequest_user_id']);
        $this->set('photo_id_row', $photo_id_row);
        /* ] */
        $otherRequest = [];
        /* previous request  data[ */
        if($row['totalRequest'] > 0) {
            $srch = new TeacherRequestSearch();
            $srch->addCondition('utrequest_user_id', '=', $row['utrequest_user_id']);
            $srch->addCondition('utrequest_id', '!=', $utrequest_id);
            $srch->addOrder('utrequest_id','desc');
            $rs = $srch->getResultSet();
            $otherRequest = FatApp::getDb()->fetchAll($rs);
        }
        /* ] */

        $this->set('spokenLanguagesArr', SpokenLanguage::getAllLangs($this->adminLangId));
        $this->set('TeachingLanguagesArr', TeachingLanguage::getAllLangs($this->adminLangId));
        $this->set('spokenLanguageProfArr', SpokenLanguage::getProficiencyArr($this->adminLangId));
        $this->set('otherRequest', $otherRequest);
        $this->_template->render(false, false);
    }

    public function viewProfilePic($userId)
    {
        $userId = FatUtility::int($userId);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE, $userId);
        $image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        $w = 100;
        $h = 100;
        AttachedFile::displayImage($image_name, $w, $h);
    }

    public function teacherRequestUpdateForm($utrequest_id)
    {
        $utrequest_id = FatUtility::int($utrequest_id);
        if ($utrequest_id < 1) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        /*  */
        $srch = new TeacherRequestSearch();
        $srch->addCondition('utrequest_id', '=', $utrequest_id);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addMultipleFields(array('utrequest_id'));
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        /* ] */

        if (!$row) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getTeacherRequestUpdateForm();
        $frm->fill(array('utrequest_id' => $utrequest_id ));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setUpTeacherRequestStatus()
    {
        $this->objPrivilege->canEditTeacherApprovalRequests();
        $frm = $this->getTeacherRequestUpdateForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $utrequest_id = FatApp::getPostedData('utrequest_id', FatUtility::VAR_INT, 0);
        $comment =  FatApp::getPostedData('utrequest_comments', FatUtility::VAR_STRING, '');
        /* [ */
        $srch = new TeacherRequestSearch();
        $srch->joinUserCredentials();
        $srch->joinTeacherRequestValues();
        $srch->addCondition('utrequest_id', '=', $utrequest_id);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addMultipleFields(array(
            'utrequest_status',
            'utrequest_user_id',
            'utrequest_comments',
            'utrequest_reference',
            'user_first_name',
            'user_last_name',
            'credential_email',
            'utrvalue_user_first_name',
            'utrvalue_user_last_name',
            'utrvalue_user_gender',
            'utrvalue_user_phone',
            'utrvalue_user_profile_info',
            'utrvalue_user_video_link',
            'utrvalue_user_teach_slanguage_id',
            'utrvalue_user_language_speak',
            'utrvalue_user_language_speak_proficiency'
        ));
        $rs = $srch->getResultSet();
        $requestRow = FatApp::getDb()->fetch($rs);

        if (!$requestRow) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */
        $requestRow['utrequest_comments'] = $comment;
        $statusArr = TeacherRequest::getStatusArr($this->adminLangId);
        unset($statusArr[TeacherRequest::STATUS_PENDING]);
        if($requestRow['utrequest_status'] != TeacherRequest::STATUS_PENDING) {
            Message::addErrorMessage(Label::getLabel('LBL_Request_Status_Already_Changed', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        if ($post['utrequest_status'] == $requestRow['utrequest_status']) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Status_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $post['utrequest_status_change_date'] =  date('Y-m-d H:i:s');
        $TeacherRequest = new TeacherRequest($utrequest_id);
        $TeacherRequest->assignValues($post);
        if (!$TeacherRequest->save()) {
            Message::addErrorMessage($TeacherRequest->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        /* User is marked as teacher and request data synced with profile data[ */
        if ($post['utrequest_status'] == TeacherRequest::STATUS_APPROVED && $requestRow['utrequest_status'] != TeacherRequest::STATUS_APPROVED) {

            /* syncing user common data[ */
            $user = new User($requestRow['utrequest_user_id']);
            $userUpdateDataArr = array(
                'user_is_teacher' => 1,
                'user_preferred_dashboard' => User::USER_TEACHER_DASHBOARD,
                'user_first_name' => $requestRow['utrvalue_user_first_name'],
                'user_last_name' => $requestRow['utrvalue_user_last_name'],
                'user_gender' => $requestRow['utrvalue_user_gender'],
                'user_phone' => $requestRow['utrvalue_user_phone'],
                'user_profile_info'	=> $requestRow['utrvalue_user_profile_info'],
            );
            $user->assignValues($userUpdateDataArr);
            if (true != $user->save()) {
                Message::addErrorMessage($user->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            /* ] */
            /* syncing user profile pic[ */
            $requestedProfilePicRow = AttachedFile::getAttachment(AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_PROFILE_IMAGE, $requestRow['utrequest_user_id']);
            if ($requestedProfilePicRow['afile_physical_path'] != "") {
                $profilePicDataArr = array(
                    'afile_type' => AttachedFile::FILETYPE_USER_PROFILE_IMAGE,
                    'afile_record_id' => $requestRow['utrequest_user_id'],
                    'afile_record_subid' => 0,
                    'afile_lang_id' => 0,
                    'afile_screen' => 0,
                    'afile_physical_path' => $requestedProfilePicRow['afile_physical_path'],
                    'afile_name' => $requestedProfilePicRow['afile_name'],
                    'afile_display_order' => $requestedProfilePicRow['afile_display_order'],
                );
                $db = FatApp::getDb();
                if (!$db->insertFromArray(AttachedFile::DB_TBL, $profilePicDataArr, false, array(), $profilePicDataArr)) {
                    Message::addErrorMessage($db->getError());
                    FatUtility::dieWithError(Message::getHtml());
                }
            }
            /* ] */

            /* syncing teacher settings[ */
            $userSetting = new UserSetting($requestRow['utrequest_user_id']);
            $settingDataArr = array(
                'us_user_id' => $requestRow['utrequest_user_id'],
                'us_video_link' => $requestRow['utrvalue_user_video_link'],
                //'us_teach_slanguage_id'	=>	$requestRow['utrvalue_user_teach_slanguage_id'],
            );
            if (true != $userSetting->saveData($settingDataArr)) {
                Message::addErrorMessage($userSetting->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            /* ] */

            /* syncing teach languages[ */
            $db = FatApp::getDb();
            $teachLanguagesArr = json_decode($requestRow['utrvalue_user_teach_slanguage_id']);
            if (!empty($teachLanguagesArr)) {
                foreach ($teachLanguagesArr as $key => $slanguage_id) {
                    $dataArr = array(
                            'utl_us_user_id' => $requestRow['utrequest_user_id'],
                            'utl_slanguage_id' => $slanguage_id,
                        );

                    if (!$db->insertFromArray(
                            UserToLanguage::DB_TBL_TEACH,
                            $dataArr,
                            false,
                            array(),
                            $dataArr
                        )) {
                        Message::addErrorMessage($db->getError());
                        FatUtility::dieWithError(Message::getHtml());
                    }
                }
            }
            /* ] */

            /* syncing spoken languages[ */
            $db = FatApp::getDb();
            $spokenLanguagesArr = json_decode($requestRow['utrvalue_user_language_speak']);
            $spokenLanguageProfArr = json_decode($requestRow['utrvalue_user_language_speak_proficiency']);
            if (!empty($spokenLanguagesArr)) {
                foreach ($spokenLanguagesArr as $key => $slanguage_id) {
                    $dataArr = array(
                        'utsl_user_id' => $requestRow['utrequest_user_id'],
                        'utsl_slanguage_id' => $slanguage_id,
                        'utsl_proficiency' => $spokenLanguageProfArr[$key]
                    );

                    if (!$db->insertFromArray(
                        User::DB_TBL_USER_TO_SPOKEN_LANGUAGES,
                        $dataArr,
                        false,
                        array(),
                        $dataArr
                    )) {
                        Message::addErrorMessage($db->getError());
                        FatUtility::dieWithError(Message::getHtml());
                    }
                }
            }
            /* ] */


            /* activating qualifications[ */
            $dataArr = array(
                'uqualification_active' => 1
            );
            if (!$db->updateFromArray(
                UserQualification::DB_TBL,
                $dataArr,
                array('smt' => 'uqualification_user_id = ?', 'vals' => array($requestRow['utrequest_user_id']) )
            )) {
                Message::addErrorMessage($db->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            /* ] */
            $userNotification = new UserNotifications($requestRow['utrequest_user_id']);
            $userNotification->sendTeacherApprovalNotification();
        }
        /* ] */

        /* email sending[ */
        $email	= new EmailHandler();
        $requestRow['utrequest_status'] = $post['utrequest_status'];
        if (!$email->SendTeacherRequestStatusChangeNotification($this->adminLangId, $requestRow)) {
            Message::addErrorMessage(Label::getLabel('LBL_Email_Could_Not_Be_Sent', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */

        $this->set('msg', Label::getLabel('LBL_Status_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function searchQualifications($user_id)
    {
        $user_id = FatUtility::int($user_id);
        if ($user_id < 1) {
            Message::addErrorMessage(Label::getLabel('MSG_Invalid_Request', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $srch = new UserQualificationSearch();
        $srch->addCondition('uqualification_user_id', '=', $user_id);
        $rs = $srch->getResultSet();
        $arr_listing = FatApp::getDb()->fetchAll($rs);
        $this->set('arr_listing', $arr_listing);
        $this->_template->render(false, false);
    }

    private function getTeacherRequestUpdateForm()
    {
        $frm = new Form('frmTeacherRequestUpdateForm');
        $fld = $frm->addHiddenField('', 'utrequest_id', 0);
        $fld->requirements()->setInt();

        $statusArr = TeacherRequest::getStatusArr($this->adminLangId);
        unset($statusArr[TeacherRequest::STATUS_PENDING]);

        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'utrequest_status', $statusArr, '')->requirements()->setRequired();
        $frm->addTextArea('', 'utrequest_comments', '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSrch');
        $fld = $frm->addHiddenField('', 'page', 1);
        $fld->requirements()->setRequired();
        $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '');
        $statusArr = array(
            '-1' => Label::getLabel('LBL_All', $this->adminLangId)
        ) + TeacherRequest::getStatusArr($this->adminLangId);
        $frm->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'status', $statusArr, '', array(), '');
        $frm->addDateField(Label::getLabel('LBL_Date_From', $this->adminLangId), 'date_from', '', array(
            'readonly' => 'readonly',
            'class' => 'field--calender'
        ));
        $frm->addDateField(Label::getLabel('LBL_Date_To', $this->adminLangId), 'date_to', '', array(
            'readonly' => 'readonly',
            'class' => 'field--calender'
        ));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function photoIdFile($recordId)
    {
        $recordId = FatUtility::int($recordId);
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_TEACHER_APPROVAL_USER_APPROVAL_PROOF, $recordId, 0);
        $image_name = isset($file_row['afile_physical_path']) ? $file_row['afile_physical_path'] : '';
        switch (strtoupper($sizeType)) {
            case 'THUMB':
                $w = 100;
                $h = 100;
                AttachedFile::displayImage($image_name, $w, $h);
                break;
            default:
                AttachedFile::displayOriginalImage($image_name);
                break;
        }
    }
}
