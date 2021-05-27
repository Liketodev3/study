<?php
class RobotController extends AdminBaseController
{

    private $fileName = './../robots.txt';
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewRobotsSection($this->admin_id);
    }

    public function index()
    {
        $frm = $this->getForm($this->adminLangId);        
        $this->set('frm', $frm);
        $this->_template->render();
    }

    public function setup()
    {
        $this->objPrivilege->canEditRobotsSection($this->admin_id);
        $frm = $this->getForm($this->adminLangId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if(!file_exists($this->fileName)){
            Message::addErrorMessage(Label::getLabel('Msg_Robots.txt_file_not_exist'),$this->adminLangId);
            FatUtility::dieJsonError(Message::getHtml());
        }
        if(!is_writable($this->fileName)){
            Message::addErrorMessage(Label::getLabel('Msg_Write_Permission_Denied'),$this->adminLangId);
            FatUtility::dieJsonError(Message::getHtml());
        }

        if(!file_put_contents($this->fileName,$post['robot_file_txt'])){
            Message::addErrorMessage(Label::getLabel('Msg_Something_went_Wrong'),$this->adminLangId);
            FatUtility::dieJsonError(Message::getHtml());
        }
   
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getForm(int $langId)
    {
        $frm = new Form('frmRobot');
        $robotTxt = (file_exists($this->fileName)) ? file_get_contents($this->fileName): Label::getLabel(Label::getLabel('Msg_Robots.txt_file_not_exist'));
        $frm->addTextArea(Label::getLabel('LBL_Robots_File_Txt', $langId),'robot_file_txt',$robotTxt);
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}


