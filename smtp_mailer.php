<?php

function smtp_mailer($to,$subject, $msg){

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port = 587;
$mail->Host = "smtp.gmail.com";
$mail->Username = "diyorayashvi90@gmail.com";
$mail->Password = "kwwe hnqr aebr ikho";

$mail->IsHTML(true);
$mail->AddAddress($to);
$mail->SetFrom("yourgmail@gmail.com","Hotel Booking");
$mail->Subject = $subject;
$mail->Body =$msg;
$mail->Send();

}

class PHPMailer {

public $SMTPDebug;
public $SMTPAuth;
public $SMTPSecure;
public $Port;
public $Host;
public $Username;
public $Password;

private $to;
private $subject;
private $body;
private $from;

function IsSMTP(){}
function IsHTML($v){}

function AddAddress($to){
$this->to=$to;
}

function SetFrom($from,$name){
$this->from=$from;
}

function Subject($s){
$this->subject=$s;
}

function Body($b){
$this->body=$b;
}

function __set($name,$value){
$this->$name=$value;
}

function Send(){

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: ".$this->from;

mail($this->to,$this->subject,$this->body,$headers);

}

}

?>
