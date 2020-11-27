<?php
class DummyController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

	public function getAttachments($fileType)
    {
        $srch = new SearchBase(AttachedFile::DB_TBL);
        $srch->addCondition('afile_type', '=', $fileType);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs, 'afile_id');
    }
	
    public function list()
    {
		$slideAttchments = $this->getAttachments(AttachedFile::FILETYPE_HOME_PAGE_BANNER);
		var_dump($slideAttchments);
		exit;
	}
    public function test()
    {
		$slideAttchments = $this->getAttachments(AttachedFile::FILETYPE_HOME_PAGE_BANNER);

		foreach($slideAttchments as $slideAttchment){
			if(!Slide::getAttributesById($slideAttchment['afile_record_id'])){
				$attachedFile = new AttachedFile($slideAttchment['afile_id']);
				if($attachedFile->deleteRecord()){
					var_dump(unlink(CONF_UPLOADS_PATH. $slideAttchment['afile_physical_path']));
				}
			}
			
		}
		// var_dump($slideAttchments);
		exit;

    }

    public function createProcedures() {
		$db = FatApp::getDb();
		$con = $db->getConnectionObject();
		$queries = array(
			"DROP FUNCTION IF EXISTS `GETBLOGCATCODE`",
			"CREATE FUNCTION `GETBLOGCATCODE`(`id` INT) RETURNS varchar(255) CHARSET utf8
            BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);

				SET catid = id;
				SET code = '';
				WHILE catid > 0  AND LENGTH(code) < 240 DO
					SET code = CONCAT(RIGHT(CONCAT('000000', catid), 6), '_', code);
					SELECT bpcategory_parent INTO catid FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
				END WHILE;
				RETURN code;
			END",
            "DROP FUNCTION IF EXISTS `GETBLOGCATORDERCODE`",
			"CREATE FUNCTION `GETBLOGCATORDERCODE`(`id` INT) RETURNS varchar(500) CHARSET utf8
            BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);
				DECLARE myorder INT(11);
				SET catid = id;
				SET code = '';
				set myorder = 0;
				WHILE catid > 0   AND LENGTH(code) < 240 DO
					SELECT bpcategory_parent, bpcategory_display_order  INTO catid, myorder FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
					SET code = CONCAT(RIGHT(CONCAT('000000', myorder), 6), code);
				END WHILE;
				RETURN code;
			END"
		);

		foreach ($queries as $qry) {
			if (!$con->query($qry)) {
				die($con->error);
			}
		}
		echo 'Created All the Procedures.';
	}

	public function testCurl()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.thelessonspace.com/v2/spaces/launch/",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>"{ \t\n\t\"user\" : {\"name\" : \"Priyanka-new\",\"leader\":false},\n\t\"timeouts\": {\n\t    \"not_before\": \"2020-08-27T13:46:17+05:30\",\n\t    \"not_after\": \"2020-08-27T13:50:17+05:30\"\n\t}\n}",
		CURLOPT_HTTPHEADER => array(
			"accept: application/json",
			"authorization: Organisation 2f5884ec-3d29-4dc5-bcfb-e42da065bb38",
			"cache-control: no-cache",
			"content-type: application/json",
			"postman-token: e8e4aa90-3c98-434b-8d62-412d9775a403"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		echo "<br>";
		echo  $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		echo "<br>";
		curl_close($curl);
		echo "<pre>";
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response,true);
			print_r($response);
			// if(is_array($response)) {
			// 	$values = array_values($response);
			// 	if(is_array($values[0]))
			// }
			print_r(array_values($response));
			die;
		}
	}

}
