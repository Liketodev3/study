<?php
class Statistics extends MyAppModel
{
    const TYPE_TODAY = 1;
    const TYPE_LAST_WEEK = 2;
    const TYPE_THIS_MONTH = 3;
    const TYPE_LAST_MONTH = 4;
    const TYPE_LAST_YEAR = 5;

    const REPORT_EARNING = 1;
    const REPORT_SOLD_LESSONS = 2;

    public function __construct($id = 0)
    {
        $this->db = FatApp::getDb();
        $this->userId = $id;
    }

    public static function getDurationTypesArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return array(
            static::TYPE_TODAY	=>	Label::getLabel('LBL_Today', $langId),
            static::TYPE_LAST_WEEK	=>	Label::getLabel('LBL_Last_Week', $langId),
            static::TYPE_THIS_MONTH	=>	Label::getLabel('LBL_This_Month', $langId),
            static::TYPE_LAST_MONTH	=>	Label::getLabel('LBL_Last_Month', $langId),
            static::TYPE_LAST_YEAR	=>	Label::getLabel('LBL_Last_Year', $langId),

        );
    }

    public function getEarning($type)
    {
        $type = strtolower($type);

        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $nowDate = MyDate::changeDateTimezone(date('Y-m-d H:i:s'), $user_timezone, $systemTimeZone);

        switch ($type) {
            case static::TYPE_TODAY:
                //$srch = new SearchBase(Order::DB_TBL, 'o');
                $srch = new OrderSearch();
                $srch->joinOrderProduct();
                $srch->addCondition('op_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('sum(order_net_amount) as earning, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $srch->addCondition('mysql_func_DATE(order_date_added)', '=', 'mysql_func_DATE("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_WEEK:
                $srch = new OrderSearch();
                $srch->joinOrderProduct();
                $srch->addCondition('op_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('sum(order_net_amount) as earning, MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate'));
                $srch->addCondition('mysql_func_YEARWEEK(order_date_added)', '=', 'mysql_func_YEARWEEK("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_THIS_MONTH:
                $srch = new OrderSearch();
                $srch->joinOrderProduct();
                $srch->addCondition('op_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('sum(order_net_amount) as earning, MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate'));
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_MONTH:
                $srch = new OrderSearch();
                $srch->joinOrderProduct();
                $srch->addCondition('op_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('sum(order_net_amount) as earning, MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate'));
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("'. $nowDate .'" - INTERVAL 1 MONTH)', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_YEAR:
                $srch = new OrderSearch();
                $srch->joinOrderProduct();
                $srch->addCondition('op_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('sum(order_net_amount) as earning, MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate'));
                //$srch->addCondition('MONTH(order_date_added)', '=', 'MONTH(NOW() - INTERVAL 1 MONTH)');
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
        }
    }

    public function getSoldlessons($type)
    {
        $type = strtolower($type);
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $nowDate = MyDate::changeDateTimezone(date('Y-m-d H:i:s'), $user_timezone, $systemTimeZone);
        switch ($type) {
            case static::TYPE_TODAY:
                $srch = new ScheduledLessonSearch();
                $srch->joinOrder();
                $srch->addCondition('slesson_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $srch->addCondition('mysql_func_DATE(order_date_added)', '=', 'mysql_func_DATE("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_WEEK:
                $srch = new ScheduledLessonSearch();
                $srch->joinOrder();
                $srch->addCondition('slesson_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $srch->addCondition('mysql_func_YEARWEEK(order_date_added)', '=', 'mysql_func_YEARWEEK("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_THIS_MONTH:
                $srch = new ScheduledLessonSearch();
                $srch->joinOrder();
                $srch->addCondition('slesson_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("'. $nowDate .'")', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_MONTH:
                $srch = new ScheduledLessonSearch();
                $srch->joinOrder();
                $srch->addCondition('slesson_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("'. $nowDate .'" - INTERVAL 1 MONTH)', 'AND', true);
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
            case static::TYPE_LAST_YEAR:
                $srch = new ScheduledLessonSearch();
                $srch->joinOrder();
                $srch->addCondition('slesson_teacher_id', '=', $this->userId);
                $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
                $srch->addMultipleFields(array('count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate'));
                $rs = $srch->getResultSet();
                return $this->db->fetch($rs);
                break;
        }
    }

    public function getLast12MonthsSales()
    {
        $last12Months = $this->getLast12MonthsDetails();

        foreach ($last12Months as $key => $val) {
            $srch = new OrderSearch();
            $srch->joinOrderProduct();
            $srch->addCondition('op_teacher_id', '=', $this->userId);
            $srch->addMultipleFields(array('SUM(order_net_amount) as Sales','op_order_id'));
            $srch->addCondition('mysql_func_month(order_date_added)', '=', $val['monthCount'], 'AND', true);
            $srch->addCondition('mysql_func_year(order_date_added)', '=', $val['year'], 'AND', true);
            //$srch->addHaving('mysql_func_count(DISTINCT order_user_id)', '>', 1,'AND',true);
            $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
            $rs = $srch->getResultSet();
            $row = $this->db->fetch($rs);

            $srch = new OrderSearch();
            $srch->joinOrderProduct();
            $srch->addCondition('op_teacher_id', '=', $this->userId);
            $srch->addMultipleFields(array('SUM(order_net_amount) as Sales','op_order_id'));
            $srch->addCondition('mysql_func_month(order_date_added)', '=', $val['monthCount'], 'AND', true);
            $srch->addCondition('mysql_func_year(order_date_added)', '=', $val['year'], 'AND', true);
            $srch->addHaving('mysql_func_count(DISTINCT order_user_id)', '=', 1, 'AND', true);
            $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
            $rs = $srch->getResultSet();
            $rownew = $this->db->fetch($rs);

            //$sales_data[] = array("duration" => $val['monthShort'] . "-" . $val['yearShort'], "OldCustomersValue" => round($row["Sales"], 2), "NewCustomersValue" => round($rownew["Sales"], 2));
            $sales_data[] = array("duration" => $val['monthShort'] . "-" . $val['yearShort'], "OldCustomersValue" => round($row["Sales"], 2));
        }
        return array_reverse($sales_data);
    }

    private function getLast12MonthsDetails()
    {
        $month = date('m');
        $year = date('Y');
        $i = 1;
        $date = array();
        while ($i <= 12) {
            $timestamp = mktime(0, 0, 0, $month, 1, $year);
            $date[$i]['monthCount'] = date('m', $timestamp);
            $date[$i]['monthShort'] = date('M', $timestamp);
            $date[$i]['yearShort'] = date('y', $timestamp);
            $date[$i]['year'] = date('Y', $timestamp);
            $month--;
            $i++;
        }
        return $date;
    }
}
