<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Advance Payment</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Payment Verfication</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
                  <div class="card card-success">
                      <div class="card-header bg-success"><h3 class="card-title-new">Status of Advance Payments Application</h3></div>
                        <div class="form-horizontal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <nav>
                                                <div class="nav nav-tabs ml-4"role="tablist">
                                                    <a class="nav-item nav-link active" id="pending_button" data-toggle="tab" href="" role="tab" aria-selected="true">Pending (<?php echo count($payment_pendingList); ?>)</a>
                                                    <a class="nav-item nav-link" id="notconfirmed_button" data-toggle="tab" href="" role="tab" aria-selected="false">Not Confirmed (<?php echo count($payment_notconfirmed); ?>)</a>
                                                    <a class="nav-item nav-link" id="replied_button" data-toggle="tab" href="" role="tab"  aria-selected="false">Replied (<?php echo count($paymemt_replied); ?>)</a>
                                                    <a class="nav-item nav-link" id="confirmed_button" data-toggle="tab" href="" role="tab"  aria-selected="false">Confirmed (<?php echo count($payment_confirmed); ?>)</a>
                                                </div>
                                            </nav>


                                    <!-- Pending List -->
                                    <div class="col-sm-12 m_minus_one" id="pending_list_table">
                                        <div class="card-header"><h3 class="card-title-new">List of Pending Advance Payments Application</h3></div>
                                            <div class="form-horizontal">
                                                <table id="pending_list_table_data" class="table m-0 table-bordered table-striped table-hover">
                                                    <thead class="tablehead">
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Payment Date</th>
                                                            <th>Amount</th>
                                                            <th>Recipt No</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($payment_pendingList as $key => $value) { ?>

                                                            <tr>
                                                                <td><?php echo $value['customer_id']; ?></td>
                                                                <td><?php echo $value['transaction_date']; ?></td>
                                                                <td><?php echo $value['amount_paid']; ?></td>
                                                                <td><?php echo $value['transaction_id']; ?></td>
                                                                <td><?php echo $this->Html->link('', array('controller' => 'paymentverifications', 'action'=>'inspect_payment_fetch_id', $value['id'],7),array('class'=>'far fa-edit','title'=>'Edit')); ?></td>
                                                            </tr>

                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                <!-- Not Confirmed -->
                                <div class="col-sm-12 m_minus_one" id="notconfirm_list_table">
                                    <div class="card-header"><h3 class="card-title-new">List of Not Confirmed Advance Payments Application</h3></div>
                                        <div class="form-horizontal">
                                            <table id="notconfirm_list_table_data" class="table m-0 table-bordered table-striped table-hover">
                                                <thead class="tablehead">
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Payment Date</th>
                                                        <th>Amount</th>
                                                        <th>Recipt No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($payment_notconfirmed as $key => $value) { ?>

                                                        <tr>
                                                            <td><?php echo $value['customer_id']; ?></td>
                                                            <td><?php echo $value['transaction_date']; ?></td>
                                                            <td><?php echo $value['amount_paid']; ?></td>
                                                            <td><?php echo $value['transaction_id']; ?></td>
                                                            <td><?php echo $this->Html->link('', array('controller' => 'paymentverifications', 'action'=>'inspect_payment_fetch_id', $value['id'],7),array('class'=>'far fa-eye','title'=>'Edit')); ?></td>
                                                        </tr>

                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                <!-- Replied -->
                                <div class="col-sm-12 m_minus_one" id="replied_list_table">
                                    <div class="card-header"><h3 class="card-title-new">List of Replied Payments Application</h3></div>
                                        <div class="form-horizontal">
                                            <table id="replied_list_table_data" class="table m-0 table-bordered table-striped table-hover">
                                                <thead class="tablehead">
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Payment Date</th>
                                                        <th>Amount</th>
                                                        <th>Recipt No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($paymemt_replied as $key => $value) { ?>

                                                    <tr>
                                                        <td><?php echo $value['customer_id']; ?></td>
                                                        <td><?php echo $value['transaction_date']; ?></td>
                                                        <td><?php echo $value['amount_paid']; ?></td>
                                                        <td><?php echo $value['transaction_id']; ?></td>
                                                        <td><?php echo $this->Html->link('', array('controller' => 'paymentverifications', 'action'=>'inspect_payment_fetch_id', $value['id'],7),array('class'=>'far fa-edit','title'=>'Edit')); ?></td>
                                                    </tr>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            <!-- Confirm --> 
                            <div class="col-sm-12 m_minus_one" id="confirm_list_table">
                                <div class="card-header"><h3 class="card-title-new">List of Confirmed Advance Payments Application</h3></div>
                                    <div class="form-horizontal">
                                        <table id="confirm_list_table_data" class="table m-0 table-bordered table-striped table-hover">
                                            <thead class="tablehead">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Payment Date</th>
                                                    <th>Amount</th>
                                                    <th>Recipt No</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($payment_confirmed as $key => $value) { ?>

                                                    <tr>
                                                        <td><?php echo $value['customer_id']; ?></td>
                                                        <td><?php echo $value['transaction_date']; ?></td>
                                                        <td><?php echo $value['amount_paid']; ?></td>
                                                        <td><?php echo $value['transaction_id']; ?></td>
                                                        <td><?php echo $this->Html->link('', array('controller' => 'paymentverifications', 'action'=>'inspect_payment_fetch_id', $value['id'],7),array('class'=>'far fa-eye','title'=>'Edit')); ?></td>
                                                    </tr>

                                                    <?php } ?>
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

<?php echo $this->Html->script('advance_payment/adv_payment_verification/hide_show_for_payment_table'); ?>