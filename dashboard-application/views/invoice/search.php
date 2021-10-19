<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
    <table class="table table--styled table--responsive table--aligned-middle">
        <thead>
        <tr>
            <th> Invoice ID </th>
            <th> Order id </th>
            <th> Order status </th>
            <th> Amount</th>
            <th> Currency</th>
            <th> Action</th>
        </tr>
        </thead>
        <tbody class="promo_list_table">
        <?php if($records){ ?>
            <?php foreach($records as $key => $record): ?>

                <?php foreach($record as $val): ?>
                    <tr>
                        <td><?= 'IN_'.$val['order_user_id'].'_'.strtotime($key); ?></td>
                        <td><?= $val['order_id']; ?></td>
                        <td><?= $val['order_is_paid']; ?></td>
                        <td><?= $val['order_net_amount']; ?></td>
                        <td><?= $val['order_currency_code']; ?></td>
                        <td>
                            <a href="<?php echo CommonHelper::generateUrl('Invoice', 'details', [$val['order_id']]) ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--messaging">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.yo-coach.svg#view'; ?>"></use>
                                </svg>
                                <!--           <div class="tooltip tooltip--top bg-black"><?php /*echo Label::getLabel('LBL_Message'); */?></div>-->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php endforeach; ?>
        <?php }else{  ?>
            <tr class="text-center">
                <td colspan="6">List is empty</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

