<?php

$output = shell_exec("echo $(vmstat 1 2 | awk 'NR>3{ print 100 - $15 }')");

header('Content-Type: application/json');
echo json_encode(['cpuutilization' => floatval($output), "disk_read_bytes" => 0.0, "disk_write_bytes"=>0.0,"network_in"=>0.0,"network_out"=>0.0]);
