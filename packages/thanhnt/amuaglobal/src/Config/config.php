<?php
return [
	"app-name" => 'amua global',
	"description" => "app for sell product",
	'package' => 'amuaglobal',
	'version' => '1.0.0',
	"mode" => "date_range",
	"currency_code" => "VND",   // USD | VND | EUR
	"exchange_vnd" => 26666,
	'cart_key' => 'amua_cart', // cart(ahomeglobal) || cart_key(abookglobal)
	'payment' => [
		'stripe' => [
			'active' => true,
			'ui_mode' => 'custom', // custom(thanh toán trực tiếp) | embedded | hosted(chuyển hướng c1)
		],
		'paypal' => [],
		'checkmoney' => [],
	],
	'shipping_method' => [
		['key' => 'on_shop', 'name' => 'On Shop', 'shipping_cost' => 0, 'description' => 'Nhận tại cửa hàng', 'id' => 1],
		['key' => 'on_address', 'name' => 'On local address', 'shipping_cost' => 10000, 'description' => 'Nhận hàng tại nhà', 'id' => 2],
		['key' => 'dhl', 'name' => 'DHL', 'shipping_cost' => 15000, 'description' => 'Giao hàng qua DHL nhanh', 'id' => 3],
	],
];
