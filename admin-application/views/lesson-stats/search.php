<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$userTypes = User::getUserTypesArr($adminLangId);

$arr_flds = array(
    'listserial' => Label::getLabel('LBL_Sr_no.', $adminLangId),
    'user_full_name' => Label::getLabel('LBL_User_Details', $adminLangId),
    'user_type' => Label::getLabel('LBL_User_Type', $adminLangId),
    // 'upcomingLessons' => Label::getLabel('LBL_Upcoming', $adminLangId),
    // 'completedLessons' => Label::getLabel('LBL_Completed', $adminLangId),
    'rescheduledLessons' => Label::getLabel('LBL_Rescheduled', $adminLangId),
    'cancelledLessons' => Label::getLabel('LBL_Cancelled', $adminLangId),
    'action' => Label::getLabel('LBL_Action', $adminLangId)
);


$tbl = new HtmlElement(
    'table',
    array('width' => '100%', 'class' => 'table table-responsive table--hovered')
);

$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}

$sr_no = $page == 1 ? 0 : $pageSize * ($page - 1);

foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr');

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'user_full_name':
                $td->appendElement('plaintext', array(), 'N: ' . $row[$key] . '<br> E: ' . $row['credential_email'], true);
                break;
            case 'user_type':
                $str = '';
                if ($row['user_is_learner']) {
                    $str .= $userTypes[User::USER_TYPE_LEANER] . '<br/>';
                }
                if ($row['user_is_teacher']) {
                    $str .= $userTypes[User::USER_TYPE_TEACHER];
                }
                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'upcomingLessons':
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
            case 'completedLessons':
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
            case 'rescheduledLessons':
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
            case 'cancelledLessons':
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class" => "actions actions--centered"));
                $li = $ul->appendElement("li", array('class' => 'droplink'));
                $li->appendElement(
                    'a',
                    array(
                        'href' => 'javascript:void(0)', 'class' => 'button small green',
                        'title' => Label::getLabel('LBL_Download_Reports', $adminLangId)
                    ),
                    '<i class="ion-android-more-horizontal icon"></i>',
                    true
                );
                $innerDiv = $li->appendElement('div', array('class' => 'dropwrap'));
                $innerUl = $innerDiv->appendElement('ul', array('class' => 'linksvertical'));

                // $innerLiBoth=$innerUl->appendElement('li');
                // $innerLiBoth->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
                // 	'title'=>Label::getLabel('LBL_Download_Both_Report',$adminLangId),
                // 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::BOTH_REPORT.")"),
                // 	Label::getLabel('LBL_Both',$adminLangId), true);

                // $innerLiReschedule=$innerUl->appendElement('li');
                // $innerLiReschedule->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
                // 	'title'=>Label::getLabel('LBL_Download_Reschedule_Report',$adminLangId),
                // 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::NOT_CANCELLED_REPORT.")"),
                // 	Label::getLabel('LBL_Reschedule',$adminLangId), true);

                // $innerLiCancelled=$innerUl->appendElement('li');
                // $innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
                // 	'title'=>Label::getLabel('LBL_Download_Cancelled_Report',$adminLangId),
                // 	"onclick"=>"exportReport(".$row['user_id'].", ".LessonStatusLog::CANCELLED_REPORT.")"),
                // 	Label::getLabel('LBL_Cancelled',$adminLangId), true);

                // $innerLiCancelled=$innerUl->appendElement('li');
                // $innerLiCancelled->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 
                // 	'title'=>Label::getLabel('LBL_View_Report',$adminLangId),
                // 	"onclick"=>"viewReport(".$row['user_id'].", ".LessonStatusLog::BOTH_REPORT.")"),
                // 	Label::getLabel('LBL_View_Report',$adminLangId), true);

                $innerLiCancelled = $innerUl->appendElement('li');
                $innerLiCancelled->appendElement(
                    'a',
                    array(
                        'href' => "javascript:void(0)", 'class' => 'button small green',
                        'title' => Label::getLabel('LBL_View_Rescheduled_Report', $adminLangId),
                        "onclick" => "viewReport(" . $row['user_id'] . ", " . LessonStatusLog::RESCHEDULED_REPORT . ")"
                    ),
                    Label::getLabel('LBL_View_Rescheduled_Report', $adminLangId),
                    true
                );

                $innerLiCancelled = $innerUl->appendElement('li');
                $innerLiCancelled->appendElement(
                    'a',
                    array(
                        'href' => "javascript:void(0)", 'class' => 'button small green',
                        'title' => Label::getLabel('LBL_View_Cancelled_Report', $adminLangId),
                        "onclick" => "viewReport(" . $row['user_id'] . ", " . LessonStatusLog::CANCELLED_REPORT . ")"
                    ),
                    Label::getLabel('LBL_View_Cancelled_Report', $adminLangId),
                    true
                );

                /* $innerLiUpcoming = $innerUl->appendElement('li');
                $innerLiUpcoming->appendElement(
                    'a',
                    array(
                        'href' => "javascript:void(0)", 'class' => 'button small green',
                        'title' => Label::getLabel('LBL_View_Upcoming_Report', $adminLangId),
                        "onclick" => "viewUpcomingAndCompletedReport(" . $row['user_id'] . ", " . LessonStatusLog::UPCOMING_REPORT . ")"
                    ),
                    Label::getLabel('LBL_View_Upcoming_Report', $adminLangId),
                    true
                );

                $innerLiCompleted = $innerUl->appendElement('li');
                $innerLiCompleted->appendElement(
                    'a',
                    array(
                        'href' => "javascript:void(0)", 'class' => 'button small green',
                        'title' => Label::getLabel('LBL_View_Completed_Report', $adminLangId),
                        "onclick" => "viewUpcomingAndCompletedReport(" . $row['user_id'] . ", " . LessonStatusLog::COMPLETED_REPORT . ")"
                    ),
                    Label::getLabel('LBL_View_Completed_Report', $adminLangId),
                    true
                ); */
                break;

            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement(
        'td',
        array(
            'colspan' => count($arr_flds)
        ),
        Label::getLabel('LBL_No_Records_Found', $adminLangId)
    );
}
echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array(
    'name' => 'frmRescheduledReportSearchPaging'
));
$pagingArr = array('pageCount' => $pageCount, 'page' => $page, 'recordCount' => $recordCount, 'adminLangId' => $adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
