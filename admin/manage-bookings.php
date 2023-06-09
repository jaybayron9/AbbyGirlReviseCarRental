<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
}

if (isset($_GET['eid'])) {
	$eid = intval($_GET['eid']);
	$status = "2";
	$sql = $dbh->query("UPDATE tblbooking SET Status='{$status}' WHERE  id='{$eid}'");

	if ($sql) {
		$msg = "Booking Successfully Cancelled";
	} else {
		$msg = "Something Went Wrong. Please try again";
	}
}

if (isset($_GET['aeid'])) {
	$aeid = intval($_GET['aeid']);
	$status = 1;

	$sql = $dbh->query("UPDATE tblbooking SET Status = '{$status}' WHERE  id='{$aeid}'");

	if ($sql) {
		$msg = "Booking Successfully Confirmed";
	} else {
		$msg = "Something Went Wrong. Please try again";
	}
}


?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">

	<title>Car Rental Portal |Admin Manage testimonials </title>
	<link rel="shortcut icon" href="../assets/images/favicon-icon/favicon.png">
	<!-- <script src="https://cdn.tailwindcss.com"></script> -->
	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
	<style>
		.errorWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #fff;
			border-left: 4px solid #dd3d36;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
		}

		.succWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #fff;
			border-left: 4px solid #5cb85c;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
		}
	</style>

</head>

<body>
	<?php include('includes/header.php'); ?>

	<div class="ts-main-content">
		<?php include('includes/leftbar.php'); ?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Manage Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Bookings Info</div>
							<input type="date" name="sort-date" id="search_date" style="margin: 10px;">
							<button id="print" style="font-weight: bold;">Print</button>
							<div class="panel-body">
								<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>


								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th></th>
											<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Time</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

										<?php $sql = "SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id ORDER BY tblbooking.Status";
										$results = $dbh->query($sql);
										$cnt = 1;
										if ($results->num_rows > 0) {
											foreach ($results as $result) {				?>
												<tr>
													<td> <input type="checkbox" data-row-data="<?= $result['id'] ?>" id="" class="select" value="<?= $result['id'] ?>">
													</td>
													<td><?php echo htmlentities($cnt); ?></td>
													<td><?php echo htmlentities($result['FullName']); ?></td>
													<td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid); ?>"><?php echo htmlentities($result['BrandName']); ?> , <?php echo htmlentities($result['VehiclesTitle']); ?></td>
													<td><?php echo htmlentities($result['FromDate']); ?></td>
													<td><?php echo htmlentities($result['ToDate']); ?></td>
													<td><?php echo htmlentities($result['message']); ?></td>
													<td><?php
														if ($result['Status'] == 0) {
															echo htmlentities('Not Confirmed yet');
														} else if ($result['Status'] == 1) {
															echo htmlentities('Confirmed');
														} else {
															echo htmlentities('Cancelled');
														}
														?></td>
													<td><?= date('Y-m-d', strtotime($result['PostingDate'])) ?></td>
													<td><?= date('g: i a', strtotime($result['PostingDate'])) ?></td>
													<td>
														<a href="manage-bookings.php?aeid=<?php echo htmlentities($result['id']); ?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a> /
														<a href="manage-bookings.php?eid=<?php echo htmlentities($result['id']); ?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a> /
														<a data-row-data="<?= htmlentities($result['id']) ?>" class="row-print" href="#"> Print</a>
													</td>
												</tr>
										<?php $cnt = $cnt + 1;
											}
										} ?>

									</tbody>
								</table>



							</div>
						</div>



					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<!-- <script src="js/jquery.min.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script>
		$(document).ready(function() {
			var table = $('#zctb').DataTable({
				"lengthMenu": [10, 25, 50, 100, 200, 500, 1000],
				"initComplete": function() {
					$('div.dataTables_filter input').attr('maxlength', 20);
				},
				columns: [
					{ title: '<input type="checkbox" name="" id="selectAll">' },
					{ title: '#' },
					{ title: 'Name' },
					{ title: 'Vehicle' },
					{ title: 'From Date' },
					{ title: 'To Date' },
					{ title: 'Message' },
					{ title: 'Status' },
					{ title: 'Posting date' },
					{ title: 'Time' },
					{ title: 'Action' },
				]
			});

			$.fn.dataTable.ext.search.push(
				function(settings, data, dataIndex) {
					var searchDate = $('#search_date').val();
					var date = data[8]; // assuming the date is in the first column
					if (searchDate === '') {
						return true;
					}
					if (date === searchDate) {
						return true;
					}
					return false;
				}
			);

			$('#search_date').on('change', function() {
				table.draw();
			});

			$('#selectAll').click(function() {
				$('.select').not(this).prop('checked', this.checked);
			});

			$('#print').click(function() {
				var checkboxes = $('.select');
				var rowData = [];
				checkboxes.each(function() {
					if ($(this).is(':checked')) {
						var data = $(this).data('row-data');
						rowData.push(data);
					}
				});

				$.ajax({
					url: 'print.php?a=1',
					type: 'POST',
					data: {
						rowData: rowData,
						date: $('#search_date').val()
					},
					success: function(response) {
						if (response !== '1') {
							window.open('print.php?a=2', '_blank');
						} else {
							alert('Please select row(s) to print.');
						}
					}
				});
			});

			$('.row-print').click(function() {
				var id = $(this).data('row-data');
				window.open('ind-print.php?pid='+ id, '_');
			});
		})
	</script>
</body>

</html>