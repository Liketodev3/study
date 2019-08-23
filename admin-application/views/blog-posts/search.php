<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
		'listserial'=>Label::getLabel('LBL_Sr._No',$adminLangId),
		'post_title'=>Label::getLabel('LBL_Post_Title',$adminLangId),
		'categories' => Label::getLabel('LBL_Category',$adminLangId),
		'post_published_on' => Label::getLabel('LBL_Published_Date',$adminLangId),
		'post_published' => Label::getLabel('LBL_Post_Status',$adminLangId),
		'action' => Label::getLabel('LBL_Action',$adminLangId),
	);

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered','id'=>'post'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1?0:$pageSize*($page-1);

foreach ($arr_listing as $sn=>$row){
	$sr_no++;
	$tr = $tbl->appendElement('tr');
	if($row['post_published']==1){
		$tr->setAttribute ("id",$row['post_id']);
	}

	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'post_published_on':
				$td->appendElement('plaintext', array(), FatDate::format($row['post_published_on'] , true));
			break;
			case 'post_added_on':
				$td->appendElement('plaintext', array(), FatDate::format($row['post_added_on'] , true));
			break;
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'post_title':
				if($row['post_title']!=''){
					$td->appendElement('plaintext', array(), $row['post_title'],true);
					$td->appendElement('br', array());
					$td->appendElement('plaintext', array(), '('.$row[$key].')',true);
				}else{
					$td->appendElement('plaintext', array(), $row[$key],true);
				}				
				break;
			case 'post_published':
				$postStatusArr = applicationConstants::getBlogPostStatusArr($adminLangId);
				$td->appendElement('plaintext', array(), $postStatusArr[$row[$key]], true);
			break;
			case 'child_count':
				if($row[$key]==0){
					$td->appendElement('plaintext', array(), $row[$key], true);
				}else{
					$td->appendElement('a', array('href'=>CommonHelper::generateUrl('BlogPostCategories','index',array($row['post_id'])),'title'=>Label::getLabel('LBL_View_Categories',$adminLangId)),$row[$key] );
				}
			break;
			case 'action':
				$ul = $td->appendElement("ul",array("class"=>"actions actions--centered"));
				if($canEdit){				

					$li = $ul->appendElement("li",array('class'=>'droplink'));
					$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Label::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
              		$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
              		$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
              		$innerLiEdit=$innerUl->appendElement('li');
					$innerLiEdit->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Label::getLabel('LBL_Edit',$adminLangId),"onclick"=>"addBlogPostForm(".$row['post_id'].")"),Label::getLabel('LBL_Edit',$adminLangId), true);
					$innerLiDelete=$innerUl->appendElement('li');
					$innerLiDelete->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 'title'=>Label::getLabel('LBL_Delete',$adminLangId),"onclick"=>"deleteRecord(".$row['post_id'].")"),Label::getLabel('LBL_Delete',$adminLangId), true);
					
				}
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key], true);
			break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Label::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmSearchPaging'
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);