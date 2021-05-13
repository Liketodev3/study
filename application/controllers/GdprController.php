<?php
class GdprController extends LoggedUserController
{ 
    public function index()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $frmRequest = $this->getGdprRequestForm();
        $gdpr_request_sent = Gdpr::getGdprReqSentOrNot($userId);
        $this->set('gdpr_request_sent', $gdpr_request_sent);
        $this->set('frmRequest', $frmRequest);
        $this->_template->render();
    }

    private function getGdprRequestForm() 
    {
        $frm = new Form('gdprRequestForm');
        $frm->addFormTagAttribute('class', 'form');
        $frm->addFormTagAttribute('onsubmit', 'gdprApprovalReuest(this); return(false);');
        $frm->addTextArea(Label::getLabel('LBl_Reason_for_Erasure'), 'gdprdatareq_reason')->requirements()->setRequired(true);
        // $frm->addSelectBox(Label::getLabel('LBL_Data_Erasure_Type'), 'gdprdatareq_type', Gdpr::getGdprRequestTypeArr($this->siteLangId), -1, array(), '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Send', $this->siteLangId), array('class' => 'btn btn--primary block-on-mobile'));
        return $frm;
    }

    public function gdprApprovalReuest() 
    {
        $gdpr_request_data = [];
        $userId = UserAuthentication::getLoggedUserId();
        if (1 > $userId) {
            FatUtility::dieWithError(Label::getLabel('LBL_Invalid_Request'));
        }
        $frm = $this->getGdprRequestForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (!$post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatApp::redirectUser(FatUtility::generateUrl('Gdpr'));
            return;
        }

        $gdpr_request_data['gdprdatareq_user_id']   = $userId;
        $gdpr_request_data['gdprdatareq_reason']    = $post['gdprdatareq_reason'];
        $gdpr_request_data['gdprdatareq_type']      = Gdpr::TRUNCATE_DATA;
        $gdpr_request_data['gdprdatareq_added_on']  = date('Y-m-d H:i:s');
        $gdpr_request_data['gdprdatareq_updated_on']= date('Y-m-d H:i:s');
        $gdpr_request_data['gdprdatareq_request_sent']= applicationConstants::YES;
        $gdprObj = new Gdpr();
        $gdprObj->assignValues($gdpr_request_data);
        if (!$gdprObj->save()) {
            FatUtility::dieJsonError($gdprObj->getError());
        }
        $this->set('msg', Label::getLabel("LBL_GDPR_Request_Added_Successfully!"));
        $this->_template->render(false, false, 'json-success.php');
    }
    
}