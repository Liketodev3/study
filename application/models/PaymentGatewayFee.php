<?php
class PaymentGatewayFee extends MyAppModel
{
    const DB_TBL = 'tbl_payment_gateway_fee';
    const DB_TBL_PREFIX = 'pgfee_';

    private $currancyId;
    private $pMethodId;

    public function __construct(int $pMethodId, int $currancyId)
    {
        $this->currancyId = $currancyId;
        $this->pMethodId = $pMethodId;
    }

    public static function getSearchObject(bool $joinPaymentMethod = true, bool $joinCurrancy = true) :  object
    {
        $srch = new SearchBase(static::DB_TBL, 'pgfee');

        if ($joinPaymentMethod) {
            $srch->joinTable(PaymentMethods::DB_TBL,'INNER JOIN','pgfee_pmethod_id = pmethod_id','pm');
        }

        if ($joinCurrancy) {
            $srch->joinTable(Currency::DB_TBL,'INNER JOIN','pgfee_currency_id = currency_id','curr');
        }

        return $srch;
    }
	
	public static function getGatewayFee(int $pMethodId, int $currancyId) : float
    {
        $srch = static::getSearchObject();
		$srch->addCondition('pgfee_pmethod_id', '=', $pMethodId);
		$srch->addCondition('pgfee_currency_id', '=', $currancyId);
		$srch->addMultipleFields(['pgfee.*']);
		$resultSet = $srch->getResultSet();
		$data =   FatApp::getDb()->fetch($resultSet);
		if(empty($data['pgfee_fee'])) {
			return 0.00;
		}
        return FatUtility::float($data['pgfee_fee']);
    }

    public function setupFee(float $fee) : bool
    {
        $tableRecordObj =  new TableRecord(self::DB_TBL);
        $fields = array(
                'pgfee_pmethod_id' => $this->pMethodId,
                'pgfee_currency_id' => $this->currancyId,
                'pgfee_fee' => $fee,
            );
        $tableRecordObj->setFlds($fields);

        if($tableRecordObj->addNew(array(),$fields) === false){
            $this->error =  $tableRecordObj->getError();
            return false;
        }
        return true;
    }


}
