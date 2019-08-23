<?php $arr_flds = array(
	'check'=>'',
	'profile'=> '',	
    //'noti_is_read' => Label::getLabel('LBL_Notification_Is_Read', $siteLangId),
    'noti_title' => Label::getLabel('LBL_Notification_TITLE', $siteLangId),
    'noti_sent_on' => Label::getLabel('LBL_Notification_Sent_ON', $siteLangId),
);



$tbl = new HtmlElement('table', array('class' => 'table-listing'));

$sr_no = 0;
//print_r($list); die;
foreach ($list as $sn => $order) {
    $sr_no++;
	$notiTrcls = 'is-read';
	if($order['noti_is_read']==0){
		$notiTrcls = '';
	}
    $tr = $tbl->appendElement('tr', array('class' => $notiTrcls));	
	$bell = '/images/bell-colored.svg';
	$notificationUrl = CommonHelper::generateUrl('notifications','readNotification',array($order['noti_id']));
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td',array('class'=>($key=='check')?'td--check':(($key=='profile')?'td--avtar':'')));
        switch ($key) {
			case 'check':
					$td->appendElement('plaintext',array('class'=>'td--check'),'<label class="checkbox"><input type="checkbox" class="check-record" rel='.$order['noti_id'].'><i class="input-helper"></i></label>',true);
			break;          
			case 'profile':
			if( $order['noti_type'] == UserNotifications::NOTICATION_FOR_TEACHER_APPROVAL ){
				$div = $td->appendElement('div', array('class'=>'avtar avtar--xsmall','data-text'=> 'A'));				
			}else{
				$div = $td->appendElement('div', array('class'=>'avtar avtar--xsmall','data-text'=> 'A'));
                $picId = ($order['noti_sub_record_id']==0)?UserAuthentication::getLoggedUserId():$order['noti_sub_record_id'];
                if( true == User::isProfilePicUploaded($order['noti_sub_record_id']) ){
					$div->appendElement('img', array('src'=>CommonHelper::generateUrl('Image','user',array($picId,'MINI',true),CONF_WEBROOT_FRONT_URL)));
				}
			}
			break;
            case 'noti_is_read':
				if($order[$key]==1){
					$bell = '/images/bell-gray.svg';
				}
                $txt = '<div class="bell-notify"><img src="'.$bell.'" alt=""></div>';
                $td->appendElement('plaintext', array(), $txt, true);
                break;

			case 'noti_title':
                $txt = '<div class="listing__desc"><strong><a href="'.$notificationUrl.'">'.$order[$key].'</a></strong>';
				/*if( $order['noti_type'] == UserNotifications::NOTICATION_FOR_ORDER_RECIEVED ){
					$txt .= '<br> <strong>Order Status: </strong>'."<span class='status ".CommonHelper::getOrderStatusClassName($order['order_status'])."'>".$order['orderstatus_name']."</span>";
				}*/
                $txt .= '<br>'.$order['noti_desc'].'</div>';				
                $td->appendElement('plaintext', array(), $txt, true);
                break;
          

            case 'noti_sent_on':
				$dateTime = explode(' ',$order['noti_sent_on']);
                $txt = '<span class="date"> '.$dateTime[0].' <span class="time">'.$dateTime[1].'</span></span>';
                $td->appendElement('plaintext', array(), $txt, true);
                break;			
			
            default:
                $td->appendElement('plaintext', array(), '<span class="caption--td">' . $val . '</span>' . $order[$key], true);
                break;
        }
    }
}
if (count($list) == 0) {
    // $tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_Unable_to_find_any_record', $siteLangId));
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId' => $siteLangId));
} else {
    echo $tbl->getHtml();
}

$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmNotificationSrch'));
$pagingArr = array('pageCount' => $pageCount, 'page' => $page, 'recordCount' => $recordCount, 'callBackJsFunc' => 'goToNotificationSearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr); ?>