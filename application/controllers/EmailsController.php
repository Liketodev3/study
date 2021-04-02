<?php
class EmailsController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->_template->addCss('css/style.css');
    }

    public function index($page = 1)
    {
        $srch = new SearchBase('tbl_email_archives');
        $srch->addOrder('emailarchive_id', 'desc');
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $emailsList = FatApp::getDb()->fetchAll($rs);
        $this->set('emailsList', $emailsList);
        $this->set('recordCount', $srch->recordCount());
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->_template->render(false, false, 'emails/index.php');
    }

    public function view($id)
    {
        $srch = new SearchBase('tbl_email_archives');
        $srch->addCondition('emailarchive_id', '=', $id);
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetch($rs);
        echo $data['emailarchive_body'];
        die;
    }
}
