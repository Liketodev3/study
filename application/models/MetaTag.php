<?php
class MetaTag extends MyAppModel
{
    const DB_TBL = 'tbl_meta_tags';
    const DB_TBL_PREFIX = 'meta_';

    const DB_LANG_TBL = 'tbl_meta_tags_lang';
    const DB_LANG_TBL_PREFIX = 'metalang_';

    const DB_USERS_TBL = 'tbl_users';
    const DB_USERS_TBL_PREFIX = 'user_';

    const DB_GRP_CLS_TBL = 'tbl_group_classes';
    const DB_GRP_CLS_TBL_PREFIX = 'grpcls_';

    const DB_USERS_TCHER_REQ = 'tbl_user_teacher_requests';
    const DB_USERS_TCHER_REQ_PREFIX = 'utrequest_';

    const META_GROUP_DEFAULT = -1;
    const META_GROUP_OTHERS = 0;
    const META_GROUP_TEACHERS = 1;
    const META_GROUP_GRP_CLASSES = 2;
    const META_GROUP_CMS_PAGE = 3;
    const META_GROUP_BLOG_CATEGORIES = 4;
    const META_GROUP_BLOG_POSTS = 5;

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

            static::META_GROUP_DEFAULT => array(
                'serial' => 0,
                'name' => Label::getLabel('LBL_Default', $langId),
                'controller' => '',
                'action' => '',
                'isEntity' => false
            ),
            static::META_GROUP_OTHERS => array(
                'serial' => 1,
                'name' => Label::getLabel('LBL_Others', $langId),
                'controller' => '',
                'action' => '',
                'isEntity' => false
            ),
            static::META_GROUP_TEACHERS => array(
                'serial' => 2,
                'name' => 'Teachers',
                'controller' => 'Teachers',
                'action' => 'view',
                'isEntity' => false
            ),
            static::META_GROUP_GRP_CLASSES => array(
                'serial' => 3,
                'name' => 'Group Classes',
                'controller' => 'GroupClasses',
                'action' => 'view',
                'isEntity' => false
            ),
            static::META_GROUP_CMS_PAGE => array(
                'serial' => 4,
                'name' => Label::getLabel('LBL_CMS_Page', $langId),
                'controller' => 'Cms',
                'action' => 'view',
                'isEntity' => false
            ),
            static::META_GROUP_BLOG_CATEGORIES => array(
                'serial' => 5,
                'name' => Label::getLabel('LBL_Blog_Categories', $langId),
                'controller' => 'Blog',
                'action' => 'category',
                'isEntity' => false
            ),
            static::META_GROUP_BLOG_POSTS => array(
                'serial' => 6,
                'name' => Label::getLabel('LBL_Blog_Posts', $langId),
                'controller' => 'Blog',
                'action' => 'postDetail',
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

    public static function getMetaTagsAdvancedTeacher($langId)
    {
        $srch = new SearchBase(static::DB_USERS_TBL, 'u');
        $srch->joinTable(static::DB_TBL, 'LEFT OUTER JOIN', "mt.meta_record_id = u.user_id and mt.meta_controller = 'Teachers' and mt.meta_action = 'view' ", 'mt');
        $srch->joinTable(static::DB_LANG_TBL, 'LEFT OUTER JOIN', "mt_l.metalang_meta_id = mt.meta_id AND mt_l.metalang_lang_id = " . $langId, 'mt_l');
        $srch->addCondition('u.user_is_teacher', '=', 1);
        $srch->addMultipleFields(array('meta_id', 'meta_identifier', 'meta_title', 'CONCAT(user_first_name, " ", user_last_name) as teacherFullName', 'u.user_id'));
        return $srch;
    }

    public static function getMetaTagsAdvancedGrpClasses($langId)
    {
        $metaTagSrch = new SearchBase(static::DB_GRP_CLS_TBL, 'gcls');
        $metaTagSrch->joinTable(static::DB_TBL, 'LEFT OUTER JOIN', "mt.meta_record_id = gcls.grpcls_id and mt.meta_controller = 'GroupClasses' and mt.meta_action = 'view' ", 'mt');
        $metaTagSrch->joinTable(static::DB_LANG_TBL, 'LEFT OUTER JOIN', "mt_l.metalang_meta_id = mt.meta_id AND mt_l.metalang_lang_id = " . $langId, 'mt_l');
        $metaTagSrch->addMultipleFields(array('meta_id', 'meta_identifier', 'meta_title', 'grpcls_title', 'grpcls_id'));
        $metaTagSrch->addOrder('grpcls_start_datetime', 'DESC');

        return $metaTagSrch;
    }

    public static function getTeacherIDByUserName($teacherUserName)
    {
        $teacherId = '';
        $usrNameSrch = new SearchBase(static::DB_USERS_TBL, 'u');
        $usrNameSrch->addCondition('u.user_url_name', '=', $teacherUserName);
        $usrNameSrch->addFld('u.user_id');
        $rs = $usrNameSrch->getResultSet();
        if ($rs) {
            $records = FatApp::getDb()->fetch($rs);
            if (!empty($records)) {
                $teacherId = $records['user_id'];
            }
        }
        return $teacherId;
    }

    public static function getOrignialUrlFromComponents($row)
    {
        if (empty($row) || $row['meta_controller'] == '') {
            return false;
        }
        $url = '';
        foreach ([$row['meta_controller'], $row['meta_action'], $row['meta_record_id'], $row['meta_subrecord_id']] as $value) {
            if ($value != '0' && $value != '') {
                $url .= $value . '/';
            }
        }


        return rtrim($url, '/');
    }
}
