
	<!-- 
	* The script implemented by Shankhpal calculates the pending work that has remained 
	*	incomplete for more than 5 days. It utilizes an Ajax function to retrieve and display a list of the pending tasks.
	* @version 23rd June 2023
	 -->
<!-- Modal -->

<div class="modal fade" id="myPendingWorkModel" tabindex="-1" role="dialog" aria-labelledby="myPendingWorkModelLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content myPendingWorkModel-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myPendingWorkModelLabel">List of Pending Applications: More than 5 Working Days, of <?php echo $_SESSION["f_name"];?> <?php echo $_SESSION["l_name"];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Application Type</th>
              <th>Application Id</th>
              <th>process</th>
            </tr>
          </thead>
          <tbody id="myPendingWorkModelBody">
            <!-- Table rows will be dynamically inserted here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php 
if (!isset($_SESSION['pendingwork']) || $_SESSION['pendingwork'] == null) {
  echo $this->Html->script('dashboard/toDisplay5DaysPendingWork');
  echo $this->Html->css('dashboard/all_pending_work_list_view');
}else{
  echo $this->Html->script('dashboard/onclickpendingwork');
}
?>