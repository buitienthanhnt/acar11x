<?php

echo 123;
$keys = ['key2', 'key3'];
$arr = [ "key1"=>100, "key2"=>200, "key3"=>300, 'key4'=>400 ];

print_r(
	array_intersect_key(
		$arr,
		array_flip($keys)
	)
);