<?php

function factorial($n,$start) {
    $result = 0;
    $a = 1;
    for ($i = 1; $i <= $n; $i++) {
        $a = $i*$a;
    }

    $end = microtime(true);
    echo $end."\n";
    echo 'result:'.$a."\n";
    echo 'end:'.($end-$start);
}


$start = microtime(true);
echo $start."\n";
sleep(1);

$a = 50;
factorial($a,$start);



