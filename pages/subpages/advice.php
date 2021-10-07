<?php
    require('config/connect.php');
    //Creating translators' dropdown
    $sql_translator = "SELECT * FROM `tb_translators` WHERE active_status = 1";
    $result_translator = $conn->query($sql_translator);
    $opt_translator = '<select class="form-control form-control-md"  name="translatorCompany" id="translatorCompany">';
    while ($row_translator = $result_translator->fetch_assoc()) {
        $opt_translator.= "<option value=" . $row_translator['translator_id'] . ">" . $row_translator['translator_name_arm'] . "</option>";
    }
    $opt_translator.= '</select>';

?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <div class="advice_container">
        <h5 class="modal-title mb-3 mt-3" id="adviceModalLabel">Նոր խորհրդատվության հարցում</h5>
        <form method="POST" action="config/config.php" >
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="translatorCompany" class="label_pers_page">Թարգմանչական ընկերությունը</label>
                    <?php echo $opt_translator; ?>
                </div>
                <div class="form-group col-md-3">
                    <label for="language" class="label_pers_page">Նշել լեզուն</label>
                    <input type="text" class="form-control" id="translationLanguage" name="translationLanguage" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="translationDate" class="label_pers_page">Ընտրել ամսաթիվը</label>
                    <input type="date" id="translationDate" class="form-control" name="translationDate" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="timeFrom" class="label_pers_page">Սկիզբ։</label>
                    <input class="form-control timepicker text-center" jt-timepicker="" time="model.time" time-string="model.timeString"
                           default-time="model.options.defaultTime" time-format="model.options.timeFormat"
                           start-time="model.options.startTime" min-time="model.options.minTime"
                           max-time="model.options.maxTime" interval="model.options.interval"
                           dynamic="model.options.dynamic" scrollbar="model.options.scrollbar"
                           dropdown="model.options.dropdown" id="timeFrom" name="adviceTimeFrom" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="timeTo" class="label_pers_page">Ավարտ։</label>
                    <input class="form-control timepicker text-center" jt-timepicker="" time="model.time" time-string="model.timeString"
                           default-time="model.options.defaultTime" time-format="model.options.timeFormat"
                           start-time="model.options.startTime" min-time="model.options.minTime"
                           max-time="model.options.maxTime" interval="model.options.interval"
                           dynamic="model.options.dynamic" scrollbar="model.options.scrollbar"
                           dropdown="model.options.dropdown" id="timeTo" name="adviceTimeTo" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="sendToapproveAdvice" value="asd">Ուղարկել</button>
        </form>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        $(document).ready(function(){
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