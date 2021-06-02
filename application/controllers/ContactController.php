<?php

class ContactController extends MyAppController
{
    public function index()
    {
        $cPageId = FatUtility::int(5);
        $srch = ContentPage::getSearchObject($this->siteLangId);
        $srch->addMultipleFields(['cpage_id', 'IFNULL(cpage_title, cpage_identifier) as cpage_title',
            'cpage_layout', 'cpage_image_title', 'cpage_image_content', 'cpage_content', ]);
        $srch->addCondition('cpage_id', '=', $cPageId);
        $pageData = FatApp::getDb()->fetch($srch->getResultset());
        $contactFrm = $this->contactUsForm();
        $post = FatApp::getPostedData();
        if (!empty($post)) {
            $post = $contactFrm->getFormDataFromArray($post);
            $contactFrm->fill($post);
        }
        $this->set('contactFrm', $contactFrm);
        $this->set('siteLangId', $this->siteLangId);
        $this->set('pageData', $pageData);
        $this->_template->render();
    }

    public function contactSubmit()
    {
        $frm = $this->contactUsForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatApp::redirectUser(CommonHelper::generateUrl('contact'));
        }
        if (!CommonHelper::verifyCaptcha()) {
            Message::addErrorMessage(Label::getLabel('MSG_That_captcha_was_incorrect', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('contact'));
        }
        $email = explode(',', FatApp::getConfig('CONF_CONTACT_EMAIL'));
        foreach ($email as $emailId) {
            $emailId = trim($emailId);
            if (false === filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $email = new EmailHandler();
            if (!$email->sendContactFormEmail($emailId, $this->siteLangId, $post)) {
                Message::addErrorMessage(Label::getLabel('MSG_email_not_sent_server_issue', $this->siteLangId));
            } else {
                Message::addMessage(Label::getLabel('MSG_your_message_sent_successfully', $this->siteLangId));
            }
        }
        FatApp::redirectUser(CommonHelper::generateUrl('contact'));
    }

    private function contactUsForm()
    {
        $frm = new Form('frmContact');
        $frm->addRequiredField(Label::getLabel('LBL_Your_Name', $this->siteLangId), 'name', '');
        $frm->addEmailField(Label::getLabel('LBL_Your_Email', $this->siteLangId), 'email', '');
        $fld_phn = $frm->addRequiredField(Label::getLabel('LBL_Your_Phone', $this->siteLangId), 'phone');
        $fld_phn->requirements()->setRegularExpressionToValidate('^[\s()+-]*([0-9][\s()+-]*){5,20}$');
        $frm->addTextArea(Label::getLabel('LBL_Your_Message', $this->siteLangId), 'message')->requirements()->setRequired();
        $frm->addHtml('', 'htmlNote', '<div class="g-recaptcha" data-sitekey="'.FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '').'"></div>');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('BTN_SUBMIT', $this->siteLangId));

        return $frm;
    }
}
