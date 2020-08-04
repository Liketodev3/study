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
        $this->db=FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(array(
                'pmethod_code',
                'pmethod_type'
        ));
    }

    public static function getTypeArray() : array
    {
        return array(
            self::TYPE_PAYMENT_METHOD => Label::getLabel('LBL_Payment_Method'),
            self::TYPE_PAYMENT_METHOD_PAYOUT => Label::getLabel('LBL_Payout')
        );
    }

    public static function getSearchObject($langId = 0, $isActive = true)
    {
        $langId = FatUtility::int($langId);

        $srch = new SearchBase(static::DB_TBL, 'pm');
        if ($isActive==true) {
            $srch->addCondition('pm.'.static::DB_TBL_PREFIX.'active', '=', applicationConstants::ACTIVE);
        }

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_LANG_TBL,
                'LEFT OUTER JOIN',
                'pm_l.pmethodlang_'.static::DB_TBL_PREFIX.'id = pm.'.static::DB_TBL_PREFIX.'id and pm_l.pmethodlang_lang_id = '.$langId,
                'pm_l'
            );
        }

        $srch->addOrder('pm.'.static::DB_TBL_PREFIX.'active', 'DESC');
        $srch->addOrder('pm.'.static::DB_TBL_PREFIX.'display_order', 'ASC');
        return $srch;
    }

    public function cashOnDeliveryIsActive()
    {
        $paymentMethod = PaymentMethods::getSearchObject();
        $paymentMethod->addMultipleFields(array('pmethod_id','pmethod_type','pmethod_code','pmethod_active'));
        $paymentMethod->addCondition('pmethod_code', '=', 'cashondelivery');
        $paymentMethod->addCondition('pmethod_active', '=', applicationConstants::YES);
        $rs = $paymentMethod->getResultSet();
        if (FatApp::getDb()->fetch($rs)) {
            return true;
        } else {
            return false;
        }
    }
    // public static function getPayoutMethodsWithSettings()
    // {
    //         $srchObj = self::getPayoutMethodsSrchObj();
    // }
    public static function getPayoutMethods(int $langId = 0) : array
    {
        $paymentMethod = PaymentMethods::getSearchObject();
        $paymentMethod->addMultipleFields(array(
            'pmethod_id','pmethod_code',
            'paysetting_key','paysetting_value',
            'IFNULL(pmethod_name,pmethod_identifier) as pmName'

        ));
        $paymentMethod->addCondition('pmethod_type', '=', self::TYPE_PAYMENT_METHOD_PAYOUT);
        $paymentMethod->addCondition('pmethod_active', '=', applicationConstants::YES);
        if ($langId > 0) {
            $srch->joinTable(
                static::DB_LANG_TBL,
                'LEFT JOIN',
                'pm_l.pmethodlang_'.static::DB_TBL_PREFIX.'id = pm.'.static::DB_TBL_PREFIX.'id and pm_l.pmethodlang_lang_id = '.$langId,
                'pm_l'
            );
        }
        $paymentMethod->joinTable(PaymentSettings::DB_PAYMENT_METHOD_SETTINGS_TBL, 'left join', 'paysetting_pmethod_id = pmethod_id');
        $rs = $paymentMethod->getResultSet();
        $resultData =  FatApp::getDb()->fetchAll($rs);
        if(empty($resultData)) {
            $this->error = Label::getLabel('LBL_No_Payment_Method_Active');
            return array();
        }
        $paymentMethod = array();
        foreach ($resultData as $key => $value) {
            if(!array_key_exists($value['pmethod_id'],$paymentMethod)){
                $paymentMethod[$value['pmethod_id']] = [
                        'pmethod_id' => $value['pmethod_id'],
                        'pmethod_code' => $value['pmethod_code'],
                ];
            }
            if(!empty($value['paysetting_key'])) {
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
                    // add other case accourding to new payout methods
            }
        }
        if(empty($paymentMethod)) {
            $this->error = Label::getLabel('LBL_No_Payment_Method_Active');
            return array();
        }
        return $paymentMethod;
        // $paymentMethod->joinTable(PaymentSettings::DB_PAYMENT_METHOD_SETTINGS_TBL, 'left join', 'paysetting_pmethod_id = pmethod_id');
        // $rs = $paymentMethod->getResultSet();
        //
    }


}
