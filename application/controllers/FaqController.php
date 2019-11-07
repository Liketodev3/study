<?php
class FaqController extends MyAppController
{
    public function index()
    {
        $srch = new SearchBase(Faq::DB_TBL);
        $srch->addMultipleFields(array(
            'faq_id',
            'faq_category',
            'faq_title',
        ));
        $srch->joinTable(Faq::DB_TBL_LANG, 'LEFT OUTER JOIN', 'faqlang_faq_id=faq_id AND faqlang_lang_id = ' . $this->siteLangId);
        $srch->setPageSize(50);
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetchAll($rs);
        $finaldata = array();
        foreach ($data as $val) {
            $finaldata[$val['faq_category']][] = $val;
        }
        $this->set('finaldata', $finaldata);
        $this->set('typeArr', Faq::getFaqCategoryArr());
        $this->_template->render();
    }

    public function view($faqId)
    {
        $faqId = FatUtility::int($faqId);
        if ($faqId <= 0) {
            FatUtility::exitWithErrorCode(404);
        }
        $srchbase = new SearchBase(Faq::DB_TBL);
        $srchbase->joinTable(Faq::DB_TBL_LANG, 'LEFT OUTER JOIN', 'faqlang_faq_id=faq_id AND faqlang_lang_id = ' . $this->siteLangId);

        $srch = clone $srchbase;
        $srch->addMultipleFields(array(
            'faq_id',
            'faq_category',
            'faq_title',
            'faq_description',
        ));
        $srch->addCondition('faq_id', '=', $faqId);
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        $type = Faq::getFaqCategoryArr()[$data['faq_category']];
        $srchOther = clone $srchbase;
        $srchOther->addMultipleFields(array(
            'faq_id',
            'faq_title',
        ));
        $srchOther->addCondition('faq_id', '!=', $faqId);
        $srchOther->addCondition('faq_category', '=', $data['faq_category']);
        $rsOther = $srchOther->getResultSet();
        $dataOther = FatApp::getDb()->fetchAll($rsOther);
        $this->set('dataOther', $dataOther);
        $this->set('data', $data);
        $this->set('type', $type);
        $this->_template->render();
    }

    public function category($categoryId)
    {
        $categoryId = FatUtility::int($categoryId);
        if ($categoryId <= 0) {
            FatUtility::exitWithErrorCode(404);
        }
        $srch = new SearchBase(Faq::DB_TBL);
        $srch->joinTable(Faq::DB_TBL_LANG, 'LEFT OUTER JOIN', 'faqlang_faq_id=faq_id AND faqlang_lang_id = ' . $this->siteLangId);
        $srch->addMultipleFields(array(
            'faq_id',
            'faq_title',
        ));
        $srch->addCondition('faq_category', '=', $categoryId);
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetchAll($rs);
        $this->set('data', $data);
        $this->set('categoryId', $categoryId);
        $this->_template->render();
    }
}
