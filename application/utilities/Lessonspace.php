<?php
class Lessonspace 
{
    const LESSON_ID_PREFIX = "LESSON_";
    const API_BASE_URL = "https://api.thelessonspace.com/v2/";
    const LAUNCH_API_URL = "spaces/launch/";
    
    private $error;
	private $isError;
    private $apikey;
    

    public function __construct()
    {
        $this->error = '';
        $this->isError = false;
        $this->apikey =  FatApp::getConfig('CONF_LESSONSPACE_API_KEY', FatUtility::VAR_STRING, '');
       
    }

    public function getError() {
        return $this->error;
    }

	public function isError() {
		return $this->isError;
    }
    
    public function launch(array $lessonData ) : array
    {
        if(empty($this->apikey)) {
            $this->isError = true;
            $this->error =  Label::getLabel('LBL_LESSONSPACE_API_KEY_NOT_DEFINED');
            return array();
        }
        
        $apiUrl = self::API_BASE_URL.self::LAUNCH_API_URL;

        $response =  $this->executeCurl( 'POST', $apiUrl, $lessonData);
        
        if($this->isError()) {
            return array();
        }

        return $response;
    }

    public function executeCurl( string $method = '', string $url, array $postData = []) : array
    {
           
			//open connection
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
                "accept: application/json",
                "authorization: Organisation ".$this->apikey,
                "content-type: application/json",
            ));

            if($method == "POST") {
                $postData = json_encode($postData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }

            $curlResult = curl_exec($ch);
			//close connection
           
            if (curl_errno( $ch )) {
                $this->isError= true;
                $this->error =  'Error:' . curl_error($ch);
                return array();
            }
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if($httpStatus < 200 || $httpStatus > 299) {
                $this->isError= true;
                $this->error =  Label::getLabel('LBL_SOMETHING_WENT_WORNG_IN_LESSONSPACE_API');
                return array();
            }

            curl_close($ch);

			return json_decode($curlResult, true);
		
    }

}
