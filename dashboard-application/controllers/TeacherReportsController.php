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
            'graphData' => []
        ];
        foreach ($post['report_type'] as $value) {
            if($value == Statistics::REPORT_EARNING){
                $responseArray['earningData'] = $statObj->getEarning($post['duration_type'], true);
                $responseArray['earningData']['earning'] = CommonHelper::displayMoneyFormat($responseArray['earningData']['earning']);
            }
            if($value == Statistics::REPORT_EARNING){
                $responseArray['soldLessons'] = $statObj->getSoldLessons($post['duration_type'], true);
            }
        }

        if($post['forGraph']){
            $userTimezone =  MyDate::getUserTimeZone();
            $systemTimezone =  MyDate::getTimeZone();
            $fromDate = MyDate::changeDateTimezone($responseArray['earningData']['fromDate'], $systemTimezone, $userTimezone);
            $toDate = MyDate::changeDateTimezone($responseArray['earningData']['toDate'], $systemTimezone, $userTimezone);
            $earningLabel = Label::getLabel('Lbl_Earning');
            $lessonSoldLabel = Label::getLabel('Lbl_LESSONS_SOLD');
            $graphArray['column'] = [
                'durationType' => $durationType[$post['duration_type']].' '.$fromDate.' - '.$toDate,
                'earningLabel' => $earningLabel,
                'lessonSoldLabel' => $lessonSoldLabel,
            ];
            $graphArray['rowData'] = [];
            
            $rowData = [];
            if(!empty($responseArray['earningData']['earningData'])){
                foreach ($responseArray['earningData']['earningData'] as $key => $value) {
                    $rowData[$key] = [
                        $key,
                        CommonHelper::displayMoneyFormat($value['earning'], true, false, false),
                        $earningLabel." ".CommonHelper::displayMoneyFormat($value['earning']),
                        0,
                        $lessonSoldLabel." 0"
                    ];
                }
            }
            if (!empty($responseArray['soldLessons']['lessonData'])) {
                foreach ($responseArray['soldLessons']['lessonData'] as $key => $value) {
                    if (array_key_exists($key, $rowData)) {
                        $rowData[$key][3] = $value['lessonCount'];
                        $rowData[$key][4] =  $lessonSoldLabel." ".$value['lessonCount'];
                    } else {
                        $rowData[$key] = [
                        $key,
                        0,
                        $earningLabel." ".CommonHelper::displayMoneyFormat(0),
                        $value['lessonCount'],
                        $lessonSoldLabel." ".$value['lessonCount']
                    ];
                    }
                }
            }
            $graphArray['rowData'] = array_values($rowData);
            $responseArray['graphData'] = $graphArray;
        }
        FatUtility::dieJsonSuccess($responseArray);
    }

}
