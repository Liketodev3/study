<?php
class ScheduledLessonDetails extends MyAppModel
{
    const DB_TBL = 'tbl_scheduled_lesson_details';
    const DB_TBL_PREFIX = 'sldetail_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }
    
    public static function getSearchObj()
    {
        $srch = new SearchBase(self::DB_TBL);
        return $srch;
    }

    public function save()
    {
        if ($this->getMainTableRecordId() == 0) {
            $this->setFldValue('sldetail_added_on', date('Y-m-d H:i:s'));
        }

        return parent::save();
    }

    public function changeStatus($status = 0)
    {
        $status = FatUtility::int($status);
		$this->setFldValue('sldetail_learner_status', $status);
        return parent::save();
    }
    
    public static function getDetailIdByOrderId($orderId)
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition(static::tblFld('order_id'), '=', $orderId);
        $srch->addFld(static::tblFld('id'));
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if(empty($row)) return 0;
        return $row[static::tblFld('id')];
    }
    
    public static function getScheduledSearchObj($recordId, $attr = null)
    {
        $recordId = FatUtility::convertToType($recordId, FatUtility::VAR_INT);
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition(static::tblFld('slesson_id'), '=', $recordId);
        if (null != $attr) {
            if (is_array($attr)) {
                $srch->addMultipleFields($attr);
            } elseif (is_string($attr)) {
                $srch->addFld($attr);
            }
        }
        return $srch;
    }
	
	public static function getScheduledRecordsByLessionId($recordId, $attr = null)
    {
        $db = FatApp::getDb();
        $srch = self::getScheduledSearchObj($recordId, $attr = null);
        $cnd = $srch->addCondition(static::tblFld('learner_status'), '=', ScheduledLesson::STATUS_SCHEDULED);
        $cnd->attachCondition(static::tblFld('learner_status'), '=', ScheduledLesson::STATUS_NEED_SCHEDULING);

        $rs = $srch->getResultSet();
        $rows = $db->fetchAll($rs);
        return $rows;
    }
	
	public static function getAttributesByLessionId($recordId, $attr = null)
    {
        $db = FatApp::getDb();
        $srch = self::getScheduledSearchObj($recordId, $attr = null);
        $rs = $srch->getResultSet();
        $row = $db->fetch($rs);
        if (!is_array($row)) {
            return false;
        }
        if (is_string($attr)) {
            return $row[$attr];
        }

        return $row;
    }
	
	public static function getAttributesByLessonAndLearnerId($recordId, $learnerId, $attr = null)
    {
        $db = FatApp::getDb();
        $srch = self::getScheduledSearchObj($recordId, $attr = null);
        $srch->addCondition(static::tblFld('learner_id'), '=', $learnerId);
        $rs = $srch->getResultSet();
        $row = $db->fetch($rs);
        if (!is_array($row)) {
            return false;
        }
        if (is_string($attr)) {
            return $row[$attr];
        }
        return $row;
    }
    
    public function getCancelledGroupClassByDetailId()
    {
        $sldetail_id = $this->getMainTableRecordId();
        
        if ($sldetail_id<1) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        
        $srch = new ScheduledLessonSearch(false);
        $srch->joinGroupClass();
        $srch->joinScheduledLessionDetails();
        $srch->joinOrder();
        $srch->joinOrderProducts();
        $srch->doNotCalculateRecords();
        $srch->addCondition('sld.sldetail_id', '=', $sldetail_id);
        $srch->addCondition('grpcls.grpcls_status', '=', TeacherGroupClasses::STATUS_CANCELLED);
        
        $srch->addFld(array(
            'sldetail_order_id',
            'order_net_amount',
        ));
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!$row) {
            $this->error = Label::getLabel('LBL_Invalid_Request');
            return false;
        }
        
        $order_id = $row['sldetail_order_id'];
        
        $orderObj =  new Order();
        $order = $orderObj->getOrderById($order_id);

        if($order==false) {
            $this->error = Label::getLabel('LBL_Error:_Please_perform_this_action_on_valid_record.');
            return false;
        }
        
        if ($order["order_is_paid"]==Order::ORDER_IS_CANCELLED ) {
            $this->error = Label::getLabel('LBL_Already_Refunded');
            return false;
        }
        return $row;
    }
    
    public function getAmountPaidAlready()
    {
        $sldetails = $this->getCancelledGroupClassByDetailId( );
        if(empty($sldetails)){
            $this->error = $this->getError();
            return false;
        }
        return $sldetails['order_net_amount'];
    }
}
