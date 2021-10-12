<?php

$template_1 = function($lang, $refugee, $serviceDate, $sheetCount, $devHead, $sheetCreater)
{
    $thisDate= date("d.m.Y");
	list($year, $month, $day) = explode("-", $serviceDate);
	$serviceDate = $day.'.'.$month.'.'.$year;
    $pdfBody='<style>
                    .container{
                        border: 2px solid #000;
                        padding: 20px 20px 0;
                        margin: 0 auto;
                    }
                    .little_font{
                        font-size: 10px;
                    }
                    .content{
                        width: 100%;
                        height: 100%;
                    }
                    table{
                        width: 100%;
                    }
                    .table_header{            
                        margin-bottom: 20px;
                    }
                    .table_header td{
                        text-align: center;
                    }
                    .logo_img{
                        width: 150px;
                    }
                    .text_center{
                        font-weight: 700;
                        text-align: center;
                        margin: 3px 0;
                    }
                    .table__one{
                        margin-top: 30px;
                    }        
                    .tableone_title{
                        width: 30%;
                        text-align: right;  
                        padding-top: 10px;          
                    }
                    .tableone_content{
                        padding-left: 20px;
                        width: 70%;        
                        border-bottom: 2px solid #000;
                        padding-top: 10px; 
                    }
                    .tabletwo_title{
                        width: 72%;
                        text-align: right;
                        padding-top: 15px;
                    }
                    .tabletwo_content{
                        width: 28%;
                        text-align: right;
                        padding-top: 15px;
                        padding-left: 100px;
                        border-bottom: 2px solid #000;
                    }
                    .table_three{
                        width: 100%;
                        margin: 30px auto 0;
                    
                    }
                    .tablethree_title{
                        text-align: right;
                        width: 35%;
                    }
                    .tablethree_hours{
                        width: 15%;
                        text-align: right;
                        border-top: 2px solid #000;
                        padding-left: 20px;
                    }
                    .tablethree_from{
                        width: 8%;
                        text-align: center;
                    }
                    .tablethree_hour{
                        text-align: center;
                        width: 10%;
                        border-bottom: 2px solid #000;
                    }
                    .tablethree_to{
                        text-align: center;
                    }
                    .tablethree_minute{
                        text-align: center;
                        border-bottom: 2px solid #000;
                    }
                    .table_four{
                        width: 95%;
                        margin: 0 auto;
                        margin-top: 30px;
                    }
                    .tablefour_title{
                        width: 92%;
                    }
                    .address{            
                        width: 95%;
                        margin: 30px auto;
                        font-weight: bold;
                        border-bottom: 2px solid #000;
                    }
                    .company{
                        margin: 0 auto;
                        width: 95%;
                        padding-bottom: 3px;
                    }
                    .table_five{
                        width: 95%;
                        margin: 10px auto;
                    }
                    .pashton{
                        width: 20%;
                        border-bottom: 2px solid #000;
                    }
                    .tablefive_title{
                        width: 15%;
                    }
                    .signature{
                        width: 20%;
                    }
                    .storagrutyun{
                        width: 20%;
                        border-bottom: 2px solid #000;
                    }
                    .amsativ{
                        width: 10%;
                    }
                    .date{
                        border-bottom: 2px solid #000;
                        font-weight: 700;
                    }
                    hr{
                        width: 95%;
                        border: 1px solid #000;
                        margin: 30px auto;
                    }
                    .creater{
                        width: 90%;
                        margin: 10px auto;
                    }
                    .little{
                        font-size: 12px;
                    }
                    .boldText{
                        font-weight: 700;
                    }
                </style>';
    
    $pdfBody.='<div class="container">
                <div class="content">
                        <table class="table_header">
                            <tr>
                                <td>"ԿՐԹԱՐԱՆ ՍՈՆԱ" ՍՊԸ</td>
                                <td></td>
                                <td>"KRTARAN SONA" LLC</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><img src="templates/logo/1.jpg" class="logo_img" alt=""></td>
                                <td></td>
                            </tr>
                        </table>
                        <p class="text_center">Request for Interpretation/Translation Service</p>
                        <p class="text_center">Գրավոր թարգմանչական ծառայությունների տրամադրման խնդրագիր</p>
                        <table class="table__one">
                            <tr>
                                <td class="tableone_title">Requester/Ներկայացնող</td>
                                <td class="tableone_content">Միգրացիոն ծառայություն(ՄԾ)</td>
                            </tr>
                            <tr>
                                <td class="tableone_title">Language requires/Լեզու</td>
                                <td class="tableone_content">'.$lang.', գրավոր/'.$refugee.'Ի գործով</td>
                            </tr>
                        </table>
                        <table class="table_two">
                            <tr>
                                <td class="tabletwo_title">Date Service Required/ Ծառայության տրամադրման ամսաթիվ</td>
                                <td class="tabletwo_content">'.$serviceDate.'թ</td>
                            </tr>
                        </table>
                        <table class="table_three">
                            <tr>
                                <td  class="tablethree_title">Expected Duration of Service</td>
                                <td></td>
                                <td class="tablethree_from">from</td>
                                <td class="tablethree_hour"></td>
                                <td class="tablethree_to">To/-ից</td>
                                <td class="tablethree_minute"></td>
                            </tr>
                            <tr>
                                <td class="tablethree_title"><span class="little_font">Ծառայության տրամադրման ակնկալվող տևողությունը</span>/առդիր՝ '.$sheetCount.' թերթ</td>
                                <td class="tablethree_hours">(hours/ Ժամ)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table_four">
                            <tr>
                                <td class="tablefour_title">Place Service Requested at (address, including the floor and office number)/ Վայրը․ (հասցե, ներառյալ հարկը և գրասենյակի համարը)</td>
                                <td></td>
                            </tr>
                        </table>
                        <p class="address">ք․ Երևան, Ուլնեցի 31, 2-րդ հարկ, 208 սենյակ</p>
                        <p class="company">On behalf of the Requesting Agency</p>
                        <p class="company">Խնդրագիր ներկայացնող կազմակերպության անունից</p>
                        <p class="address">Name/ Անուն Ազգանուն։  '.$devHead.'</p>
                        <table class="table_five">
                            <tr>
                                <td class="tablefive_title little">Title/</td>
                                <td class="boldText"><span class="little_font">"Ապաստանի և իրավական հարցերի"</span></td>
                                <td class="little">Signature/</td>
                                <td></td>
                                <td>Date/</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="little">Պաշտոն։</td>
                                <td class="pashton boldText"><span class="little_font">բաժնի պետ</span></td>
                                <td class="signature little">Ստորագրություն։</td>
                                <td class="storagrutyun"></td>
                                <td class="amsativ little">Ամսաթիվ։</td>
                                <td class="date boldText">'.$thisDate.'թ</td>
                            </tr>
                        </table>
                        <hr>
                        <p class="creater">Կատ․՝ '.$sheetCreater.'</p>
                </div>
            </div>
            ';
    return $pdfBody;
}

?>