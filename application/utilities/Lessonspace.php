<?php
class Lessonspace 
{
    private $error;
	private $isError;
	private $apikey;

    public function __construct()
    {
        $this->error = '';
        $this->isError = false;
        $this->apikey =  FatApp::getConfig('CONF_LESSONSPACE_API_KEY', FatUtility::VAR_STRING, '');
       
    }

    

    function getError() {
        return $this->error;
    }

	function isError() {
		return $this->isError;
    }
    
    public function launch(int $isTeacher = 0, $lessonData ) : string
    {
        if(empty($this->apikey)) {
            $this->isError = true;
            $this->error =  Label::getLabel('LBL_API_KEY_NOT_DEFINED');
            return '';
        }
    }

    public function executeCurl( string $method = '', array $postData = [], string $url) : array
    {
           
            
			//open connection
            $ch = curl_init();
            
            //set the url, number of POST vars, POST data
            
            curl_setopt($ch, CURLOPT_URL, $url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
                "accept: application/json",
                "authorization: Organisation ".$this->apikey,
                "content-type: application/json",
            ));

            if($method == "post") {
                $postData = json_encode($postData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }

            $curlResult = curl_exec($ch);
			//close connection
           

            if (curl_errno($ch)) {
                $this->isError= true;
                $this->error =  'Error:' . curl_error($ch);
                return array();
            }
            
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

        

			return $curlResult;
		
    }
    
    function getHeadersFromCurlResponse($response) : array
    {
        $headers = array();

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers['http_code'] = $line;
            else
            {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }

        return $headers;
    }

}
