<?php

class LessonMeetings
{

    public function getMeetingData(array $lessonData): array
    {
        $meetingData = [];
        $activeMettingTool = FatApp::getConfig('CONF_ACTIVE_MEETING_TOOL', FatUtility::VAR_STRING, ApplicationConstants::MEETING_COMET_CHAT);
        $loggedUserId = UserAuthentication::getLoggedUserId();
        switch ($activeMettingTool) {
            case ApplicationConstants::MEETING_ZOOM:
                if ($loggedUserId == $lessonData['teacherId']) {
                    return $this->getHostZoomMeetingData($lessonData);
                }
                return $this->getAttendeeZoomMeetingData($lessonData);
                break;
            case ApplicationConstants::MEETING_LESSON_SPACE:
                return $this->getLessonSpaceMeetingData($lessonData);
                break;
            case ApplicationConstants::MEETING_COMET_CHAT:
                return $this->getCometChatMeetingData($lessonData);
                break;
            case ApplicationConstants::MEETING_WIZIQ:
                if ($loggedUserId == $lessonData['teacherId']) {
                    return $this->getHostWiziqMeetingData($lessonData);
                }
                return $this->getAttendeeWiziqMeetingData($lessonData);
                break;
        }
        return [];
    }

    private function getHostZoomMeetingData(array $lessonData): array
    {
        $meetingDetails = $this->getHostZoomMeetingDetails($lessonData);
        if (!empty($meetingDetails)) {
            return $meetingDetails;
        }
        $teacherData = [
            'first_name' => $lessonData['teacherFirstName'],
            'last_name' => $lessonData['teacherLastName'],
            'email' => $lessonData['teacherEmail']
        ];
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $zoom = new Zoom();
        $zoomTeacherId = $zoom->createUser($teacherData);
        $startTime = $lessonData['slesson_date'] . ' ' . $lessonData['slesson_start_time'];
        $teachingLangs = TeachingLanguage::getAllLangs();
        $title = $lessonData['slesson_grpcls_id'] > 0 ? $lessonData['grpcls_title'] : '';
        $title = ($title ? $title : (!$lessonData['is_trial'] ? $teachingLangs[$lessonData['slesson_slanguage_id']] : ''));
        $title = $title ? $title : Label::getLabel('LBL_Trial_Lesson');
        $meetingData = [
            'zoomTeacherId' => $zoomTeacherId,
            'title' => ($title ? $title : Label::getLabel('LBL_N/A')),
            'start_time' => $startTime,
            'duration' => $lessonData['op_lesson_duration'],
            'description' => '',
        ];
        $meetingInfo = $zoom->createMeeting($meetingData);
        if (!$lessonMeetingDetail->addDetails(LessonMeetingDetail::KEY_ZOOM_RAW_DATA, json_encode($meetingInfo))) {
            throw new Exception($lessonMeetingDetail->getError());
        }
        return $this->getHostZoomMeetingDetails($lessonData);
    }

    private function getHostZoomMeetingDetails(array $lessonData): array
    {
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $meetingRow = $lessonMeetingDetail->getMeetingDetails(LessonMeetingDetail::KEY_ZOOM_RAW_DATA);
        if (empty($meetingRow)) {
            return [];
        }
        $row = json_decode($meetingRow, true);
        if (empty($row)) {
            return [];
        }
        $zoom = new Zoom();
        $meetingData = [
            'id' => $row['id'],
            'url' => $row['start_url'],
            'username' => $lessonData['teacherFullName'],
            'email' => $lessonData['teacherEmail'],
            'role' => Zoom::ROLE_HOST,
            'signature' => $zoom->generateSignature($row['id'], Zoom::ROLE_HOST)
        ];
        return $meetingData;
    }

    private function getAttendeeZoomMeetingData(array $lessonData): array
    {
        $meetingDetails = $this->getAttendeeZoomMeetingDetails($lessonData);
        if (!empty($meetingDetails)) {
            return $meetingDetails;
        }
        $teacherData = [
            'first_name' => $lessonData['teacherFirstName'],
            'last_name' => $lessonData['teacherLastName'],
            'email' => $lessonData['teacherEmail']
        ];
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $zoom = new Zoom();
        $zoomTeacherId = $zoom->createUser($teacherData);
        $startTime = $lessonData['slesson_date'] . ' ' . $lessonData['slesson_start_time'];
        $teachingLangs = TeachingLanguage::getAllLangs($this->siteLangId);
        $title = $lessonData['slesson_grpcls_id'] > 0 ? $lessonData['grpcls_title'] : '';
        $title = ($title ? $title : (!$lessonData['is_trial'] ? $teachingLangs[$lessonData['slesson_slanguage_id']] : ''));
        $title = $title ? $title : Label::getLabel('LBL_Trial_Lesson');
        $meetingData = [
            'zoomTeacherId' => $zoomTeacherId,
            'title' => ($title ? $title : Label::getLabel('LBL_N/A')),
            'start_time' => $startTime,
            'duration' => $lessonData['op_lesson_duration'],
            'description' => '',
        ];
        $meetingInfo = $zoom->createMeeting($meetingData);
        if (!$lessonMeetingDetail->addDetails(LessonMeetingDetail::KEY_ZOOM_RAW_DATA, json_encode($meetingInfo))) {
            throw new Exception($lessonMeetingDetail->getError());
        }
        return $this->getHostZoomMeetingDetails($lessonData);
    }

    private function getAttendeeZoomMeetingDetails(array $lessonData): array
    {
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $meetingRow = $lessonMeetingDetail->getMeetingDetails(LessonMeetingDetail::KEY_ZOOM_RAW_DATA);
        if (empty($meetingRow)) {
            return [];
        }
        $row = json_decode($meetingRow, true);
        if (empty($row)) {
            return [];
        }
        $zoom = new Zoom();
        $meetingData = [
            'id' => $row['id'],
            'url' => $row['join_url'],
            'username' => $lessonData['learnerFullName'],
            'email' => $lessonData['learnerEmail'],
            'role' => Zoom::ROLE_ATTENDEE,
            'signature' => $zoom->generateSignature($row['id'], Zoom::ROLE_ATTENDEE)
        ];
        return $meetingData;
    }

    private function getLessonSpaceMeetingData(array $lessonData): array
    {
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], UserAuthentication::getLoggedUserId());
        $lessonUrl = $lessonMeetingDetail->getUserLessonUrl();
        if (!empty($lessonUrl)) {
            return ['url' => $lessonUrl];
        }
        $meetingDetails = $this->formatLessonDataForLessonSpace($lessonData);
        $lessonspace = new Lessonspace();
        $meetingInfo = $lessonspace->launch($meetingDetails);
        if (!$lessonMeetingDetail->addDetails(LessonMeetingDetail::KEY_LS_URL, $meetingInfo['client_url'])) {
            throw new Exception($lessonMeetingDetail->getError());
        }
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], UserAuthentication::getLoggedUserId());
        if (!$lessonMeetingDetail->addDetails(LessonMeetingDetail::KEY_LS_ROOM_ID, $meetingInfo['room_id'])) {
            throw new Exception($lessonMeetingDetail->getError());
        }
        return ['url' => $meetingInfo['client_url']];
    }

    private function formatLessonDataForLessonSpace(array $lessonData): array
    {
        $lessonspaceData = [];
        $userTimezone = MyDate::getUserTimeZone();
        $systemTimeZone = MyDate::getTimeZone();
        $getTimeZoneOffset = MyDate::getOffset($userTimezone);
        $startTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $lessonData['slesson_date'] . ' ' . $lessonData['slesson_start_time'], true, $userTimezone);
        $endTime = MyDate::convertTimeFromSystemToUserTimezone('Y-m-d H:i:s', $lessonData['slesson_end_date'] . ' ' . $lessonData['slesson_end_time'], true, $userTimezone);
        $unixStartTime = strtotime($startTime);
        $unixeEndTime = strtotime($endTime);
        $startTime = date('Y-m-d', $unixStartTime) . 'T' . date('H:i:s', $unixStartTime) . $getTimeZoneOffset;
        $endTime = date('Y-m-d', $unixeEndTime) . 'T' . date('H:i:s', $unixeEndTime) . $getTimeZoneOffset;
        $loggedUserId = UserAuthentication::getLoggedUserId();
        $userDetails = [
            'name' => ($loggedUserId == $lessonData['teacherId'] ? $lessonData['teacherFullName'] : $lessonData['learnerFullName']),
            'leader' => ($loggedUserId == $lessonData['teacherId'])
        ];
        if (true == User::isProfilePicUploaded($loggedUserId)) {
            $image = CommonHelper::generateFullUrl('Image', 'user', [$loggedUserId]) . '?' . time();
            $userDetails['profile_picture'] = $image;
        }
        return [
            "id" => Lessonspace::LESSON_ID_PREFIX . $lessonData['slesson_id'],
            "user" => $userDetails,
            'timeouts' => ["not_before" => $startTime, "not_after" => $endTime,],
            "features" => [
                'invite' => false,
                'fullscreen' => true,
                'endSession' => false,
                'whiteboard.equations' => true,
                'whiteboard.infiniteToggle' => true
            ]
        ];
    }

    private function getCometChatMeetingData(array $lessonData): array
    {
        $cometChat = new CometChat();
        if ($lessonData['slesson_grpcls_id'] > 0) {
            $chat_group_id = $lessonData['slesson_grpcls_id'] > 0 ? $lessonData['grpcls_title'] : "LESSON-" . $lessonData['slesson_id'];
            $params = ['GUID' => $chat_group_id, 'name' => $chat_group_id, 'type' => CometChat::GROUP_TYPE_PRIVATE];
            $groupDetails = $cometChat->createGroup($params);
        }
        return [];
    }

    private function getHostWiziqMeetingData(array $lessonData): array
    {
        $meetDetail = $this->getHostWiziqMeetingDetails($lessonData);
        if (!empty($meetDetail)) {
            return $meetDetail;
        }
        $startTime = $lessonData['slesson_date'] . ' ' . $lessonData['slesson_start_time'];
        $teachingLangs = TeachingLanguage::getAllLangs();
        $title = $lessonData['slesson_grpcls_id'] > 0 ? $lessonData['grpcls_title'] : '';
        $title = ($title ? $title : (!$lessonData['is_trial'] ? $teachingLangs[$lessonData['slesson_slanguage_id']] : ''));
        $title = $title ? $title : Label::getLabel('LBL_Trial_Lesson');
        $meetingData = [
            "slesson_id" => $lessonData['slesson_id'],
            "title" => $title, "start_time" => $startTime,
            "duration" => $lessonData['op_lesson_duration'],
            "presenter_name" => $lessonData['teacherFullName'],
            "presenter_id" => $lessonData['teacherId'],
        ];
        $wiziq = new Wiziq();
        $data = $wiziq->createMeeting($meetingData);
        if ($data === false) {
            throw new Exception($wiziq->getError());
        }
        $meetDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        if (!$meetDetail->addDetails(LessonMeetingDetail::KEY_WIZIQ_RAW_DATA, json_encode($data))) {
            throw new Exception($meetDetail->getError());
        }
        return $this->getHostWiziqMeetingDetails($lessonData);
    }

    private function getHostWiziqMeetingDetails(array $lessonData): array
    {
        $meetDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['teacherId']);
        $meetingData = $meetDetail->getMeetingDetails(LessonMeetingDetail::KEY_WIZIQ_RAW_DATA);
        if (empty($meetingData)) {
            return [];
        }
        $detail = json_decode($meetingData, true);
        return empty($detail) ? [] : $detail;
    }

    private function getAttendeeWiziqMeetingData(array $lessonData): array
    {
        $meetingDetails = $this->getAttendeeWiziqMeetingDetails($lessonData);
        if (!empty($meetingDetails)) {
            return $meetingDetails;
        }
        $meeting = $this->getHostWiziqMeetingDetails($lessonData);
        $classId = FatUtility::int($meeting['class_id'] ?? 0);
        if ($classId < 1) {
            return [];
        }
        $studentData = [
            'student_id' => $lessonData['learnerId'],
            'student_name' => $lessonData['learnerFullName'],
            'student_email' => $lessonData['learnerEmail'],
        ];
        $wiziq = new Wiziq();
        $meetingData = $wiziq->addStudent($classId, $studentData);
        if ($meetingData === false) {
            throw new Exception($wiziq->getError());
        }
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['learnerId']);
        if (!$lessonMeetingDetail->addDetails(LessonMeetingDetail::KEY_WIZIQ_RAW_DATA, json_encode($meetingData))) {
            throw new Exception($lessonMeetingDetail->getError());
        }
        return $this->getAttendeeWiziqMeetingDetails($lessonData);
    }

    private function getAttendeeWiziqMeetingDetails(array $lessonData): array
    {
        $lessonMeetingDetail = new LessonMeetingDetail($lessonData['slesson_id'], $lessonData['learnerId']);
        $meetingRow = $lessonMeetingDetail->getMeetingDetails(LessonMeetingDetail::KEY_WIZIQ_RAW_DATA);
        if (empty($meetingRow)) {
            return [];
        }
        $row = json_decode($meetingRow, true);
        if (empty($row)) {
            return [];
        }
        return $row;
    }

}
