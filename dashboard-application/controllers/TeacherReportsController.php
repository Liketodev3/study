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
        $reportSearchForm =  $this->reportSearchForm($this->siteLangId);
        $post = $reportSearchForm->getFormDataFromArray(FatApp::getPostedData());
        if (!$post) {
            Message::addErrorMessage(Label::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $statObj = new Statistics(UserAuthentication::getLoggedUserId());
        $responseArray = [
            'earningData' => [],
            'soldLessons' => [],
        ];
        foreach ($post['report_type'] as $value) {
            if($value == Statistics::REPORT_EARNING){
                $responseArray['earningData'] = $statObj->getEarning($post['earing_duration']);
            }

            if($value == Statistics::REPORT_EARNING){
                $responseArray['soldLessons'] = $statObj->getSoldLessons($post['lesson_duration']);
            }
        }

        FatUtility::dieJsonSuccess($responseArray);
    }

}
