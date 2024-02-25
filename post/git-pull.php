<?php
require_once "inc_all.php";
$output = exec("/bin/bash /var/www/prototype.fee-simple.net/git-pull.sh");
// Handle the output or any other logic as needed
mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Git-Pull', log_action = 'Pull-Initiated', log_description = '$output via', log_ip = '$ip', log_user_agent = '$user_agent'");
?>
