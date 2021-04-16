<?php
class CustomRouter
{
	static function setRoute(&$controller, &$action, &$queryString)
	{
		define('LANG_CODES_ARR', Language::getAllCodesAssoc());
		if (defined('SYSTEM_FRONT') && SYSTEM_FRONT === true && !FatUtility::isAjaxCall()) {
			if (UrlHelper::isStaticContentProvider($controller, $action)) {
				return true;
			}

			/***----General details of url -----****/
			$url = $_SERVER['REQUEST_URI'];
			$url_components = parse_url($url);
			$path = $url_components['path'];
			$uri = Common::getUriFromPath($path);
			$last_param = '';
			$uri_segment = explode('/', $uri);

			/***----manage Lang code in url -----****/
			if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0) && in_array(strtoupper($controller), LANG_CODES_ARR)) {

				$langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
				$langCodes = array_flip(LANG_CODES_ARR);
				if (in_array(strtoupper($controller), LANG_CODES_ARR)) {
					$langId = $langCodes[strtoupper($controller)];
				}
				$langId = ($langId > 0) ? $langId : CommonHelper::getLangId();
				define('SYSTEM_LANG_ID', $langId);
				CommonHelper::setDefaultSiteLangCookie($langId);
				$controller = ($action == 'index') ? 'Home' : $action;
				if (!array_key_exists(0, $queryString)) {
					$action = 'index';
				} else {
					$action = $queryString[0];
					array_shift($queryString);
				}
				array_shift($uri_segment);
			} else {
				if (isset($_COOKIE['defaultSiteLang'])) {
					if (array_key_exists($_COOKIE['defaultSiteLang'], LANG_CODES_ARR)) {
						define('SYSTEM_LANG_ID', $_COOKIE['defaultSiteLang']);
					}
				} else {
					define('SYSTEM_LANG_ID', FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1));
				}
			}


			$uriSegmentCount = count($uri_segment);
			if ($uriSegmentCount > 2) {
				$urlwithoutparameter = array_slice($uri_segment, 0, 2);
				$lastParamArray = array_slice($uri_segment, (-$uriSegmentCount + 2), ($uriSegmentCount - 2), true);
				$last_param = '/' . implode('/', $lastParamArray);
				$replaceArray = array_fill(count($urlwithoutparameter) - 1, count($lastParamArray), 'urlparameter');
				$uri_segment = array_merge($urlwithoutparameter, $replaceArray);
			}

			$new_url = urldecode(implode('/', $uri_segment));

			$url = urldecode($_SERVER['REQUEST_URI']);
			if (strpos($url, "?") !== false && strpos($url, "/?") === false) {
				$url = str_replace('?', '/?', $url);
			}
			$customUrl = rtrim(substr($url, strlen(CONF_WEBROOT_URL)), '/');
			$customUrl = explode('/?', $customUrl);

			/* [ Handled lang code in url */
			if (FatApp::getConfig('CONF_LANG_SPECIFIC_URL', FatUtility::VAR_INT, 0)) {
				$langCustomUrl = explode('/', $customUrl[0]);
				if (isset($langCustomUrl[0]) && $langCustomUrl[0] != '') {
					if (in_array(strtoupper($langCustomUrl[0]), LANG_CODES_ARR)) {
						$customUrl[0] = substr($customUrl[0], strlen(reset($langCustomUrl)));
						$customUrl[0] = ltrim($customUrl[0], '/');
					}
				}
			}
			/* ] */
			/* [ Check url rewritten by the system or system url with query parameter*/
			$row = false;
			if (!empty($customUrl[0])) {
				$srch = UrlRewrite::getSearchObject();
				$srch->doNotCalculateRecords();
				$srch->addMultipleFields(array('urlrewrite_custom', 'urlrewrite_original'));
				$srch->setPageSize(1);
				$cond = $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $customUrl[0]);
				if (!empty($new_url)) {
					$cond->attachCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', 'LIKE', $new_url, 'OR');
				}
				//$srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', SYSTEM_LANG_ID);
				$rs = $srch->getResultSet();
				$row = FatApp::getDb()->fetch($rs);

				//CommonHelper::printArray($row, true);
				if (!$row && !FatUtility::isAjaxCall()) {
					$srch = UrlRewrite::getSearchObject();
					$srch->doNotCalculateRecords();
					$srch->addMultipleFields(array('urlrewrite_custom', 'urlrewrite_original', 'urlrewrite_http_resp_code'));
					$srch->setPageSize(1);
					$cond = $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'original', '=', $customUrl[0]);
					if (!empty($urlwithoutparameter)) {
						$cond->attachCondition(UrlRewrite::DB_TBL_PREFIX . 'original', 'LIKE', implode('/', $urlwithoutparameter), 'OR');
					}
					$srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'lang_id', '=', SYSTEM_LANG_ID);
					$rs = $srch->getResultSet();
					$res = FatApp::getDb()->fetch($rs);
					if (!empty($res) && $res['urlrewrite_custom'] != '') {
						$redirectQueryString = (isset($customUrl[1]) && $customUrl[1] != '') ?  '?' . $customUrl[1] : '';
						if (strpos($res['urlrewrite_custom'], 'urlparameter') !== false) {
							$rewriteCustomUrl = implode('/', array_slice(explode('/', $res['urlrewrite_custom']), 0, 2)) . $last_param;
							header("Location: " . rtrim(CommonHelper::generateFullUrl(CONF_WEBROOT_URL), '/') . '/' . $rewriteCustomUrl . $redirectQueryString, true, $res['urlrewrite_http_resp_code']);
						} else {
							header("Location: " . rtrim(CommonHelper::generateFullUrl(CONF_WEBROOT_URL), '/') . '/' . $res['urlrewrite_custom'] . $redirectQueryString, true, $res['urlrewrite_http_resp_code']);
						}
						header("Connection: close");
					}
				}
			}
			if (!$row && (!isset($customUrl[1]) || (isset($customUrl[1]) && strpos($customUrl[1], 'pagesize') === false))) {
				return;
			}
			/*]*/
			$url = ((!empty($row['urlrewrite_original'])) ? $row['urlrewrite_original'] : '') . $last_param;
			if (!$row && isset($customUrl[1])) {
				$url = $customUrl[0];
			}
			$arr = explode('/', $url);
			$controller = (isset($arr[0])) ? $arr[0] : '';
			array_shift($arr);
			$action = (isset($arr[0])) ? $arr[0] : '';
			array_shift($arr);
			$queryString = $arr;
			/* [ used in case of filters when passed through url*/
			//array_shift($customUrl);
			if (isset($customUrl[1]) && !empty($customUrl[1])) {
				$customUrl = explode('&', $customUrl[1]);
				$queryString = array_merge($queryString, $customUrl);
			}
			if ($controller != '' && $action == '') {
				$action = 'index';
			}
			if ($controller == '') {
				$controller = 'Content';
			}
			if ($action == '') {
				$action = 'error404';
			}
		}
	}
}
