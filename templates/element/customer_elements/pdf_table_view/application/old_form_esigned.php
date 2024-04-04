<div class="row">
	<section class="col-lg-12 connectedSortable">
		<div class="card card-info">
			<div class="card-header"><h3 class="card-title-new">Old Certificate Esigned</h3></div>
				<div class="card-body">
					<table class="table m-0 table-bordered">
						<thead class="tableHead">
							<th>Applicant Id</th>
							<th>Certificate Pdf</th>
							<th>Date</th>
						</thead>

						<tbody>
							<td><?php echo $checkOldCertEsigned['customer_id']; ?></td>
							<td><a target="_blank" href="<?php echo $checkOldCertEsigned['pdf_file']; ?>">Certificate</td>
							<td><?php echo $checkOldCertEsigned['created']; ?></td>
						</tbody>
					</table>
				</div>
			</div>
	</section>
</div>