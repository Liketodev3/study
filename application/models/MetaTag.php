<?php
class MetaTag extends MyAppModel
{
    const DB_TBL = 'tbl_meta_tags';
    const DB_TBL_PREFIX = 'meta_';

    const DB_LANG_TBL ='tbl_meta_tags_lang';
    const DB_LANG_TBL_PREFIX ='metalang_';


    const META_GROUP_CMS_PAGE = 'cms_page_view' ;
    const META_GROUP_DEFAULT = 'default' ;
    const META_GROUP_ADVANCED = 'advanced_setting' ;


    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getTabsArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (!$langId) {
            $langId = CommonHelper::getLangId();
        }
        $metaGroups = array(

            static::META_GROUP_CMS_PAGE => array(
            'serial' => 6,
            'name' => Label::getLabel('LBL_CMS_Page', $langId),
            'controller' => 'Cms',
            'action' => 'view',
            'isEntity' => false
            ),
            static::META_GROUP_DEFAULT => array(
            'serial' => 0,
            'name' => Label::getLabel('LBL_Default', $langId),
            'controller' => '',
            'action' => '',
            'isEntity' => false
            ),
            static::META_GROUP_ADVANCED => array(
            'serial' => 99,
            'name' => Label::getLabel('LBL_Advanced_Setting', $langId),
            'controller' => '',
            'action' => '',
            'isEntity' => false
            ),

        );

        uasort($metaGroups, function ($group1, $group2) {
            if ($group1['serial'] == $group2['serial']) {
                return 0;
            }
            return ($group1['serial'] < $group2['serial']) ? -1 : 1;
        });

        return $metaGroups;
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'mt');
    
        return $srch;
    }
}
