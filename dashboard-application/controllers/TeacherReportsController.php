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
        $post = FatApp::getPostedData();
        if (!$post) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $statObj = new Statistics(UserAuthentication::getLoggedUserId());
        switch ($post['type']) {
            case Statistics::REPORT_EARNING:
                $earningData = $statObj->getEarning($post['duration']);
                $this->set('earningData', $earningData);
                $this->_template->render(false, false);
                break;
            case Statistics::REPORT_SOLD_LESSONS:
                $soldLessons = $statObj->getSoldLessons($post['duration']);
                $this->set('soldLessons', $soldLessons);
                $this->set('siteLangId', $this->siteLangId);
                $this->_template->render(false, false, 'teacher-reports/get-sold-lessons.php');
                break;
        }
    }

}
