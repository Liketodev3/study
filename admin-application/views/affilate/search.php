<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
    <div class="sectionbody">
        <table class="table table--details">
            <thead>
            <tr>
                <td> Teacher 1st level commission </td>
                <td> Teacher 2nd level commission </td>
                <td> Student 1st level commission </td>
                <td> Student 2nd level commission</td>
            </tr>
            </thead>
            <tbody class="promo_list_table">
            <?php if($records){ ?>
                <?php foreach($records as $record): ?>
                    <tr>
                        <td><input type="number" onchange="updateAffilate(this)" step="any" name="teacher_1" min="0" max="100" value="<?= $record['teacher_1']; ?>"></td>
                        <td><input type="number" onchange="updateAffilate(this)" step="any" name="teacher_2" min="0" max="100" value="<?= $record['teacher_2']; ?>"></td>
                        <td><input type="number" onchange="updateAffilate(this)" step="any" name="student_1" min="0" max="100" value="<?= $record['student_1']; ?>"></td>
                        <td><input type="number" onchange="updateAffilate(this)" step="any" name="student_2" min="0" max="100" value="<?= $record['student_2']; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            <?php }else{  ?>
                <tr class="text-center">
                    <td colspan="4">List is empty</td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</section>
<script >

</script>

