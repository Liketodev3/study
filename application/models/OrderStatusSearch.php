<?php
class OrderStatusSearch extends SearchBase
{
    public function __construct($langId = 0, $doNotCalculateRecords = true, $doNotLimitRecords = true)
    {
        parent::__construct(OrderStatus::DB_TBL, 'os');

        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(OrderStatus::DB_TBL.'_lang', 'LEFT JOIN', 'orderstatus_id = orderstatuslang_orderstatus_id AND orderstatuslang_lang_id = ' . $langId, 'os_l');
        }

        if (true == $doNotCalculateRecords) {
            $this->doNotCalculateRecords();
        }

        if (true == $doNotLimitRecords) {
            $this->doNotLimitRecords();
        }

        $this->addOrder('orderstatus_priority');
    }
}
