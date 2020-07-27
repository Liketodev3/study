<?php
class MessagesController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $frm = $this->getSearchForm($this->siteLangId);
        $this->set('frmSrch', $frm);
        $this->_template->addJs('js/enscroll-0.6.2.min.js');
        $this->_template->render();
    }

    public function search()
    {
        $frm = $this->getSearchForm($this->siteLangId);
        $isActive = FatUtility::int(FatApp::getPostedData()['isactive']);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $post['page'] = empty(FatApp::getPostedData()['page']) ? 1 : FatApp::getPostedData()['page'];
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : FatUtility::int($post['page']);
        $pagesize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = Thread::getThreads(UserAuthentication::getLoggedUserId());
        if (isset(FatApp::getPostedData()['is_unread']) and (FatApp::getPostedData()['is_unread'] != '' || FatApp::getPostedData()['is_unread'] == 1)) {
            $srch->addHaving('message_is_unread', '=', FatApp::getPostedData()['is_unread']);
        }

        if ($post['keyword'] != '') {
            $cnd = $srch->addCondition('tfr.user_first_name', 'like', "%" . $post['keyword']);
            $cnd->attachCondition('tfr.user_last_name', 'like', "%" . $post['keyword'] . "%", 'OR');
            $cnd->attachCondition('tfr_c.credential_username', 'like', "%" . $post['keyword'] . "%", 'OR');
        }

        //$srch->setPageNumber($page);
        // $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs, 'thread_id');
        $recordCount = $srch->recordCount();
        $pageCount = $srch->pages();
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $recordCount);
        $this->set('loggedUserId', UserAuthentication::getLoggedUserId());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('isActive', $isActive);
        $this->_template->render(false, false);
    }

    private function getSearchForm($langId)
    {
        $frm = new Form('frmMessageSrch');
        $frm->addTextBox(Label::getLabel("LBL_From", $langId), 'keyword');
        $frm->addSelectBox(Label::getLabel("LBL_Status", $langId), 'is_unread', array(0 => Label::getLabel("LBL_Read", $langId), 1 => Label::getLabel("LBL_Unread", $langId)),array(),array(), Label::getLabel('LBL_Select'));
        $frm->addHiddenField('', 'page');
        $fldSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel("LBL_Submit", $langId));
        $fldCancel = $frm->addResetButton("", "btn_clear", Label::getLabel("LBL_Clear", $langId), array('onclick' =>  'clearSearch();', 'class' => 'btn--clear'));
        $fldSubmit->attachField($fldCancel);
        return $frm;
    }

    public function initiate($userId)
    {
        //$userId = FatUtility::int( CommonHelper::decryptId($userId) );
        if ($userId < 1 || $userId == UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Label::getLabel('LBL_Invalid_Request'));
            $json['redirectUrl'] = CommonHelper::redirectUserReferer(true);
            FatUtility::dieJsonError($json);
        }
        $userArr = array(
            $userId,
            UserAuthentication::getLoggedUserId()
        );
        $threadobj = new Thread();
        $threadId = $threadobj->getThreadId($userArr);
        $srch = new MessageSearch();
        $srch->joinThreadMessage();
        $srch->joinMessagePostedFromUser();
        $srch->joinMessagePostedToUser();
        //$srch->joinThreadStartedByUser();
        $srch->addMultipleFields(array('tth.*', 'ttm.message_id', 'ttm.message_text', 'ttm.message_date', 'ttm.message_is_unread'));
        $srch->addCondition('ttm.message_deleted', '=', 0);
        $srch->addCondition('tth.thread_id', '=', $threadId);
        $cnd = $srch->addCondition('ttm.message_from', '=', $userId);
        $cnd->attachCondition('ttm.message_to', '=', $userId, 'OR');
        $srch->addOrder('message_id', 'DESC');
        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs, 'message_id');
            if ($srch->recordCount() > 0) {
                $json['redirectUrl'] = CommonHelper::generateUrl('Messages');
                $json['threadId'] = $threadId;
                FatUtility::dieJsonSuccess($json);
            }
        }
        $frm = $this->sendMessageForm($this->siteLangId);
        $frm->fill(array('message_thread_id' => $threadId));
        $this->set('frm', $frm);
        $json['html'] = $this->_template->render(false, false, 'messages/generate-thread-pop-up.php', true, false);
        FatUtility::dieJsonSuccess($json);
        if ($threadId) {
            FatApp::redirectUser(CommonHelper::generateUrl('Messages', 'thread', array($threadId)));
        }
        Message::addErrorMessage($threadobj->getError());
        CommonHelper::redirectUserReferer();
    }

    public function thread($threadId)
    {
        $threadId = FatUtility::int($threadId);
        $userId = UserAuthentication::getLoggedUserId();
        $threadUsers = Thread::getThreadUsers($threadId);
        if (1 > $threadId || !in_array($userId, $threadUsers)) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_ACCESS', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
        /* $threadData = Thread::getAttributesById($threadId);
          if($threadData == false){
          Message::addErrorMessage(Label::getLabel('MSG_INVALID_ACCESS',$this->siteLangId));
          commonHelper::redirectUserReferer();
          } */

        //$threadTypeArr = Thread::getThreadTypeArr($this->siteLangId);
        $srch = new MessageSearch();
        $srch->joinThreadMessage();
        $srch->joinMessagePostedFromUser();
        $srch->joinMessagePostedToUser();
        $srch->addMultipleFields(array('tth.*'));
        $srch->addCondition('tth.thread_id', '=', $threadId);
        //$srch->addOrder('message_id','DESC');
        $rs = $srch->getResultSet();
        $threadDetails = FatApp::getDb()->fetch($rs);
        if ($threadDetails == false) {
            Message::addErrorMessage(Label::getLabel('MSG_INVALID_ACCESS', $this->siteLangId));
            commonHelper::redirectUserReferer();
        }

        $frmSrch = $this->getMsgSearchForm($this->siteLangId);
        $frmSrch->fill(array('thread_id' => $threadId));
        $frm = $this->sendMessageForm($this->siteLangId);
        $frm->fill(array('message_thread_id' => $threadId));
        $threadObj = new Thread($threadId);
        if (!$threadObj->markUserMessageRead($threadId, $userId)) {
            Message::addErrorMessage($threadObj->getError());
        }
        $this->set('frmSrch', $frmSrch);
        $this->set('frm', $frm);
        $this->set('threadDetails', $threadDetails);
        $this->set('loggedUserId', $userId);
        $this->set('loggedUserName', ucfirst(UserAuthentication::getLoggedUserAttribute('user_first_name')));
        $this->_template->render();
    }

    private function sendMessageForm($langId)
    {
        $frm = new Form('frmSendMessage');
        $fld = $frm->addTextarea(Label::getLabel("LBL_Message", $langId), 'message_text', '')->requirements();
        $fld->setRequired(true);
        $fld->setLength(0, 1000);
        $frm->addHiddenField('', 'message_thread_id');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Send', $langId));
        return $frm;
    }

    private function getMsgSearchForm($langId)
    {
        $frm = new Form('frmMessageSrch');
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'thread_id');
        return $frm;
    }

    public function messageSearch()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $post = FatApp::getPostedData();
        $threadId = FatUtility::int($post['thread_id']);
        $threadUsers = Thread::getThreadUsers($threadId);
        if (1 > $threadId || !in_array($userId, $threadUsers)) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
        }
        $otherUserId = ($threadUsers[0]==$userId) ? $threadUsers[1] : $threadUsers[0];
        $otherUserDetail = User::getAttributesById($otherUserId, array('user_id', 'user_first_name', 'user_last_name', 'user_is_teacher','user_url_name'));
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : FatUtility::int($post['page']);
        $pagesize = FatApp::getConfig('CONF_FRONTEND_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch = new MessageSearch();
        $srch->joinThreadMessage();
        $srch->joinMessagePostedFromUser();
        $srch->joinMessagePostedToUser();
        //$srch->joinThreadStartedByUser();
        $srch->addMultipleFields(array('tth.*', 'ttm.message_id', 'ttm.message_text', 'ttm.message_date', 'ttm.message_is_unread'));
        $srch->addCondition('ttm.message_deleted', '=', 0);
        $srch->addCondition('tth.thread_id', '=', $threadId);
        $cnd = $srch->addCondition('ttm.message_from', '=', $userId);
        $cnd->attachCondition('ttm.message_to', '=', $userId, 'OR');
        $srch->addOrder('message_id', 'DESC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs, 'message_id');
        }
        ksort($records);
        $pageCount = $srch->pages();
        $this->set('arrListing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('userId', $userId);
        $this->set('otherUserDetail', $otherUserDetail);
        $frm = $this->sendMessageForm($this->siteLangId);
        $frm->fill(array('message_thread_id' => $threadId));
        $this->set('frm', $frm);
        $startRecord = ($page - 1) * $pagesize + 1;
        $endRecord = $pagesize;
        $totalRecords = $srch->recordCount();
        if ($totalRecords < $endRecord) {
            $endRecord = $totalRecords;
        }
        $json['totalRecords'] = $totalRecords;
        $json['startRecord'] = $startRecord;
        $json['endRecord'] = $endRecord;
        $threadObj = new Thread($threadId);
        if (!$threadObj->markUserMessageRead($threadId, $userId)) {
            Message::addErrorMessage($threadObj->getError());
        }
        $json['msg'] = Label::getLabel('LBL_Processing...', $this->siteLangId);
        $json['html'] = $this->_template->render(false, false, 'messages/message-search.php', true);
        $json['loadMoreBtnHtml'] = $this->_template->render(false, false, '_partial/load-previous-btn.php', true);
        FatUtility::dieJsonSuccess($json);
    }

    public function sendMessage()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $frm = $this->sendMessageForm($this->siteLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieWithError(current($frm->getValidationErrors()));
        }
        $threadId = FatUtility::int($post['message_thread_id']);
        $threadUsers = Thread::getThreadUsers($threadId);
        if (1 > $threadId || !in_array($userId, $threadUsers)) {
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
        }
        $srch = new MessageSearch();
        // $srch->joinMessagePostedFromUser();
        //$srch->joinMessagePostedToUser();
        $srch->addMultipleFields(array('tth.*'));
        $srch->addCondition('tth.thread_id', '=', $threadId);
        $rs = $srch->getResultSet();
        $threadDetails = array();
        if ($rs) {
            $threadDetails = FatApp::getDb()->fetch($rs);
        }
        if (empty($threadDetails)) {
            //Message::addErrorMessage(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
            FatUtility::dieWithError(Label::getLabel('MSG_Invalid_Access', $this->siteLangId));
        }
        $messageSendTo = ($threadUsers[0] == $userId) ? $threadUsers[1] : $threadUsers[0];
        $data = array(
            'message_thread_id' => $threadId,
            'message_from' => $userId,
            'message_to' => $messageSendTo,
            'message_text' => $post['message_text'],
            'message_date' => date('Y-m-d H:i:s'),
            'message_is_unread' => 1
        );

        $tObj = new Thread();
        if (!$insertId = $tObj->addThreadMessages($data)) {
            FatUtility::dieWithError(Label::getLabel($tObj->getError(), $this->siteLangId));
        }
        /****/
        $toUserDate = User::getAttributesById($messageSendTo, array('user_first_name', 'user_last_name'));
        $fromUserData = User::getAttributesById($userId, array('user_first_name', 'user_last_name'));
        $db = FatApp::getDb();
        $userSrch = User::getSearchObject(true);
        $userSrch->addMultipleFields(array('credential_email'));
        $userSrch->addCondition('credential_user_id', '=', $messageSendTo);
        $userRs = $userSrch->getResultSet();
        $_userData = $db->fetch($userRs);
        $toUserEmail = $_userData['credential_email'];
        $tpl = 'new_message_arrived';
        $vars = array(
            '{from_user_name}' => $fromUserData['user_first_name']." ".$fromUserData['user_last_name'],
            '{to_user_name}' => $toUserDate['user_first_name']." ".$toUserDate['user_last_name'],
            '{message}' => $post['message_text'],
            '{action}' => Label::getLabel('MSG_New_Message_Arrived', $this->siteLangId),
        );

        if (!EmailHandler::sendMailTpl($toUserEmail, $tpl, $this->siteLangId, $vars)) {
            Message::addErrorMessage(Label::getLabel('LBL_Mail_not_sent', $this->siteLangId));
        }
        /****/
        $msg = Label::getLabel('MSG_Message_Submitted_Successfully!', $this->siteLangId);
        $this->set('threadId', $threadId);
        $this->set('messageId', $insertId);
        $this->set('msg', $msg);
        $json['threadId'] = $threadId;
        $json['messageId'] = $insertId;
        $json['msg'] = $msg;
        FatUtility::dieJsonSuccess($json);
        //$this->_template->render(false, false, 'json-success.php');
    }

    public function getUnreadCount()
    {
        if (CommonHelper::getUnreadMsgCount()) {
            $json['html'] = "<span class='count'>".CommonHelper::getUnreadMsgCount()."</span>";
        } else {
            $json['html']='';
        }
        FatUtility::dieJsonSuccess($json);
    }
}
