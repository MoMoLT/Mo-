<?php
header("Content-Type:text/html; charset=utf-8"); 
$db = mysqli_connect("localhost", "root", " ", "momodb");
$file = fopen("logs.txt", "a+");

$c = fwrite($file, "ÄáÂê\n");
if($c)
{
echo "hh";
}else
{
echo "---";
}
?>
