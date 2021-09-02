<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
    <div class="sectionhead">
        <h4>Promo list</h4>
    </div>
    <div class="sectionbody">
        <table class="table table--details">
            <thead>
            <tr>
                <td> Id </td>
                <td> Name </td>
            </tr>
            </thead>
            <tbody class="promo_list_table">
            <?php if($promo_users){ ?>
                <?php foreach($promo_users as $promo): ?>
                <tr>
                    <td><?= $promo['user_id']; ?></td>
                    <td><?= $promo['user_first_name'].' '.$promo['user_last_name']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php }else{  ?>
                <tr class="text-center">
                    <td colspan="2">Promo list is empty</td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</section>
<script >

</script>