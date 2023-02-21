<?php

include('header_priv.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9"><h2><i><b>Member Attendance Progress</b></i><h2></div>
        <div class="col-md-3" align="right">
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <table class="table table-striped table-bordered" id="dupmemberpriv_table">
          <thead>
            <tr>
              <th>Member Name</th>
              <th>SVS Code</th>
              <th>Attendance Percentage</th>
            </tr>
          </thead>
          <tbody>
            <b>You're doing very great! Keep it going!</b><br><br>
          </tbody>
        </table>
  		</div>
  	</div>
  </div>
</div>
</body>
</html>
<script>
  var dataTable = $('#dupmemberpriv_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"attendance_priv_action.php",
      type:"POST",
      data:{action:'index_fetch'}
    }
  });
</script>