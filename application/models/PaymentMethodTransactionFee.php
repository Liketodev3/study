<?php
class PaymentMethodTransactionFee extends MyAppModel
{
    const DB_TBL = 'tbl_payment_method_transaction_fee';
    const DB_TBL_PREFIX = 'pmtfee_';

    private $currancyId;
    private $pMethodId;

    public function __construct(int $pMethodId, int $currancyId)
    {
        $this->currancyId = $currancyId;
        $this->pMethodId = $pMethodId;
    }

    public static function getSearchObject(bool $joinPaymentMethod = true, bool $joinCurrancy = true) :  object
    {
        $srch = new SearchBase(static::DB_TBL, 'pmtfee');

        if ($joinPaymentMethod) {
            $srch->joinTable(PaymentMethods::DB_TBL,'INNER JOIN','pmtfee_pmethod_id = pmethod_id','pm');
        }

        if ($joinCurrancy) {
            $srch->joinTable(Currency::DB_TBL,'INNER JOIN','pmtfee_currency_id = currency_id','curr');
        }

        return $srch;
    }

	public static function getGatewayFee(int $pMethodId, int $currancyId) : float
    {
        $srch = static::getSearchObject();
		$srch->addCondition('pmtfee_pmethod_id', '=', $pMethodId);
		$srch->addCondition('pmtfee_currency_id', '=', $currancyId);
		$srch->addMultipleFields(['pmtfee.*']);
		$resultSet = $srch->getResultSet();
		$data =   FatApp::getDb()->fetch($resultSet);
		if(empty($data['pmtfee_fee'])) {
			return 0.00;
		}
        return FatUtility::float($data['pmtfee_fee']);
    }

    public function setupFee(float $fee) : bool
    {
        $tableRecordObj =  new TableRecord(self::DB_TBL);
        $fields = array(
                'pmtfee_pmethod_id' => $this->pMethodId,
                'pmtfee_currency_id' => $this->currancyId,
                'pmtfee_fee' => $fee,
            );
        $tableRecordObj->setFlds($fields);

        if($tableRecordObj->addNew(array(),$fields) === false){
            $this->error =  $tableRecordObj->getError();
            return false;
        }
        return true;
    }


}
