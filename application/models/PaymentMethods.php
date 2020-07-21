<?php
class PaymentMethods extends MyAppModel
{
    const DB_TBL = 'tbl_payment_methods';
    const DB_LANG_TBL = 'tbl_payment_methods_lang';
    const DB_TBL_PREFIX = 'pmethod_';

    const TYPE_PAYMENT_METHOD = 1; //payment method using in front end for payments processing ex:- (paypal standard, stripe, Authorize.net)
    const TYPE_PAYMENT_METHOD_PAYOUT = 2; // payment methods using for payout ex :- ( paypal payout)


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
}
