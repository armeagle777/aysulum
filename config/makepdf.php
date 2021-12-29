<?php
session_start();
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head" && $_SESSION['role']!=="police" && $_SESSION['role']!=="nss")){
  exit;
}
require_once 'connect.php';

//Personal page pdf generating
if(isset($_GET["case_id"]))
{
    $pers_id=$_GET["case_id"];
    // Require composer autoload
    require_once __DIR__ . '/vendor/autoload.php';
    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf();
    $case_id='';
    $application_date='';
    $arrival_date='';
    $role='';
    $bday='00';
    $bmonth='00';
    $byear='0000';    
    $gender='';
    $fname_arm='';
    $lname_arm='';
    $mname_arm='';
    $fname_eng='';
    $lname_eng='';
    $mname_eng='';
    $image='../includes/images/Blank-Profile.jpg';
    $kron='';
    $azgutyun='';
    $passport='';
    $citizenship='-';
    $mshtakan_yerkir='-';
    $citizenship_address='-';
    $mshtakan_yerkir_address='-';
    $departure_from_citizen='-';
    $departure_from_residence='-';
    $preferred_traslator_sex='Ցանկացած';
    $preferred_interviewer_sex='Ցանկացած';
    $wanted_court='<input type="checkbox" />';
    $wanted_moj='<input type="checkbox"  />';
    $illegal_border='<input type="checkbox"  />';
    $invalid='<input type="checkbox"  />';
    $pregnant='<input type="checkbox"  />';
    $seriously_ill='<input type="checkbox"  />';
    $trafficking_victim='<input type="checkbox"  />';
    $violence_victim='<input type="checkbox"  />';
    $pdfPage='  <style>
                    .sub_title {
                        font-size: 0.9em;
                        color: #707070;
                        margin-bottom: 5px;
                        padding-bottom: 5px;
                        border-bottom: solid 3px #FABB4D;
                    }
                    .pl{
                        padding-left:30px;
                    }
                    img{
                        width:100px;
                        height:100px;
                    }
                    h3{
                        text-align:center;
                        margin-bottom: 20px;
                    }
                    .checkbox{
                        text-align:center;
                    }
                    .head{
                        font-size: 0.8em;
                        color: #324157;
                        font-weight: bold;
                        padding-top: 20px;
                    }
                    .personal{
                        width: 100px;
                        border: 1px solid #ced4da;
                        padding: .25rem .5rem;
                        font-size: .875rem;
                        background-color: #e9ecef;
                        color: #495057;
                    }
                    .default{
                        background-color: #e9ecef;
                        padding: .25rem .5rem;
                        font-size: .875rem;
                        border: 1px solid #ced4da;
                        color: #495057;
                    }
                    .new{
                        width: 200px;
                        border: 1px solid #ced4da;
                        padding: .25rem .5rem;
                        font-size: .875rem;
                        background-color: #e9ecef;
                        color: #495057;
                    }
                    th{
                        border-bottom: 1px solid #000;
                    }
                    tr{
                        border-bottom: 1px solid #000;
                    }

                </style>';
    $sql_person="SELECT 
    P.personal_id, P.case_id,P.f_name_arm,P.f_name_eng,P.l_name_arm,P.l_name_eng,P.m_name_arm,P.m_name_eng,
    P.b_day,P.b_month,P.b_year,P.sex,P.role,P.image,P.citizenship,P.previous_residence,P.citizen_adr,P.residence_adr,P.departure_from_citizen,
    P.departure_from_residence,P.arrival_date,P.doc_num,P.etnicity,P.religion,P.preferred_traslator_sex,P.preferred_interviewer_sex,P.invalid,
    P.pregnant,P.seriously_ill,P.trafficking_victim,P.violence_victim,P.comment,P.illegal_border,P.transfer_moj,P.deport_prescurator,    
    C.application_date,C.case_id,
    R.role_id,R.der,
    G.religion_id,G.religion_arm,
    E.etnic_id,E.etnic_eng,
    CC.country_arm AS citizenship_country_name,
    RC.country_arm AS residence_country_name    
    FROM tb_person AS P
    INNER JOIN  tb_case AS C ON P.case_id=C.case_id
    LEFT JOIN tb_role AS R ON P.role=R.role_id
    LEFT JOIN tb_religions AS G ON P.religion= G.religion_id
    LEFT JOIN tb_etnics AS E ON P.etnicity=E.etnic_id 
    LEFT JOIN tb_country AS CC ON P.citizenship=CC.country_id 
    LEFT JOIN tb_country AS RC ON P.previous_residence=RC.country_id
    WHERE P.personal_id=$pers_id";
    $res_person=$conn->query($sql_person);
    if($res_person->num_rows > 0)
    {
        $row_person=$res_person->fetch_assoc();
        $case_id=$row_person["case_id"];
        if(!is_null($row_person["application_date"])){$application_date=$row_person["application_date"];}
        if(!is_null($row_person["arrival_date"])){$arrival_date=$row_person["arrival_date"];}
        if(!is_null($row_person["b_day"])){$bday=$row_person["b_day"];}
        if(!is_null($row_person["b_month"])){$bmonth=$row_person["b_month"];}
        if(!is_null($row_person["b_year"])){$byear=$row_person["b_year"];}
        $bdate=$byear.'-'.$bmonth.'-'.$bday;
        if(!is_null($row_person["role"])){$role=$row_person["der"];}
        if($row_person["illegal_border"] == 1){$illegal_border='<input type="checkbox" checked="checked" />';}
        if($row_person["wanted_moj"] == 1){$wanted_moj='<input type="checkbox" checked="checked" />';}
        if($row_person["wanted_court"] == 1){$wanted_court='<input type="checkbox" checked="checked" />';}
        if($row_person["invalid"] == 1){$invalid='<input type="checkbox" checked="checked" />';}
        if($row_person["pregnant"] == 1){$pregnant='<input type="checkbox" checked="checked" />';}
        if($row_person["seriously_ill"] == 1){$seriously_ill='<input type="checkbox" checked="checked" />';}
        if($row_person["trafficking_victim"] == 1){$trafficking_victim='<input type="checkbox" checked="checked" />';}
        if($row_person["violence_victim"] == 1){$violence_victim='<input type="checkbox" checked="checked" />';}
        if($row_person["preferred_traslator_sex"] == 1){$preferred_traslator_sex='Արական';}elseif($row_person["preferred_traslator_sex"] == 2){$preferred_traslator_sex='Իգական';}
        if($row_person["preferred_interviewer_sex"] == 1){$preferred_interviewer_sex='Արական';}elseif($row_person["preferred_interviewer_sex"] == 2){$preferred_interviewer_sex='Իգական';}
        if(!is_null($row_person["f_name_arm"])){$fname_arm=$row_person["f_name_arm"];}
        if(!is_null($row_person["l_name_arm"])){$lname_arm=$row_person["l_name_arm"];}
        if(!is_null($row_person["m_name_arm"])){$mname_arm=$row_person["m_name_arm"];}
        if(!is_null($row_person["role"])){$fname_eng=$row_person["f_name_eng"];}
        if(!is_null($row_person["role"])){$lname_eng=$row_person["l_name_eng"];}
        if(!is_null($row_person["role"])){$mname_eng=$row_person["m_name_eng"];}
        if(!is_null($row_person["doc_num"])){$passport=$row_person["doc_num"];}
        if(!is_null($row_person["religion"])){$kron=$row_person["religion_arm"];}
        if(!is_null($row_person["etnicity"])){$azgutyun=$row_person["etnic_eng"];}
        if(!is_null($row_person["citizen_adr"])){$citizenship_address=$row_person["citizen_adr"];}
        if(!is_null($row_person["residence_adr"])){$mshtakan_yerkir_address=$row_person["residence_adr"];}
        if(!is_null($row_person["departure_from_residence"]) && $row_person["departure_from_residence"] != ''){$departure_from_residence=$row_person["departure_from_residence"];}
        if(!is_null($row_person["departure_from_citizen"]) && $row_person["departure_from_citizen"] != ''){$departure_from_citizen=$row_person["departure_from_citizen"];}
        if($row_person["citizenship"] != 0){$citizenship=$row_person["citizenship_country_name"];}
        if($row_person["previous_residence"] != 0){$mshtakan_yerkir=$row_person["residence_country_name"];}
        if(!is_null($row_person["image"])){$image='../uploads/'.$case_id.'/'.$pers_id.'/'.$row_person["image"];}
        if(!is_null($row_person["sex"]))
        {
            if($row_person["sex"]==1){$gender='Արական';}else{$gender='Իգական';}
        }
    }
    $pdfPage.=' <h3>'.$fname_arm.' '.$lname_arm.'</h3>
                <table>
                    <tr>                        
                        <td>Գործ #</td>
                        <td class="default">'.$case_id.'</td>
                        <td>Դերը</td>
                        <td class="default">'.$role.'</td>
                        <td rowspan="3" class="pl"><img src="'.$image.'"/></td>
                    </tr>
                    <tr>
                        <td>Դիմումի ա/թ</td>
                        <td class="default">'.$application_date.'</td>
                        <td>Ծննդյան ա/թ</td>
                        <td class="default">'.$bdate.'</td>
                    </tr>
                    <tr>
                        <td>ՀՀ ժամանման ա/թ</td>
                        <td class="default">'.$arrival_date.'</td>
                        <td>սեռ</td>
                        <td class="default">'.$gender.'</td>                        
                    </tr>
                </table>
                <h5 class="sub_title">Անձնական տվյալներ</h5>  
                <table>
                    <tr>
                        <td class="head">Անուն(հայատառ)</td>                                            
                        <td class="head">Անուն(լատինատառ)</td>                        
                        <td class="head">Կրոն</td>                        
                    </tr>
                    <tr>                       
                        <td class="new">'.$fname_arm.'</td> 
                        <td class="new">'.$fname_eng.'</td>                         
                        <td class="new">'.$kron.'</td>
                    </tr>
                    <tr>
                        <td class="head">Ազգանուն(հայատառ)</td>
                        <td class="head">Ազգանուն(լատինատառ)</td>  
                        <td class="head">Ազգություն</td> 
                    </tr>
                    <tr>
                        <td class="new">'.$lname_arm.'</td>                     
                        <td class="new">'.$lname_eng.'</td>                        
                        <td class="new">'.$azgutyun.'</td>
                    </tr>
                    <tr>                    
                        <td class="head">Հայրանուն(հայատառ)</td>
                        <td class="head">Հայրանուն(լատինատառ)</td>
                        <td class="head">Անձնագիր</td>
                    </tr>
                    <tr>
                        <td class="new">'.$mname_arm.'</td>                        
                        <td class="new">'.$mname_eng.'</td>                       
                        <td class="new">'.$passport.'</td>
                    </tr>
                </table>
                <h5 class="sub_title">Քաղաքացիություն || մշտական բնակության երկիր</h5>  
                <table>
                    <tr>
                        <td class="head">Քաղաքացիություն</td>
                        <td class="head">Նախկին մշտ․ բնակ․ երկիր</td>
                    </tr>
                    <tr>
                        <td class="new">'.$citizenship.'</td>
                        <td class="new">'.$mshtakan_yerkir.'</td>
                    </tr>
                    <tr>
                        <td class="head">Հասցեն քաղ․ երկրում</td>
                        <td class="head">Հասցեն նախ․ բնակ․ երկրում</td>
                    </tr>
                    <tr>
                        <td class="new">'.$citizenship_address.'</td>
                        <td class="new">'.$mshtakan_yerkir_address.'</td>
                    </tr>
                    <tr>
                        <td class="head">Քաղ․ երկիրը լքելու ամսաթիվ</td>
                        <td class="head">Բնակ․ երկիրը լքելու ամսաթիվ</td>
                    </tr>
                    <tr>
                        <td class="new">'.$departure_from_citizen.'</td>
                        <td class="new">'.$departure_from_residence.'</td>
                    </tr>
                </table>
                <h5 class="sub_title">Հատուկ նշումներ</h5>  
                <table>
                    <tr>
                        <td class="head checkbox">Անօրինական սահմանահատում</td>
                        <td class="head checkbox">Հետախուզում (դատախազ․)</td>
                        <td class="head checkbox">Հետախուզում (դատարան)</td>
                    </tr>
                    <tr>
                        <td class="checkbox">'.$illegal_border.'</td>
                        <td class="checkbox">'.$wanted_moj.'</td>
                        <td class="checkbox">'.$wanted_court.'</td>
                    </tr>
                    <tr>
                        <td class="head checkbox">Հաշմանդամ</td>
                        <td class="head checkbox">Հղի կին</td>
                        <td class="head checkbox">Ծանր հիվանդ</td>
                        <td class="head checkbox">Թրաֆիկինգի զոհ</td>
                        <td class="head checkbox">Բռնության զոհ</td>
                    </tr>
                    <tr>
                        <td class="checkbox">'.$invalid.'</td>
                        <td class="checkbox">'.$pregnant.'</td>
                        <td class="checkbox">'.$seriously_ill.'</td>
                        <td class="checkbox">'.$trafficking_victim.'</td>
                        <td class="checkbox">'.$violence_victim.'</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="head">Նախընտրելի հարցազրուցավարի սեռը</td>
                        <td colspan="2" class="head">Նախընտրելի թարգմանչի սեռը</td>
                    </tr>
                    <tr>
                        <td  class="new">'.$preferred_interviewer_sex.'</td>
                        <td></td>
                        <td  class="new">'.$preferred_traslator_sex.'</td>
                        <td></td>
                    </tr>
                </table>';
    $sql_education="SELECT E.edu_id, E.specialization,E.institution,E.start_year,E.end_year,E.edu_lvl,L.edu_lvl AS education_level  FROM  tb_education AS E LEFT JOIN tb_edu_lvl AS L ON E.edu_lvl =L.lvl_id WHERE E.personal_id=$pers_id"    ;
    $result_education = $conn->query($sql_education);
    if($result_education->num_rows > 0)
    {        
        $pdfPage.=' <h5 class="sub_title">Կրթություն</h5>
                    <table>
                        <tr>
                            <th>Սկիզբ</th>
                            <th>Ավարտ</th>
                            <th>Կրթության մակարդակ</th>
                            <th>Մասնագիտություն</th>
                            <th>Հաստատություն</th>
                        </tr>';
        while($row_education=$result_education->fetch_assoc())
        {
            $edu_start='';
            $edu_end='';
            $edu_lvl='';
            $spec='';
            $institute='';
            if(!is_null($row_education["specialization"])){$spec=$row_education["specialization"];}
            if(!is_null($row_education["institution"])){$institute=$row_education["institution"];}
            if(!is_null($row_education["start_year"])){$edu_start=$row_education["start_year"];}
            if(!is_null($row_education["end_year"])){$edu_end=$row_education["end_year"];}
            if($row_education["edu_lvl"] != 0 ){$edu_lvl=$row_education["education_level"];}
            $pdfPage.=' <tr>
                            <td class="checkbox">'.$edu_start.'</td>
                            <td class="checkbox">'.$edu_end.'</td>
                            <td class="checkbox">'.$edu_lvl.'</td>
                            <td class="checkbox">'.$spec.'</td>
                            <td class="checkbox">'.$institute.'</td>
                        </tr>';
        }
        $pdfPage.='</table>';
    }
    $sql_job="SELECT * FROM tb_employment WHERE personal_id=$pers_id";
    $result_job=$conn->query($sql_job);
    if($result_job->num_rows > 0)
    {
        $pdfPage.=' <h5 class="sub_title">Աշխատանքային գործունեություն</h5>
        <table>
            <tr>
                <th>Սկիզբ</th>
                <th>Ավարտ</th>
                <th>Պաշտոն</th>
                <th>Կազմակերպություն</th>
            </tr>'; 
        while($row_job=$result_job->fetch_assoc())
        {
            $start_date='';
            $end_date='';
            $occupation='';
            $organization='';
            if(!is_null($row_job["start_date"])){$start_date=$row_job["start_date"];}
            if(!is_null($row_job["end_date"])){$end_date=$row_job["end_date"];}
            if(!is_null($row_job["occupation"])){$occupation=$row_job["occupation"];}
            if(!is_null($row_job["organization"])){$organization=$row_job["organization"];}
            $pdfPage.=' <tr>
                            <td class="checkbox">'.$start_date.'</td>
                            <td class="checkbox">'.$end_date.'</td>
                            <td class="checkbox">'.$occupation.'</td>
                            <td class="checkbox">'.$organization.'</td>
                        </tr>';
        }
        $pdfPage.='</table>';
    }

    $sql_cards="SELECT * FROM tb_cards WHERE personal_id=$pers_id";
    $result_cards=$conn->query($sql_cards);
    if($result_cards->num_rows>0)
    {
        $pdfPage.=' <h5 class="sub_title">Վկայականներ</h5>
        <table>
            <tr>
                <th>սերիա</th>
                <th>համար</th>
                <th>տրամադրման ա/թ</th>
                <th>վավեր է մինչև</th>
                <th>տպագրման ա/թ</th>
                <th>գործող</th>
            </tr>';
        while($row_cards=$result_cards->fetch_assoc())
        {
            $serial='';
            $card_number='';
            $issued='';
            $valid='-';
            $printed='';
            $actual_card='Այո';
            if(!is_null($row_cards["serial"])){$serial=$row_cards["serial"];}
            if(!is_null($row_cards["card_number"])){$card_number=$row_cards["card_number"];}
            if(!is_null($row_cards["issued"])){$issued=$row_cards["issued"];}
            if(!is_null($row_cards["valid"])){$valid=$row_cards["valid"];}
            if($row_cards["actual_card"] == 0){$actual_card="Ոչ";}
            if(!is_null($row_cards["printed"])){$printed=date('Y-m-d', strtotime($row_cards["printed"]));}
            $pdfPage.=' <tr>
                            <td class="checkbox">'.$serial.'</td>
                            <td class="checkbox">'.$card_number.'</td>
                            <td class="checkbox">'.$issued.'</td>
                            <td class="checkbox">'.$valid.'</td>
                            <td class="checkbox">'.$printed.'</td>
                            <td class="checkbox">'.$actual_card.'</td>
                        </tr>';
        }
        $pdfPage.='</table>';
    }

    $mpdf->WriteHTML($pdfPage);
    // Output a PDF file directly to the browser
    $fileName=$fname_arm." ".$lname_arm;
    $mpdf->Output("$fileName.pdf","D");
    exit;
}  

//Non family member person pdf generating
if(isset($_GET["non_family_person"]))
{
    $pers_id=$_GET["non_family_person"];
    // Require composer autoload
    require_once __DIR__ . '/vendor/autoload.php';
    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf();
    // Write some HTML code:
    $f_name_arm='';
    $l_name_arm='';
    $m_name_arm='';
    $f_name_eng='';
    $l_name_eng='';
    $m_name_eng='';
    $case_id='';
    $b_day='00';
    $b_month='00';
    $b_year='0000';
    $citizenship='';
    $residence='';
    $role='';
    $sex='Արական';
    $pdfPage='  <style>
                    .header_span{
                        font-size: 16px;
                        font-style: italic;
                    }
                    .personal{
                        width: 200px;
                        border: 1px solid #ced4da;
                        padding: .25rem .5rem;
                        font-size: .875rem;
                        background-color: #e9ecef;
                        color: #495057;
                    }
                    .default{
                        background-color: #e9ecef;
                        padding: .25rem .5rem;
                        font-size: .875rem;
                        border: 1px solid #ced4da;
                        color: #495057;
                    }
                    .head{
                        font-size: 0.8em;
                        color: #324157;
                        font-weight: bold;
                        padding-top: 20px;
                    }
                </style>';
    $sql_non_family_person="SELECT 
                            M.member_id,M.case_id,M.f_name_arm,M.l_name_arm,M.m_name_arm,M.f_name_eng,M.l_name_eng,M.m_name_eng,M.b_day,M.b_month,
                            M.b_year,M.sex,M.citizenship,M.residence,M.role,M.sex,
                            C.country_id,C.country_arm AS citizenship_country, 
                            R.country_id,R.country_arm AS residence_country,
                            L.role_id,L.der
                            FROM tb_members AS M
                            LEFT JOIN tb_country AS C ON M.citizenship=C.country_id
                            LEFT JOIN tb_country AS R ON M.residence=R.country_id
                            LEFT JOIN tb_role AS L ON M.role=L.role_id 
                            WHERE M.member_id = $pers_id";
    $result_non_family=$conn->query($sql_non_family_person);
    if($result_non_family->num_rows>0)
    {
        $row_non_family=$result_non_family->fetch_assoc();
        if(!is_null($row_non_family["case_id"])){$case_id=$row_non_family["case_id"];}
        if(!is_null($row_non_family["f_name_arm"])){$f_name_arm=$row_non_family["f_name_arm"];}
        if(!is_null($row_non_family["l_name_arm"])){$l_name_arm=$row_non_family["l_name_arm"];}
        if(!is_null($row_non_family["m_name_arm"])){$m_name_arm=$row_non_family["m_name_arm"];}
        if(!is_null($row_non_family["f_name_eng"])){$f_name_eng=$row_non_family["f_name_eng"];}
        if(!is_null($row_non_family["l_name_eng"])){$l_name_eng=$row_non_family["l_name_eng"];}
        if(!is_null($row_non_family["m_name_eng"])){$m_name_eng=$row_non_family["m_name_eng"];}
        if(!is_null($row_non_family["citizenship"])){$citizenship=$row_non_family["citizenship_country"];}
        if(!is_null($row_non_family["residence"])){$residence=$row_non_family["residence_country"];}
        if(!is_null($row_non_family["role"])){$role=$row_non_family["der"];}
        if(!is_null($row_non_family["b_day"])){$b_day=$row_non_family["b_day"];}
        if(!is_null($row_non_family["b_month"])){$b_month=$row_non_family["b_month"];}
        if(!is_null($row_non_family["b_year"])){$b_year=$row_non_family["b_year"];}
        $bdate=$b_year.'-'.$b_month.'-'.$b_day;
        if($row_non_family["sex"]==2){$sex='Իգական';}
    }
    $pdfPage.='
    <h5>Ապաստանի հայցում չընդգրկված ընտանիքի անդամ <span class="header_span">'.$f_name_arm.' '.$l_name_arm.'</span></h5>
    <table>
        <tr>
            <td class="head">Հայց №</td>
            <td class="head">Դերը</td>
            <td class="head">Ծննդյան ա/թ</td>
        </tr>
        <tr>
            <td class="default">'.$case_id.'</td>
            <td class="default">'.$role.'</td>
            <td class="default">'.$bdate.'</td>
        </tr>
        <tr>
            <td class="head">Անուն (հայատառ)</td>
            <td class="head">Ազգանուն (հայատառ)</td>
            <td class="head">Հայրանուն (հայատառ)</td>
        </tr>
        <tr>
            <td class="personal">'.$f_name_arm.'</td>
            <td class="personal">'.$l_name_arm.'</td>
            <td class="personal">'.$m_name_arm.'</td>
        </tr>
        <tr>
            <td class="head">Անուն (լատինատառ)</td>
            <td class="head">Ազգանուն (լատինատառ)</td>
            <td class="head">Հայրանուն (լատինատառ)</td>
        </tr>
        <tr>
            <td class="personal">'.$f_name_eng.'</td>
            <td class="personal">'.$l_name_eng.'</td>
            <td class="personal">'.$m_name_eng.'</td>
        </tr>
        <tr>
            <td class="head">Քաղաքացիություն</td>
            <td class="head">Մշտական բնակ. երկիր</td>
            <td class="head">Սեռը</td>
        </tr>
        <tr>
            <td class="default">'.$citizenship.'</td>
            <td class="default">'.$residence.'</td>
            <td class="default">'.$sex.'</td>
        </tr>
    </table>
    
    ';
    $mpdf->WriteHTML($pdfPage);
    // Output a PDF file directly to the browser
    $fileName=$f_name_arm." ".$l_name_arm;
    $mpdf->Output("$fileName.pdf","D");
    $conn->close();
    exit;
} 
  

//ID card pdf generating
if(isset($_GET["id_card"]))
{
    $pers_id=$_GET["id_card"];
    $card_id=$_GET["card_id"];
    // Require composer autoload
    require_once __DIR__ . '/vendor/autoload.php';


    // require '../includes/vendor/autoload.php';

    // // This will output the barcode as HTML output to display in the browser
    // $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    // $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);


    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [85.725, 53.975]]);
    // Write some HTML code:
    $f_name_arm='';
    $l_name_arm='';
    $sex='Ա';
    $b_day='00';
    $b_month='00';
    $b_year='0000';
    $RA_apartment='';
    $RA_street='';
    $RA_building='';

    $pdfPage='<style>
    @page{
        margin-top: 1cm;
        margin-left: 0.1cm;
        margin-right: 0.2cm;
        margin-bottom: 0.1cm;
    }
    body{
        margin:0;
        padding: 0;
    }
    table{
        margin: 0;
        font-weight: bold;
        width: 100%;
    }
    img{
        margin:0;
        
    }
    .profile_img{
        width: 2cm;
        height: 2.7cm;
    }
    .img{
        padding: 0;
        width:100%;
        height: 100px;
    }
    .pl{
        width: 100px;
    }
    .right {
        float: left;
        width: 68%;
      }
    .left {
        float: left;
        width: 29%;
    }
    .paddingTop{
        padding-top: 9px;
    }
    .paddingLittle{
        padding-top: 4px;
    }
    .addr{
      margin: 1px;
    }
    .bar, .serial{
        padding-top: 3px;
    }
    .name{
        font-size: 10px;
        width: 100%;
    }
    .littleFont{
        font-size: 9px;
    }
    .left table tr td{
        text-align: center;
    }
    </style>';
    $sql_card_person="SELECT P.personal_id , P.case_id,P.f_name_arm,P.l_name_arm,P.image,P.sex,P.b_day,P.b_month,P.b_year, 
    C.serial,C.card_number,C.issued,C.valid,
    Y.country_arm ,
    S.application_date,S.RA_street,S.RA_building,S.RA_apartment,
    M.ADM1_ARM,
    O.ADM3_ARM,
    T.ADM4_ARM
    FROM tb_person AS P
    LEFT JOIN tb_cards AS C ON P.personal_id=C.personal_id 
    LEFT JOIN tb_country AS Y ON P.citizenship=Y.country_id 
    LEFT JOIN tb_case AS S ON P.case_id=S.case_id
    LEFT JOIN tb_marz AS M ON S.RA_marz=M.marz_id
    LEFT JOIN tb_arm_com AS O ON S.RA_community=O.community_id
    LEFT JOIN tb_settlement AS T ON S.RA_settlement=T.settlement_id  
    WHERE  C.card_id = $card_id";
    $result_card=$conn->query($sql_card_person);
    if($result_card->num_rows > 0)
    {
        $row_card=$result_card->fetch_assoc();
        $case_id=$row_card["case_id"];
        $f_name_arm=$row_card["f_name_arm"];
        $l_name_arm=$row_card["l_name_arm"];
        $image=$row_card["image"];
        $serial=$row_card["serial"];
        $card_number=$row_card["card_number"];
        $b_day=$row_card["b_day"];
        $b_month=$row_card["b_month"];
        $b_year=$row_card["b_year"];
        $b_year=$row_card["b_year"];
        $country_arm=$row_card["country_arm"];
        $application_date=$row_card["application_date"];
        $ADM1_ARM=$row_card["ADM1_ARM"];
        $ADM3_ARM=$row_card["ADM3_ARM"];
        $ADM4_ARM=$row_card["ADM4_ARM"];
        $valid=$row_card["valid"];
        $issued=$row_card["issued"];
        $text=$row_card["bar"];
        $fullAddress='';
        
        if($row_card["sex"] == 2)
        {
            $sex="Ի";
        }
        if($row_card["RA_street"] != '' && !is_null($row_card["RA_street"]))
        {
            $RA_street=$row_card["RA_street"];
        }
        if($row_card["RA_building"] != '' && !is_null($row_card["RA_building"]))
        {
            $RA_building=$row_card["RA_building"];
        }
        if($row_card["RA_apartment"] != '' && !is_null($row_card["RA_apartment"]))
        {
            $RA_apartment=$row_card["RA_apartment"];
        }
        if($ADM1_ARM ==1)
        {
            $fullAddress=$ADM1_ARM.' '.$RA_street.' '.$RA_building.' '.$RA_apartment;
        }else
        {
            $fullAddress=$ADM3_ARM.' '.$ADM4_ARM.' '.$RA_street.' '.$RA_building.' '.$RA_apartment;
        }
        $pdfPage.='
                <div class="left">
                    <table>
                        <tr>
                            <td class="img"><img class="profile_img" src = "../uploads/'.$case_id.'/'.$pers_id.'/'.$image.'" /></td>
                        </tr>
                        <tr>
                            <td class="serial">'.$serial.$card_number.'</td>
                        </tr>
                        <tr>
                            <td><img class="bar" alt="barcode" src="barcode/barcode.php?codetype=Code128&size=90&text='.$text.'&print=true&sizefactor=8" /></td>
                        </tr>
                    </table>
                </div>
                <div class="right">
                    <table>
                        <tr>
                            <td class="name paddingLittle">'.$l_name_arm .'</td>
                            <td></td>                            
                        </tr>
                        <tr>
                            <td class="name paddingTop">'.$f_name_arm .'</td>  
                            <td></td>                          
                        </tr>
                        <tr>
                            <td class="littleFont paddingTop">'.$sex .'</td>                            
                            <td class="al_r littleFont paddingTop">'.$country_arm .'</td>                            
                        </tr>
                        <tr>
                            <td class="littleFont paddingTop">'.$b_day.'.'.$b_month.'.'.$b_year .'</td> 
                            <td class="al_r littleFont paddingTop pl">'.$application_date.'</td>                           
                        </tr>
                    </table>
                    <p class="addr littleFont paddingTop">'.$fullAddress.'</p>
                    <table>
                        <tr>
                            <td class="littleFont paddingTop">'.$issued.'</td>
                            <td class="pl littleFont paddingTop">'.$valid.'</td>
                        </tr>
                    </table>
                </div>';
    }else
    {
        $pdfPage.='Nothing found';
    }

    $mpdf->WriteHTML($pdfPage);
    $fileName=$f_name_arm." ".$l_name_arm;
	$mpdf->Output();
//    $mpdf->Output("$fileName.pdf","D");
    $conn->close();
    exit;
}