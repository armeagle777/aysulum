<?php
    if (!isset($_SESSION['username']) || ($_SESSION['role'] !== "admin" && $_SESSION['role'] !== "operator" && $_SESSION['role'] !== "statist" && $_SESSION['role'] !== "viewer" && $_SESSION['role'] !== "lawyer" && $_SESSION['role'] !== 'nss' && $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police' && $_SESSION['role'] !== "officer" && $_SESSION['role'] !== "devhead" && $_SESSION['role'] !== "coispec" && $_SESSION['role'] !== "head" && $_SESSION['role']!=="general") ) {
        header("location: ../index.php");
    }

    $user = $_SESSION['user_id'];

    //get homepage
    if (isset($_GET['homepage']) && file_exists('pages/subpages/' . $_GET['homepage'] . '.php')) {
        $homepage = htmlspecialchars($_GET['homepage']);
    } else {
        $homepage = 'ended';
    }
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<div class="topnav2" id="myTopnav2">
    <div class="dem">
        <div id="left"></div>
        <div id="bungie">
            <ul>
                <li>
                    <a href="?page=archive&homepage=ended" <?php if ($homepage == "ended") { ?> class="active active_subpage" <?php } ?> ><i
                                class="fas fa-envelope"></i> Ավարտված գործեր</a></li>
                <?php
                    if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head') {
                        ?>
                        <li>
                            <a href="?page=archive&homepage=old_cases" <?php if ($homepage == "old_cases") { ?> class="active_subpage" <?php } ?> ><i
                                        class="fa fa-inbox" aria-hidden="true"></i> Արխիվացված գործեր </a>
                        </li>
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


