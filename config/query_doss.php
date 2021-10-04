<?php

require('connect.php');
date_default_timezone_set('Asia/Yerevan');


$query_doss = "SELECT * FROM tb_doss";
$result_doss = $conn->query($query_doss);

$r103_A ="";
$r104_A ="";
$r105_A ="";
$r202_A ="";
$r202_B ="";
$r202_C ="";
$r202_D ="";
$r203_A ="";
$r203_B ="";
$r204_A ="";
$r204_B ="";
$r205_A ="";
$r205_B ="";
$r206_A ="";
$r206_B ="";
$r207_A ="";
$r207_B ="";
$r207_C ="";
$r207_D ="";
$r208_A ="";
$r208_B ="";
$r209_A ="";
$r209_B ="";
$r210_A ="";
$r210_B ="";
$r211_A ="";
$r211_B ="";
$r212_A ="";
$r212_B ="";
$r213_A ="";
$r213_B ="";
$r214_A ="";
$r214_B ="";
$r215_A ="";
$r215_B ="";
$r216_A ="";
$r216_B ="";
$r217_A ="";
$r217_B ="";
$r218_A ="";
$r218_B ="";
$r219_A ="";
$r219_B ="";
$r220_A ="";
$r220_B ="";
$r221_A ="";
$r221_B ="";
$r222_A ="";
$r222_B ="";

$r103_A_sex ="";
$r104_A_sex ="";
$r105_A_sex ="";
$r202_A_sex ="";
$r202_B_sex ="";
$r202_C_sex ="";
$r202_D_sex ="";
$r203_A_sex ="";
$r203_B_sex ="";
$r204_A_sex ="";
$r204_B_sex ="";
$r205_A_sex ="";
$r205_B_sex ="";
$r206_A_sex ="";
$r206_B_sex ="";
$r207_A_sex ="";
$r207_B_sex ="";
$r207_C_sex ="";
$r207_D_sex ="";
$r208_A_sex ="";
$r208_B_sex ="";
$r209_A_sex ="";
$r209_B_sex ="";
$r210_A_sex ="";
$r210_B_sex ="";
$r211_A_sex ="";
$r211_B_sex ="";
$r212_A_sex ="";
$r212_B_sex ="";
$r213_A_sex ="";
$r213_B_sex ="";
$r214_A_sex ="";
$r214_B_sex ="";
$r215_A_sex ="";
$r215_B_sex ="";
$r216_A_sex ="";
$r216_B_sex ="";
$r217_A_sex ="";
$r217_B_sex ="";
$r218_A_sex ="";
$r218_B_sex ="";
$r219_A_sex ="";
$r219_B_sex ="";
$r220_A_sex ="";
$r220_B_sex ="";
$r221_A_sex ="";
$r221_B_sex ="";
$r222_A_sex ="";
$r222_B_sex ="";




while ($row = $result_doss->fetch_assoc()) {
  
  if ($row['doss_id'] == '1' && $row['doss_status'] == '1') {
   $r103_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r103_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r103_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
  elseif($row['doss_id'] == '1' && $row['doss_status'] == '0')
  {
    $r103_A = '<i class="fas fa-circle-notch">0</i> ';
  }
 

   if ($row['doss_id'] == '2' && $row['doss_status'] == '1') {
   $r104_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r104_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r104_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '2' && $row['doss_status'] == '0')
  {
    $r104_A = '<i class="fas fa-circle-notch">0</i> ';
  }

   if ($row['doss_id'] == '3' && $row['doss_status'] == '1') {
   $r105_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r105_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r105_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '3' && $row['doss_status'] == '0')
  {
    $r105_A = '<i class="fas fa-circle-notch">0</i> ';
  }
  

  if ($row['doss_id'] == '8' && $row['doss_status'] == '1') {
   $r203_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r203_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r203_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '8' && $row['doss_status'] == '0')
  {
    $r203_A = '<i class="fas fa-circle-notch">0</i>';
  }


  if ($row['doss_id'] == '9' && $row['doss_status'] == '1') {
   $r203_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r203_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r203_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '9' && $row['doss_status'] == '0')
  {
    $r203_B = '<i class="fas fa-circle-notch">0</i> ';
  }



if ($row['doss_id'] == '10' && $row['doss_status'] == '1') {
   $r204_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r204_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r204_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '10' && $row['doss_status'] == '0')
  {
    $r204_A = '<i class="fas fa-circle-notch">0</i>';
  }


  if ($row['doss_id'] == '11' && $row['doss_status'] == '1') {
   $r204_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r204_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r204_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '11' && $row['doss_status'] == '0')
  {
    $r204_B = '<i class="fas fa-circle-notch">0</i> ';
  }

if ($row['doss_id'] == '12' && $row['doss_status'] == '1') {
   $r205_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r205_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r205_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '12' && $row['doss_status'] == '0')
  {
    $r205_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '13' && $row['doss_status'] == '1') {
   $r205_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r205_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r205_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '13' && $row['doss_status'] == '0')
  {
    $r205_B = '<i class="fas fa-circle-notch">0</i>';
  }


if ($row['doss_id'] == '14' && $row['doss_status'] == '1') {
   $r206_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r206_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r206_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '14' && $row['doss_status'] == '0')
  {
    $r206_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '15' && $row['doss_status'] == '1') {
   $r206_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r206_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r206_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '15' && $row['doss_status'] == '0')
  {
    $r206_B = '<i class="fas fa-circle-notch">0</i>';
  }

  
  //208
  if ($row['doss_id'] == '20' && $row['doss_status'] == '1') {
   $r208_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r208_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r208_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '20' && $row['doss_status'] == '0')
  {
    $r208_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '21' && $row['doss_status'] == '1') {
   $r208_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r208_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r208_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '21' && $row['doss_status'] == '0')
  {
    $r208_B = '<i class="fas fa-circle-notch">0</i>';
  }


 //209
  if ($row['doss_id'] == '22' && $row['doss_status'] == '1') {
   $r208_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r209_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r209_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '22' && $row['doss_status'] == '0')
  {
    $r209_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '23' && $row['doss_status'] == '1') {
   $r209_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r209_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r209_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '23' && $row['doss_status'] == '0')
  {
    $r209_B = '<i class="fas fa-circle-notch">0</i>';
  }

//210
  if ($row['doss_id'] == '24' && $row['doss_status'] == '1') {
   $r210_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r210_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r210_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '24' && $row['doss_status'] == '0')
  {
    $r210_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '25' && $row['doss_status'] == '1') {
   $r210_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r210_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r210_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '25' && $row['doss_status'] == '0')
  {
    $r210_B = '<i class="fas fa-circle-notch">0</i>';
  }

//211
  if ($row['doss_id'] == '26' && $row['doss_status'] == '1') {
   $r211_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r211_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r211_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '26' && $row['doss_status'] == '0')
  {
    $r211_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '27' && $row['doss_status'] == '1') {
   $r211_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r211_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r211_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '27' && $row['doss_status'] == '0')
  {
    $r211_B = '<i class="fas fa-circle-notch">0</i>';
  }
 
 //212
  if ($row['doss_id'] == '28' && $row['doss_status'] == '1') {
   $r212_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r212_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r212_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '28' && $row['doss_status'] == '0')
  {
    $r212_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '29' && $row['doss_status'] == '1') {
   $r212_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r212_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r212_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '29' && $row['doss_status'] == '0')
  {
    $r212_B = '<i class="fas fa-circle-notch">0</i>';
  }

  //213
  if ($row['doss_id'] == '30' && $row['doss_status'] == '1') {
   $r213_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r213_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r213_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '30' && $row['doss_status'] == '0')
  {
    $r213_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '31' && $row['doss_status'] == '1') {
   $r213_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r213_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r213_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '31' && $row['doss_status'] == '0')
  {
    $r213_B = '<i class="fas fa-circle-notch">0</i>';
  }

  //214
  if ($row['doss_id'] == '32' && $row['doss_status'] == '1') {
   $r214_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r214_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r214_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '32' && $row['doss_status'] == '0')
  {
    $r214_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '33' && $row['doss_status'] == '1') {
   $r214_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r214_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r214_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '33' && $row['doss_status'] == '0')
  {
    $r214_B = '<i class="fas fa-circle-notch">0</i>';
  }

 //215
  if ($row['doss_id'] == '34' && $row['doss_status'] == '1') {
   $r215_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r215_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r215_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '34' && $row['doss_status'] == '0')
  {
    $r215_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '35' && $row['doss_status'] == '1') {
   $r215_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r215_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r215_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '35' && $row['doss_status'] == '0')
  {
    $r215_B = '<i class="fas fa-circle-notch">0</i>';
  }

//216
  if ($row['doss_id'] == '36' && $row['doss_status'] == '1') {
   $r216_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r216_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r216_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '36' && $row['doss_status'] == '0')
  {
    $r216_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '37' && $row['doss_status'] == '1') {
   $r216_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r216_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r216_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '37' && $row['doss_status'] == '0')
  {
    $r216_B = '<i class="fas fa-circle-notch">0</i>';
  }

//217
  if ($row['doss_id'] == '38' && $row['doss_status'] == '1') {
   $r217_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r217_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r217_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '38' && $row['doss_status'] == '0')
  {
    $r217_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '39' && $row['doss_status'] == '1') {
   $r217_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r217_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r217_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '39' && $row['doss_status'] == '0')
  {
    $r217_B = '<i class="fas fa-circle-notch">0</i>';
  }

  //218
  if ($row['doss_id'] == '40' && $row['doss_status'] == '1') {
   $r218_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r218_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r218_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '40' && $row['doss_status'] == '0')
  {
    $r218_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '41' && $row['doss_status'] == '1') {
   $r218_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r218_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r218_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '41' && $row['doss_status'] == '0')
  {
    $r218_B = '<i class="fas fa-circle-notch">0</i>';
  }

//219
  if ($row['doss_id'] == '42' && $row['doss_status'] == '1') {
   $r219_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r219_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r219_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '42' && $row['doss_status'] == '0')
  {
    $r219_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '43' && $row['doss_status'] == '1') {
   $r219_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r219_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r219_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '43' && $row['doss_status'] == '0')
  {
    $r219_B = '<i class="fas fa-circle-notch">0</i>';
  }

//220
  if ($row['doss_id'] == '44' && $row['doss_status'] == '1') {
   $r220_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r220_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r220_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '44' && $row['doss_status'] == '0')
  {
    $r220_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '45' && $row['doss_status'] == '1') {
   $r220_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r220_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r220_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '45' && $row['doss_status'] == '0')
  {
    $r220_B = '<i class="fas fa-circle-notch">0</i>';
  }

//221
  if ($row['doss_id'] == '46' && $row['doss_status'] == '1') {
   $r221_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r221_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r221_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '46' && $row['doss_status'] == '0')
  {
    $r221_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '47' && $row['doss_status'] == '1') {
   $r221_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r221_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r221_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '47' && $row['doss_status'] == '0')
  {
    $r221_B = '<i class="fas fa-circle-notch">0</i>';
  }

//222
  if ($row['doss_id'] == '48' && $row['doss_status'] == '1') {
   $r222_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r222_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r222_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '48' && $row['doss_status'] == '0')
  {
    $r222_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '49' && $row['doss_status'] == '1') {
   $r222_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r222_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r222_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '49' && $row['doss_status'] == '0')
  {
    $r222_B = '<i class="fas fa-circle-notch">0</i>';
  }


// 207 family room
 if ($row['doss_id'] == '16' && $row['doss_status'] == '1') {
   $r207_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r207_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r207_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '16' && $row['doss_status'] == '0')
  {
    $r207_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '17' && $row['doss_status'] == '1') {
   $r207_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r207_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r207_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '17' && $row['doss_status'] == '0')
  {
    $r207_B = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '18' && $row['doss_status'] == '1') {
   $r207_C = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r207_C_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r207_C_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '18' && $row['doss_status'] == '0')
  {
    $r207_C = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '19' && $row['doss_status'] == '1') {
   $r207_D = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r207_D_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r207_D_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '19' && $row['doss_status'] == '0')
  {
    $r207_D = '<i class="fas fa-circle-notch">0</i>';
  }  

// 202 family room
 if ($row['doss_id'] == '4' && $row['doss_status'] == '1') {
   $r202_A = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r202_A_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r202_A_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '4' && $row['doss_status'] == '0')
  {
    $r202_A = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '5' && $row['doss_status'] == '1') {
   $r202_B = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r202_B_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r202_B_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '5' && $row['doss_status'] == '0')
  {
    $r202_B = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '6' && $row['doss_status'] == '1') {
   $r202_C = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r202_C_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r202_C_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '6' && $row['doss_status'] == '0')
  {
    $r202_C = '<i class="fas fa-circle-notch">0</i>';
  }

if ($row['doss_id'] == '7' && $row['doss_status'] == '1') {
   $r202_D = '<i class="fas fa-check"></i>';
   if ($row['doss_sex'] == 1) {
    $r202_D_sex = '<i class="fas fa-mars" style="color: blue;"></i>';
    }
    else
    {
     $r202_D_sex = '<i class="fas fa-venus" style="color: pink; "></i>'; 
    }
  }
   elseif($row['doss_id'] == '7' && $row['doss_status'] == '0')
  {
    $r202_D = '<i class="fas fa-circle-notch">0</i>';
  }  










}

?>