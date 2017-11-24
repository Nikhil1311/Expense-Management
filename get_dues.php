<?php
require('init.php');

$ans = getDues();
echo json_encode($ans);
?>