<?php

    $input_xml = '<EkengInfoRequest>
    <ssn>{your ssn}</ssn>
    </EkengInfoRequest>';

    $url = "https://eth.ekeng.am/api/tax/ssn";

    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // Following line is compulsary to add as it is:
    curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlRequest=" . $input_xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $data = curl_exec($ch);
    curl_close($ch);

    print_r($data);

 ?>