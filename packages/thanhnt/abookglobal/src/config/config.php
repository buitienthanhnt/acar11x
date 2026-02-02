<?php
return [
	'package' => 'abookglobal',
	'version' => '1.0.0',
	'payment' => [
		'stripe' => [
			'active' => true,
			'ui_mode' => 'custom', // custom(thanh toán trực tiếp) | embedded | hosted(chuyển hướng c1)
		],
		'paypal' => [],
	],
	'shipping_method' => [
		['key' => 'on_shop', 'name' => 'On Shop', 'shipping_cost' => 0, 'description' => 'Nhận tại cửa hàng', 'id' => 1],
		['key' => 'check_money_order', 'name' => 'check money order', 'shipping_cost' => 10000, 'description' => 'Thanh toán khi nhận hàng', 'id' => 2],
		['key' => 'dhl', 'name' => 'DHL', 'shipping_cost' => 15000, 'description' => 'Giao hàng DHL nhanh', 'id' => 3],
	],

];
