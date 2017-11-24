<?php
include ('init.php');

$groups = get_groups();

echo json_encode($groups);
?>