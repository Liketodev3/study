<?php

class Wiziq extends FatModel
{

    private $secretKey;
    private $accessKey;
    private $serviceUrl;

    const METHOD_CREATE = 'create';
    const METHOD_ADD_ATTENDEES = 'add_attendees';

    public function __construct()
    {
        $this->secretKey = FatApp::getConfig('WIZIQ_API_SECRET_KEY');
        $this->accessKey = FatApp::getConfig('WIZIQ_API_ACCESS_KEY');
        $this->serviceUrl = FatApp::getConfig('WIZIQ_API_SERVICE_URL');
    }

    public function createMeeting($teacherId)
    {
        $parameters['signature'] = $this->generateSignature(static::METHOD_CREATE, $parameters);
        $parameters["presenter_id"] = $teacherId;
        $parameters["presenter_name"] = "Sher Singh";
        $parameters["presenter_email"] = "info@sabiteach.com";
        $parameters["start_time"] = "2021-04-07 12:45";
        $parameters["title"] = "Programming Class";
        $parameters["duration"] = "";
        $parameters["time_zone"] = "Asia/Kolkata";
        $parameters["attendee_limit"] = "4";
        $parameters["language_culture_name"] = "en-us";
        try {
            $requestUrl = $this->serviceUrl . '?method=' . static::METHOD_CREATE;
            $XMLReturn = $this->postRequest($requestUrl, http_build_query($parameters, '', '&'));
        } catch (Exception $e) {
            $this->error =  $e->getMessage();
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
        $methodTag = $objDOM->getElementsByTagName("method");
        echo "method=" . $method = $methodTag->item(0)->nodeValue;
        $class_idTag = $objDOM->getElementsByTagName("class_id");
        echo "<br>Class ID=" . $class_id = $class_idTag->item(0)->nodeValue;
        $recording_urlTag = $objDOM->getElementsByTagName("recording_url");
        echo "<br>recording_url=" . $recording_url = $recording_urlTag->item(0)->nodeValue;
        $presenter_emailTag = $objDOM->getElementsByTagName("presenter_email");
        echo "<br>presenter_email=" . $presenter_email = $presenter_emailTag->item(0)->nodeValue;
        $presenter_urlTag = $objDOM->getElementsByTagName("presenter_url");
        echo "<br>presenter_url=" . $presenter_url = $presenter_urlTag->item(0)->nodeValue;
    }

    public function addTeacher()
    {
    }

    public function addStudent(array $student)
    {
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
        $parameters['signature'] = $this->generateSignature(static::METHOD_ADD_ATTENDEES, $parameters);
        $requestParameters["class_id"] = "11595"; //required
        $requestParameters["attendee_list"] = $XMLAttendee;
        try {
            $requestUrl = $this->serviceUrl . '?method=' . static::METHOD_ADD_ATTENDEES;
            $XMLReturn = $this->postRequest($requestUrl, http_build_query($parameters, '', '&'));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        if (empty($XMLReturn)) {
            $this->error = Label::getLabel('LBL_WIZIQ_NOT_RESPONDING');
            return false;
        }
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
        $params = array('http' => array('method' => 'POST',            'content' => $data));
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
