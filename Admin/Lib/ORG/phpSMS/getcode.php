<?php
session_start();
$UserId = "";//请在这里输入您的用户ID
$key="";//请在这里更换您的KEY
$phone=$_POST["Tel"];
if(!preg_match('/^[0-9]{11,13}$/',$phone))
{
print("error");
exit();
}
$md5=md5($UserId."0".$phone.$key);
$curlPost ='UserId='.$UserId.'&phone='.$phone.'&Md5Str='.$md5;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://221.194.44.133/SMSInterface.php');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$curlPost);
$data =trim(curl_exec($ch));
curl_close($ch);
$startlen=strpos($data, 'charset=UTF-8')+strlen('charset=UTF-8');
$str=trim(substr($data, $startlen,strlen($data)-$startlen));
if(strlen($str)>1)
{
$_SESSION["code"]=$str;
print("ok");
exit();
}
else
{
print("error");
exit();
}
?>

