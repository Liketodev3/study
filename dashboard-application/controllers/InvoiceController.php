<?php


class InvoiceController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $this->_template->render();
    }

    public function search()
    {

        $res = Order::getOrders(['status' => '-2','keyword' => '', 'date_from' => '', 'date_to' => '', 'page'=> '1'], User::USER_TYPE_TEACHER, UserAuthentication::getLoggedUserId());

        $sortedData = array();
                foreach ($res['Orders'] as $element) {
                    $timestamp = strtotime($element['order_date_added']);
                    $date = date("d.m.Y", $timestamp); //truncate hours:minutes:seconds
                    if ( ! isSet($sortedData[$date]) ) { //first entry of that day
                        $sortedData[$date] = array($element);
                    } else { //just push current element onto existing array
                        $sortedData[$date][] = $element;
            }
        }

        $this->set("records", $sortedData);

        $this->_template->render(false, false);
    }

    public function details($order){

        $userId = UserAuthentication::getLoggedUserId();

        $srch = new SearchBase(Transaction::DB_TBL);
        $srch->addCondition('utxn_order_id', '=',$order);
        /*      $srch->addGroupBy('order_date_added');
                $srch->addMultipleFields(['GROUP BY order_date_added']);*/
        $res = FatApp::getDb()->fetchAll($srch->getResultSet());

        $this->set("records", $res);
        $this->_template->render();
    }


}