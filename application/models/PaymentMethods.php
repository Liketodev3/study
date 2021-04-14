<?php

class PaymentMethods extends MyAppModel
{

    const DB_TBL = 'tbl_payment_methods';
    const DB_LANG_TBL = 'tbl_payment_methods_lang';
    const DB_TBL_PREFIX = 'pmethod_';
    const TYPE_PAYMENT_METHOD = 1; //payment method using in front end for payments processing ex:- (paypal standard, stripe, Authorize.net)
    const TYPE_PAYMENT_METHOD_PAYOUT = 2; // payment methods using for payout ex :- ( paypal payout)
    const BANK_PAYOUT_KEY = 'BankPayout';

    private $db;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(['pmethod_code', 'pmethod_type']);
    }

    public static function getTypeArray(): array
    {
        return [
            self::TYPE_PAYMENT_METHOD => Label::getLabel('LBL_Payment_Method'),
            self::TYPE_PAYMENT_METHOD_PAYOUT => Label::getLabel('LBL_Payout')
        ];
    }

    public static function getSearchObject($langId = 0, $isActive = true)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 'pm');
        if ($isActive == true) {
            $srch->addCondition('pm.pmethod_active', '=', applicationConstants::ACTIVE);
        }
        if ($langId > 0) {
            $srch->joinTable(static::DB_LANG_TBL, 'LEFT OUTER JOIN', 'pm_l.pmethodlang_pmethod_id = pm.pmethod_id and pm_l.pmethodlang_lang_id = ' . $langId, 'pm_l');
        }
        $srch->addOrder('pm.pmethod_active', 'DESC');
        $srch->addOrder('pm.pmethod_display_order', 'ASC');
        return $srch;
    }

    public function cashOnDeliveryIsActive()
    {
        $paymentMethod = PaymentMethods::getSearchObject();
        $paymentMethod->addMultipleFields(['pmethod_id', 'pmethod_type', 'pmethod_code', 'pmethod_active']);
        $paymentMethod->addCondition('pmethod_code', '=', 'cashondelivery');
        $paymentMethod->addCondition('pmethod_active', '=', applicationConstants::YES);
        $rs = $paymentMethod->getResultSet();
        if (FatApp::getDb()->fetch($rs)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getPayoutMethods(bool $isActive = true): array
    {
        $paymentMethodObj = PaymentMethods::getSearchObject();
        $paymentMethodObj->addMultipleFields([
            'pmethod_id', 'pmethod_code',
            'paysetting_key', 'paysetting_value',
            'IFNULL(pmethod_name,pmethod_identifier) as pmName'
        ]);
        $paymentMethodObj->addCondition('pmethod_type', '=', self::TYPE_PAYMENT_METHOD_PAYOUT);
        if ($isActive) {
            $paymentMethodObj->addCondition('pmethod_active', '=', applicationConstants::YES);
        }
        $paymentMethodObj->doNotLimitRecords();
        $langId = CommonHelper::getLangId();
        $paymentMethodObj->joinTable(static::DB_LANG_TBL, 'LEFT JOIN', 'pm_l.pmethodlang_pmethod_id = pm.pmethod_id and pm_l.pmethodlang_lang_id = ' . $langId, 'pm_l');
        $paymentMethodObj->joinTable(PaymentSettings::DB_PAYMENT_METHOD_SETTINGS_TBL, 'left join', 'paysetting_pmethod_id = pmethod_id');
        $rs = $paymentMethodObj->getResultSet();
        $resultData = FatApp::getDb()->fetchAll($rs);
        if (empty($resultData)) {
            return [];
        }
        $paymentMethod = [];
        foreach ($resultData as $key => $value) {
            if (!array_key_exists($value['pmethod_id'], $paymentMethod)) {
                $paymentMethod[$value['pmethod_id']] = [
                    'pmethod_id' => $value['pmethod_id'],
                    'pmethod_code' => $value['pmethod_code'],
                    'pmName' => $value['pmName'],
                ];
            }
            if (!empty($value['paysetting_key'])) {
                $paymentMethod[$value['pmethod_id']][$value['paysetting_key']] = $value['paysetting_value'];
            }
        }
        foreach ($paymentMethod as $key => $value) {
            switch ($value['pmethod_code']) {
                case PaypalPayout::KEY_NAME:
                    if (empty($value['paypal_client_id']) || empty($value['paypal_client_secret'])) {
                        unset($paymentMethod[$key]);
                    }
                    break;
            }
        }
        if (empty($paymentMethod)) {
            return [];
        }
        return $paymentMethod;
    }

}
