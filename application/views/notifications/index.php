<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
?>
    <section class="section section--grey section--page">
		<?php //$this->includeTemplate('_partial/dashboardTop.php'); ?>  
		<div class="container container--fixed">
			<div class="page-panel -clearfix">
			  <div class="page-panel__left">
	   <!--div class="tab-swticher">
			<a href="dashboard.html" class="btn btn--large is-active">Teacher</a>
			<a href="learner_dashboard.html" class="btn btn--large">Student</a>
		</div-->
				<?php $this->includeTemplate('account/_partial/dashboardNavigation.php'); ?>		
		</div>
				<div class="page-panel__right">

	  <div class="page-head">
				   <div class="d-flex justify-content-between align-items-center">
						 <div><h1><?php echo Label::getLabel('LBL_My_Notifications'); ?></h1></div>
						
					</div>
				 </div>
<div class="box -padding-20">
                             <div class="page-controls">
                                <div class="row">
                                    <aside class="col-lg-6 col-md-6 col-sm-6">
                                        <ul class="controls">
                                            <li>
                                                <span>
                                                    <label class="checkbox">
                                                        <input type="checkbox" class="check-all" ><i class="input-helper"></i>
                                                    </label>
                                                </span>
                                            </li>
                                        </ul>
                                        <ul class="controls">
                                            <li>
                                               <a href="javascript:void(0)" onclick="deleteRecords()">
                                               <span class="svg-icon">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="482.428px" height="482.429px" viewBox="0 0 482.428 482.429" style="enable-background:new 0 0 482.428 482.429;" xml:space="preserve">
    <g>
        <g>
            <path d="M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098
                c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117
                h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828
                C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879
                C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096
                c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266
                c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979
                V115.744z"></path>
            <path d="M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07
                c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z"></path>
            <path d="M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07
                c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z"></path>
            <path d="M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07
                c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z"></path>
        </g>
    </g>
    </svg></span>
                                            </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="searchNotification(document.frmNotificationSrch);">
                                                    <span class="svg-icon">
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="344.37px" height="344.37px" viewBox="0 0 344.37 344.37" style="enable-background:new 0 0 344.37 344.37;" xml:space="preserve">
    <g>
            <path d="M334.485,37.463c-6.753-1.449-13.396,2.853-14.842,9.603l-9.084,42.391C281.637,40.117,228.551,9.155,170.368,9.155
                c-89.603,0-162.5,72.896-162.5,162.5c0,6.903,5.596,12.5,12.5,12.5c6.903,0,12.5-5.597,12.5-12.5
                c0-75.818,61.682-137.5,137.5-137.5c49.429,0,94.515,26.403,118.925,68.443l-41.674-8.931c-6.752-1.447-13.396,2.854-14.841,9.604
                c-1.446,6.75,2.854,13.396,9.604,14.842l71.536,15.33c1.215,0.261,2.449,0.336,3.666,0.234c2.027-0.171,4.003-0.836,5.743-1.962
                c2.784-1.801,4.738-4.634,5.433-7.875l15.331-71.536C345.535,45.555,341.235,38.911,334.485,37.463z"></path>
            <path d="M321.907,155.271c-6.899,0.228-12.309,6.006-12.081,12.905c1.212,36.708-11.942,71.689-37.042,98.504
                c-25.099,26.812-59.137,42.248-95.844,43.46c-1.53,0.05-3.052,0.075-4.576,0.075c-47.896-0.002-92.018-24.877-116.936-65.18
                l43.447,11.65c6.668,1.787,13.523-2.168,15.311-8.837c1.788-6.668-2.168-13.522-8.836-15.312l-70.664-18.946
                c-3.202-0.857-6.615-0.409-9.485,1.247c-2.872,1.656-4.967,4.387-5.826,7.589L0.43,293.092
                c-1.788,6.668,2.168,13.522,8.836,15.311c1.085,0.291,2.173,0.431,3.245,0.431c5.518,0,10.569-3.684,12.066-9.267l10.649-39.717
                c29.624,46.647,81.189,75.367,137.132,75.365c1.797,0,3.604-0.029,5.408-0.089c43.381-1.434,83.608-19.674,113.271-51.362
                s45.209-73.031,43.776-116.413C334.586,160.453,328.805,155.026,321.907,155.271z"></path>
        </g>
    </svg>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a  href="javascript:void(0)" onclick="changeStatus(0)">
                                                    <span class="svg-icon">
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 483.3 483.3" style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve">
    <g>
            <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"></path>
            <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"></path>
        </g>
    </svg>
                                                    </span>
                                                </a>
                                            </li>
                                            <li><a  href="javascript:void(0)" onclick="changeStatus(1)">
                                                <span class="svg-icon">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="407.513px" height="407.513px" viewBox="0 0 407.513 407.513" style="enable-background:new 0 0 407.513 407.513;" xml:space="preserve">
    <g>
        <path d="M387.06,399.833c0.193-0.398,0.381-0.795,0.535-1.217c0.166-0.437,0.293-0.869,0.407-1.318
            c0.114-0.408,0.208-0.814,0.281-1.236c0.08-0.491,0.12-0.977,0.146-1.471c0.013-0.266,0.08-0.512,0.08-0.775V160.378
            c0-0.074-0.027-0.146-0.027-0.221c-0.014-0.732-0.106-1.457-0.233-2.18c-0.039-0.201-0.033-0.404-0.08-0.602
            c-0.2-0.91-0.501-1.799-0.896-2.658c-0.087-0.205-0.228-0.381-0.327-0.578c-0.321-0.633-0.67-1.258-1.098-1.84
            c-0.214-0.297-0.48-0.551-0.723-0.83c-0.32-0.367-0.603-0.758-0.963-1.1c-0.08-0.072-0.174-0.117-0.247-0.189
            c-0.073-0.061-0.12-0.137-0.188-0.197L212.671,3.3c-5.129-4.4-12.699-4.4-17.828,0L23.784,149.984
            c-0.066,0.061-0.113,0.137-0.187,0.197c-0.074,0.072-0.168,0.117-0.248,0.189c-0.361,0.342-0.642,0.732-0.963,1.1
            c-0.24,0.279-0.501,0.533-0.722,0.83c-0.429,0.582-0.776,1.207-1.097,1.84c-0.101,0.197-0.241,0.373-0.328,0.578
            c-0.395,0.859-0.695,1.748-0.896,2.658c-0.046,0.197-0.04,0.4-0.08,0.602c-0.127,0.723-0.221,1.447-0.233,2.18
            c0,0.074-0.027,0.146-0.027,0.221v233.438c0,0.256,0.067,0.493,0.08,0.747c0.027,0.498,0.067,0.99,0.147,1.48
            c0.067,0.422,0.167,0.83,0.274,1.238c0.113,0.442,0.24,0.879,0.407,1.313c0.154,0.42,0.342,0.822,0.535,1.228
            c0.194,0.387,0.395,0.77,0.622,1.143c0.254,0.416,0.542,0.799,0.836,1.185c0.16,0.206,0.274,0.436,0.448,0.637
            c0.101,0.113,0.221,0.188,0.321,0.297c0.407,0.441,0.849,0.83,1.311,1.211c0.273,0.234,0.541,0.488,0.836,0.695
            c0.494,0.348,1.022,0.629,1.564,0.907c0.288,0.15,0.555,0.337,0.856,0.466c0.668,0.291,1.377,0.498,2.1,0.684
            c0.187,0.047,0.361,0.129,0.548,0.17c0.91,0.188,1.846,0.295,2.809,0.295h342.117c0.963,0,1.898-0.104,2.81-0.295
            c0.152-0.033,0.294-0.1,0.446-0.141c0.757-0.185,1.498-0.404,2.193-0.709c0.288-0.123,0.549-0.306,0.83-0.447
            c0.548-0.285,1.083-0.568,1.584-0.92c0.295-0.211,0.562-0.465,0.843-0.699c0.455-0.375,0.896-0.756,1.291-1.188
            c0.102-0.105,0.229-0.185,0.321-0.293c0.187-0.211,0.307-0.449,0.475-0.666c0.287-0.375,0.568-0.752,0.822-1.156
            C386.665,400.616,386.865,400.228,387.06,399.833z M46.394,195.095l87.088,93.203L46.394,363.81V195.095z M204.237,263.193
            l134.039,116.925H69.398L204.237,263.193z M274.327,287.984l86.793-92.894v168.602L274.327,287.984z M203.757,31.737
            l151.291,129.734L253.67,269.968l-39.896-34.803c-2.567-2.238-5.73-3.287-8.888-3.313c-0.193-0.017-1.01-0.021-1.224,0
            c-3.149,0.02-6.312,1.062-8.881,3.286l-40.591,35.201L52.466,161.472L203.757,31.737z"></path>
    </g>
    </svg>
                                                </span>
                                            </a></li>
                                        </ul>
                                    </aside>
                                </div>
                            </div>
                         </div>				 					
					 						 
 <div id="ordersListing"><?php echo Label::getLabel('LBL_Loading..',$siteLangId); ?></div>	  
    </div>
    </div>
    </div>

  </section>