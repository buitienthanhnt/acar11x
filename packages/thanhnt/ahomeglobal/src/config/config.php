<?php

/**
 * return define config for the package
 */
return [
	"app-name" => 'ahome global',
	"description" => "app for book hotel quick",
	"version" => "1.0.0",
	"mode" => "date_range", // date_range | list_date(default)
	"qty_mode" => false, // true: support qty for room booking | false: not support
	"currency_code" => "VND",   // USD | VND | EUR
	"exchange_vnd" => 26331,
	"payment" => [
		"stripe" => [
			"active" => true,
			"ui_mode" => "custom", // custom(thanh toán trực tiếp) | embedded | hosted(chuyển hướng c1)
		]
	],
	'cart' => 'abook_cart', // cart(ahomeglobal) || cart_key(abookglobal)
];
