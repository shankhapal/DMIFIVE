<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">User Manuals</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item">
							<?php echo $this->element('other_elements/common_breadcrumbs'); ?>
							<li class="breadcrumb-item active">User Manuals</li>
						</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-12"><h4>All Mannuals</h4></div>
						<div class="col-12">
							<div class="card card-primary card-tabs">
								<div class="card-header p-0 pt-1">
									<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Applicant Manuals</a>
										</li>
									
										<?php if($userType == 'User'){ ?>
											<li class="nav-item">
												<a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Users Manuals</a>
											</li>
										<?php } ?>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content" id="custom-tabs-one-tabContent">
										<div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
											<table class="table m-0 table-bordered table-striped">
												<caption>Applicant Manauls</caption>
												<thead class="tablehead">
													<tr>
														<th>Title</th>
														<th>Manual Link</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if (!empty($getApplicantManuals)) {
														$i = 0;
														foreach ($getApplicantManuals as $each) { ?>
														<tr>
															<td><?php echo $each['manual_name'] ?></td>
															<td><a href="<?php echo $each['link'];?>" target="_blank">View Manual</a></td>
														</tr>
													<?php $i=$i+1; } }?>
												</tbody>
											</table>
										</div>
										<div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
											<table class="table m-0 table-bordered table-striped">
												<caption>DMI Manauls</caption>
												<thead class="tablehead">
													<tr>
														<th>Title</th>
														<th>Manual Link</th>
													</tr>
												</thead>
												<tbody>
														<?php
														if (!empty($getUserManuals)) {
															$i = 0;
															foreach ($getUserManuals as $each) { ?>
															<tr>
																<td><?php echo $each['manual_name'] ?></td>
																<td><a href="<?php echo $each['link'];?>" target="_blank">View Manual</a></td>
															</tr>
														<?php $i=$i+1; } }?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

