<?php
class OrderPayment extends Order
{
    private $orderAttributes;
    private $paymentOrderId;
    private $orderLangId;

    public function __construct($orderId, $langId = 0)
    {
        if (empty($orderId)) {
            trigger_error("Invalid Request", E_USER_ERROR);
        }
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $orderId);

        $this->paymentOrderId = $orderId;
        $this->orderLangId = $langId;
        $this->loadOrderData();
    }

    private function loadOrderData()
    {
        $this->orderAttributes = $this->getOrderById($this->paymentOrderId);
    }

    public function getOrderPrimaryinfo()
    {
        $orderInfo = $this->orderAttributes;
        $userObj = new User($orderInfo["order_user_id"]);
        $userInfo = $userObj->getUserInfo(array('user_first_name', 'credential_email', 'user_phone'), true, true, true);

        $currencyArr = Currency::getCurrencyAssoc($this->orderLangId);
        $orderCurrencyCode = !empty($currencyArr[FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)]) ? $currencyArr[FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)] : '';

        $arrOrder = array(
            "order_id" => $orderInfo["order_id"],
            "invoice" => $orderInfo["order_id"],
            "customer_id" => $orderInfo["order_user_id"],
            "user_name" => $userInfo["user_first_name"],
            "user_email" => $userInfo["credential_email"],
            "order_currency_code" => $orderCurrencyCode,
            "order_type" => $orderInfo['order_type'],
            "order_is_paid" => $orderInfo["order_is_paid"],
            "order_language_id" => $orderInfo["order_language_id"],
            "order_language_code" => $orderInfo["order_language_code"],
            "site_system_name" => FatApp::getConfig("CONF_WEBSITE_NAME_" . $orderInfo["order_language_id"]),
            "site_system_admin_email" => FatApp::getConfig("CONF_SITE_OWNER_EMAIL", FatUtility::VAR_STRING, ''),
            "order_wallet_amount_charge" => $orderInfo['order_wallet_amount_charge'],
            "paypal_bn" => "FATbit_SP",
        );
        return $arrOrder;
    }
    public function chargeFreeOrder($amountToBeCharge = 0, $isFreeTrial = false)
    {
        if ($amountToBeCharge > 0) {
            $this->error = Label::getLabel('MSG_Invalid_Order');
            return false;
        }

        $langId = FatApp::getConfig('conf_default_site_lang');
        $orderInfo = $this->orderAttributes;

        /* [ */
        $transObj = new Transaction($orderInfo["order_user_id"]);
        $formattedOrderId = "#".$orderInfo["order_id"];

        $utxn_comments = Label::getLabel('LBL_ORDER_PLACED_{order-id}', $langId);
        
        if($isFreeTrial) {
            $utxn_comments = Label::getLabel('LBL_Teacher_Free_Trial_Booked_for_Order:_{order-id}', $langId);
        }

        $utxn_comments = str_replace("{order-id}", $formattedOrderId, $utxn_comments);

        $db = FatApp::getDb();

        $db->startTransaction();
        $txnDataArr = array(
            'utxn_user_id'	=>	$orderInfo["order_user_id"],
            'utxn_debit'	=>	$amountToBeCharge,
            'utxn_status'	=>	Transaction::STATUS_COMPLETED,
            'utxn_order_id'	=>	$orderInfo["order_id"],
            'utxn_comments'	=>	$utxn_comments,
            'utxn_type'		=>	Transaction::TYPE_LESSON_BOOKING
        );

        $transObj->assignValues($txnDataArr);
        if (!$transObj->save()) {
            $this->error = $transObj->getError();
            return false;
        }
        /* ] */

        /* Send email to User[ */
        /* $emailNotificationObj = new EmailHandler();
        $emailNotificationObj->sendTxnNotification( $txnId, $langId ); */
        /* ] */

        if (!$this->addOrderPayment(
            Label::getLabel('LBL_User_Wallet', $langId),
            'W-'.time(),
            $amountToBeCharge,
            Label::getLabel("LBL_Received_Payment", $langId),
            Label::getLabel('LBL_Payment_From_User_Wallet', $langId),
            true
        )) {
            return false;
        }
        $db->commitTransaction();
        return true;
    }

    public function chargeUserWallet($amountToBeCharge)
    {
        $defaultSiteLangId = FatApp::getConfig('conf_default_site_lang');
        $orderInfo = $this->orderAttributes;
        $userWalletBalance = User::getUserBalance($orderInfo["order_user_id"]);

        if ($userWalletBalance < $amountToBeCharge) {
            $this->error = Message::addErrorMessage(Label::getLabel('MSG_Wallet_Balance_is_less_than_amount_to_be_charge', $defaultSiteLangId));
            return false;
        }

        $formattedOrderValue = "#".$orderInfo["order_id"];
        $transObj = new Transaction($orderInfo["order_user_id"]);

        $utxnComments = Transaction::formatTransactionCommentByOrderId($orderInfo["order_id"], $defaultSiteLangId);
        $txnDataArr = array(
            'utxn_user_id'	=>	$orderInfo["order_user_id"],
            'utxn_debit'	=>	$amountToBeCharge,
            'utxn_status'	=>	Transaction::STATUS_COMPLETED,
            'utxn_order_id'	=>	$orderInfo["order_id"],
            'utxn_comments'	=>	$utxnComments,
            'utxn_type'		=>	Transaction::TYPE_LESSON_BOOKING
        );
        $transObj->assignValues($txnDataArr);
        if (!$transObj->save()) {
            $this->error = $transObj->getError();
            return false;
        }

        $txnId = $transObj->getMainTableRecordId();
        /* Send email to User[ */
        // $emailNotificationObj = new EmailHandler();
        // $emailNotificationObj->sendTxnNotification( $txnId, $defaultSiteLangId );
        /* ] */

        // Update Order table user wallet charge amount
        $orderWalletAmountCharge = $orderInfo['order_wallet_amount_charge'] - $amountToBeCharge;
        if (!FatApp::getDb()->updateFromArray(
            Order::DB_TBL,
            array( 'order_wallet_amount_charge' => $orderWalletAmountCharge),
            array('smt'=>'order_id = ?', 'vals'=>array($orderInfo["order_id"]))
        )) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }

        if (!$this->addOrderPayment(Label::getLabel('LBL_User_Wallet', $defaultSiteLangId), 'W-'.time(), $amountToBeCharge, Label::getLabel("LBL_Received_Payment", $defaultSiteLangId), Label::getLabel('LBL_Payment_From_User_Wallet', $defaultSiteLangId), true)) {
            return false;
        }
        return true;
    }

    public function getOrderPaymentGatewayAmount()
    {
        $orderInfo = $this->orderAttributes;
        $orderPaymentGatewayCharge = $orderInfo["order_net_amount"] - $orderInfo['order_wallet_amount_charge'];
        return round($orderPaymentGatewayCharge, 2);
    }

    public function addOrderPayment($paymentMethodName, $txnId, $amount, $comments = '', $response = '', $isWallet = false, $opId = 0)
    {
        $defaultSiteLangId = FatApp::getConfig('conf_default_site_lang');
        $orderInfo = $this->orderAttributes;

        if (empty($orderInfo)) {
            $this->error = Label::getLabel('MSG_Invalid_Order');
            return false;
        }

        /* [ */
        $orderProductSrch = new OrderProductSearch();
        $orderProductSrch->addCondition('op_order_id', '=', $this->paymentOrderId);
        $orderProductSrch->addMultipleFields(array(
            'op_teacher_id',
            'op_grpcls_id',
            'op_slanguage_id',
            'op_lpackage_lessons',
            'op_lpackage_is_free_trial'
        ));
        $rs = $orderProductSrch->getResultSet();
        $orderProductRow = FatApp::getDb()->fetch($rs);
        /* ] */

        if ($orderProductRow) {
            $orderInfo = $orderInfo + $orderProductRow;
        }

        if (!FatApp::getDb()->insertFromArray(
            static::DB_TBL_ORDER_PAYMENTS,
            array(
                'opayment_order_id' => $this->paymentOrderId,
                'opayment_method' => $paymentMethodName,
                'opayment_gateway_txn_id'=>$txnId,
                'opayment_amount'=>$amount,
                'opayment_comments'=>$comments,
                'opayment_gateway_response'=>$response,
                'opayment_date' => date('Y-m-d H:i:s')
            )
        )) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }

        $totalPaymentPaid = $this->getOrderPaymentPaid($this->paymentOrderId);
        $orderBalance = ($orderInfo['order_net_amount'] - $totalPaymentPaid) ;

        if ($orderBalance <= 0) {
            $this->addOrderPaymentHistory($this->paymentOrderId, Order::ORDER_IS_PAID, Label::getLabel('LBL_Received_Payment', $defaultSiteLangId), 1);


            /* and for free trial made entry from freepaycontroller */
            /* add schedulaed lessons[ */
            if ($orderProductRow) {
                $counter = $orderInfo['op_lpackage_lessons']>0 ? $orderInfo['op_lpackage_lessons'] : 1;
                for ($i = 0; $i < $counter; $i++) {
                    if ($orderInfo['op_lpackage_is_free_trial'] == 0) {
                        $slesson_id = 0;
						$slesson_date = '0000-00-00';
						$slesson_end_date = '0000-00-00';
						$slesson_start_time = '00:00:00';
						$slesson_end_time = '00:00:00';
						$slesson_status = ScheduledLesson::STATUS_NEED_SCHEDULING;
						if($orderInfo['op_grpcls_id']>0){
							$slesson_id = ScheduledLessonSearch::getLessonInfoByGrpClsid($orderInfo['op_grpcls_id'], 'slesson_id');
							$grpClsDetails = TeacherGroupClasses::getAttributesById($orderInfo['op_grpcls_id']);
							$slesson_date = date('Y-m-d', strtotime($grpClsDetails['grpcls_start_datetime']));
							$slesson_end_date = date('Y-m-d', strtotime($grpClsDetails['grpcls_end_datetime']));
							$slesson_start_time = date('H:i:s', strtotime($grpClsDetails['grpcls_start_datetime']));
							$slesson_end_time = date('H:i:s', strtotime($grpClsDetails['grpcls_end_datetime']));
							$slesson_status = ScheduledLesson::STATUS_SCHEDULED;
						}
                        $sLessonArr = array(
							'slesson_teacher_id'	=>	$orderInfo['op_teacher_id'],
							'slesson_grpcls_id'	    =>	$orderInfo['op_grpcls_id'],
							'slesson_slanguage_id'	=>	$orderInfo['op_slanguage_id'],
							'slesson_date'	        =>	$slesson_date,
							'slesson_end_date'	    =>	$slesson_end_date,
							'slesson_start_time'	=>	$slesson_start_time,
							'slesson_end_time'	    =>	$slesson_end_time,
							'slesson_status'	    =>	$slesson_status
						);
						
						if($slesson_id<1){
							$sLessonObj = new ScheduledLesson();
							$sLessonObj->assignValues($sLessonArr);
							if (!$sLessonObj->save()) {
								$this->error = $sLessonObj->getError();
								return false;
							}
							$slesson_id = $sLessonObj->getMainTableRecordId();
						}
						$sLessonDetailAr = array(
							'sldetail_slesson_id'	=>	$slesson_id,
							'sldetail_order_id'	=>	$this->paymentOrderId,
							'sldetail_learner_id'	=>	$orderInfo['order_user_id'],
							'sldetail_learner_status'	=>	$slesson_status
						);
						$slDetailsObj = new ScheduledLessonDetails();
                        $slDetailsObj->assignValues($sLessonDetailAr);
                        if (!$slDetailsObj->save()) {
                            $this->error = $slDetailsObj->getError();
                            return false;
                        }
                        
                        $sldetailId = $slDetailsObj->getMainTableRecordId();
                        
                        if($orderInfo['op_grpcls_id']>0){
                            // share on student google calendar
                            $token = current(UserSetting::getUserSettings($orderInfo['order_user_id']))['us_google_access_token'];
                            if($token){
                                $view_url = CommonHelper::generateFullUrl('LearnerScheduledLessons', 'view', array($sldetailId));
                                $google_cal_data = array(
                                    'title' => FatApp::getConfig('CONF_WEBSITE_NAME_'.$defaultSiteLangId),
                                    'summary' => sprintf(Label::getLabel("LBL_CLASS-%s"), $grpClsDetails['grpcls_title']),
                                    'description' => sprintf(Label::getLabel("LBL_Click_here_to_deliver_the_lesson:_%s"), $view_url),
                                    'url' => $view_url,
                                    'start_time' => date('c', strtotime($slesson_date.' '.$slesson_start_time)),
                                    'end_time' => date('c', strtotime($slesson_end_date.' '.$slesson_end_time)),
                                    'timezone' => MyDate::getTimeZone(),
                                );
                                // CommonHelper::printArray($google_cal_data);die;
                                $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
                                if($calId){
                                    $sLessonDetailObj = new ScheduledLessonDetails($sldetailId);
                                    $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', $calId);
                                    $sLessonDetailObj->save();
                                }
                            }

                            // share on teacher google calendar
                            $token = current(UserSetting::getUserSettings($orderInfo['op_teacher_id']))['us_google_access_token'];
                            if($token){
                                $sLessonObj = new ScheduledLesson($slesson_id);
                                $sLessonObj->loadFromDb();
                                $oldCalId = $sLessonObj->getFldValue('slesson_teacher_google_calendar_id');

                                if(!$oldCalId){
                                    $view_url = CommonHelper::generateFullUrl('TeacherScheduledLessons', 'view', array($slesson_id));
                                    $google_cal_data = array(
                                        'title' => FatApp::getConfig('CONF_WEBSITE_NAME_'.$defaultSiteLangId),
                                        'summary' => sprintf(Label::getLabel("LBL_CLASS-%s"), $grpClsDetails['grpcls_title']),
                                        'description' => sprintf(Label::getLabel("LBL_Click_here_to_deliver_the_lesson:_%s"), $view_url),
                                        'url' => $view_url,
                                        'start_time' => date('c', strtotime($slesson_date.' '.$slesson_start_time)),
                                        'end_time' => date('c', strtotime($slesson_end_date.' '.$slesson_end_time)),
                                        'timezone' => MyDate::getTimeZone(),
                                    );
                                    $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
                                    if($calId){
                                        $sLessonObj->setFldValue('slesson_teacher_google_calendar_id', $calId);
                                        $sLessonObj->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            /* ] */

            /* $notificationData = array(
                'notification_record_type' => Notification::TYPE_ORDER,
                'notification_record_id' => $this->paymentOrderId,
                'notification_user_id' => $orderInfo['order_user_id'],
                'notification_label_key' => Notification::NEW_ORDER_STATUS_NOTIFICATION,
                'notification_added_on' => date('Y-m-d H:i:s'),
            );

            Notification::saveNotifications($notificationData);*/

            if (!empty($orderInfo['order_discount_coupon_code'])) {
                $srch = DiscountCoupons::getSearchObject();
                $srch->addCondition('coupon_code', '=', $orderInfo['order_discount_coupon_code']);
                $rs = $srch->getResultSet();
                $row = FatApp::getDb()->fetch($rs);
                if (!empty($row)) {
                    if (!FatApp::getDb()->insertFromArray(CouponHistory::DB_TBL, array('couponhistory_coupon_id' => $row['coupon_id'], 'couponhistory_order_id' => $orderInfo['order_id'],'couponhistory_user_id'=>$orderInfo['order_user_id'],'couponhistory_amount'=>$orderInfo['order_discount_total'],'couponhistory_added_on' => date('Y-m-d H:i:s')))) {
                        $this->error = FatApp::getDb()->getError();
                        return false;
                    }
                    FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD, array('smt' => 'couponhold_coupon_id = ? and couponhold_user_id = ?', 'vals' => array($row['coupon_id'], $orderInfo['order_user_id'] ) ));
                }
            }
        }

        if ($orderInfo['order_type'] == Order::TYPE_GIFTCARD) {
            $giftcard = new Giftcard();
            $giftcard->addGiftcardDetails($orderInfo['order_id']);
        }

        if ($orderInfo['order_type'] ==  Order::TYPE_WALLET_RECHARGE) {
            $formattedOrderValue = "#".$orderInfo["order_id"];
            $transObj = new Transaction($orderInfo["order_user_id"]);

            $txnDataArr = array(
        'utxn_user_id'	=>	$orderInfo["order_user_id"],
        'utxn_credit'	=>	$amount,
        'utxn_status'	=>	Transaction::STATUS_COMPLETED,
        'utxn_order_id'	=>	$orderInfo["order_id"],
        'utxn_comments'	=>	sprintf(Label::getLabel('LBL_Loaded_Money_to_Wallet', $defaultSiteLangId), $formattedOrderValue),
        'utxn_type'		=>	Transaction::TYPE_LOADED_MONEY_TO_WALLET
        );
            if (!$txnId = $transObj->addTransaction($txnDataArr)) {
                $this->error = $transObj->getError();
                return false;
            }
            $userNotification = new UserNotifications($orderInfo["order_user_id"]);
            $userNotification->sendWalletCreditNotification();
            /* Send email to User[ */
            $emailNotificationObj = new EmailHandler();
            $emailNotificationObj->sendTxnNotification($txnId, $defaultSiteLangId);
            /* ] */
        }

        /* [ */
        $orderPaymentFinancials = $this->getOrderPaymentFinancials($this->paymentOrderId, $this->orderLangId);
        $orderCredits = $orderPaymentFinancials["order_credits_charge"];

        if ($orderCredits > 0 && !$isWallet) {
            $this->chargeUserWallet($orderCredits);
        }
        /* ] */

        return true;
    }

    public function addOrderPaymentComments($comments)
    {
        $paymentOrderId = $this->paymentOrderId;
        $orderInfo = $this->orderAttributes;
        if (empty($orderInfo)) {
            $this->error = Label::getLabel('MSG_Invalid_Order');
            return false;
        }
        if (!$this->addOrderPaymentHistory($paymentOrderId, Order::ORDER_IS_PENDING, $comments, false)) {
            return false;
        }
        return true;
    }

    public function getOrderPayment($paymentMethodName = '')
    {
        $srch = new SearchBase(Order::DB_TBL_ORDER_PAYMENTS, 'op');
        $srch->addCondition('opayment_order_id','=',$this->paymentOrderId);
        if(!empty($paymentMethodName)) {
            $srch->addCondition('opayment_method','=',$paymentMethodName);
        }
        return $srch;

    }
}
