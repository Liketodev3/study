<?php

class TeacherReportsController extends TeacherBaseController
{

    public function index()
    {
        $statObj = new Statistics(UserAuthentication::getLoggedUserId());
        $ordersData = $statObj->getLast12MonthsSales();
        $ordersChartData = '';
        foreach ($ordersData as $key => $val) {
            $ordersChartData .= "['" . $val["duration"] . "', " . $val["OldCustomersValue"] . "],";
        }
        $ordersChartData = rtrim($ordersChartData, ',');
        $durationArr = Statistics::getDurationTypesArr(CommonHelper::getLangId());
        $this->set('durationArr', $durationArr);
        $this->set('arr', $ordersChartData);
        $this->_template->render();
    }

    public function getStatisticalData()
    {
        $type = FatApp::getPostedData('type');
        $duration = FatApp::getPostedData('duration' , FatUtility::VAR_INT, 0);
        if (empty($type) || empty($duration)) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $statObj = new Statistics(UserAuthentication::getLoggedUserId());

        switch ($type) {
            case Statistics::REPORT_EARNING:
                $earningData = $statObj->getEarning($duration);
                $this->set('earningData', $earningData);
                $this->_template->render(false, false);
                break;
            case Statistics::REPORT_SOLD_LESSONS:
                $soldLessons = $statObj->getSoldLessons($duration);
                $this->set('soldLessons', $soldLessons);
                $this->set('siteLangId', $this->siteLangId);
                $this->_template->render(false, false, 'teacher-reports/get-sold-lessons.php');
            break;
        }
    }

}
