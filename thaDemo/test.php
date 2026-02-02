<?php

// echo 123;
// $keys = ['key2', 'key3'];
// $arr = [ "key1"=>100, "key2"=>200, "key3"=>300, 'key4'=>400 ];

// print_r(
// 	array_intersect_key(
// 		$arr,
// 		array_flip($keys)
// 	)
// );

$date = [
	'2024-06-04',
	'2024-06-01',
	'2024-06-05',
	'2024-06-02',
	'2025-02-05',
	'2023-06-03',
	
];

print_r(min($date));
print_r(max($date));