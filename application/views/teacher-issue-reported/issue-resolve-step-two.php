<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('id', 'bankInfoFrm');
$frm->setFormTagAttribute('class','form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$selectFld = $frm->getField('issue_resolve_type');
$selectFld->setOptionListTagAttribute('class', 'listing listing--vertical listing--selection');
$frm->setFormTagAttribute('onsubmit', 'issueResolveSetupStepTwo(this); return(false);');
?>
<div class="box -padding-20">
<h4><?php echo Label::getLabel('LBL_Resolve_Issue'); ?></h4>
<blockquote class="IssueBlockquote">
	<h6> <?php echo Label::getLabel('LBL_Selected_issue(s)'); ?> </h6>
	<?php
	if ( $issueDeatils['issrep_issues_resolve'] !='' ) {
		$_resolveIssues = explode(',', $issueDeatils['issrep_issues_resolve'] );
		echo '<ul>';
		foreach( $_resolveIssues as $_rissue ) {
			echo '<li>' . $issues_options[$_rissue].'</li>';
		}
		echo '</ul>';
	}
	?>
	<hr />
	<p><?php echo nl2br($issueDeatils['issrep_resolve_comments']); ?></p>	
</blockquote>
<?php echo $frm->getFormHtml(); ?>
</div>