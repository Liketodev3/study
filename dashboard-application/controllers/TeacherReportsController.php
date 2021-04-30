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
        $durationField = $reportSearchForm->getField('duration_type');
        $durationType = $durationField->options;
        $statObj = new Statistics(UserAuthentication::getLoggedUserId());
        $responseArray = [
            'earningData' => [],
            'soldLessons' => [],
            'graphArray' => []
        ];
        foreach ($post['report_type'] as $value) {
            if($value == Statistics::REPORT_EARNING){
                $responseArray['earningData'] = $statObj->getEarning($post['duration_type'], true);
            }
            if($value == Statistics::REPORT_EARNING){
                $responseArray['soldLessons'] = $statObj->getSoldLessons($post['duration_type'], true);
            }
        }
        if($post['forGraph']){
            $graphArray = [];
            $graphArray[] = [
                $durationType[$post['duration_type']],
                Label::getLabel('Lbl_Earning'),
                Label::getLabel('Lbl_LESSONS_SOLD'),
            ];
            if(!empty($responseArray['earningData']['earningData'])){
                foreach ($responseArray['earningData']['earningData'] as $key => $value) {
                    $graphArray[$key] = [
                        $key,
                        $value['earning'],
                        0,
                    ];
                }
            }
            if (!empty($responseArray['earningData']['lessonData'])) {
                foreach ($responseArray['soldLessons']['lessonData'] as $key => $value) {
                    if (array_key_exists($key, $graphArray)) {
                        $graphArray[$key][2] = $value['lessonCount'];
                    } else {
                        $graphArray[$key] = [
                        $key,
                        0,
                        $value['lessonCount'],
                    ];
                    }
                }
            }
            $graphArray = array_values($graphArray);
        }
        FatUtility::dieJsonSuccess($responseArray);
    }

}
