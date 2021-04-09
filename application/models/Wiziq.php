<?php

class Wiziq extends FatModel
{

    private $secretKey;
    private $accessKey;
    private $serviceUrl;

    const CREATE_MEET = 'create';
    const ADD_TEACHER = 'add_teacher';
    const EDIT_TEACHER = 'edit_teacher';
    const ADD_ATTENDEES = 'add_attendees';
    const GET_TEACHER = 'get_teacher_details';

    public function __construct()
    {
        $this->secretKey = FatApp::getConfig('WIZIQ_API_SECRET_KEY');
        $this->accessKey = FatApp::getConfig('WIZIQ_API_ACCESS_KEY');
        $this->serviceUrl = FatApp::getConfig('WIZIQ_API_SERVICE_URL');
    }

    public function getTeacherId(int $userId): int
    {
        $srch = new SearchBase('tbl_wiziq_teachers');
        $srch->addCondition('wizteach_user_id', '=', $userId);
        $srch->addFld('wizteach_teacher_id');
        $srch->doNotCalculateRecords();
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        return FatUtility::int($row['wizteach_teacher_id'] ?? 0);
    }

    public function setupTeacher(int $userId, string $method)
    {
        $userObj = new User($userId);
        $user = $userObj->getUserInfo(['user_first_name', 'user_last_name', 'credential_email'], true);
        $parameters['signature'] = $this->generateSignature($method, $parameters);
        $parameters["image"] = CommonHelper::generateFullUrl('Image', 'user', [$userId], CONF_WEBROOT_FRONTEND);
        $parameters["name"] = $user['user_first_name'] . ' ' . $user['user_last_name'];
        $parameters["email"] = $user['credential_email'];
        $parameters["password"] = CommonHelper::getRandomPassword(10);
        $parameters["can_schedule_class"] = true;
        $parameters["is_active"] = true;
        if ($method === static::EDIT_TEACHER) {
            $parameters['teacher_id'] = $this->getTeacherId($userId);
        }
        try {
            $requestUrl = $this->serviceUrl . '?method=' . $method;
            $XMLReturn = $this->postRequest($requestUrl, http_build_query($parameters, '', '&'));
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        // $XMLReturn = "<rsp status='fail' call_id='88cbefd27a0b'><error code='1064' msg='Teacher Email ID already exists.'/></rsp>";
        // $XMLReturn = "<rsp status='ok' call_id='1b8ad9e6fa71'><method>add_teacher</method><add_teacher status='true'><teacher_id>621251</teacher_id><teacher_email>sher1@dummyid.com</teacher_email></add_teacher></rsp>";
        $rsp = simplexml_load_string($XMLReturn);
        if ($rsp == false) {
            $this->error = Label::getLabel('LBL_WIZIQ_NOT_RESPONDING');
            return false;
        }
        if ($rsp->attributes()->status != 'ok') {
            $this->error = $rsp->error['msg'];
            return false;
        }
        if ($method == static::EDIT_TEACHER) {
            $teacherId = $rsp->edit_teacher->teacher_id ?? 0;
        } else {
            $teacherId = $rsp->add_teacher->teacher_id ?? 0;
        }
        if ($teacherId < 1) {
            $this->error = Label::getLabel('LBL_WIZIQ_NOT_RESPONDING');
            return false;
        }
        $wiziqTeacherData = [
            'wizteach_user_id' => $userId,
            'wizteach_teacher_id' => $teacherId,
            'wizteach_name' => $parameters["name"],
            'wizteach_email' => $parameters["email"],
            'wizteach_image' => $parameters["image"],
            'wizteach_created' => date('Y-m-d H:i')
        ];
        $record = new TableRecord('tbl_wiziq_teachers');
        $record->assignValues($wiziqTeacherData);
        if (!$record->addNew([], $wiziqTeacherData)) {
            $this->error = $record->getError();
            return false;
        }
        return $wiziqTeacherData;
    }

    public function getTeachers()
    {
        $method = static::GET_TEACHER;
        $parameters['signature'] = $this->generateSignature($method, $parameters);
        $parameters['email'] = 'shersingh@dummyid.com';
        //$parameters['teacher_id'] = '621240';
        $requestUrl = $this->serviceUrl . '?method=' . $method;
        $XMLReturn = $this->postRequest($requestUrl, http_build_query($parameters, '', '&'));
        echo $XMLReturn;
        die;
    }

    public function createMeeting(array $data)
    {
        $parameters['signature'] = $this->generateSignature(static::CREATE_MEET, $parameters);
        $parameters["title"] = $data['title'];
        $parameters["duration"] = $data['duration'];
        $parameters["start_time"] = $data['start_time'];
        $parameters["presenter_id"] = $data['presenter_id'];
        $parameters["presenter_name"] = $data['presenter_name'];
        $parameters["presenter_email"] = $data['presenter_email'];
        $parameters["language_culture_name"] = 'en-us';
        try {
            $requestUrl = $this->serviceUrl . '?method=' . static::CREATE_MEET;
            $XMLReturn = $this->postRequest($requestUrl, http_build_query($parameters, '', '&'));
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        if (empty($XMLReturn)) {
            $this->error = Label::getLabel('LBL_WIZIQ_NOT_RESPONDING');
            return false;
        }
        try {
            $objDOM = new DOMDocument();
            $objDOM->loadXML($XMLReturn);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        $status = $objDOM->getElementsByTagName("rsp")->item(0);
        $attribNode = $status->getAttribute("status");
        if ($attribNode == "fail") {
            $error = $objDOM->getElementsByTagName("error")->item(0);
            $this->error = $error->getAttribute("msg");
            return false;
        }
        return [
            'method' => ($objDOM->getElementsByTagName("method"))->item(0)->nodeValue ?? '',
            'class_id' => ($objDOM->getElementsByTagName("class_id"))->item(0)->nodeValue ?? '',
            'recording_url' => ($objDOM->getElementsByTagName("recording_url"))->item(0)->nodeValue ?? '',
            'presenter_email' => ($objDOM->getElementsByTagName("presenter_email"))->item(0)->nodeValue ?? '',
            'presenter_url' => ($objDOM->getElementsByTagName("presenter_url"))->item(0)->nodeValue ?? ''
        ];
    }

    public function addStudent(int $classId, array $student)
    {

        require_once("AuthBase.php");
        $authBase = new AuthBase($secretAcessKey, $access_key);
        $XMLAttendee = "<attendee_list>
			<attendee>
			  <attendee_id><![CDATA[101]]></attendee_id>
			  <screen_name><![CDATA[john]]></screen_name>
                          <language_culture_name><![CDATA[es-ES]]></language_culture_name>
			</attendee>
			<attendee>
			  <attendee_id><![CDATA[102]]></attendee_id>
			  <screen_name><![CDATA[mark]]></screen_name>
                          <language_culture_name><![CDATA[ru-RU]]></language_culture_name>
			</attendee>
		  </attendee_list>";

        $parameters['signature'] = $this->generateSignature(static::ADD_ATTENDEES, $parameters);
        $parameters["class_id"] = $classId; //required
        $parameters["attendee_list"] = $XMLAttendee;
        $httpRequest = new HttpRequest();
        try {
            $XMLReturn = $httpRequest->wiziq_do_post_request($webServiceUrl . '?method=add_attendees', http_build_query($requestParameters, '', '&'));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        if (!empty($XMLReturn)) {
            try {
                $objDOM = new DOMDocument();
                $objDOM->loadXML($XMLReturn);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            $status = $objDOM->getElementsByTagName("rsp")->item(0);
            $attribNode = $status->getAttribute("status");
            if ($attribNode == "ok") {
                $methodTag = $objDOM->getElementsByTagName("method");
                echo "<br>method=" . $method = $methodTag->item(0)->nodeValue;

                $class_idTag = $objDOM->getElementsByTagName("class_id");
                echo "<br>class_id=" . $class_id = $class_idTag->item(0)->nodeValue;

                $add_attendeesTag = $objDOM->getElementsByTagName("add_attendees")->item(0);
                echo "<br>add_attendeesStatus=" . $add_attendeesStatus = $add_attendeesTag->getAttribute("status");

                $attendeeTag = $objDOM->getElementsByTagName("attendee");
                $length = $attendeeTag->length;
                for ($i = 0; $i < $length; $i++) {
                    $attendee_idTag = $objDOM->getElementsByTagName("attendee_id");
                    echo "<br>attendee_id=" . $attendee_id = $attendee_idTag->item($i)->nodeValue;

                    $attendee_urlTag = $objDOM->getElementsByTagName("attendee_url");
                    echo "<br>attendee_url=" . $attendee_url = $attendee_urlTag->item($i)->nodeValue;
                }
            } else if ($attribNode == "fail") {
                $error = $objDOM->getElementsByTagName("error")->item(0);
                echo "<br>errorcode=" . $errorcode = $error->getAttribute("code");
                echo "<br>errormsg=" . $errormsg = $error->getAttribute("msg");
            }
        } //end if	
    }

    public function completeMeeting()
    {
        
    }

    private function generateTimeStamp()
    {
        return time();
    }

    private function generateSignature($method, &$parameters)
    {
        $signatureBase = "";
        $secretAcessKey = urlencode($this->secretKey);
        $parameters["access_key"] = $this->accessKey;
        $parameters["timestamp"] = $this->generateTimeStamp();
        $parameters["method"] = $method;
        foreach ($parameters as $key => $value) {
            if (strlen($signatureBase) > 0) {
                $signatureBase .= "&";
            }
            $signatureBase .= "$key=$value";
        }
        return base64_encode($this->hmacsha1($secretAcessKey, $signatureBase));
    }

    private function hmacsha1($key, $data)
    {
        $blocksize = 64;
        $hashfunc = 'sha1';
        if (strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }
        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
        return $hmac;
    }

    private function postRequest($url, $data, $optional_headers = null)
    {
        $params = ['http' => ['method' => 'POST', 'content' => $data]];
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        $php_errormsg = '';
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

}
