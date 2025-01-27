<div class="row">
    <section class="col-lg-12 connectedSortable">
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">Surrendered Certificate Versions</h3></div>
            <div class="card-body">
                <table id="example2" class="table m-0 table-bordered">
                    <caption>Surrender Certificate</caption>
                    <thead class="tablehead">
                        <tr>
                            <th>Applicant Id</th>
                            <th>Certificate Pdf</th>
                            <th>Grant Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="boldtext"><?php echo $surrender_grant_certificate['customer_id']; ?></td>
                            <td><?php $split_file_path = explode("/",$surrender_grant_certificate['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                <a target="blank" href="<?php echo $surrender_grant_certificate['pdf_file']; ?>"><?php echo $file_name; ?></a>
                            </td>
                            <td><?php echo substr($surrender_grant_certificate['date'],0,-9); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>