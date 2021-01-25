<?php
class PwaController extends AdminBaseController
{
    public function index()
    {
        $frm = $this->getForm($this->adminLangId);
        $record = Configurations::getConfigurations();
        if (!empty($record['CONF_PWA_SETTINGS'])) {
            $data['pwa_settings'] = json_decode($record['CONF_PWA_SETTINGS'], true);
            $frm->fill($data);
        }
        if (empty(AttachedFile::getAttachment(AttachedFile::FILETYPE_PWA_APP_ICON, 0))) {
            $frm->getField('icon')->requirement->setRequired();
        }
        if (empty(AttachedFile::getAttachment(AttachedFile::FILETYPE_PWA_SPLASH_ICON, 0))) {
            $frm->getField('splash_icon')->requirement->setRequired();
        }

        $this->set('frm', $frm);
        $this->_template->render();
    }

    public function setup()
    {
        $frm = $this->getForm($this->adminLangId);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            Message::addErrorMessage($frm->getValidationErrors());
            return $this->index();
        }

        $pwaSettings = $post['pwa_settings'];

        FatApp::getDb()->startTransaction();

        if (!empty($_FILES['icon']['name'])) {
            $attchedFile = new AttachedFile();
            if (!$attchedFile->saveImage($_FILES['icon']['tmp_name'], AttachedFile::FILETYPE_PWA_APP_ICON, 0, 0, $_FILES['icon']['name'], 0, true)) {
                FatApp::getDb()->rollbackTransaction();
                Message::addErrorMessage($attchedFile->getError());
                return $this->index();
            }
        }

        if (!empty($_FILES['splash_icon']['name'])) {
            $attchedFile = new AttachedFile();
            if (!$attchedFile->saveImage($_FILES['splash_icon']['tmp_name'], AttachedFile::FILETYPE_PWA_SPLASH_ICON, 0, 0, $_FILES['splash_icon']['name'], 0, true)) {
                FatApp::getDb()->rollbackTransaction();
                Message::addErrorMessage($attchedFile->getError());
                return $this->index();
            }
        }

        $configurations = new Configurations();
        if (!$configurations->update(['CONF_PWA_SETTINGS' => json_encode($pwaSettings)])) {
            FatApp::getDb()->rollbackTransaction();
            Message::addErrorMessage(FatApp::getDb()->getError());
            return $this->index();
        }

        FatApp::getDb()->commitTransaction();
        Message::addMessage(Label::getLabel('LBL_Success'));
        FatApp::redirectUser(CommonHelper::generateUrl('Pwa'));
    }

    public function langForm(int $langId)
    {
    }

    private function getForm(int $langId): Form
    {

        $orientationArr = PWA::orientationArr($langId);
        $displayArr = PWA::displayArr($langId);

        $frm = new Form('pwaFrm');

        $frm->addRequiredField(Label::getLabel('PWALBL_App_Name'), 'pwa_settings[app_name]');
        $frm->addRequiredField(Label::getLabel('PWALBL_App_Short_Name'), 'pwa_settings[app_short_name]')->htmlAfterField = '<small>'.Label::getLabel('HTMLAFTER_PWA_APP_SHORT_NAME').'</small>';
        $frm->addTextBox(Label::getLabel('PWALBL_Description'), 'pwa_settings[description]');
        $frm->addFileUpload(Label::getLabel('PWALBL_App_Icon'), 'icon');
        $frm->addFileUpload(Label::getLabel('PWALBL_Splash_Icon'), 'splash_icon');
        $frm->addRequiredField(Label::getLabel('PWALBL_Background_Color'), 'pwa_settings[background_color]');
        $frm->addRequiredField(Label::getLabel('PWALBL_Theme_Color'), 'pwa_settings[theme_color]');
        $frm->addRequiredField(Label::getLabel('PWALBL_Start_Page'), 'pwa_settings[start_page]');
        $contentPages = ContentPage::getPagesForSelectBox($langId);
        $frm->addSelectBox(Label::getLabel('PWALBL_Offline_Page', $langId), 'pwa_settings[offline_page]', $contentPages)->requirements()->setRequired();

        $frm->addSelectBox(Label::getLabel('PWALBL_Orientation'), 'pwa_settings[orientation]', $orientationArr, '', [], '')->requirements()->setRequired();
        $frm->addSelectBox(Label::getLabel('PWALBL_Display'), 'pwa_settings[display]', $displayArr, '', [], '')->requirements()->setRequired();
        // $frm->addTextBox(Label::getLabel('PWALBL_Cache_Strategy'), 'pwa_settings[background_color]');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save', $langId));

        return $frm;
    }

    private function getLangForm(int $langId): Form
    {
        $frm = new Form('pwaLangFrm');
        return $frm;
    }
}
