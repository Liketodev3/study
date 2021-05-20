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
        $userInfo = $userObj->getUserInfo(['user_first_name', 'user_last_name', 'credential_email', 'user_phone', 'user_country_id'], true, true, true);
        $orderCurrencyCode = CommonHelper::getSystemCurrencyData()['currency_code'];
        $arrOrder = [
            "order_id" => $orderInfo["order_id"],
            "invoice" => $orderInfo["order_id"],
            "customer_id" => $orderInfo["order_user_id"],
            "user_name" => $userInfo["user_first_name"] . ' ' . $userInfo["user_last_name"],
            "user_phone" => $userInfo["user_phone"],
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
            "user_country_id" => $userInfo["user_country_id"],
        ];
        return $arrOrder;
    }

    public function chargeFreeOrder($amountToBeCharge = 0, $isFreeTrial = false)
    {
        if ($amountToBeCharge > 0) {
            $this->error = Label::getLabel('MSG_Invalid_Order');
            return false;
        }
        $langId = FatApp::getConfig('conf_default_site_lang');
        if (!$this->addOrderPayment(
            'NoCharge',
            'W-' . time(),
            $amountToBeCharge,
            Label::getLabel("LBL_No_Charges", $langId),
            Label::getLabel('LBL_No_Charges', $langId),
            true
        )) {
            return false;
        }
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
        $formattedOrderValue = "#" . $orderInfo["order_id"];
        $transObj = new Transaction($orderInfo["order_user_id"]);
        $utxnComments = Transaction::formatTransactionCommentByOrderId($orderInfo["order_id"], $defaultSiteLangId);
        $txnDataArr = [
            'utxn_user_id' => $orderInfo["order_user_id"],
            'utxn_debit' => $amountToBeCharge,
            'utxn_status' => Transaction::STATUS_COMPLETED,
            'utxn_order_id' => $orderInfo["order_id"],
            'utxn_comments' => $utxnComments,
            'utxn_type' => Transaction::TYPE_LESSON_BOOKING
        ];
        $transObj->assignValues($txnDataArr);
        if (!$transObj->save()) {
            $this->error = $transObj->getError();
            return false;
        }
        $txnId = $transObj->getMainTableRecordId();
        $orderWalletAmountCharge = $orderInfo['order_wallet_amount_charge'] - $amountToBeCharge;
        if (!FatApp::getDb()->updateFromArray(
            Order::DB_TBL,
            ['order_wallet_amount_charge' => $orderWalletAmountCharge],
            ['smt' => 'order_id = ?', 'vals' => [$orderInfo["order_id"]]]
        )) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        if (!$this->addOrderPayment(Label::getLabel('LBL_User_Wallet', $defaultSiteLangId), 'W-' . time(), $amountToBeCharge, Label::getLabel("LBL_Received_Payment", $defaultSiteLangId), Label::getLabel('LBL_Payment_From_User_Wallet', $defaultSiteLangId), true)) {
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
        $orderProductSrch->addMultipleFields([
            'op_teacher_id',
            'op_grpcls_id',
            'op_tlanguage_id',
            'op_qty',
            'op_lpackage_is_free_trial'
        ]);
        $rs = $orderProductSrch->getResultSet();
        $orderProductRow = FatApp::getDb()->fetch($rs);
        /* ] */
        if ($orderProductRow) {
            $orderInfo = $orderInfo + $orderProductRow;
        }
        if (!FatApp::getDb()->insertFromArray(
            static::DB_TBL_ORDER_PAYMENTS,
            [
                'opayment_order_id' => $this->paymentOrderId,
                'opayment_method' => $paymentMethodName,
                'opayment_gateway_txn_id' => $txnId,
                'opayment_amount' => $amount,
                'opayment_comments' => $comments,
                'opayment_gateway_response' => $response,
                'opayment_date' => date('Y-m-d H:i:s')
            ]
        )) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        $totalPaymentPaid = $this->getOrderPaymentPaid($this->paymentOrderId);
        $orderBalance = ($orderInfo['order_net_amount'] - $totalPaymentPaid);
        if ($orderBalance <= 0) {
            $this->addOrderPaymentHistory($this->paymentOrderId, Order::ORDER_IS_PAID, Label::getLabel('LBL_Received_Payment', $defaultSiteLangId), 1);
            /* and for free trial made entry from freepaycontroller */
            /* add schedulaed lessons[ */
            if ($orderProductRow) {
                $counter = $orderInfo['op_qty'] > 0 ? $orderInfo['op_qty'] : 1;
                for ($i = 0; $i < $counter; $i++) {
                    if ($orderInfo['op_lpackage_is_free_trial'] == 0) {
                        $slesson_id = 0;
                        $slesson_date = '0000-00-00';
                        $slesson_end_date = '0000-00-00';
                        $slesson_start_time = '00:00:00';
                        $slesson_end_time = '00:00:00';
                        $slesson_status = ScheduledLesson::STATUS_NEED_SCHEDULING;
                        if ($orderInfo['op_grpcls_id'] > 0) {
                            $slesson_id = ScheduledLessonSearch::getLessonInfoByGrpClsid($orderInfo['op_grpcls_id'], 'slesson_id');
                            $grpClsDetails = TeacherGroupClasses::getAttributesById($orderInfo['op_grpcls_id']);
                            $slesson_date = date('Y-m-d', strtotime($grpClsDetails['grpcls_start_datetime']));
                            $slesson_end_date = date('Y-m-d', strtotime($grpClsDetails['grpcls_end_datetime']));
                            $slesson_start_time = date('H:i:s', strtotime($grpClsDetails['grpcls_start_datetime']));
                            $slesson_end_time = date('H:i:s', strtotime($grpClsDetails['grpcls_end_datetime']));
                            $slesson_status = ScheduledLesson::STATUS_SCHEDULED;
                        }
                        $sLessonArr = [
                            'slesson_teacher_id' => $orderInfo['op_teacher_id'],
                            'slesson_grpcls_id' => $orderInfo['op_grpcls_id'],
                            'slesson_slanguage_id' => $orderInfo['op_tlanguage_id'],
                            'slesson_date' => $slesson_date,
                            'slesson_end_date' => $slesson_end_date,
                            'slesson_start_time' => $slesson_start_time,
                            'slesson_end_time' => $slesson_end_time,
                            'slesson_status' => $slesson_status
                        ];

                        if ($slesson_id < 1) {
                            $sLessonObj = new ScheduledLesson();
                            $sLessonObj->assignValues($sLessonArr);
                            if (!$sLessonObj->save()) {
                                $this->error = $sLessonObj->getError();
                                return false;
                            }
                            $slesson_id = $sLessonObj->getMainTableRecordId();
                        }
                        $sLessonDetailAr = [
                            'sldetail_slesson_id' => $slesson_id,
                            'sldetail_order_id' => $this->paymentOrderId,
                            'sldetail_learner_id' => $orderInfo['order_user_id'],
                            'sldetail_learner_status' => $slesson_status
                        ];
                        $slDetailsObj = new ScheduledLessonDetails();
                        $slDetailsObj->assignValues($sLessonDetailAr);
                        if (!$slDetailsObj->save()) {
                            $this->error = $slDetailsObj->getError();
                            return false;
                        }

                        $sldetailId = $slDetailsObj->getMainTableRecordId();

                        if ($orderInfo['op_grpcls_id'] > 0) {
                            $tgrpcls = new TeacherGroupClassesSearch(false);
                            $grpClsRow = $tgrpcls->getClassBasicDetails($orderInfo['op_grpcls_id'], $orderInfo['order_user_id']);
                            $vars = [
                                '{learner_name}' => $grpClsRow['learner_full_name'],
                                '{teacher_name}' => $grpClsRow['teacher_full_name'],
                                '{class_name}' => $grpClsRow['grpcls_title'],
                                '{class_date}' => MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $grpClsRow['grpcls_start_datetime'], false, $grpClsRow['teacherTimeZone']),
                                '{class_start_time}' => MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $grpClsRow['grpcls_start_datetime'], true, $grpClsRow['teacherTimeZone']),
                                '{class_end_time}' => MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $grpClsRow['grpcls_end_datetime'], true, $grpClsRow['teacherTimeZone']),
                                '{learner_comment}' => '',
                                '{status}' => ScheduledLesson::getStatusArr()[ScheduledLesson::STATUS_SCHEDULED],
                            ];
                            if (!EmailHandler::sendMailTpl($grpClsRow['teacherEmailId'], 'learner_class_book_email', $defaultSiteLangId, $vars)) {
                                FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
                            }
                            $vars['{class_date}'] = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d', $grpClsRow['grpcls_start_datetime'], false, $grpClsRow['learnerTimeZone']);
                            $vars['{class_start_time}'] = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $grpClsRow['grpcls_start_datetime'], true, $grpClsRow['learnerTimeZone']);
                            $vars['{class_end_time}'] = MyDate::convertTimeFromSystemToUserTimezone('H:i:s', $grpClsRow['grpcls_end_datetime'], true, $grpClsRow['learnerTimeZone']);

                            if (!EmailHandler::sendMailTpl($grpClsRow['learnerEmailId'], 'class_book_email_confirmation', $defaultSiteLangId, $vars)) {
                                FatUtility::dieJsonError(Label::getLabel('LBL_Mail_not_sent!'));
                            }

                            // share on student google calendar
                            $token = UserSetting::getUserSettings($orderInfo['order_user_id'])['us_google_access_token'];
                            if ($token) {
                                $view_url = CommonHelper::generateFullUrl('LearnerScheduledLessons', 'view', [$sldetailId]);
                                $google_cal_data = [
                                    'title' => FatApp::getConfig('CONF_WEBSITE_NAME_' . $defaultSiteLangId),
                                    'summary' => sprintf(Label::getLabel("LBL_Group_Class_Scheduled_for_%s"), $grpClsDetails['grpcls_title']),
                                    'description' => sprintf(Label::getLabel("LBL_Click_here_to_join_the_class:_%s"), $view_url),
                                    'url' => $view_url,
                                    'start_time' => date('c', strtotime($slesson_date . ' ' . $slesson_start_time)),
                                    'end_time' => date('c', strtotime($slesson_end_date . ' ' . $slesson_end_time)),
                                    'timezone' => MyDate::getTimeZone(),
                                ];
                                $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
                                if ($calId) {
                                    $sLessonDetailObj = new ScheduledLessonDetails($sldetailId);
                                    $sLessonDetailObj->setFldValue('sldetail_learner_google_calendar_id', $calId);
                                    $sLessonDetailObj->save();
                                }
                            }
                            // share on teacher google calendar
                            $token = UserSetting::getUserSettings($orderInfo['op_teacher_id'])['us_google_access_token'];
                            if ($token) {
                                $sLessonObj = new ScheduledLesson($slesson_id);
                                $sLessonObj->loadFromDb();
                                $oldCalId = $sLessonObj->getFldValue('slesson_teacher_google_calendar_id');
                                if (!$oldCalId) {
                                    $view_url = CommonHelper::generateFullUrl('TeacherScheduledLessons', 'view', [$slesson_id]);
                                    $google_cal_data = [
                                        'title' => FatApp::getConfig('CONF_WEBSITE_NAME_' . $defaultSiteLangId),
                                        'summary' => sprintf(Label::getLabel("LBL_Group_Class_Scheduled_for_%s"), $grpClsDetails['grpcls_title']),
                                        'description' => sprintf(Label::getLabel("LBL_Click_here_to_deliver_the_class:_%s"), $view_url),
                                        'url' => $view_url,
                                        'start_time' => date('c', strtotime($slesson_date . ' ' . $slesson_start_time)),
                                        'end_time' => date('c', strtotime($slesson_end_date . ' ' . $slesson_end_time)),
                                        'timezone' => MyDate::getTimeZone(),
                                    ];
                                    $calId = SocialMedia::addEventOnGoogleCalendar($token, $google_cal_data);
                                    if ($calId) {
                                        $sLessonObj->setFldValue('slesson_teacher_google_calendar_id', $calId);
                                        $sLessonObj->save();
                                    }
                                }
                            }
                        }
                    }
                }
                /* Upudate Studens and Lessions counts */
                $userId = FatUtility::int($orderInfo['op_teacher_id']);
                (new TeacherStat($userId))->setStudentLessionCount();
            }

            /* ] */
            if (!empty($orderInfo['order_discount_coupon_code'])) {
                $srch = DiscountCoupons::getSearchObject();
                $srch->addCondition('coupon_code', '=', $orderInfo['order_discount_coupon_code']);
                $rs = $srch->getResultSet();
                $row = FatApp::getDb()->fetch($rs);
                if (!empty($row)) {
                    if (!FatApp::getDb()->insertFromArray(CouponHistory::DB_TBL, [
                        'couponhistory_coupon_id' => $row['coupon_id'],
                        'couponhistory_order_id' => $orderInfo['order_id'],
                        'couponhistory_user_id' => $orderInfo['order_user_id'],
                        'couponhistory_amount' => $orderInfo['order_discount_total'],
                        'couponhistory_added_on' => date('Y-m-d H:i:s')
                    ])) {
                        $this->error = FatApp::getDb()->getError();
                        return false;
                    }
                    FatApp::getDb()->deleteRecords(DiscountCoupons::DB_TBL_COUPON_HOLD, ['smt' => 'couponhold_coupon_id = ? and couponhold_user_id = ?', 'vals' => [$row['coupon_id'], $orderInfo['order_user_id']]]);
                }
            }
        }
        if ($orderInfo['order_type'] == Order::TYPE_GIFTCARD) {
            $giftcard = new Giftcard();
            $giftcard->addGiftcardDetails($orderInfo['order_id']);
        }
        if ($orderInfo['order_type'] == Order::TYPE_WALLET_RECHARGE) {
            $formattedOrderValue = "#" . $orderInfo["order_id"];
            $transObj = new Transaction($orderInfo["order_user_id"]);
            $txnDataArr = [
                'utxn_user_id' => $orderInfo["order_user_id"],
                'utxn_credit' => $amount,
                'utxn_status' => Transaction::STATUS_COMPLETED,
                'utxn_order_id' => $orderInfo["order_id"],
                'utxn_comments' => sprintf(Label::getLabel('LBL_Loaded_Money_to_Wallet', $defaultSiteLangId), $formattedOrderValue),
                'utxn_type' => Transaction::TYPE_LOADED_MONEY_TO_WALLET
            ];
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
        $srch->addCondition('opayment_order_id', '=', $this->paymentOrderId);
        if (!empty($paymentMethodName)) {
            $srch->addCondition('opayment_method', '=', $paymentMethodName);
        }
        return $srch;
    }
}
