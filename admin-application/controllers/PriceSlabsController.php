<?php
class PriceSlabsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPriceSlab();
    }

    public function index()
    {
        $canEdit = $this->objPrivilege->canEditPriceSlab($this->admin_id, true);
        $this->set("canEdit", $canEdit);
        $this->_template->render();
    }
    
    public function search()
    {
       
        $pagesize  = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $postedData =  FatApp::getPostedData();
       
        $searchObject = PriceSlab::getSearchObject(false);
        $searchObject->addOrder('prislab_active', 'desc');
        $searchObject->addOrder('prislab_id', 'desc');
        $searchObject->setPageNumber($page);
        $searchObject->setPageSize($pagesize);
        $records = FatApp::getDb()->fetchAll($searchObject->getResultSet());

        $canEdit = $this->objPrivilege->canEditPriceSlab($this->admin_id, true);

        $this->set('pageCount', $searchObject->pages());
        $this->set("canEdit", $canEdit);
        $this->set("records", $records);
        $this->set('recordCount', $searchObject->recordCount());
        $this->set('postedData', $postedData);
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->_template->render(false, false);
    }

    public function form(int $slotId = 0)
    {
        $form = $this->getForm($slotId);
        if (0 < $slotId) {
            $data = PriceSlab::getAttributesById($slotId, array(
                'prislab_id',
                'prislab_min',
                'prislab_max'
            ));
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
           
            $form->fill($data);
        }
        $this->set('psId', $slotId);
        $this->set('form', $form);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditPriceSlab();
        $frm  = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $psId = $post['prislab_id'];
        unset($post['prislab_id']);
        $PriceSlabSearchObject = PriceSlab::getSearchObject();
        $searchObject = clone $PriceSlabSearchObject;
        $searchObject->addCondition('prislab_max','>=', $post['prislab_min']);
        $searchObject->addCondition('prislab_min','<=',$post['prislab_max']);
        $searchObject->addCondition('prislab_id','!=',$psId);
        $resultSet = $searchObject->getResultSet();
        $slotData = FatApp::getDb()->fetch($resultSet);
        if(!empty($slotData)){
            Message::addErrorMessage(Label::getLabel('LBL_YOUR_SLOT_IS_COLLAPSE_WITH_OTHER_SLOTS'));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if($post['prislab_min'] != 1){
            $searchObject =  clone $PriceSlabSearchObject;
            $searchObject->addCondition('prislab_min','=', 1);
            $searchObject->addCondition('prislab_id','!=',$psId);
            $resultSet = $searchObject->getResultSet();
            $minSlotData = FatApp::getDb()->fetch($resultSet);
            if(empty($minSlotData)){
                Message::addErrorMessage(Label::getLabel('LBL_PLEASE_ADD_SLOT_WITH_MIN_VALUE_1_FIRST'));
                FatUtility::dieJsonError(Message::getHtml());
            }
        }
       
        $PriceSlab = new PriceSlab($psId);
        $PriceSlab->assignValues($post);
        if (!$PriceSlab->saveSlot($post['prislab_min'], $post['prislab_max'], $post['prislab_identifier'])) {
            Message::addErrorMessage($PriceSlab->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $psId =  $PriceSlab->getMainTableRecordId();
        
        $responseArray = [
            'msg' => Label::getLabel('LBL_SLOT_SAVE_SUCCESSFULLY'),
            'psId' => $psId,
            'langId' => $newTabLangId,
            'status' => applicationConstants::YES,
        ];
        FatUtility::dieJsonSuccess($responseArray);
    } 

    public function changeStatus()
    {
        $this->objPrivilege->canEditPriceSlab();
        $psId = FatApp::getPostedData('psId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);

        $slotData = PriceSlab::getAttributesById($psId, array(
            'prislab_id',
            'prislab_active'
        ));
        if ($slotData == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $PriceSlab  = new PriceSlab($psId);
        if (!$PriceSlab->changeStatus($status)) {
            Message::addErrorMessage($PriceSlab->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieJsonSuccess($this->str_update_record);
    }

    private function getForm(int $psId = 0) :object
    { 
        $form  = new Form('PriceSlabFrm');
        $form->addHiddenField('', 'prislab_id', $psId);
        $minField = $form->addRequiredField(Label::getLabel('LBL_Min', $this->adminLangId), 'prislab_min');
        $maxField=  $form->addRequiredField(Label::getLabel('LBL_max', $this->adminLangId), 'prislab_max');
        $minField->requirements()->setIntPositive();
        $minField->requirements()->setRange(1,999999);
        $maxField->requirements()->setIntPositive();
        $maxField->requirements()->setRange(1,999999);
        $minField->requirements()->setCompareWith('prislab_max', 'lt', Label::getLabel('LBL_max', $this->adminLangId));
        $maxField->requirements()->setCompareWith('prislab_min', 'gt', Label::getLabel('LBL_min', $this->adminLangId));
        // $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        // $form->addSelectBox(Label::getLabel('LBL_Status', $this->adminLangId), 'prislab_active', $activeInactiveArr, '', array(), '');
        $form->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $form;
    }
}
