<?php

/*extract($_GET);
$img=$_GET["img"];
// echo "$img";

$file=fopen("name.txt","w");
$pos=0;
fseek($file,$pos);
fwrite($file,$img);
fclose($file);*/

/*$command = escapeshellcmd('activate deep');
$output = shell_exec($command);*/
$command = escapeshellcmd('python hi.py');
$output = shell_exec($command);
echo $output;


?>