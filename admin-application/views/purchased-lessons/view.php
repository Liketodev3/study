<div class="page">
    <div class="fixed_container">
      <div class="row">
         <div class="space">
           <div class="page__title">
              <div class="row">
                  <div class="col--first col-lg-6">
                      	<span class="page__icon"><i class="ion-android-star"></i></span>
                      	<h5><?php echo Label::getLabel('LBL_Order_Detail', $adminLangId); ?></h5>
			        	<?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                  </div>
              </div>
            </div>
            <section class="section">
                <div class="sectionhead">
					<?php
						$ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
						$li = $ul->appendElement("li", array('class'=>'droplink'));
						$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
						$innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
						$innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));
						$innerLi=$innerUl->appendElement('li');
						$innerLi->appendElement('a', array('href'=>FatUtility::generateUrl('PurchasedLessons'),'class'=>'button small green redirect--js','title'=>Label::getLabel('LBL_Back_To_Purchased_Lessons', $adminLangId)), Label::getLabel('LBL_Back_To_Purchased_Lessons', $adminLangId), true);
						echo $ul->getHtml();
					?>
                    <h4><?php echo Label::getLabel('LBL_Customer_Order_Detail', $adminLangId); ?></h4>
                </div>
                <div class="sectionbody">
					<?php
						$pendingAmount  =  0;
						$totalPaidAmount =   array_sum(array_column($orderPayments, 'opayment_amount'));
						if ($totalPaidAmount < $order["order_net_amount"]) {
							$pendingAmount =  $order["order_net_amount"] - $totalPaidAmount;
						}
					?>
					<table class="table table--details">
                        <tr>
                          <td><strong><?php echo Label::getLabel('LBL_Order/Invoice_ID', $adminLangId); ?>:</strong> <?php echo $order["order_id"]; ?></td>
                          <td><strong><?php echo Label::getLabel('LBL_Order_Date', $adminLangId); ?>: </strong> <?php echo MyDate::format($order['order_date_added'], true); ?></td>
                          <td><strong><?php echo Label::getLabel('LBL_Payment_Status', $adminLangId); ?>:</strong> <?php echo Order::getPaymentStatusArr($adminLangId)[$order['order_is_paid']]?></td>
                        </tr>
                        <tr>
                          <td><strong><?php echo Label::getLabel('LBL_Order_Amount', $adminLangId); ?>: </strong> <?php echo CommonHelper::displayMoneyFormat($order["order_net_amount"], true, true); ?> </td>
                          <td><strong><?php echo Label::getLabel('LBL_Order_Amount_Paid', $adminLangId); ?>: </strong><?php echo CommonHelper::displayMoneyFormat($totalPaidAmount, true, true); ?></td>

                          <td><strong><?php echo Label::getLabel('LBL_Order_Amount_Pending', $adminLangId); ?>: </strong><?php echo CommonHelper::displayMoneyFormat($pendingAmount, true, true); ?></td>
                        </tr>
                    </table>
                </div>
            </section>
			  <div class="row row--cols-group">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Label::getLabel('Lbl_Student_Details'); ?></h4>
                            </div>
                            <div class="row space">
                                <div class="addresas-group">
                                    <h5><?php echo Label::getLabel('Lbl_Student_Details'); ?></h5>
                                    <p>
										<strong><?php echo Label::getLabel('LBL_Name'); ?> : </strong><?php echo $order['userFullName']; ?><br>
										<strong><?php echo Label::getLabel('LBL_Email'); ?> : </strong><?php echo $order['userEmail']; ?><br>                               
									</p>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Label::getLabel('Lbl_Teacher_Details'); ?></h4>
                            </div>
                            <div class="row space">
                                <div class="addresas-group">
                                    <h5><?php echo Label::getLabel('Lbl_Teacher_Details'); ?></h5>
                                    <p>
										<strong><?php echo Label::getLabel('LBL_Name'); ?> : </strong><?php echo $order['teacherFullName']; ?><br>
										<strong><?php echo Label::getLabel('LBL_Email'); ?> : </strong><?php echo $order['teacherEmail']; ?><br>                               
									</p>
                                </div>
                            </div>
                        </section>
                    </div>
					<div class="col-lg-4 col-md-4 col-sm-4">
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Label::getLabel('Lbl_Lesson_Details'); ?></h4>
                            </div>
                            <div class="row space">
                                <div class="addresas-group">
                                    <h5><?php echo Label::getLabel('Lbl_Lesson_Details'); ?></h5>
                                    <p>
										<strong><?php echo Label::getLabel('LBL_Class_Type'); ?> : </strong><?php echo ($order['op_grpcls_id'] > 0) ? Label::getLabel('LBL_GROUP_CLASS') : Label::getLabel('LBL_ONE_TO_ONE'); ?><br>

										<?php if($order['op_grpcls_id'] > 0) { ?>
										
											<strong><?php echo Label::getLabel('LBL_Name'); ?> : </strong><?php echo $order['grpcls_title']; ?><br>                               
											<strong><?php echo Label::getLabel('LBL_Start_Date_time'); ?> : </strong> <?php echo MyDate::format($order['slesson_date'].' '.$order['slesson_start_time'], true); ?><br>                               
											<strong><?php echo Label::getLabel('LBL_End_Date_time'); ?> : </strong> <?php echo MyDate::format($order['slesson_end_date'].' '.$order['slesson_end_time'], true); ?><br>                                                             
											<strong><?php echo Label::getLabel('LBL_Class_Status'); ?> : </strong> <?php echo TeacherGroupClasses::getStatusArr($adminLangId)[$order['grpcls_status']]; ?><br>                                                             
										
										<?php }else{ ?>
											<strong><?php echo Label::getLabel('LBL_Free_Trial'); ?> : </strong><?php echo ($order['op_lpackage_is_free_trial'] == applicationConstants::YES) ? Label::getLabel('LBL_YES') : Label::getLabel('LBL_NO'); ?><br> 
											<strong><?php echo Label::getLabel('LBL_NO._OF_LESSONS'); ?> : </strong><?php echo $order['op_qty']; ?><br> 
											<strong><?php echo Label::getLabel('LBL_LESSON_DURATION'); ?> : </strong><?php echo $order['op_lesson_duration'] .' '.Label::getLabel('LBL_MINS').'/'.Label::getLabel('LBL_PER_LESSON'); ?><br> 
											<strong><?php echo Label::getLabel('LBL_LESSON_PRICE'); ?> : </strong><?php echo $order['op_unit_price'] .'/'.Label::getLabel('LBL_PER_LESSON'); ?><br> 
											<strong><?php echo Label::getLabel('LBL_Admin_Commission_(%)'); ?> : </strong><?php echo $order['op_commission_percentage'] .'%'; ?><br> 
										<?php } ?>

										<strong><?php echo Label::getLabel('LBL_Teach_Language'); ?> : </strong><?php echo $order['teachLang']; ?><br> 
									</p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
		          <section class="section">
                <div class="sectionhead">
                    <h4><?php echo Label::getLabel('LBL_Giftcard_Details', $adminLangId); ?></h4>
                </div>
                <div class="sectionbody">
                <table class="table">
								<tr>
									<th>#</td>
									<th><?php echo Label::getLabel('LBL_Giftcard_Invoice_ID', $adminLangId); ?></th>
									<th><?php echo Label::getLabel('LBL_Giftcard_Code', $adminLangId); ?></th>
                  <th><?php echo Label::getLabel('LBL_Buyer_Name', $adminLangId); ?></th>
                  <th><?php echo Label::getLabel('LBL_Giftcard_Recipient_Name', $adminLangId); ?></th>
									<th><?php echo Label::getLabel('LBL_Giftcard_Amount', $adminLangId); ?></th>
					        <th><?php echo Label::getLabel('LBL_Giftcard_Exipre_Date', $adminLangId);?></th>
                  <th><?php echo Label::getLabel('LBL_Giftcard_Status', $adminLangId); ?></th>
									<th><?php echo Label::getLabel('LBL_GiftCard_Used_Date', $adminLangId); ?></th>
									<th><?php echo Label::getLabel('LBL_Total', $adminLangId); ?></th>
								</tr>
								<?php
                                $k = 1;
                                $cartTotal = 0;
                foreach ($orderProducts as $op) {
                    $cartTotal = $cartTotal + $op['op_unit_price']; ?>
								<tr>
									<td><?php echo $k; ?></td>
									<td><?php echo $op['op_invoice_number']; ?></td>
									<td><span class='giftcard'><?php echo $op['giftcard_code']; ?></span></td>
                  <td><?php echo $op['gcbuyer_name']."<br/>".$op['gcbuyer_email']; ?></td>
                  <td><?php echo $op['recipient_name']."<br/>".$op['recipient_email']; ?></td>
                  <td><?php echo CommonHelper::displayMoneyFormat($op["op_unit_price"], true, true); ?></td>
									<td><?php echo MyDate::format($op['giftcard_expiry_date']); ?></td>
                  <td><?php echo $op['giftcard_status']==1 ? Label::getLabel('LBL_GIFTCARD_USED', $adminLangId) : Label::getLabel('LBL_GIFTCARD_UNUSED', $adminLangId)  ; ?></td>
                  <td ><?php echo MyDate::format($op['giftcard_used_date']); ?></td>
                  <td ><?php echo CommonHelper::displayMoneyFormat($op["op_unit_price"], true, true); ?></td>
								</tr>
								<?php
                                $k++;
                } ?>


								<td colspan="8" class="text-right"><strong><?php echo Label::getLabel('LBL_Order_Total', $adminLangId); ?></strong></td>
									<td class="text-right" colspan="1"><strong><?php echo CommonHelper::displayMoneyFormat($order['order_net_amount'], true, true); ?></strong></td>
								</tr>
							</table>
						</div>
		       </section>
          <!--div class="row row--cols-group">
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <section class="section">
                      <div class="sectionhead">
                          <h4><?php echo Label::getLabel('LBL_Customer_Details', $adminLangId); ?></h4>
                      </div>
                      <div class="row space">
                          <div class="address-group">
                              <h5><?php echo Label::getLabel('LBL_Customer_Details', $adminLangId); ?></h5>
                              <p><strong><?php echo Label::getLabel('LBL_Name', $adminLangId); ?>: </strong><?php echo $order["buyer_user_name"]?><br><strong><?php echo Label::getLabel('LBL_Email', $adminLangId); ?>: </strong><?php echo $order['buyer_email']; ?><br><strong><?php echo Label::getLabel('LBL_Phone_Number', $adminLangId); ?>:</strong> <?php echo CommonHelper::displayNotApplicable($adminLangId, $order['buyer_phone']); ?></p>
                          </div>
                      </div>
                  </section>
              </div>

          </div-->
          <?php if (!empty($order["comments"]) && count($order["comments"])>0) {?>
          <section class="section">
              <div class="sectionhead">
                  <h4><?php echo Label::getLabel('LBL_Order_Status_History', $adminLangId); ?></h4>
              </div>
              <div class="sectionbody">
								<table class="table">
									<tbody>
										<tr>
											<th width="10%"><?php echo Label::getLabel('LBL_Date_Added', $adminLangId); ?></th>
											<th width="15%"><?php echo Label::getLabel('LBL_Customer_Notified', $adminLangId); ?></th>
											<th width="15%"><?php echo Label::getLabel('LBL_Payment_Status', $adminLangId); ?></th>
											<th width="60%"><?php echo Label::getLabel('LBL_Comments', $adminLangId); ?></th>
										</tr>
										<?php foreach ($order["comments"] as $key=>$row) {?>
										<tr>
											<td><?php echo MyDate::format($row['oshistory_date_added']);?></td>
											<td><?php echo $yesNoArr[$row['oshistory_customer_notified']];?></td>
											<td><?php echo ($row['oshistory_orderstatus_id']>0)?$orderStatuses[$row['oshistory_orderstatus_id']]:CommonHelper::displayNotApplicable($adminLangId, '');?></td>
											<td><div class="break-me"><?php echo nl2br($row['oshistory_comments']);?></div></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
                </div>
            </section>
            <?php } ?>

					<?php if (!empty($order['payments'])) {?>
					<section class="section">
						<div class="sectionhead">
							<h4><?php echo Label::getLabel('LBL_Order_Payment_History', $adminLangId); ?></h4>
						</div>
						<div class="sectionbody">
							<table class="table">
								<tbody>
									<tr>
										<th width="10%"><?php echo Label::getLabel('LBL_Date_Added', $adminLangId); ?></th>
										<th width="10%"><?php echo Label::getLabel('LBL_Txn_ID', $adminLangId); ?></th>
										<th width="15%"><?php echo Label::getLabel('LBL_Payment_Method', $adminLangId); ?></th>
										<th width="10%"><?php echo Label::getLabel('LBL_Amount', $adminLangId); ?></th>
										<th width="15%"><?php echo Label::getLabel('LBL_Comments', $adminLangId); ?></th>
										<th width="40%"><?php echo Label::getLabel('LBL_Gateway_Response', $adminLangId); ?></th>
									</tr>
									<?php foreach ($orderPayments as $key=>$row) { ?>
									<tr>
										<td><?php echo MyDate::format($row['opayment_date']);?></td>
										<td><?php echo $row['opayment_gateway_txn_id'];?></td>
										<td><?php echo $row['opayment_method'];?></td>
										<td><?php echo CommonHelper::displayMoneyFormat($row['opayment_amount'], true, true);?></td>
										<td><div class="break-me"><?php echo nl2br($row['opayment_comments']);?></div></td>
										<td><div class="break-me"><?php echo nl2br($row['opayment_gateway_response']);?></div></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</section>
					<?php }?>


					<?php if (!$order["order_is_paid"] && $canEdit) {?>
						<section class="section">
							<div class="sectionhead">
								<h4><?php echo Label::getLabel('LBL_Order_Payments', $adminLangId); ?></h4>
							</div>
							<div class="sectionbody space">
								<?php
                                $frm->setFormTagAttribute('onsubmit', 'updatePayment(this); return(false);');
                                $frm->setFormTagAttribute('class', 'web_form');
                                $frm->developerTags['colClassPrefix'] = 'col-md-';
                                $frm->developerTags['fld_default_col'] = 12;


                                $paymentFld = $frm->getField('opayment_method');
                                $paymentFld->developerTags['col'] = 4;

                                $gatewayFld = $frm->getField('opayment_gateway_txn_id');
                                $gatewayFld->developerTags['col'] = 4;

                                $amountFld = $frm->getField('opayment_amount');
                                $amountFld->developerTags['col'] = 4;

                                $submitFld = $frm->getField('btn_submit');
                                $submitFld->developerTags['col'] = 4;

                                echo $frm->getFormHtml(); ?>
							</div>
						</section>
					<?php }?>

                    </div>
                </div>
            </div>
       </div>
