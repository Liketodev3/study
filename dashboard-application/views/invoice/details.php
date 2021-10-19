<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="container container--fixed">
    <div class="page__head">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-6">
                <h1>Order details</h1>
            </div>
        </div>
    </div>
    <div class="page__body">
        <div class="page-content">
            <div id="listing" class="table-scroll">
                <table class="table table--styled table--responsive table--aligned-middle">
                    <thead>
                    <tr>
                        <th> ID </th>
                        <th> Credit </th>
                        <th> Debit </th>
                        <th> Comment </th>
                        <th> User </th>
                        <th> Date </th>
                        <!--         <td> Action</td>-->
                    </tr>
                    </thead>
                    <tbody class="promo_list_table">
                    <?php if($records){ ?>
                        <?php foreach($records as  $record): ?>
                            <?php $userData = User::getAttributesById($record['utxn_user_id'], ['user_first_name','user_last_name']);?>
                            <tr>
                                <td><?= $record['utxn_id']; ?></td>
                                <td><?= $record['utxn_credit']; ?></td>
                                <td><?= $record['utxn_debit']; ?></td>
                                <td><?= $record['utxn_comments']; ?></td>
                                <td><?php echo $userData['user_first_name'] ?> <?php echo $userData['user_last_name'] ?></td>
                                <td><?= $record['utxn_date']; ?></td>
                                <!--<td><a href="<?php /*echo CommonHelper::generateUrl('Invoice', 'details', [$val['order_id']]) */?>">show</a></td>-->
                            </tr>
                        <?php endforeach; ?>
                    <?php }else{  ?>
                        <tr class="text-center">
                            <td colspan="5">List is empty</td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>




