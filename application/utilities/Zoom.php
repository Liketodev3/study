<?php
class Zoom
{
    const ROLE_HOST = 1;
    const ROLE_ATTENDEE = 0;
    const BASE_URL = 'https://api.zoom.us/v2';
    private $token, $apiKey, $apiSecret;
    
    public function __construct()
    {
        $this->token = FatApp::getConfig('CONF_ZOOM_JWT_TOKEN');
        $this->apiKey = FatApp::getConfig('CONF_ZOOM_API_KEY');
        $this->apiSecret = FatApp::getConfig('CONF_ZOOM_API_SECRET');
    }
    
    public function checkUserAndGetUserId($email)
    {
        $page_number = 1;
        do{
            $response = self::getUsers($page_number);
            $emailIds = array_column($response['users'], 'email');
            $idx = array_search($email,$emailIds);
            if($idx!==false){
                return $response['users'][$idx]['id'];
            }
            $page_number++;
        }while($page_number<=$response['page_count']);
        
        return false;
    }
    
    public function getUsers($page_number=1)
    {
        $url = self::BASE_URL."/users/";

        $api_data = http_build_query(array(
            "page_number" => $page_number,
            "page_size" => 300
        ));
        
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        );
        
        return CommonHelper::curlReq($url.'?'.$api_data, array(), $headers);
    }
    
    public function createUser($teacherData)
    {
        if($id = $this->checkUserAndGetUserId($teacherData['email'])){
            return $id;
        }
        $url = self::BASE_URL."/users";

        $api_data = json_encode(array(
            "action" => "custCreate",
            "user_info" => array( 
                "first_name" => $teacherData['first_name'],
                "last_name" => $teacherData['last_name'],
                "email" => $teacherData['email'],
                "type" => 1, // const 
            )
        ));

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        );
        
        $res = CommonHelper::curlReq($url, $api_data, $headers);
        if(empty($res['id'])){
            throw new Exception($res['message']);
        }
        return $res['id'];
    }
    
    public function createMeeting($meeting_data)
    {
        // create User
        $url = self::BASE_URL."/users/$meeting_data[zoomTeacherId]/meetings";

        $api_data = json_encode(array(
            "topic"     => $meeting_data['title'],
            "type"      => 2, //always a scheduled meeting for now
            "start_time" => date('c', strtotime($meeting_data['start_time'])),
            "duration"  => $meeting_data['duration'],
            "timezone"  => MyDate::getTimeZone(),
            "agenda"    => $meeting_data['description'],
            /* "settings"  => array(
                "approval_type" => 1, // manually approve
                "registration_type" => 2,
                'registrants_email_notification' => true,
                'registrants_confirmation_email' => true,
                "contact_name" => "Rahul Mittal",
                "contact_email" => "rahul.mittal@fatbit.in",
                "close_registration" => true,
            ) */
        ));
        // CommonHelper::printArray($api_data);die;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        );
        return CommonHelper::curlReq($url, $api_data, $headers);
    }
    
    public function generateSignature($meeting_number, $role)
    {
        $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
        $data = base64_encode($this->apiKey . $meeting_number . $time . $role);        
        $hash = hash_hmac('sha256', $data, $this->apiSecret, true);        
        $_sig = $this->apiKey . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);        
        //return signature, url safe base64 encoded
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }
    
    public function getMeetingDetails($meetingId)
    {
        $url = self::BASE_URL."/meetings/".$meetingId;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        );
        
        $res = CommonHelper::curlReq($url, array(), $headers);
        return $res;
    }
    
    public function endMeeting($meetingId)
    {
        $meetingData = $this->getMeetingDetails($meetingId);
        if(empty($meetingData)){
            throw new Exception(Label::getLabel('MSG_Meeting_does_not_exists'));
            return false;
        }
        // CommonHelper::printArray($meetingData);die;
        $url = self::BASE_URL."/meetings/$meetingId/status";

        $api_data = json_encode(array(
            "action" => "end"
        ));

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        );
        
        return CommonHelper::curlPutReq($url, $api_data, $headers);
    }
}