<?php

use function GuzzleHttp\json_encode;

class CustomController extends MyAppController
{
    public function paymentFailed()
    {
        $textMessage = Label::getLabel('MSG_learner_failure_order_{contact-us-page-url}');
        $contactUsPageUrl = CommonHelper::generateUrl('contact');
        $textMessage = str_replace('{contact-us-page-url}', '<a href="'.$contactUsPageUrl.'">'. Label::getLabel('LBL_Contact_Us') .'</a>', $textMessage);

        $this->set('textMessage', $textMessage);

        $this->_template->render();
     
    }

    public function paymentSuccess($orderId = null)
    {
        $textMessage = Label::getLabel('MSG_learner_success_order_{dashboard-url}_{contact-us-page-url}');
        $arrReplace = array(
            '{dashboard-url}' => CommonHelper::generateUrl('learner'),
            '{contact-us-page-url}' => CommonHelper::generateUrl('custom', 'contactUs'),
        );

        foreach ($arrReplace as $key => $val) {
            $textMessage = str_replace($key, $val, $textMessage);
        }
        if ($orderId) {
            $orderObj = new Order();
            $order = $orderObj->getOrderById($orderId);
            if (isset($order['order_type'])) {
                $this->set('orderType', $order['order_type']);
            }
            $orderObj = $orderObj->getLessonsByOrderId($orderId);
            $orderObj->addFld('slesson_grpcls_id');
            $orderObj->doNotCalculateRecords(true);
            $orderObj->doNotLimitRecords(true);
            $lessonInfo = FatApp::getDb()->fetch($orderObj->getResultSet());
            $this->set('lessonInfo', $lessonInfo);
        }
        $this->set('textMessage', $textMessage);
       
        $this->_template->render();
    }
        public function updateUserCookies()
        {
            
            if(UserAuthentication::isUserLogged()){
                $UserCookieConsent  = new UserCookieConsent(UserAuthentication::getLoggedUserId());
                $UserCookieConsent->saveOrUpdateSetting([], false);
             }
             
            CommonHelper::setCookieConsent();
            return true;
        }

    public function paymentCancel()
    {
        FatApp::redirectUser(CommonHelper::generateFullUrl('Custom', 'paymentFailed'));
    }


    public function cookieForm()
    {
        $cookieForm = $this->getCookieForm();
        if(UserAuthentication::isUserLogged()){
            $userCookieConsent  = new UserCookieConsent(UserAuthentication::getLoggedUserId());
            $cookieSetting = $userCookieConsent->getCookieSettings();
            $cookieSetting =  \json_decode($cookieSetting, true);
            $cookieForm->fill($cookieSetting);
         }

        $this->set('cookieForm',$cookieForm);
        $this->_template->render(false, false);
    }

    protected function getCookieForm()
    {
        $form =  new Form('cookieForm');
        $checkboxValue =  applicationConstants::YES;
        $form->addCheckBox(Label::getLabel('LBL_Necessary'), UserCookieConsent::COOKIE_NECESSARY_FIELD, $checkboxValue, array(), true, 1);
        $form->addCheckBox(Label::getLabel('LBL_Preferences'), UserCookieConsent::COOKIE_PREFERENCES_FIELD, $checkboxValue, array(), true, 0);
        $form->addCheckBox(Label::getLabel('LBL_Statistics'), UserCookieConsent::COOKIE_STATISTICS_FIELD, $checkboxValue, array(), true, 0);
        $form->addSubmitButton('','btn_submit', Label::getLabel('LBL_Save_Cookie'));
        return $form;
    }

    public function saveCookieSetting()
    {
        $cookieForm = $this->getCookieForm();

         $data = $cookieForm->getFormDataFromArray(FatApp::getPostedData());
         if ($data == false) {
             Message::addErrorMessage(current($cookieForm->getValidationErrors()));
             FatUtility::dieJsonError(Message::getHtml());
         }
         unset($data['btn_submit']);
         unset($data['necessary']);
         if(UserAuthentication::isUserLogged()){
            $userCookieConsent  = new UserCookieConsent(UserAuthentication::getLoggedUserId());
            $userCookieConsent->saveOrUpdateSetting($data, false);
         }
         
         $data = \json_encode($data);
         CommonHelper::setCookieConsent($data);
         FatUtility::dieJsonSuccess(Label::getLabel('LBL_Cookie_settings_update_successfully'));
    }
}
