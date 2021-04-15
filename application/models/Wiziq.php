<?php

class Wiziq extends FatModel
{

    private $secretKey;
    private $accessKey;
    private $serviceUrl;

    const CREATE_MEET = 'create';
    const ADD_ATTENDEES = 'add_attendees';


    public function __construct()
    {
        $this->secretKey = FatApp::getConfig('WIZIQ_API_SECRET_KEY');
        $this->accessKey = FatApp::getConfig('WIZIQ_API_ACCESS_KEY');
        $this->serviceUrl = FatApp::getConfig('WIZIQ_API_SERVICE_URL');
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

    public function updateMeeting()
    {
    }

    public function cancelMeeting()
    {
    }

    public function addStudent(int $classId, array $student)
    {
        $XMLAttendee = "<attendee_list>
			                <attendee>
                			    <attendee_id><![CDATA[" . $student['student_id'] . "]]></attendee_id>
			                    <screen_name><![CDATA[" . $student['student_name'] . "]]></screen_name>
                                <language_culture_name><![CDATA[es-ES]]></language_culture_name>
                            </attendee>
                		  </attendee_list>";
        $parameters['signature'] = $this->generateSignature(static::ADD_ATTENDEES, $parameters);
        $parameters["attendee_list"] = $XMLAttendee;
        $parameters["class_id"] = $classId;
        try {
            $requestUrl = $this->serviceUrl . '?method=' . static::ADD_ATTENDEES;
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
        $attendeeId = 0;
        $attendeeUrl = '';
        $attendeeTag = $objDOM->getElementsByTagName("attendee");
        $length = $attendeeTag->length;
        for ($i = 0; $i < $length; $i++) {
            $attendeeIdTag = $objDOM->getElementsByTagName("attendee_id");
            $attendeeId = $attendeeIdTag->item($i)->nodeValue;
            $attendeeUrlTag = $objDOM->getElementsByTagName("attendee_url");
            $attendeeUrl = $attendeeUrlTag->item($i)->nodeValue;
        }
        return [
            'attendeeId' => $attendeeId, 'attendeeUrl' => $attendeeUrl,
            'method' => ($objDOM->getElementsByTagName("method"))->item(0)->nodeValue ?? '',
            'class_id' => ($objDOM->getElementsByTagName("class_id"))->item(0)->nodeValue ?? '',
        ];
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
