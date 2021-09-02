<?php


class AffilateController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCurrencyManagement();
    }

    public function index()
    {

        $this->_template->render();
    }

    public function search()
    {
        $srch = new SearchBase(Affilate::DB_TBL);
        $res = FatApp::getDb()->fetchAll($srch->getResultSet());
        $this->set("records", $res);

        $this->_template->render(false, false);
    }

    public function updateAffilate($a,$b){

        $srch = new Affilate(1);

        $srch->assignValues([$a => $b]);
        $srch->save();
        $this->_template->render(false, false,'json-success.php');

    }


}