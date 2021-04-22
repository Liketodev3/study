<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php

$arr_flds = array(
    'listserial' => Label::getLabel('LBL_S.No.', $adminLangId),
    'sldetail_order_id' => Label::getLabel('LBL_Order_Id', $adminLangId),
    'issrep_slesson_id' => Label::getLabel('LBL_Lesson_Id', $adminLangId),
    'issrep_reported_by' => Label::getLabel('LBL_Reported_By', $adminLangId),
    'issrep_escalated_by' => Label::getLabel('LBL_Escalated_By', $adminLangId),
    'issrep_status' => Label::getLabel('LBL_Status', $adminLangId),
    'action' => Label::getLabel('LBL_Action', $adminLangId),
);
$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}
$sr_no = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array());
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'issrep_reported_by':
                $td->appendElement('plaintext', array(), $row['reporter_username'], true);
                break;
            case 'issrep_escalated_by':
                $td->appendElement('plaintext', [], User::getUserTypesArr($adminLangId)[$row[$key]] ?? 'NA', true);
                break;
            case 'issrep_status':
                $td->appendElement('plaintext', [], IssuesReported::getResolveTypeArray()[$row[$key]] ?? 'NA', true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class" => "actions actions--centered"));
                $li = $ul->appendElement("li", array('class' => 'droplink'));
                $li->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                $innerDiv = $li->appendElement('div', array('class' => 'dropwrap'));
                $innerUl = $innerDiv->appendElement('ul', array('class' => 'linksvertical'));
                $viewLi = $innerUl->appendElement('li');
                $viewLi->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_View', $adminLangId), "onclick" => "viewDetail(" . $row['issrep_slesson_id'] . ")"), Label::getLabel('LBL_View', $adminLangId), true);
                $actionLi = $innerUl->appendElement("li");
                $actionLi->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_Action', $adminLangId), "onclick" => "actionForm(" . $row['issrep_id'] . ")"), Label::getLabel('LBL_Action', $adminLangId), true);
                $txnLi = $innerUl->appendElement("li");
                $txnLi->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_Transactions', $adminLangId), "onclick" => "transactions(" . $row['issrep_slesson_id'] . "," . $row['issrep_id'] . ")"), Label::getLabel('LBL_Transactions', $adminLangId), true);
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key] ?? 'NA', true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', array('colspan' => count($arr_flds)), Label::getLabel('LBL_No_Records_Found', $adminLangId));
}
echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array(
    'name' => 'frmUserSearchPaging'
));
$pagingArr = array('pageCount' => $pageCount, 'page' => $page, 'pageSize' => $pageSize, 'recordCount' => $recordCount, 'adminLangId' => $adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
?>
