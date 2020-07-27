<?php
class GroupClassesController extends MyAppController
{
	public function index()
    {
        $frmSrch = $this->getSearchForm();
		$this->set('frmSrch', $frmSrch);
        $this->_template->addJs('js/jquery.datetimepicker.js');
        $this->_template->addCss('css/jquery.datetimepicker.css');
        $this->_template->addCss('css/classes.css');
		$this->_template->render();
	}

	public function search()
    {
		$page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
		if ($page < 2) {
			$page = 1;
		}
		$pageSize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);

		$srch = TeacherGroupClassesSearch::getSearchObj($this->siteLangId);
		$srch->setPageSize($pageSize);
		$srch->setPageNumber($page);
		$rs = $srch->getResultSet();
		$classesList = FatApp::getDb()->fetchAll($rs);
		$pagingArr = array(
            'pageCount' => $srch->pages(),
            'page' => $page,
            'pageSize' => $pageSize,
            'recordCount' => $srch->recordCount(),
        );
		$this->set('classes', $classesList);
        $min_booking_time = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
        $this->set('min_booking_time', $min_booking_time);
		$post['page'] = $page;
		$this->set('postedData', $post);
		$this->set('pagingArr', $pagingArr);
		$this->_template->render(false, false);
	}

	public function view($grpcls_id)
    {
		$srch = new stdClass();
		$srch = TeacherGroupClassesSearch::getSearchObj($this->siteLangId);
		$srch->addCondition('grpcls_id', '=', $grpcls_id);
		$srch->setPageSize(1);
		$rs = $srch->getResultSet();
		$classData = FatApp::getDb()->fetch($rs);
		if (empty($classData)) {
			FatUtility::exitWithErrorCode(404);
		}

		$this->set('class', $classData);
        $min_booking_time = FatApp::getConfig('CONF_CLASS_BOOKING_GAP', FatUtility::VAR_INT, 60);
        $this->set('min_booking_time', $min_booking_time);
        $this->_template->addCss('css/classes.css');
        // $this->_template->addCss('css/switch.css');
		$this->_template->render();
	}

    public function InterestList()
    {
        $post = FatApp::getPostedData();
        $grpClsId = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        $user_id = UserAuthentication::getLoggedUserId();

        if(!$grpClsId){
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $srch2 = new RequestedTimeslotsFollowersSearch();
        $srch2->doNotCalculateRecords(true);
        $srch2->addFld('IF(reqslfol_reqts_id>0, 1, 0)');
        $srch2->addDirectCondition('reqslfol_reqts_id=reqts_id');
        $srch2->addCondition('reqslfol_followed_by', '=', $user_id);

        $reqtsSrch = new RequestedTimeslotsSearch();
        $reqtsSrch->addCondition('reqts_grpcls_id', '=', $grpClsId);
        $reqtsSrch->addCondition('reqts_status', '=', ApplicationConstants::ACTIVE);
        $reqtsSrch->addMultipleFields(
            array(
                'reqts_id',
                'reqts_time',
                'reqts_added_by',
                '('.$srch2->getQuery().') is_followed'
            )
        );

        $rs = $reqtsSrch->getResultSet();
        $rows = FatApp::getDb()->fetchAll($rs);

        $this->set('postedData', $post);
		$this->set('rows', $rows);

        $frm = $this->getInterstForm();
        $frm->fill(array('grpcls_id' => $grpClsId));
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function followInterest()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if($id<1){
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_request'));
        }

        // validate id
        $interest_details = RequestedTimeslots::getAttributesById($id, array('reqts_id', 'reqts_added_by'));
        if(empty($interest_details) || $interest_details['reqts_id']!=$id){
            FatUtility::dieJsonError(Label::getLabel('LBL_Invalid_request'));
        }

        $user_id = UserAuthentication::getLoggedUserId();

        if($interest_details['reqts_added_by']==$user_id){
            FatUtility::dieJsonError(Label::getLabel('LBL_You_can_not_follow_your_own_slot'));
        }

        $reqtsFolSrch = new RequestedTimeslotsFollowersSearch();
        $is_exist = $reqtsFolSrch->isTimeSlotsFollowedByUser($id, $user_id);
        if($is_exist){
            FatUtility::dieJsonError(Label::getLabel('LBL_already_followed'));
        }

        $data = array(
            'reqslfol_reqts_id' => $id,
            'reqslfol_followed_by' => $user_id,
        );

        $reqTSFolObj = new RequestedTimeslotsFollowers();
        $reqTSFolObj->assignValues($data);
        if (true !== $reqTSFolObj->save()) {
            FatUtility::dieJsonError($reqTSFolObj->getError());
        }

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Time_Slot_Followed_Successfully!'));
    }

    public function setupInterestList()
    {
        $frm = $this->getInterstForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if ($post === false) {
            FatUtility::dieJsonError(($frm->getValidationErrors()));
        }

        $user_id = UserAuthentication::getLoggedUserId();

		$user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();

		$reqts_time = MyDate::changeDateTimezone($post['time'], $user_timezone, $systemTimeZone);
        $reqtsSrch = new RequestedTimeslotsSearch();
        $is_exist = $reqtsSrch->isTimeSlotsAvailableForGroup($post['grpcls_id'], $reqts_time);
        if($is_exist){
            FatUtility::dieJsonError(Label::getLabel('LBL_Same_Time_already_added'));
        }

        $data = array(
            'reqts_grpcls_id' => $post['grpcls_id'],
            'reqts_time' => $reqts_time,
            'reqts_added_by' => $user_id,
            'reqts_status' => ApplicationConstants::ACTIVE,
        );

        $reqTSObj = new RequestedTimeslots();
        $reqTSObj->assignValues($data);
        if (true !== $reqTSObj->save()) {
            FatUtility::dieJsonError($reqTSObj->getError());
        }

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_Time_Suggested_Successfully!'));
    }

    private function getSearchForm()
    {
		$frm = new Form('frmTeacherSrch');
        $statuses = TeacherGroupClasses::getStatusArr($this->siteLangId);
        unset($statuses[TeacherGroupClasses::STATUS_PENDING]);
		$frm->addSelectBox('', 'status', $statuses);
		$frm->addTextBox('', 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_Classes')));
		$fld = $frm->addHiddenField('', 'page', 1);
		$fld->requirements()->setIntPositive();
		$frm->addSubmitButton('', 'btnSrchSubmit', '');
		return $frm;
	}

    private function getInterstForm()
    {
        $frm = new Form('frmInterest');
		$frm->addHiddenField('', 'grpcls_id')->requirements()->setRequired(true);
		$frm->addRequiredField(Label::getLabel("LBL_Time"), 'time', '', array('id' => 'time', 'placeholder' => Label::getLabel('LBL_Interested_At')));
		$frm->addSubmitButton('', 'btnSubmit', Label::getLabel("LBL_Submit"));
		return $frm;
    }
}
