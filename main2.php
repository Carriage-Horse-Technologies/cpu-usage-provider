<?php

function getCpuUsage() {
  $statFile = '/proc/stat';
  $statData = file_get_contents($statFile);
  $lines = explode("\n", $statData);

  foreach ($lines as $line) {
    $parts = explode(" ", $line);
    $cpuLabel = $parts[0];

    if (strpos($cpuLabel, 'cpu') === 0) {
      $user = $parts[1];
      $nice = $parts[2];
      $system = $parts[3];
      $idle = $parts[4];
      $iowait = $parts[5];
      $irq = $parts[6];
      $softirq = $parts[7];
      $steal = $parts[8];
      $guest = $parts[9];
      $guest_nice = $parts[10];

      $total = $user + $nice + $system + $idle + $iowait + $irq + $softirq + $steal + $guest + $guest_nice;
      $cpuUsage = 100 - ($idle / $total * 100);

      return json_encode(['cpu_usage' => $cpuUsage], JSON_PRETTY_PRINT);
    }
  }

  return false;
}

echo getCpuUsage();

