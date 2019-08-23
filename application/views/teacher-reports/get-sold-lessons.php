<h2 class="-color-secondary"><?php echo $soldLessons['lessonCount']." Lessons" ; ?></h2> <?php if($soldLessons['fromDate']){
	echo $soldLessons['fromDate']. ' - ' .$soldLessons['toDate'];
} ?>