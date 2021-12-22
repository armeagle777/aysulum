<?php
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head" && $_SESSION['role']!=="police" && $_SESSION['role']!=="nss" && $_SESSION['role']!=="dorm"  && $_SESSION['role']!=="general")){
  exit;
}
###### FUNCTIONS ########
//Create new process 
//Send mail
// Error SQL function 
//Change header location
//Notification function




############ Create new process ###############
function addProcess($connection,$case_id,$sign_status,$user_from,$receiver_id,$comment_to,$actual,$comment_status,$successFunc, $successFuncParams)
{
    $sql_new_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) 
                        VALUES ('$case_id', '$sign_status', '$user_from', '$receiver_id', '$comment_to', '$actual', '$comment_status')";
    if($connection->query($sql_new_process) === TRUE)
    {
        $connection->close();
        call_user_func_array($successFunc, $successFuncParams);
    }else
    {
        $connection->close();
        echo sqlError($connection,$sql_new_process);
    }
}

############ Send mail ###############
function sendMail($login,$pass,$host,$port,$to,$subject,$body,$attachments)
{
    require('phpmailer/PHPMailerAutoload.php');
    //Settings in GMAIL
    // Allow less secure apps: ON,    2 Step Verification : Off
    //For GMAIL $port=587, $host='smtp.gmail.com'

    $mail = new PHPMailer;
    $mail -> isSMTP();
    $mail->Host=$host;
    $mail->Port=$port;
    $mail->SMTPAuth=true;
    $mail->SMTPSecure='tls';

    $mail->Username=$login;
    $mail->Password=$pass;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($login);
    $mail->addAddress($to);
    $mail->addReplyTo($login);
    
    $mail->isHTML(true);
    $mail->Subject=$subject;
    foreach ($attachments as $attachment) {
        $mail->addAttachment($attachment);
      }
    $mail->Body=$body;

    if(!$mail -> send())
    {
        echo 'Mail cound not be sent!';
    }else
    {
        echo 'Mail has been sent!';
    }

    $mail->smtpClose();

}



########## Error SQL function ###############
function sqlError($connection,$sqlName)
{
    $errorText = "Error: ".$sqlName."<br>".$connection->error;
    return $errorText; 
}

########## Change header location ###############
function changeLocation($page,$homepage,$locationSubdataName='',$locationSubdataValue='')
{
    $locationPage="";
    if(!empty($page))
    {
        $locationPage="?page=$page";
    }
    $locationHomepage="";
    if(!empty($homepage))
    {
        $locationHomepage="&homepage=$homepage";
    }
    $subLocation='';
    if(!empty($locationSubdataName) && !empty($locationSubdataValue))
    {
        $subLocation="&$locationSubdataName=$locationSubdataValue";
    }
    $successHeaderLocation=$locationPage.$locationHomepage.$subLocation;
    header("location: ../user.php$successHeaderLocation");
};


########## Notification function ###############
function notify($connection,$comment_subject,$comment_text,$comment_status,$comment_from,$comment_to,$case_id,$request_id,$note_type,$readed,$successFunc,$successFuncParams)
{ 
    $sql_notify =   "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `request_id`, `note_type`, `readed`) 
                    VALUES ('$comment_subject', '$comment_text', '$comment_status', '$comment_from', '$comment_to', '$case_id',NULLIF('$request_id', ''), '$note_type', '$readed') ";
    if($connection->query($sql_notify) === TRUE) {
        $connection->close();
        call_user_func_array($successFunc, $successFuncParams);
    }
    else
    {
        $connection->close();
        echo sqlError($connection,$sql_notify);
    }    
}




























?>