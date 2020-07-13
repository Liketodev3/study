<?php
class MyAppController extends FatController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->action = $action;
        if (FatApp::getConfig("CONF_MAINTENANCE", FatUtility::VAR_INT, 0) && (get_class($this) != "MaintenanceController") && (get_class($this)!='Home' && $action!='setLanguage')) {
            if(UserAuthentication::isUserLogged()) {
                UserAuthentication::logout();
            }
            if (FatUtility::isAjaxCall()) {
                Message::addErrorMessage(Label::getLabel(Label::getLabel('MSG_Maintenance_Mode_Text')));
                FatUtility::dieWithError(Message::getHtml());
            }
            FatApp::redirectUser(CommonHelper::generateUrl('maintenance'));
        }
        CommonHelper::initCommonVariables();
        $this->initCommonVariables();
    }

    public function initCommonVariables()
    {
        $this->siteLangId = CommonHelper::getLangId();
        $this->siteCurrencyId = CommonHelper::getCurrencyId();
        /* [ */
        $controllerName = get_class($this);
        $arr = explode('-', FatUtility::camel2dashed($controllerName));
        array_pop($arr);
        $urlController = implode('-', $arr);
        $controllerName = ucfirst(FatUtility::dashed2Camel($urlController));
        /* ] */
        $jsVariables = array(
            'confirmUnLockPrice' => Label::getLabel('LBL_Are_you_sure_to_unlock_this_price!'),
            'confirmRemove' => Label::getLabel('LBL_Do_you_want_to_remove'),
            'confirmCancel' => Label::getLabel('LBL_Do_you_want_to_cancel'),
            'languageUpdateAlert' => Label::getLabel('LBL_On_Submit_Price_Needs_To_Be_Set'),
            'layoutDirection' => CommonHelper::getLayoutDirection(),
            'processing' => Label::getLabel('LBL_Processing...'),
            'requestProcessing' => Label::getLabel('LBL_Request_Processing...'),
            'isMandatory' => Label::getLabel('LBL_is_mandatory'),
            'pleaseEnterValidEmailId' => Label::getLabel('LBL_Please_enter_valid_email_ID_for'),
            'charactersSupportedFor' => Label::getLabel('VLBL_Only_characters_are_supported_for'),
            'pleaseEnterIntegerValue' => Label::getLabel('VLBL_Please_enter_integer_value_for'),
            'pleaseEnterNumericValue' => Label::getLabel('VLBL_Please_enter_numeric_value_for'),
            'startWithLetterOnlyAlphanumeric' => Label::getLabel('VLBL_startWithLetterOnlyAlphanumeric'),
            'mustBeBetweenCharacters' => Label::getLabel('VLBL_Length_Must_be_between_6_to_20_characters'),
            'invalidValues' => Label::getLabel('VLBL_Length_Invalid_value_for'),
            'shouldNotBeSameAs' => Label::getLabel('VLBL_should_not_be_same_as'),
            'mustBeSameAs' => Label::getLabel('VLBL_must_be_same_as'),
            'mustBeGreaterOrEqual' => Label::getLabel('VLBL_must_be_greater_than_or_equal_to'),
            'mustBeGreaterThan' => Label::getLabel('VLBL_must_be_greater_than'),
            'mustBeLessOrEqual' => Label::getLabel('VLBL_must_be_less_than_or_equal_to'),
            'mustBeLessThan' => Label::getLabel('VLBL_must_be_less_than'),
            'lengthOf' => Label::getLabel('VLBL_Length_of'),
            'valueOf' => Label::getLabel('VLBL_Value_of'),
            'mustBeBetween' => Label::getLabel('VLBL_must_be_between'),
            'mustBeBetween' => Label::getLabel('VLBL_must_be_between'),
            'and' => Label::getLabel('VLBL_and'),
            'Quit' => Label::getLabel('LBL_Quit'),
            'Reschedule' => Label::getLabel('LBL_Reschedule'),
            'chargelearner' => Label::getLabel('LBL_Charge_Learner'),
            'bookedSlotAlert' => Label::getLabel('VLBL_You_have_already_booked_this_slot._Do_you_want_to_continue?'),
            'endLessonAlert' => Label::getLabel('VLBL_Are_you_sure_to_end_this_Lesson?'),
            'Proceed' => Label::getLabel('LBL_Proceed'),
            'Confirm' => Label::getLabel('LBL_Confirm'),
            'pleaseSelect' => Label::getLabel('VLBL_Please_select'),
			'confirmCancelessonText' => Label::getLabel('LBL_Are_you_sure_want_to_cancel_this_lesson'),

            //'siteCurrencyId' => $this->siteCurrencyId,
            //'controllerName' => $controllerName,
        );

        $languages = Language::getAllNames(false);
        foreach ($languages as $val) {
            $jsVariables['language'.$val['language_id']] = $val['language_layout_direction'];
        }
        if (CommonHelper::getLayoutDirection() == 'rtl') {
            $this->_template->addCss('css/style--arabic.css');
        }
        $this->set('currencySymbolLeft', CommonHelper::getCurrencySymbolLeft());
        $this->set('currencySymbolRight', CommonHelper::getCurrencySymbolRight());
        $this->set('siteLangId', $this->siteLangId);
        $this->set('siteCurrencyId', $this->siteCurrencyId);
        $this->set('jsVariables', $jsVariables);
        $this->set('controllerName', $controllerName);
        $this->set('action', $this->action);
    }

    protected function getTeacherQualificationForm($isCertiRequried = false)
    {
        $frm = new Form('frmQualification');
        $fld = $frm->addHiddenField('', 'uqualification_id', 0);
        $fld->requirements()->setInt();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_Experience_Type'), 'uqualification_experience_type', UserQualification::getExperienceTypeArr(),'',[],Label::getLabel('LBL_Select'));
        $fld->requirements()->setRequired();
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Title'), 'uqualification_title', '', array('placeholder' =>  Label::getLabel('LBL_Eg:_B.A._English')));
        $fld->requirements()->setLength(1, 100);
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Institution'), 'uqualification_institute_name', '', array('placeholder' => Label::getLabel('LBL_Eg:_Oxford_University')));
        $fld->requirements()->setLength(1, 100);
        $fld = $frm->addRequiredField(Label::getLabel('LBL_Location'), 'uqualification_institute_address', '', array('placeholder' => Label::getLabel('LBL_Eg:_London')));
        $fld->requirements()->setLength(1, 100);
        $fld = $frm->addTextArea(Label::getLabel('LBL_Description'), 'uqualification_description', '', array('placeholder' => Label::getLabel('LBL_Eg._Focus_in_Humanist_Literature')));
        $fld->requirements()->setLength(1, 500);
        $yearArr = array();
        for ($year = date('Y')-1; $year >= 1980 ; $year--) {
            $yearArr[$year] = $year;
        }
        $fld1 = $frm->addSelectBox(Label::getLabel('LBL_Start_Year'), 'uqualification_start_year', $yearArr, '', array(), '');
        $fld1->requirements()->setRequired();
        $yearArr2[date('Y')] = Label::getLabel('LBL_Present');
        $yearArr = $yearArr2 + $yearArr;
        $fld2 = $frm->addSelectBox(Label::getLabel('LBL_End_Year'), 'uqualification_end_year', $yearArr, '', array(), '');
        $fld2->requirements()->setRequired();
        $fld2->requirements()->setCompareWith('uqualification_start_year', 'ge');
        $fld = $frm->addFileUpload(Label::getLabel('LBL_Upload_Certificate'), 'certificate');
        $fld->requirements()->setRequired($isCertiRequried);
        $fld->htmlAfterField = "<small>".Label::getLabel('LBL_NOTE:_Allowed_Certificate_Extentions!')."</small>";
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes'));
        return $frm;
    }

    public function getStates($countryId, $stateId = 0)
    {
        $countryId = FatUtility::int($countryId);
        $stateId = FatUtility::int($stateId);
        $stateObj = new State();
        $statesArr = $stateObj->getStatesByCountryId($countryId, $this->siteLangId);
        $this->set('statesArr', $statesArr);
        $this->set('stateId', $stateId);
        $this->_template->render(false, false, '_partial/states-list.php');
    }

    /* public function getBreadcrumbNodes($action) {
        $nodes = array();
        $className = get_class($this);
        $arr = explode('-', FatUtility::camel2dashed($className));
        array_pop($arr);
        $urlController = implode('-', $arr);
        $className = ucwords(implode(' ', $arr));

        if ($action == 'index') {
            $nodes[] = array('title'=>Label::getLabel('LBL_'.ucwords($className),$this->siteLangId));
        }
        else {
            $nodes[] = array('title'=>ucwords($className), 'href'=>CommonHelper::generateUrl($urlController));
            $nodes[] = array('title'=>Label::getLabel('LBL_'.ucwords($action),$this->siteLangId));
        }
        return $nodes;
    } */

    public function fatActionCatchAll($action)
    {
        $this->_template->render(false, false, 'error-pages/404.php');
        //CommonHelper::error404();
    }

    public function includeDateTimeFiles()
    {
        $this->_template->addCss(array('css/jquery-ui-timepicker-addon.css'), false);
        $this->_template->addJs(array('js/jquery-ui-timepicker-addon.js'), false);
    }

    public function setUpNewsLetter()
    {
        $post = FatApp::getPostedData();
        $frm = Common::getNewsLetterForm(CommonHelper::getLangId());
        $post = $frm->getFormDataFromArray($post);
        if($post === false) {
            Message::addErrorMessage($frm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
        $siteLangId = CommonHelper::getLangId();
        $api_key = FatApp::getConfig("CONF_MAILCHIMP_KEY");
        $list_id = FatApp::getConfig("CONF_MAILCHIMP_LIST_ID");
        if ($api_key == '' || $list_id == '') {
            Message::addErrorMessage(Label::getLabel("LBL_Newsletter_is_not_configured_yet,_Please_contact_admin", $siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        require_once(CONF_INSTALLATION_PATH . 'library/Mailchimp.php');
        $MailchimpObj = new Mailchimp($api_key);
        $Mailchimp_ListsObj = new Mailchimp_Lists($MailchimpObj);
        try {
            $subscriber = $Mailchimp_ListsObj->subscribe($list_id, array('email' => htmlentities($post['email'])));
            if (empty($subscriber['leid'])) {
                Message::addErrorMessage(Label::getLabel('MSG_Newsletter_subscription_valid_email', $siteLangId));
                FatUtility::dieWithError(Message::getHtml());
            }
        } catch (Mailchimp_Error $e) {
            Message::addErrorMessage($e->getMessage());
            // Message::addErrorMessage( Label::getLabel('MSG_Error_while_subscribing_to_newsletter', $siteLangId) );
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', Label::getLabel('MSG_Successfully_subscribed', $siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
