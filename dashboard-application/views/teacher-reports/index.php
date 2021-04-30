<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script  src="https://www.google.com/jsapi"></script>
<section class="section section--grey section--page">
    <?php //$this->includeTemplate('_partial/dashboardTop.php'); ?>
        <div class="container container--fixed">
            <div class="page-panel -clearfix">
                <div class="page-panel__left">
                    <?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>
                </div>
				
                <div class="page-panel__right">
                    <div class="box__body">
                        <div class="page-head">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><h1><?php echo Label::getLabel('LBL_My_Reports'); ?></h1></div>
                            </div>
                        </div>
						
                        <div class="gap"></div>
                    </div>
                    <!-- ] -->
					
                    <span class="-gap"></span>
					
					<div class="grids-wrap">
						<div class="box -padding-20" style="margin-bottom: 30px;">
                            <div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div class="box-bordered">
											<div class="row justify-content-between align-items-center">
												<div class="col-xl-8 col-lg-7 col-md-7"><h6><?php echo Label::getLabel('LBL_Earnings'); ?></h6></div>
												<div class="col-xl-4 col-lg-5 col-md-5">
													<div class="form form--small">
														<select id="earningMonth" onchange="getStatisticalData(1)">
															<?php foreach($durationArr as $key => $duration){?>
																<option value="<?php echo $key; ?>"><?php echo $duration; ?></option>
															<?php }?>
														</select>
													</div>
												</div>
											</div>
											<div id="earningContent"></div>
										</div>
									
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
										<div class="box-bordered">
											<div class="row justify-content-between align-items-center">
												<div class="col-xl-8 col-lg-7 col-md-7"><h6><?php echo Label::getLabel('LBL_Lessons_Sold'); ?></h6></div>
												<div class="col-xl-4 col-lg-5 col-md-5">
													<div class="form form--small">
														<select id="lessonsMonth" onchange="getStatisticalData(2)">
															<?php foreach($durationArr as $key=>$duration){?>
																<option value="<?php echo $key; ?>"><?php echo $duration; ?></option>
															<?php }?>
														</select>
													</div>
												</div>
											</div>
											<div id="lessonsSold"></div>
										</div>
										
								</div>
						</div>
					</div>
                </div>
					
                    <div class="box -padding-20">
                        <div class="table-scroll">
							<h4><?php echo Label::getLabel('LBL_Reports'); ?></h4>
							<div class="page-filters"><?php //echo $frmSrch->getFormHtml(); ?></div>
                            <div id="reportListing" class="sales-graph"></div>
                        </div>
                    </div>
					
            </div>			
</section>
<div class="gap"></div>

<!-- load Google AJAX API -->																	
<script >
    function showSalesGraph(elem){
        $('.sales-graph').hide();
         $('#'+elem).show();
    }
    
    
var w = $('.graph').width();
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses', 'Style'],
          ['2013',  1000,      400],
          ['2014',  1170,      460],
          ['2015',  660,       1120],
          ['2016',  1030,      540]
        ],[
          ['Year', 'Sales', 'Expenses', 'Style'],
          ['2013',  1000,      400],
          ['2014',  1170,      460],
          ['2015',  660,       1120],
          ['2016',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          colors: ['black', 'blue']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('reportListing'));
        chart.draw(data, options);
      }
	


</script>