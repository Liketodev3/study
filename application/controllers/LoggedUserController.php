<?php
class LoggedUserController extends MyAppController {
	
	public function __construct($action) {
		parent::__construct($action);
		UserAuthentication::checkLogin();
		$userRow = $this->verifyLoggedUser();
	}
	
	private function verifyLoggedUser(){
		$srch = new UserSearch();
		$srch->joinCredentials( false, false );
		$srch->addCondition( 'u.user_id', '=', UserAuthentication::getLoggedUserId() );
		$srch->setPageSize(1);
		$srch->addMultipleFields( array( 'user_preferred_dashboard', 'credential_email', 'credential_active', 'credential_verified') );
		
		$rs = $srch->getResultSet(  );
		$userRow = FatApp::getDb()->fetch( $rs );
		
		if( empty($userRow) || $userRow['credential_active'] != 1 ){
			if ( FatUtility::isAjaxCall() ) {
				Message::addErrorMessage(Label::getLabel('ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED_OR_NOT_ACTIVE'));
				FatUtility::dieWithError(Message::getHtml());
			}
			FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'logout'));
		}
		
		if( false === User::isAdminLogged() && 1 != $userRow['credential_verified'] ){
			if ( FatUtility::isAjaxCall() ) {
				Message::addErrorMessage(Label::getLabel('MSG_Your_Account_verification_is_pending_,_Please_try_after_reloading_the_page'));
				FatUtility::dieWithError(Message::getHtml());
			}
			FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'logout'));
		}
		
		if( UserAuthentication::getLoggedUserId() < 1 ){
			if ( FatUtility::isAjaxCall() ) {
				Message::addErrorMessage(Label::getLabel('MSG_Session_seems_to_be_expired,_Please_try_after_reloading_the_page'));
				FatUtility::dieWithError(Message::getHtml());
			}
			FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'logout'));
		}
		
		if( empty($userRow['credential_email']) ){
			if ( FatUtility::isAjaxCall() ) {
				Message::addErrorMessage(Label::getLabel('MSG_Please_Configure_Your_Email,_try_after_reloading_the_page'));
				FatUtility::dieWithError(Message::getHtml());
			}
			FatApp::redirectUser( CommonHelper::generateUrl('GuestUser', 'configureEmail') );
		}
		
		return $userRow;
	}
	
	protected function getLessonFlashCardSearchForm(){
		$frm = new Form('frmFlashCardSrch');
		$frm->addTextBox( '', 'keyword', '', array('placeholder' => Label::getLabel('LBL_Search_Flash_Cards...')) );
		$fld = $frm->addHiddenField( '', 'lesson_id' );
		$fld->requirements()->setIntPositive();
		$fld = $frm->addHiddenField( '', 'page', 1 );
		$fld->requirements()->setIntPositive();
		$btnSubmit = $frm->addSubmitButton( '', 'btn_submit', '' );
		return $frm;
	}
}