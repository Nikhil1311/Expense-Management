<?php

/*extract($_GET);
$img=$_GET["img"];
// echo "$img";

$file=fopen("name.txt","w");
$pos=0;
fseek($file,$pos);
fwrite($file,$img);
fclose($file);*/
$path=getenv('PATH');

putenv("PATH=$path:/usr/local/bin");
$command = escapeshellcmd('python3 hi.py test.png');
$output = shell_exec($command);
echo $output;


?>