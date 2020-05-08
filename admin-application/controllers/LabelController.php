<?php
class LabelController extends AdminBaseController
{
    public function __construct($action)
    {
        $ajaxCallArray = ['form', 'search', 'setup'];
		
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
		
        parent::__construct($action);
        $this->objPrivilege->canViewLanguageLabel();
    }
	
    public function index()
    {
        $frmSearch = $this->getSearchForm();
		
        $adminId = AdminAuthentication::getLoggedAdminId();		
        $canEdit  = $this->objPrivilege->canEditLanguageLabel($adminId, true);
		
        $this->set("canEdit", $canEdit);
        $this->set("frmSearch", $frmSearch);
		
        $this->_template->render();
    }
	
    public function search()
    {
        $pagesize   = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data       = FatApp::getPostedData();
        $page       = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
		
        $post = $searchForm->getFormDataFromArray($data);
        if (false === $post) {
            Message::addErrorMessage($searchForm->getValidationErrors());
            FatUtility::dieWithError(Message::getHtml());
        }
		
        $srch = Label::getSearchObject();
        $srch->joinTable('tbl_languages', 'inner join', 'label_lang_id = language_id and language_active = ' . applicationConstants::ACTIVE);
        $srch->addOrder('lbl.' . Label::DB_TBL_PREFIX . 'lang_id', 'ASC');
        $srch->addGroupBy('lbl.' . Label::DB_TBL_PREFIX . 'key');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        if (!empty($post['keyword'])) {
            $cond = $srch->addCondition('lbl.label_key', 'like', '%' . $post['keyword'] . '%', 'AND');
            $cond->attachCondition('lbl.label_caption', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        $srch->addCondition('lbl.label_lang_id', '=', $this->adminLangId);
        $rs      = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);

        $adminId = AdminAuthentication::getLoggedAdminId();
        $canEdit  = $this->objPrivilege->canEditLanguageLabel($adminId, true);
		
        $this->set("canEdit", $canEdit);
        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
		
        $this->_template->render(false, false);
    }
	
    public function form($label_id)
    {
        $label_id = FatUtility::int($label_id);
        if ($label_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $data = Label::getAttributesById($label_id, array(
            'label_key'
        ));
        if ($data == false) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $labelKey = $data['label_key'];
        $frm      = $this->getForm($labelKey);
        $srch     = Label::getSearchObject();
        $srch->addCondition('lbl.label_key', '=', $labelKey);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs     = $srch->getResultSet();
        $record = array();
        if ($rs) {
            $record = FatApp::getDb()->fetchAll($rs, 'label_lang_id');
        }
        if ($record == false) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $arr = array();
        foreach ($record as $k => $v) {
            $arr['label_key']          = $v['label_key'];
            $arr['label_caption' . $k] = $v['label_caption'];
        }
        $frm->fill($arr);
        $this->set('labelKey', $labelKey);
        $this->set('frm', $frm);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }
	
    public function setup()
    {
        $this->objPrivilege->canEditLanguageLabel();
        $data = FatApp::getPostedData();
        $frm  = $this->getForm($data['label_key']);
        $post = $frm->getFormDataFromArray($data);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $labelKey = $post['label_key'];
        $srch     = Label::getSearchObject();
        $srch->addCondition('lbl.label_key', '=', $labelKey);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs     = $srch->getResultSet();
        $record = FatApp::getDb()->fetchAll($rs, 'label_lang_id');
        if ($record == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            $data = array(
                'label_lang_id' => $langId,
                'label_key' => $labelKey,
                'label_caption' => $post['label_caption' . $langId]
            );
            $obj  = new Label();
            if (!$obj->addUpdateData($data)) {
                Message::addErrorMessage($obj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
	
	public function export()
	{
		$adminLangId = $this->adminLangId;
		$searchForm = $this->getSearchForm();
		$post = $searchForm->getFormDataFromArray(FatApp::getPostedData());
		
		$srch = new SearchBase(Label::DB_TBL, 'lbl');
		$srch->doNotCalculateRecords();
		$srch->doNotLimitRecords();
		$srch->joinTable(Language::DB_TBL, 'INNER JOIN', 'label_lang_id = language_id AND language_active = '. applicationConstants::ACTIVE );
		$srch->addOrder('label_key', 'DESC' );
		$srch->addOrder('label_lang_id', 'ASC' );
		$srch->addMultipleFields(['label_id', 'label_key', 'label_lang_id', 'label_caption']);		
		$rs = $srch->getResultSet();
		/* echo "<pre>";
		print_r(FatApp::getDb()->totalRecords($rs));
		echo "</pre>";
		die('Here'); */
		
		$langSrch = Language::getSearchObject();
		$langSrch->doNotCalculateRecords();
		$langSrch->addMultipleFields( array( 'language_id', 'language_code', 'language_name' ) );
		$langSrch->addOrder('language_id', 'ASC');
		$langRs = $langSrch->getResultSet();
		$languages = FatApp::getDb()->fetchAll($langRs);
		$sheetData = array();
		
		$arr = array( Label::getLabel('LBL_Key', $adminLangId) );
		if( $languages ){
			foreach( $languages as $lang ){
				array_push( $arr, $lang['language_code'] );
			}
		}		
		array_push($sheetData, $arr);
		
		$db = FatApp::getDb();		
		$key = '';
		$counter = 0;
		$arr = array();
		$langArr = array();		
		while( $row = $db->fetch($rs) ){				
			if( $key != $row['label_key'] ){
				if( !empty($langArr) ){
					$arr[$counter] = array('label_key' => $key );
					foreach($langArr as $k=>$val){
						if ( is_array($val) ){
							foreach ($val as $key=>$v) $val[$key] = htmlentities($v);
						}
						$arr[$counter]['data'] = $val;
					} 
					$counter++;
				}					
				$key = $row['label_key'];				
				$langArr = array();
				foreach( $languages as $lang ){
					$langArr[$key][$lang['language_id']]  = '';
				}
				$langArr[$key][$row['label_lang_id']] = $row['label_caption'] ;
			} else {
				$langArr[$key][$row['label_lang_id']] = $row['label_caption'] ;
			}
		}
		
		foreach($arr as $a ){
			$sheetArr = array();
			$sheetArr = array( $a['label_key'] );
			if( !empty($a['data']) ){
				foreach( $a['data'] as $langId=>$caption ){
					array_push( $sheetArr,html_entity_decode( $caption));
				}
			}
			array_push( $sheetData, $sheetArr );
		}
		
		CommonHelper::convertToCsv($sheetData, 'Labels_'.date("d-M-Y").'.csv', ','); exit;
	}
	
	public function importLabelsForm()
	{
		$this->objPrivilege->canEditLanguageLabel();
		$frm = $this->getImportLabelsForm();
		$this->set( 'frm', $frm );
		
		$this->_template->render(false, false);	
	}
	
	public function uploadLabelsImportedFile()
	{
		$this->objPrivilege->canEditLanguageLabel();
		if ( !is_uploaded_file($_FILES['import_file']['tmp_name']) ) {
			Message::addErrorMessage(Label::getLabel('LBL_Please_Select_A_CSV_File', $this->adminLangId));
			FatUtility::dieJsonError( Message::getHtml() );
		}
		$post = FatApp::getPostedData();
		
		if( !in_array($_FILES['import_file']['type'], CommonHelper::isCsvValidMimes()) ){
			Message::addErrorMessage( Label::getLabel( "LBL_Not_a_Valid_CSV_File", $this->adminLangId ) );
			FatUtility::dieJsonError( Message::getHtml() );
		}
		$db = FatApp::getDb();
		
		$langSrch = Language::getSearchObject();
		$langSrch->doNotCalculateRecords();
		$langSrch->addMultipleFields( array( 'language_id', 'language_code', 'language_name' ) );
		$langSrch->addOrder( 'language_id', 'ASC' );
		$langRs = $langSrch->getResultSet();
		$languages = $db->fetchAll( $langRs, 'language_code' );
		
		$csvFilePointer = fopen($_FILES['import_file']['tmp_name'], 'r');
		
		$firstLine = fgetcsv( $csvFilePointer );
		array_shift($firstLine);
		$firstLineLangArr = $firstLine;
		$langIndexLangIds = array();
		foreach( $firstLineLangArr as $key => $langCode ){
			$langIndexLangIds[$key] = $languages[$langCode]['language_id'];
		}
				
		while( ($line = fgetcsv($csvFilePointer) ) !== FALSE ){
			if( $line[0] != ''){
				$labelKey = array_shift($line);
				foreach( $line as $key => $caption ){
					$sql = "SELECT label_key FROM ". Label::DB_TBL ." WHERE label_key = " . $db->quoteVariable($labelKey). " AND label_lang_id = " .  $langIndexLangIds[$key];
					$rs = $db->query( $sql );
					if( $row = $db->fetch( $rs ) ){
						$db->updateFromArray( Label::DB_TBL, array( 'label_caption' => $caption ), array('smt' => 'label_key = ? AND label_lang_id = ?', 'vals' => array( $labelKey, $langIndexLangIds[$key] ) ) );
					} else {
						$dataToSaveArr = array( 
							'label_key'		=>	$labelKey,
							'label_lang_id'	=>	$langIndexLangIds[$key],
							'label_caption'	=>	$caption,
						);
						$db->insertFromArray(Label::DB_TBL, $dataToSaveArr );
					}
				}
			}
		}
		
		FatUtility::dieJsonSuccess(Label::getLabel('LBL_Labels_data_imported/updated_Successfully',$this->adminLangId));
	}
	
	private function getImportLabelsForm()
	{
		$frm = new Form('frmImportLabels', array('id' => 'frmImportLabels'));
		
		$fldImg = $frm->addFileUpload(Label::getLabel('LBL_File_to_be_uploaded:',$this->adminLangId), 'import_file', array('id' => 'import_file') );
		$fldImg->setFieldTagAttribute('onChange','$(\'#importFileName\').html(this.value)');
		
		$fldImg->htmlBeforeField='<div class="filefield"><span class="filename" id="importFileName"></span>';
		$fldImg->htmlAfterField='<label class="filelabel">'.Label::getLabel('LBL_Browse_File',$this->adminLangId).'</label></div><br/><small>'.nl2br(Label::getLabel('LBL_Import_Labels_Instructions',$this->adminLangId)).'</small>';
		
		$frm->addSubmitButton('','btn_submit',Label::getLabel('LBL_Import',$this->adminLangId));
		
		return $frm;
	}

    private function getSearchForm()
    {
        $frm        = new Form('frmLabelsSearch');
        $f1         = $frm->addTextBox(Label::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_Clear_Search', $this->adminLangId));
        $frm->addHiddenField('', 'page', 1);
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
	
    private function getForm($label_key)
    {
        $frm = new Form('frmLabels');
        $frm->addHiddenField('', 'label_key', $label_key);
        $languages = Language::getAllNames();
        $frm->addTextbox(Label::getLabel('LBL_Key', $this->adminLangId), 'key', $label_key);
        foreach ($languages as $langId => $langName) {
            $fld = null;
            $fld = $frm->addTextArea($langName, 'label_caption' . $langId);
            $fld->requirements()->setRequired();
        }
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}