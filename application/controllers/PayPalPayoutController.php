<?php

class PayPalPayoutController extends PaymentSettingsController
{
    public function callback() {
		$webhookData = file_get_contents('php://input');
		$webhookData = json_decode($webhookData, true);
		$event_type = $webhookData['event_type'];
		
		switch($event_type) {
			case "PAYMENT.PAYOUTS-ITEM.SUCCEEDED":
				$requestData = $webhookData['resource'];
				$withdrawStatus = Transaction::WITHDRAWL_STATUS_COMPLETED;
				$trxnStatus = Transaction::STATUS_COMPLETED;
				if (!$this->updatePayoutWithdrawRequest($requestData, $withdrawStatus, $trxnStatus)) {
					Message::addErrorMessage('Error');
					FatUtility::dieJsonError(Message::getHtml());
				}
			break;
			
			case "PAYMENT.PAYOUTS-ITEM.CANCELED":
				$requestData = $webhookData['resource'];
				$withdrawStatus = Transaction::WITHDRAWL_STATUS_DECLINED;
				$trxnStatus = Transaction::STATUS_DECLINED;
				if (!$this->updatePayoutWithdrawRequest($requestData, $withdrawStatus, $trxnStatus)) {
					Message::addErrorMessage('Error');
					FatUtility::dieJsonError(Message::getHtml());
				}
			break;
			
			case "PAYMENT.PAYOUTS-ITEM.DENIED": 
				$requestData = $webhookData['resource'];
				$withdrawStatus = Transaction::WITHDRAWL_STATUS_DECLINED;
				$trxnStatus = Transaction::STATUS_DECLINED;
				if (!$this->updatePayoutWithdrawRequest($requestData, $withdrawStatus, $trxnStatus)) {
					Message::addErrorMessage('Error');
					FatUtility::dieJsonError(Message::getHtml());
				}
			break;
			
			case "PAYMENT.PAYOUTS-ITEM.FAILED":
				$requestData = $webhookData['resource'];
				$withdrawStatus = Transaction::WITHDRAWL_STATUS_PAYOUT_FAILED;
				$trxnStatus = Transaction::STATUS_DECLINED;
				if (!$this->updatePayoutWithdrawRequest($requestData, $withdrawStatus, $trxnStatus)) {
					Message::addErrorMessage('Error');
					FatUtility::dieJsonError(Message::getHtml());
				}
			break;
			
		}
	}
	
	public function updatePayoutWithdrawRequest($requestData, $status, $trxnStatus) {
		if (empty($requestData)) {
			return false;
		}
		$transaction_id = $requestData['transaction_id'];
		$payout_item_id = $requestData['payout_item_id'];
		$payout_batch_id = $requestData['payout_batch_id'];
		$sender_batch_id = $requestData['sender_batch_id'];
		$arryId = explode('_', $sender_batch_id);
		$withdrawalId = end($arryId);
		$withdrawalId = FatUtility::int($withdrawalId);
		$response = array(
			'transaction_id' => $transaction_id,
			'payout_item_id' => $payout_item_id,
			'payout_batch_id' => $payout_batch_id,
			'sender_batch_id' => $sender_batch_id
		);
		
		$assignFields = array(
			'withdrawal_status'=> $status, 
			'withdrawal_response'=> json_encode($response)
		);
		
		if (!FatApp::getDb()->updateFromArray(User::DB_TBL_USR_WITHDRAWAL_REQ, $assignFields, array('smt'=>'withdrawal_id=?', 'vals'=>array($withdrawalId)))) {
			return false;
		}
		FatApp::getDb()->updateFromArray(Transaction::DB_TBL, array("utxn_status"=>$trxnStatus), 
            array('smt'=>'utxn_withdrawal_id=?','vals'=>array($withdrawalId)));
		
		return true;
	}
	
}
