<?php
class Statistics extends MyAppModel
{

    const TYPE_TODAY = 1;
    const TYPE_LAST_WEEK = 2;
    const TYPE_THIS_MONTH = 3;
    const TYPE_LAST_MONTH = 4;
    const TYPE_LAST_YEAR = 5;
    const TYPE_ALL = 6;
    const REPORT_EARNING = 1;
    const REPORT_SOLD_LESSONS = 2;

    public function __construct(int $userId = 0)
    {
        $this->db = FatApp::getDb();
        $this->userId = $userId;
    }

    public static function getDurationTypesArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return [
            static::TYPE_TODAY => Label::getLabel('LBL_Today', $langId),
            static::TYPE_LAST_WEEK => Label::getLabel('LBL_Last_Week', $langId),
            static::TYPE_THIS_MONTH => Label::getLabel('LBL_This_Month', $langId),
            static::TYPE_LAST_MONTH => Label::getLabel('LBL_Last_Month', $langId),
            static::TYPE_LAST_YEAR => Label::getLabel('LBL_Last_Year', $langId),
        ];
    }

    public function getEarning($type, $forGraph = false)
    {
        $type = strtolower($type);
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $nowDate = date('Y-m-d H:i:s');
        $nowDateTimestamp = time();

        $srch = new OrderSearch();
        $srch->joinOrderProduct();
        $srch->joinScheduledLessonDetail();
        $srch->joinScheduledLesson();
        $srch->addCondition('op_teacher_id', '=', $this->userId);
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $srch->addCondition('slesson_is_teacher_paid', '=', applicationConstants::YES);
        $srch->addMultipleFields(['IFNULL(sum(op_commission_charged),0) as earning']);
        switch ($type) {
            case static::TYPE_TODAY:
                $startDate = MyDate::changeDateTimezone(date('Y-m-d', $nowDateTimestamp) . ' 12:00:00', $user_timezone, $systemTimeZone);
                $srch->addCondition('mysql_func_DATE(order_date_added)', '=', 'mysql_func_DATE("' . $nowDate . '")', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%H:%i') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%H:%i')");
                }
                break;
            case static::TYPE_LAST_WEEK:
                $startDate = date('Y-m-d H:i:s', strtotime('-1 week', $nowDateTimestamp));
                $endDate = $nowDate;
                $srch->addCondition('order_date_added', '>=', $startDate, 'AND', true);
                $srch->addCondition('order_date_added', '<=', $endDate, 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%W') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%W')");
                }
                break;
            case static::TYPE_THIS_MONTH:
                $startDate = MyDate::changeDateTimezone(date('Y-m', $nowDateTimestamp) . '-01 00:00:00', $user_timezone, $systemTimeZone);
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("' . $nowDate . '")', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%d-%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%d-%m-%Y')");
                }
                break;
            case static::TYPE_LAST_MONTH:
                $startDate = date('Y-m-d', strtotime('first day of previous month', $nowDateTimestamp)) .' 00:00:00';
                $endDate = date('Y-m-d', strtotime('last day of previous month +1 days', $nowDateTimestamp)) .' 00:00:00';
                $startDate = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $endDate = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("' . $nowDate . '" - INTERVAL 1 MONTH)', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%d-%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%d-%m-%Y')");
                }
                break;
            case static::TYPE_LAST_YEAR:
                $startDate = date('Y-m-d', strtotime('last year January 1st', $nowDateTimestamp)).' 00:00:00';
                $endDate = date('Y-m-d', strtotime('last year December 31st +1 days', $nowDateTimestamp)) .' 00:00:00';
                $startDate = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $endDate = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);

                $srch->addMultipleFields(['MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate', 'YEAR("' . $nowDate . '" - INTERVAL 1 YEAR) as ss']);
                $srch->addCondition('mysql_func_YEAR(order_date_added)', '>=', 'mysql_func_YEAR("' . $nowDate . '" - INTERVAL 1 YEAR)', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%m-%Y')");
                }
                break;
            case static::TYPE_ALL:
            default:
                $endDate = $nowDate;
                $startDate = '';
                $srch->addMultipleFields(['MIN(date(order_date_added)) as fromDate, MAX(date(order_date_added)) as toDate']);
                $srch->addCondition('order_date_added', '<=', $endDate, 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%d-%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%d-%m-%Y')");
                }
            break;
        }

        $data['fromDate'] = $startDate;
        $data['toDate'] = $nowDate;
        $data['earningData'] = [];
        if ($forGraph) {
            $earningData = $this->db->fetchAll($srch->getResultSet(), 'groupDate');
            $data['earningData'] = $earningData;
            $data['earning'] = array_sum(array_column($earningData, 'earning'));
        }else{
            $earningData = $this->db->fetch($srch->getResultSet());
            $data['earningData'] =  $earningData;
            $data['earning'] =  CommonHelper::displayMoneyFormat($earningData['earning']);
        }
        return $data;
    }

    public function getSoldlessons($type, $forGraph = false)
    {
        $type = strtolower($type);
        $user_timezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $nowDate = date('Y-m-d H:i:s');
        $nowDateTimestamp = time();
        $srch = new ScheduledLessonSearch();
        $srch->joinOrder();
        $srch->addCondition('slesson_teacher_id', '=', $this->userId);
        $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
        $srch->addMultipleFields(['count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate']);
        switch ($type) {
            case static::TYPE_TODAY:
                $startDate = MyDate::changeDateTimezone(date('Y-m-d', $nowDateTimestamp) . ' 12:00:00', $user_timezone, $systemTimeZone);
                $srch->addCondition('mysql_func_DATE(order_date_added)', '=', 'mysql_func_DATE("' . $nowDate . '")', 'AND', true);
                $data = $this->db->fetch($srch->getResultSet());

                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%H:%i') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%H:%i')");
                }

                break;
            case static::TYPE_LAST_WEEK:
                $startDate = date('Y-m-d H:i:s', strtotime('-1 week', $nowDateTimestamp));
                $endDate = $nowDate;

                $srch->addCondition('order_date_added', '>=', $startDate, 'AND', true);
                $srch->addCondition('order_date_added', '<=', $endDate, 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%W') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%W')");
                }
     
                break;
            case static::TYPE_THIS_MONTH:
                $startDate = MyDate::changeDateTimezone(date('Y-m', $nowDateTimestamp) . '-01 12:00:00', $user_timezone, $systemTimeZone);
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("' . $nowDate . '")', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%d-%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%d-%m-%Y')");
                }
                break;
            case static::TYPE_LAST_MONTH:
                $startDate = date('Y-m-d', strtotime('first day of previous month', $nowDateTimestamp));
                $endDate = date('Y-m-d', strtotime('last day of previous month', $nowDateTimestamp)) . ' 23:59:59';
                $startDate = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $endDate = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
           
                $srch->addCondition('mysql_func_MONTH(order_date_added)', '=', 'mysql_func_MONTH("' . $nowDate . '" - INTERVAL 1 MONTH)', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%d-%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%d-%m-%Y')");
                }
                break;
            case static::TYPE_LAST_YEAR:
                $startDate = date('Y-m-d', strtotime('last year January 1st', $nowDateTimestamp));
                $endDate = date('Y-m-d', strtotime('last year December 31st', $nowDateTimestamp)) . ' 23:59:59';
                $startDate = MyDate::changeDateTimezone($startDate, $user_timezone, $systemTimeZone);
                $endDate = MyDate::changeDateTimezone($endDate, $user_timezone, $systemTimeZone);
              
                $srch->addMultipleFields(['count(slesson_id) as lessonCount, MIN(order_date_added) as fromDate, MAX(order_date_added) as toDate']);
                $srch->addCondition('mysql_func_YEAR(order_date_added)', '>=', 'mysql_func_YEAR("' . $nowDate . '" - INTERVAL 1 YEAR)', 'AND', true);
                if($forGraph){
                    $srch->addMultipleFields(["DATE_FORMAT(order_date_added, '%m-%Y') as groupDate"]);
                    $srch->addGroupBy("DATE_FORMAT(order_date_added, '%m-%Y')");
                }
                break;
        }

        $data['fromDate'] = $startDate;
        $data['toDate'] = $nowDate;
        $data['lessonData'] = [];
        if ($forGraph) {
            $lessonData = $this->db->fetchAll($srch->getResultSet(), 'groupDate');
            $data['lessonData'] = $lessonData;
            $data['lessonCount'] = array_sum(array_column($lessonData, 'lessonCount'));
        }else{
            $lessonData = $this->db->fetch($srch->getResultSet());
            $data['lessonData'] = $lessonData;
            $data['lessonCount'] =  $lessonData['lessonCount'];
        }
        
        return $data;
    }

    public function getLast12MonthsSales()
    {
        $last12Months = $this->getLast12MonthsDetails();
        foreach ($last12Months as $key => $val) {
            $srch = new OrderSearch();
            $srch->joinOrderProduct();
            $srch->joinScheduledLessonDetail();
            $srch->joinScheduledLesson();
            $srch->addCondition('op_teacher_id', '=', $this->userId);
            $srch->addMultipleFields(['SUM(op_commission_charged) as Sales', 'op_order_id']);
            $srch->addCondition('mysql_func_month(order_date_added)', '=', $val['monthCount'], 'AND', true);
            $srch->addCondition('mysql_func_year(order_date_added)', '=', $val['year'], 'AND', true);
            $srch->addCondition('slesson_is_teacher_paid', '=', applicationConstants::YES);
            $srch->addCondition('order_is_paid', '=', Order::ORDER_IS_PAID);
            $rs = $srch->getResultSet();
            $row = $this->db->fetch($rs);
            $sales_data[] = ["duration" => $val['monthShort'] . "-" . $val['yearShort'], "OldCustomersValue" => round($row["Sales"], 2)];
        }
        return array_reverse($sales_data);
    }

    private function getLast12MonthsDetails()
    {
        $month = date('m');
        $year = date('Y');
        $i = 1;
        $date = [];
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
