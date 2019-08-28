<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<table border="1" width="80%">
	<tr>
		<th>ID</th>
		<th>Subject</th>
		<th>Sent To</th>
		<th>Sent On</th>
		<th>Action</th>
	</tr>
	<?php
	foreach( $emailsList as $email ) {
		?>
		<tr>
			<td><?php echo $email['emailarchive_id'] ?></td>
			<td><?php echo $email['emailarchive_subject'] ?></td>
			<td><?php echo $email['emailarchive_to_email'] ?></td>
			<td><?php echo $email['emailarchive_sent_on'] ?></td>
			<td><a target="_blank" href="<?php echo FatUtility::generateUrl('Emails', 'view', array( $email['emailarchive_id'] )); ?>" >View</a></td>
		</tr>
		<?php
	}
	?>
</table>
<div class="pagination">
	<?php 
	$link = FatUtility::generateUrl('Appointments', 'search', array('xxpagexx') );
	$first = "<a data-page='xxpagexx' href='".FatUtility::generateUrl('Emails','index', array('xxpagexx') )."'>First</a>";
	$last = "<a data-page='xxpagexx' href='".FatUtility::generateUrl('Emails','index', array('xxpagexx'))."'>Last</a>";
	$prev = "<a data-page='xxpagexx' href='".FatUtility::generateUrl('Emails','index', array('xxpagexx'))."'>Prev</a>";
	$next = "<a data-page='xxpagexx' href='".FatUtility::generateUrl('Emails','index', array('xxpagexx'))."'>Next</a>";
	echo FatUtility::getPageString( '<a data-page="xxpagexx" href="'.FatUtility::generateUrl('Emails','index', array('xxpagexx')).'">xxpagexx</a>', $pageCount, $page, '', '', $pageSize, $first, $last, $prev, $next );
	?>
</div>