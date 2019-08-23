<?php
class Configurations extends FatModel{
	const DB_TBL = 'tbl_configurations';
	const DB_TBL_PREFIX = 'conf_';	
	private $db;
	
	const FORM_GENERAL = 1;
	const FORM_LOCAL = 2;
	const FORM_SEO = 3;
	const FORM_OPTIONS = 4;
	const FORM_LIVE_CHAT = 5;
	const FORM_THIRD_PARTY_API = 6;
	const FORM_EMAIL = 7;
	const FORM_MEDIA = 8;
	const FORM_SERVER = 9;
	const FORM_REVIEWS = 10;

	
	function __construct(){
		parent::__construct();	
	}
	
	public static function getLangTypeFormArr(){
		return  array(
			Configurations::FORM_GENERAL,
			Configurations::FORM_EMAIL,
			Configurations::FORM_MEDIA,
		);		
	}
	
	public static function getTabsArr(){
		$adminLangId = CommonHelper::getLangId();	
		
		$configurationArr =  array(
			Configurations::FORM_GENERAL =>Label::getLabel('MSG_General',$adminLangId),
			Configurations::FORM_LOCAL =>Label::getLabel('MSG_Local',$adminLangId),
			Configurations::FORM_SEO =>Label::getLabel('MSG_Seo',$adminLangId),
			Configurations::FORM_OPTIONS =>Label::getLabel('MSG_Options',$adminLangId),
			Configurations::FORM_LIVE_CHAT =>Label::getLabel('MSG_Live_Chat',$adminLangId),
			Configurations::FORM_THIRD_PARTY_API =>Label::getLabel('MSG_Third_Party_API',$adminLangId),
			Configurations::FORM_EMAIL =>Label::getLabel('MSG_Email',$adminLangId),
			Configurations::FORM_MEDIA => Label::getLabel('MSG_Media',$adminLangId),
			Configurations::FORM_REVIEWS => Label::getLabel('MSG_Reviews',$adminLangId),
			Configurations::FORM_SERVER => Label::getLabel('MSG_Server',$adminLangId));
		return $configurationArr;
	}
	
	
	public static function dateFormatPhpArr(){
		return array( 'Y-m-d' => 'Y-m-d', 'd/m/Y' => 'd/m/Y', 'm-d-Y' => 'm-d-Y', 'M d, Y' => 'M d, Y');
	}
	
	public static function dateFormatMysqlArr(){
		return array('%Y-%m-%d','%d/%m/%Y','%m-%d-%Y','%b %d, %Y');
	}
	
	public static function dateTimeZoneArr(){
		$arr = DateTimeZone::listIdentifiers();
		$arr=array_combine($arr, $arr);
		return $arr;
	}
	
	public static function getConfigurations(){
		$srch = new SearchBase(static::DB_TBL, 'conf');
		$rs = $srch->getResultSet();
		$record = array();
		while($row = FatApp::getDb()->fetch($rs)){			
			$record [strtoupper($row['conf_name'])] = $row['conf_val'];
		}
		return $record;
	}

	public function update($data){	
	
		foreach($data as $key => $val){
			$assignValues = array('conf_name'=>$key,'conf_val'=>$val);				
			FatApp::getDb()->insertFromArray(
				static::DB_TBL,$assignValues,false,array(),$assignValues
			);
		}
		return true;
	}	
}