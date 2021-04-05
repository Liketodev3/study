<?php

class MetaTagsWriter
{

    static function getMetaTags($controller, $action, $arrParameters)
    {
        $langId = CommonHelper::getLangId();
        if (!$langId) {
            $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        }
        $websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId, FatUtility::VAR_STRING, '');
        $fatUtlityObj = new FatUtility;
        $controller = explode('-', FatUtility::camel2dashed($controller));
        array_pop($controller);
        $controllerName = implode('-', $controller);
        $controllerName = ucfirst(FatUtility::dashed2Camel($controllerName));
        $srch = new MetaTagSearch($langId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addMultipleFields([
            'IFNULL(meta_title, meta_identifier) as meta_title',
            'meta_keywords', 'meta_description', 'meta_other_meta_tags'
        ]);
        $defSearch = clone $srch;
        $srch->addCondition('meta_controller', '=', $controllerName);
        $srch->addCondition('meta_action', '=', $action);
        $srch->addOrder('meta_default', 'asc');
        if (!empty($arrParameters)) {
            switch ($controllerName) {
                default:
                    if (isset($arrParameters[0]) && $arrParameters[0] != '') {
                        $cond = $srch->addCondition('meta_record_id', '=', $arrParameters[0]);
                    }
                    if (isset($arrParameters[1]) && $arrParameters[1] != '') {
                        $cond = $srch->addCondition('meta_subrecord_id', '=', $arrParameters[1]);
                    }
                    break;
            }
        }
        $rs = $srch->getResultSet();
        if ($metas = FatApp::getDb()->fetch($rs)) {
            $title = $metas['meta_title'] . ' | ' . $websiteName;
            echo '<title>' . $title . '</title>' . "\n";
            if (isset($metas['meta_description'])) {
                echo '<meta name="description" content="' . $metas['meta_description'] . '" />';
            }
            if (isset($metas['meta_keywords'])) {
                echo '<meta name="keywords" content="' . $metas['meta_keywords'] . '" />';
            }
            if (isset($metas['meta_other_meta_tags'])) {
                echo CommonHelper::renderHtml($metas['meta_other_meta_tags'], ENT_QUOTES, 'UTF-8');
            }
        } else {
            $defSearch->addCondition('meta_type', '=', MetaTag::META_GROUP_DEFAULT);
            if ($metas = FatApp::getDb()->fetch($defSearch->getResultSet())) {
                $title = $metas['meta_title'] . ' | ' . $websiteName;
                echo '<title>' . $title . '</title>' . "\n";
                if (isset($metas['meta_description'])) {
                    echo '<meta name="description" content="' . $metas['meta_description'] . '" />';
                }
                if (isset($metas['meta_keywords'])) {
                    echo '<meta name="keywords" content="' . $metas['meta_keywords'] . '" />';
                }
                if (isset($metas['meta_other_meta_tags'])) {
                    echo CommonHelper::renderHtml($metas['meta_other_meta_tags'], ENT_QUOTES, 'UTF-8');
                }
            } else {
                return '<title>' . $websiteName . '</title>';
            }
        }
    }

}
