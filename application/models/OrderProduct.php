<?php

class OrderProduct extends MyAppModel
{

    const DB_TBL = 'tbl_order_products';
    const DB_TBL_PREFIX = 'op_';
    const ORDER_PRODUCT_TYPE_LESSON = 0;
    const ORDER_PRODUCT_TYPE_GIFTCARD = 1;

    public function __construct($orderProductId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $orderProductId);
    }

    public function refund($qty, $amount)
    {
        $this->loadFromDb();
        $this->setFldValue('op_refund_qty', $this->getFldValue('op_refund_qty') + $qty);
        $this->setFldValue('op_total_refund_amount', $this->getFldValue('op_total_refund_amount') + $amount, true);
        return $this->save();
    }

}
