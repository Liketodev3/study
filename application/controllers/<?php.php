<?php
    private static function verifyfacebookUserAccessToken($facebookUserAccessToken)
    {
        $facebookUserAccessToken = filter_var($facebookUserAccessToken, FILTER_SANITIZE_STRING);
        $my_facebook_app_id = FatApp::getConfig('CONF_FACEBOOK_APP_ID', FatUtility::VAR_STRING, '');
        $my_facebook_app_secret = FatApp::getConfig('CONF_FACEBOOK_APP_SECRET', FatUtility::VAR_STRING, '');
        $facebook_application = 'REPLACE';
        $curl_facebook1 = curl_init(); // start curl
        $url = "https://graph.facebook.com/oauth/access_token?client_id=" . $my_facebook_app_id . "&client_secret=" . $my_facebook_app_secret . "&grant_type=client_credentials";
        curl_setopt($curl_facebook1, CURLOPT_URL, $url);
        curl_setopt($curl_facebook1, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl_facebook1);
        curl_close($curl_facebook1);
        $decode_output = json_decode($output, true);
        $facebook_access_token = $decode_output['access_token'];
        $curl_facebook2 = curl_init();
        $url = "https://graph.facebook.com/debug_token?input_token=" . $facebookUserAccessToken . "&access_token=" . $facebook_access_token;
        curl_setopt($curl_facebook2, CURLOPT_URL, $url);
        curl_setopt($curl_facebook2, CURLOPT_RETURNTRANSFER, true);
        $output2 = curl_exec($curl_facebook2);
        curl_close($curl_facebook2);
        $decode_output2 = json_decode($output2, true);
        if ($my_facebook_app_id == $decode_output2['data']['app_id'] && $decode_output2['data']['application'] == $facebook_application && $decode_output2['data']['is_valid'] == true) {
            echo 'Success. Login is valid.';
        } else {
            echo 'Error.';
        }
    }