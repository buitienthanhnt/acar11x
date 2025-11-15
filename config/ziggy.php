<?php

return [
	// khai báo các định dạng của tuyến đường sẽ loại bỏ khỏi biến const Ziggy.
	// tham khảo: 
	// vendor/tightenco/ziggy/src/ZiggyServiceProvider.php: khai báo phương thức: @routes trong: resources/views/app.blade.php
	// vendor/tightenco/ziggy/src/Ziggy.php: tính toán danh sách tuyến trong hàm: toArray
	'except' => [
		'adminhtml.*', // Excludes all routes starting with 'admin.'
		'sensitive.data', // Excludes a specific route named 'sensitive.data'
		'api.v2.*', // Excludes all routes under the 'api.v2' group
		'unisharp.*', // Excludes all routes starting route name with 'unisharp. example: [unisharp.lfm.upload]
		'admin.*',// Excludes all routes starting route name with 'admin'. example: [admin.logout]
		'admin-*',// Excludes all routes starting route name with 'admin'. example: [admin-login]
		'admin_*',// Excludes all routes starting route name with 'admin'. example: [admin_writer_create]
		'livewire.*',
		'debugbar.*',
	],
];