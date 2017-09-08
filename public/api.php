<?php
$a = [];
for($i=0;$i<1000000;$i++){
	$a[$i] = ['hello'];
}
echo memory_get_usage(true);