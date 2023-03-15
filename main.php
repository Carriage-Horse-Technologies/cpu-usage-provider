<?php

function get_cpu_usage() {
    $load = sys_getloadavg();
    $usage = $load[0];
    return $usage;
}

header('Content-Type: application/json');
echo json_encode(['cpuutilization' => get_cpu_usage(), "disk_read_bytes" => 0.0, "disk_write_bytes"=>0.0,"network_in"=>0.0,"network_out"=>0.0]);

