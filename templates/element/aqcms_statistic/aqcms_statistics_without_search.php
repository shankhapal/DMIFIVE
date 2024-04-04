<?php ?>
<div>
	<div class="col-sm-6 col-md-12 circle mb-4">
		<div class="stats-card mr-4">
			<div class="fixed-stats-card-inner box-1">
				<div class="fixed-stats-card-front">
					<a href="<?php echo $this->request->getAttribute('webroot');?>reports/primary_user_details_report">
						<span class="title-number"><?php echo $statistics_counts[0]['primary_user']; ?></span><br>
						<span class="subtitle">Primary User</span>
					</a>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-2">
				<div class="stats-card-front"><a id="test-2" href="#" onclick="openForm2(); return false;">
					<span class="title-number"><?php echo $statistics_counts[0]['firms_registered']; ?></span>
					<br><span class="subtitle">Firms Registered</span></a>
				</div>
				
				<div class="stats-card-back" id="myForm2">
					<ul class="ul">
						<!-- Firms Registered -->
						<!-- added  for change the function name show the firm register reports-->
						<!-- Done by shreeya [22-05-2023]-->
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/newly_added_firm_list_report_for_stats/<?= base64_encode(1)?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_firm_reg']; ?></a>
						</li>
						<br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/newly_added_firm_list_report_for_stats/<?= base64_encode(2)?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['pp_firm_reg']; ?></a>
						</li>
						<br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/newly_added_firm_list_report_for_stats/<?= base64_encode(3)?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lb_firm_reg']; ?></a>
						</li>
						<br>
						<li class="badge bg-light"><span class="text-primary"></span> Deleted Firms : <?php echo $statistics_counts[0]['delete_firm']; ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-3">
				<div class="stats-card-front"><a id="test-3" href="#" onclick="openForm3(); return false;">
					<span class="title-number">
						<?php echo $statistics_counts[0]['ca_ip_app_n']+$statistics_counts[0]['pp_ip_app_n']+$statistics_counts[0]['lb_ip_app_n']+$statistics_counts[0]['ca_ip_app_r']+$statistics_counts[0]['pp_ip_app_r']+$statistics_counts[0]['lb_ip_app_r']+$statistics_counts[0]['ca_ip_app_bk']+$statistics_counts[0]['pp_ip_app_bk']+$statistics_counts[0]['lb_ip_app_bk']; ?>
					</span><br><span class="subtitle">In-Process</span></a>
				</div>
				<!-- New -->
				<div class="stats-card-back" id="myForm3">
					<ul class="ul ul-margin">
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'New Application'; ?></span>
							<ul>
								<li class="ul badge bg-light mb-1">
									<!-- pass new paramer for show which type of application ("new") by shreeya on date [24-05-2023]-->
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report_for_stats/<?= base64_encode("CA"). '/' .base64_encode("new")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_ip_app_n']; ?> 
									
									</a>
								</li>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report_for_stats/<?= base64_encode("PP"). '/' .base64_encode("new")?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['pp_ip_app_n']; ?>
									</a>
								</li>
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report_for_stats/<?= base64_encode("LAB"). '/' .base64_encode("new")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lb_ip_app_n']; ?>
									</a>
								</li>
							</ul>
						</li>
						<!-- REnewal -->
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'Renewal Application'; ?></span>
							<ul>
								<!-- pass new paramer for show which type of application ("renewal") by shreeya on date [25-05-2023]-->
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_renwal_applications_report/<?= base64_encode("CA"). '/' .base64_encode("renewal")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_ip_app_r']; ?>
									</a>
								</li>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_renwal_applications_report/<?= base64_encode("PP"). '/' .base64_encode("renewal")?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['pp_ip_app_r']; ?></a>
								</li>
								<li class="ul badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_renwal_applications_report/<?= base64_encode("LAB"). '/' .base64_encode("renewal")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lb_ip_app_r']; ?></a></li>
							</ul>
						</li>
						<!-- Backlog  -->
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'Backlog Application'; ?></span>
							<ul>
								<!-- pass new paramer for show which type of application ("backlog") by shreeya on date [25-05-2023]-->
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_backlog_applications_report/<?= base64_encode("CA"). '/' .base64_encode("backlog")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_ip_app_bk']; ?>
								</a></li>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_backlog_applications_report/<?= base64_encode("PP"). '/' .base64_encode("backlog")?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['pp_ip_app_bk']; ?>
								</a></li>
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/inprocess_backlog_applications_report/<?= base64_encode("LAB"). '/' .base64_encode("backlog")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lb_ip_app_bk']; ?>
								</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- Granted Application Report -->
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-4">
				<div class="stats-card-front"><a id="test-4" href="#" onclick="openForm4(); return false;">
					<span class="title-number">
						<?php echo $statistics_counts[0]['ca_new_grant']+$statistics_counts[0]['printing_new_grant']+$statistics_counts[0]['lab_new_grant']+
															$statistics_counts[0]['ca_renew_grant']+$statistics_counts[0]['printing_renew_grant']+$statistics_counts[0]['lab_renew_grant']+
															$statistics_counts[0]['ca_bk_grant']+$statistics_counts[0]['pp_bk_grant']+$statistics_counts[0]['lb_bk_grant']; ?>
					</span><br><span class="subtitle">Granted</span></a>
				</div>
				<div class="stats-card-back" id="myForm4">
					<ul class="ul ul-margin">
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'New Application ( E-signed )'; ?></span>
							<ul>
								<!-- pass new paramer for show which type of application ("new") by shreeya on date [25-05-2023]-->
								<li class="ul badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type_for_stats/<?= base64_encode("CA"). '/' .base64_encode("new")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_new_grant']; ?></a>
								</li>
								<li class="badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type_for_stats/<?= base64_encode("PP"). '/' .base64_encode("new")?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['printing_new_grant']; ?></a>
								</li>
								<li class="ul badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type_for_stats/<?= base64_encode("LAB"). '/' .base64_encode("new")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lab_new_grant']; ?></a>
								</li>
							</ul>
						</li>
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'Renewal Application ( E-signed )'; ?></span>
							<ul>
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_renewal_grant_report/<?= base64_encode("CA")?>">
										<span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_renew_grant']; ?>
									</a>
								</li>

								<li class="badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_renewal_grant_report/<?= base64_encode("PP")?>"><span class="text-primary">
								</span> Printing Press : <?php echo $statistics_counts[0]['printing_renew_grant']; ?>
							</a>
								</li>

								<li class="ul badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_renewal_grant_report/<?= base64_encode("LAB")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lab_renew_grant']; ?></a></li>
							</ul>
						</li>
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1"><?php echo 'Backlog Application'; ?></span>
							<ul>
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/granted_backlog_applications_report/<?= base64_encode("CA")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_bk_grant']; ?>
									</a>
								</li>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/granted_backlog_applications_report/<?= base64_encode("PP")?>"><span class="text-primary"></span> Printing Press : <?php echo $statistics_counts[0]['pp_bk_grant']; ?>
									</a>
								</li>
								<li class="ul badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/granted_backlog_applications_report/<?= base64_encode("LAB")?>"><span class="text-primary"></span> Approval of Laboratory : <?php echo $statistics_counts[0]['lb_bk_grant']; ?>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- RENEWAL DUE -->
	<div class="col-sm-6 col-md-12 circle">
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-5">
				<div class="stats-card-front"><a id="test-8" href="#" onclick="openForm8(); return false;">
					<span class="title-number"><?php echo $statistics_counts[0]['ca_renewal_due']+$statistics_counts[0]['pp_renewal_due']+$statistics_counts[0]['lb_renewal_due']; ?></span>
					<br><span class="subtitle">Renewal Due</span></a>
				</div>
				<div class="stats-card-back" id="myForm8">
					<ul class="ul">
						<li class="badge bg-light mb-3">
							<a href="<?php echo $this->request->getAttribute('webroot');?>reports/renewal_due_for_ca_pp_lab/<?= base64_encode("CA")?>"><span class="text-primary"></span> CA : <?php echo $statistics_counts[0]['ca_renewal_due']; ?></a>
						</li>
						<br>
						<li class="badge bg-light mb-3">
							<a href="<?php echo $this->request->getAttribute('webroot');?>reports/renewal_due_for_ca_pp_lab/<?= base64_encode("PP")?>"><span class="text-primary"></span> Printing Permission : <?php echo $statistics_counts[0]['pp_renewal_due']; ?></a>
						</li>
						<br>
						<li class="badge bg-light mb-3">
							<a href="<?php echo $this->request->getAttribute('webroot');?>reports/renewal_due_for_ca_pp_lab/<?= base64_encode("LAB")?>"><span class="text-primary"></span> Laboratory approval : <?php echo $statistics_counts[0]['lb_renewal_due']; ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- PENDING  WITH scRUNITIZER ,io ro, ho -->
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-6">
				<div class="stats-card-front"><a id="test-5" href="#" onclick="openForm5(); return false;">
					<span class="title-number"><?php echo $statistics_counts[0]['pending_mo']+$statistics_counts[0]['pending_io']+$statistics_counts[0]['pending_ro']+$statistics_counts[0]['pending_ho']; ?></span>
					<br><span class="subtitle">Pending With</span></a>
				</div>
				<div class="stats-card-back" id="myForm5">
					<ul class="ul">
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_scrunitizer_applications_report/<?= base64_encode("MO")?>"><span class="text-primary"></span> With Scrutinizer : <?php echo $statistics_counts[0]['pending_mo']; ?></a></li><br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_scrunitizer_applications_report/<?= base64_encode("IO")?>"><span class="text-primary"></span> With IO : <?php echo $statistics_counts[0]['pending_io']; ?></a></li><br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_scrunitizer_applications_report/<?= base64_encode("RO")?>"><span class="text-primary"></span> With RO : <?php echo $statistics_counts[0]['pending_ro']; ?></a></li><br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_scrunitizer_applications_report/<?= base64_encode("HO")?>"><span class="text-primary"></span> With HO : <?php echo $statistics_counts[0]['pending_ho']; ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- Esigned Appln-->
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-7">
				<div class="stats-card-front"><a id="test-6" href="#" onclick="openForm6(); return false;">
					<span class="title-number"><?php echo $statistics_counts[0]['e_sign_app_n']+$statistics_counts[0]['e_sign_insp_n']+$statistics_counts[0]['e_sign_grantc_n']+
														$statistics_counts[0]['e_sign_app_r']+$statistics_counts[0]['e_sign_insp_r']+$statistics_counts[0]['e_sign_grantc_r']; ?>
					</span><br><span class="subtitle">E-signed</span></a>
				</div>
				<div class="stats-card-back" id="myForm6">
					<ul class="ul ul-margin">
						<li class="mb-1"><span class="text-primary"></span>
							<span class="badge bg-dark mb-2">New Application</span>
							<ul class="ul">
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/esign_new_applications_report/<?= base64_encode("APP")?>"><span class="text-primary"></span> Application : <?php echo $statistics_counts[0]['e_sign_app_n']; ?></a>
								</li><br>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/esign_new_applications_report/<?= base64_encode("INSPECT")?>"><span class="text-primary"></span> Inspection Report : <?php echo $statistics_counts[0]['e_sign_insp_n']; ?></a>
								</li><br>
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/esign_new_applications_report/<?= base64_encode("GRANT")?>"><span class="text-primary"></span> Grant Certificate : <?php echo $statistics_counts[0]['e_sign_grantc_n']; ?></a>
								</li>
							</ul>
						</li>
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1">Renewal Application</span>
							<ul class="ul">
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/esign_renewal_applications_report/<?= base64_encode("APP")?>"><span class="text-primary"> Application : <?php echo $statistics_counts[0]['e_sign_app_r']; ?></span></a>
								</li><br>
								<!-- commented by Shreeya bcoz not using this tab on date [05-06-2023] -->
								<!-- <li class="badge bg-light mb-1">
									<a href="<?php //echo $this->request->getAttribute('webroot');?>reports/esign_renewal_applications_report/<?= base64_encode("INSPECT")?>"><span class="text-primary">Inspection Report : <?php echo $statistics_counts[0]['e_sign_insp_r']; ?></span></a>
								</li><br> -->
								<li class="badge bg-light mb-1">
									<a href="<?php echo $this->request->getAttribute('webroot');?>reports/esign_renewal_applications_report/<?= base64_encode("GRANT")?>"><span class="text-primary"> Grant Certificate : <?php echo $statistics_counts[0]['e_sign_grantc_r']; ?></span></a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-8">
				<div class="stats-card-front"><a id="test-7" href="#" onclick="openForm7(); return false;">
					<span class=""><?php echo $statistics_counts[0]['total_revenue']; ?></span>
					<br><span class="subtitle">Total Revenue</span></a>
				</div>
				<div class="stats-card-back" id="myForm7">
					<!-- pass new paramer for show which type of application ("new or Renewal") by shreeya on date [07-06-2023]-->
					<ul class="ul">
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report/<?= base64_encode("New")?>"><span class="text-primary"></span> New Application Revenue : <?php echo $statistics_counts[0]['reve_app_n']; ?></a></li>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report/<?= base64_encode("Renewal")?>"><span class="text-primary"></span> Renewal Application Revenue : <?php echo $statistics_counts[0]['reve_app_r']; ?></a></li>
						<!-- Added Change Request application type by Vikas on date [18-09-2023] -->
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report/<?= base64_encode("Change Request")?>"><span class="text-primary"></span> Change Request Revenue :  
						<?php if ($statistics_counts[0]['reve_app_change'] == NULL) {
							 echo 0;
						}else { echo $statistics_counts[0]['reve_app_change']; } ?></a></li>
						<!-- Added Chemist Approval application type by Vikas on date [18-09-2023] -->
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report/<?= base64_encode("Chemist Approval")?>"><span class="text-primary"></span> Chemist Approval Revenue : <?php  if ($statistics_counts[0]['reve_app_chem'] == NULL) {
							 echo 0;
						}else { echo $statistics_counts[0]['reve_app_chem']; }?></a></li>
						<!-- Added new application type by shreeya on date [22-06-2023] -->
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report/<?= base64_encode("Advance Payment")?>"><span class="text-primary"></span> Advance Payment : <?php echo $statistics_counts[0]['reve_app_adv']; ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--<div class="dot">
			<span style="font-size: 30px;"><?php //echo $totalVisitor; ?></span>
			<br><span style="font-size: 10px;">Total Visitors</span>
		</div>-->
	</div>
</div>
