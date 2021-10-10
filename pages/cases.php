<?php
	if (!isset($_SESSION['username']) || ($_SESSION['role'] !== "admin" && $_SESSION['role'] !== "operator" && $_SESSION['role'] !== "statist" && $_SESSION['role'] !== "viewer" && $_SESSION['role'] !== "lawyer" && $_SESSION['role'] !== 'nss' && $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police' && $_SESSION['role'] !== "officer" && $_SESSION['role'] !== "devhead" && $_SESSION['role'] !== "coispec" && $_SESSION['role'] !== "head")) {
		header("location: ../index.php");
	}

	$user = $_SESSION['user_id'];

	//get homepage
	if (isset($_GET['homepage']) && file_exists('pages/subpages/' . $_GET['homepage'] . '.php')) {
		$homepage = htmlspecialchars($_GET['homepage']);
	} else {
		$homepage = 'notifications';
	}
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<div class="topnav2" id="myTopnav2">
	<div class="dem">
		<div id="left"></div>
		<div id="bungie">
			<ul>
				<li>
					<a href="?page=cases&homepage=notifications" <?php if ($homepage == "notifications") { ?> class="active active_subpage" <?php } ?> ><i
								class="fas fa-envelope"></i> Նամականի </a></li>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head') {
						?>
						<li>
							<a href="?page=cases&homepage=inbox" <?php if ($homepage == "inbox") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-inbox" aria-hidden="true"></i> Մտից </a>

							<div class="notify_circle">
								<span class="label label-pill label-danger count_inbox"></span>
							</div>
						</li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'head') {
						?>
						<li>
							<a href="?page=cases&homepage=outbox" <?php if ($homepage === "outbox") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-paper-plane" aria-hidden="true"></i> Ելից </a></li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'coispec') {
						?>
						<li>
							<a href="?page=cases&homepage=coi" <?php if ($homepage == "coi") { ?> class="active_subpage" <?php } ?> ><i
										class="fas fa-info"></i> ԾԵՏ հարցումներ </a></li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'police' || $_SESSION['role'] === 'nss' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec') {
						?>
						<li>
							<a href="?page=cases&homepage=body" <?php if ($homepage == "body") { ?> class="active_subpage" <?php } ?> ><i
										class="fas fa-envelope-open-text"></i> Հարցումներ </a>
							<div class="notify_circle">
								<span class="label label-pill label-danger count_request"></span>
							</div>

						</li>
						<?php
					}
				?>


				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'viewer' || $_SESSION['role'] === 'coispec') {
						?>
						<li>
							<a href="?page=cases&homepage=draft" <?php if ($homepage == "draft") { ?> class="active_subpage" <?php } ?> ><i
										class="fab fa-firstdraft"></i> Նախագծեր </a>
							<div class="notify_circle">
								<span class="label label-pill label-danger count_draft"></span>
							</div>
						</li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'dorm' || $_SESSION['role'] === 'devhead') {
						?>
						<li>
							<a href="?page=cases&homepage=orders" <?php if ($homepage == "orders") { ?> class="active_subpage" <?php } ?> >
								<i class="fas fa-book-open"></i> Ուղեգրեր</a>
							<div class="notify_circle">
								<span class="label label-pill label-danger count_orders"></span>
							</div>
						</li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') {
						?>
						<li>
							<a href="?page=cases&homepage=my_cases" <?php if ($homepage === "my_cases") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-tasks" aria-hidden="true"></i> Իմ վարույթում </a></li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer') {
						?>
						<li>
							<a href="?page=cases&homepage=devcurrent" <?php if ($homepage == "devcurrent") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-tasks" aria-hidden="true"></i> Բաժնի ընթացիկ </a></li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') {
						?>
						<li>
							<a href="?page=cases&homepage=bordercross" <?php if ($homepage == "bordercross") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-tasks" aria-hidden="true"></i> Հատուկ գործեր </a></li>

						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator') {
						?>
						<li>
							<a href="?page=cases&homepage=currentcourt" <?php if ($homepage == "currentcourt") { ?> class="active_subpage" <?php } ?> ><i
										class="fas fa-balance-scale-right"></i> Ընթացիկ դատական </a></li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head') {
						?>
						<li>
							<a href="?page=cases&homepage=staffstat" <?php if ($homepage == "staffstat") { ?> class="active_subpage" <?php } ?> ><i
										class="fa fa-tasks" aria-hidden="true"></i> Աշխատակիցներ </a></li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'nss' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'coispec') {
						?>
						<li>
							<a href="?page=cases&homepage=aysulum" <?php if ($homepage == "aysulum") { ?> class="active_subpage" <?php } ?> ><i
										class="fas fa-users"></i> Ապաստան հայցողներ</a></li>
						<?php
					}
				?>
				<?php
					if ($_SESSION['role'] === 'admin') {
						?>
						<li>
							<a href="?page=cases&homepage=idstat" <?php if ($homepage == "idstat") { ?> class="active_subpage" <?php } ?> ><i
										class="fas fa-id-card"></i> Քարտերի վիճակագրություն</a></li>
						<?php
					}
				?>



				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'dorm') {
						?>
						<li>
							<a href="?page=cases&homepage=rooms" <?php if ($homepage == "rooms") { ?> class="active_subpage" <?php } ?> >
								<i class="far fa-building"></i> Կացարան</a></li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator') {
						?>
						<li><a href="?page=cases&homepage=interview"><i class="fas fa-microphone"></i> Հարցազրույց</a>
						</li>
						<li><a href="?page=cases&homepage=advice"><i class="fa fa-phone"></i> Խորհրդատվություն</a></li>
						<?php
					}
				?>

				<?php
					if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator') {
						?>
						<li><a href="?page=cases&homepage=new_case" style="color: green;"><i class="fa fa-plus"
						                                                                     aria-hidden="true"></i> Նոր
								գործ</a></li>
						<?php
					}
				?>
			</ul>
		</div>
		<div id="right"></div>
	</div>


	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	<script>
		$(document).ready(function () {
			$('.timepicker').timepicker({
				timeFormat: 'H:mm ',
				interval: 05,
				minTime: '09',
				maxTime: '6:00pm',
				defaultTime: '11',
				startTime: '09:00',
				dynamic: false,
				dropdown: true,
				scrollbar: true
			});
		})
	</script>


</div>
<div class="new_case">
	<?php include_once('subpages/' . $homepage . '.php'); ?>
</div>
<script src="pages/bungie.js"></script>

<script>
	$(document).ready(function () {
		$(document).on("click", "#send_approve_translate", function () {
			var translationType = $('#translation_type_select').val();
			if (translationType == null) {
				return false;
			} else if (translationType == 2) {
				var selectedFiles = $("#file_content input:checked").length;
				if (selectedFiles == 0) {
					return false;
				}
			} else if (translationType == 3 || translationType == 4) {
				var timeFrom = $("#timeFrom").val().trim();
				var timeTo = $("#timeTo").val().trim();
				if(timeFrom == '' || timeTo == ''){
					return false;
				}
			}

		})

		$(document).on('change', '#translation_type_select', function () {
			var translationType = $(this).val();
			if (translationType == 2) {
				$("#file_content").show();
				$("#timeFromDiv").hide();
				$("#timeToDiv").hide();
			} else if (translationType == 3) {
				$("#file_content").hide();
				$("#timeFromDiv").show();
				$("#timeToDiv").show();
			} else if (translationType == 4) {
				$("#file_content").hide();
				$("#timeFromDiv").show();
				$("#timeToDiv").show();
			}
		})


		$(document).ready(function () {
			$(".dropdown7").hover(function () {
				$("#fafadown").toggleClass("fa-caret-down").toggleClass("fa-caret-up");
			})

		})


		function load_unseen_requests(request = '') {
			var user = '<?php echo $u_id;?>'
			$.ajax({
				url: "fetch.php",
				method: "POST",
				data: {requests: request, user: user},
				dataType: "json",
				success: function (data) {

					if (data.unseen_requests > 0) {
						$('.count_request').html(data.unseen_requests);
					}
				}
			});
		}

		load_unseen_requests();


		function load_unseen_inbox(inboxes = '') {
			var user = '<?php echo $u_id;?>'
			$.ajax({
				url: "fetch.php",
				method: "POST",
				data: {inboxes: inboxes, user: user},
				dataType: "json",
				success: function (data) {

					if (data.unseen_inbox > 0) {
						$('.count_inbox').html(data.unseen_inbox);
					}
				}
			});
		}

		load_unseen_inbox();


		function load_unseen_draft(drafts = '') {
			var user = '<?php echo $u_id;?>'
			$.ajax({
				url: "fetch.php",
				method: "POST",
				data: {drafts: drafts, user: user},
				dataType: "json",
				success: function (data) {

					if (data.unseen_draft > 0) {
						$('.count_draft').html(data.unseen_draft);
					}
				}
			});
		}

		load_unseen_draft();

		function load_unseen_orders(orders = '') {
			var user = '<?php echo $u_id;?>'
			$.ajax({
				url: "fetch.php",
				method: "POST",
				data: {orders: orders, user: user},
				dataType: "json",
				success: function (data) {

					if (data.unseen_order > 0) {
						$('.count_orders').html(data.unseen_order);
					}
				}
			});
		}

		load_unseen_orders();

	});


</script>



