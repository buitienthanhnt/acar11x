<?php

// paypal checkout url: https://www.sandbox.paypal.com/checkoutnow?token=7Y216747KM792683K
// paypal checkout redirect success url: http://acar11x.dev/order-success?PayerID=F57WHNF868FF6&token=67P79581H5577853M

/**
 * @var array{
 * 	home_id: integer, 
 * 	room_id: integer, 
 * 	date_from: string, 
 * 	date_to: string, 
 * 	selected_time: array[string], 
 * 	total_price: float, 
 * 	currency_code: string, 
 * 	item: array{
 * 		name: string, 
 *  	description: string, 
 * 		price: float, 
 * 		quantity: string, 
 * 		category: string, 
 * 		image_url: string, 
 * 		url: string,
 *  	unit_amount: array{currency_code: string, value: float}
 * 	},
 * }
 * customer_info: array{name: string, email: string, phone: string}
 * on_payment_order: array{token: string, id: string,}
 * $cartData
 */
