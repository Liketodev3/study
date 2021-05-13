<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
    'listserial' => Label::getLabel('LBL_Sr_no.', $adminLangId),
    'lpackage_identifier' => Label::getLabel('LBL_Title', $adminLangId),
    'lpackage_is_free_trial' => Label::getLabel('LBL_Free_Trial', $adminLangId),
    'lpackage_active' => Label::getLabel('LBL_Status', $adminLangId),
    'action' => Label::getLabel('LBL_Action', $adminLangId),
);
$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr');
    $tr->setAttribute("id", $row['lpackage_id']);

    if ($row['lpackage_active'] != applicationConstants::ACTIVE) {
        /* $tr->setAttribute ("class","fat-inactive"); */
    }
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'lpackage_active':
                $active = "";
                $statusAct = '';
                if (($row['lpackage_active'] == applicationConstants::YES) && $canEdit) {
                    $active = 'checked';
                    $statusAct = 'inactiveStatus(this)';
                }
                if (($row['lpackage_active'] == applicationConstants::NO) && $canEdit) {
                    $active = '';
                    $statusAct = 'activeStatus(this)';
                }

                $statusClass = !$canEdit ? 'disabled' : '';
                $str = '<label class="statustab -txt-uppercase">                 
                     <input ' . $active . ' type="checkbox" id="switch' . $row['lpackage_id'] . '" value="' . $row['lpackage_id'] . '" onclick="' . $statusAct . '" class="switch-labels status_' . $row['lpackage_id'] . '"/>
                    <i class="switch-handles ' . $statusClass . '"></i></label>';
                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'lpackage_is_free_trial':
                $td->appendElement('plaintext', array(), applicationConstants::getYesNoArr($adminLangId)[$row[$key]], true);
                break;
            case 'lpackage_identifier':
                if ($row['lpackage_title'] != '') {
                    $td->appendElement('plaintext', array(), $row['lpackage_title'], true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '(' . $row[$key] . ')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key], true);
                }
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class" => "actions actions centered"));
                if ($canEdit) {
                    $li = $ul->appendElement("li", array('class' => 'droplink'));

                    $li->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                    $innerDiv = $li->appendElement('div', array('class' => 'dropwrap'));
                    $innerUl = $innerDiv->appendElement('ul', array('class' => 'linksvertical'));
                    $innerLiEdit = $innerUl->appendElement('li');
                    $innerLiEdit->appendElement(
                        'a',
                        array(
                            'href' => 'javascript:void(0)', 'class' => 'button small green',
                            'title' => Label::getLabel('LBL_Edit', $adminLangId), "onclick" => "editLessonPackageFormNew(" . $row['lpackage_id'] . ")"
                        ),
                        Label::getLabel('LBL_Edit', $adminLangId),
                        true
                    );
                }
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', array('colspan' => count($arr_flds)), Label::getLabel('LBL_No_Records_Found', $adminLangId));
}
echo $tbl->getHtml();
