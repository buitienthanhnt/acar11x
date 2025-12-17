<?php

/**
 * laraveldoc.php all doc.
 */

// 1. install: 
/**
 * composer global require laravel/installer
 * create project by laravel/installer:  laravel new adoc11x
 */

// 2. create project laravel by composer(has 2 ways for create a project):
/**
 * composer create-project --prefer-dist laravel/laravel adoc11x
 * 
 * demo for create laravel 11 version project:
 * composer create-project laravel/laravel acar11 "11.*"
 */

// 3. view inof of laravel project:
/**
 * php artisan about
 * or view verion
 * php artisan --version
 */

// 4. laravel env config value:
/**
 * use Illuminate\Support\Facades\App;
 * 
 * view all of environment:
 * 	$environment = App::environment();
 * 
 * get one of environment:
 * 	$environment = App::environment('local');
 * or:
 *  env('APP_DEBUG', false)
 */
// 5. laravel config file value:
/**
 * use Illuminate\Support\Facades\Config;
 * 
 * get config value:
 * 	$value = Config::get('app.timezone');
 * or:
 * 	$value = config('app.timezone');
 * 
 * To set configuration values at runtime, you may invoke the Config facade's set method or pass an array to the config function:
 * Config::set('app.timezone', 'America/Chicago');
 * config(['app.timezone' => 'America/Chicago']);
 * 
 * Config::string('config-key');
 */
	// Config::integer('config-key');
	// Config::float('config-key');
	// Config::boolean('config-key');
	// Config::array('config-key');
	// Config::collection('config-key');

// 6. laravel debug mode:
/**
 * php artisan down
 * 
 * down mode with secret option:
 * php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
 * 
 * run for pass secret key url: https://example.com/1630542a-246b-4b66-afa1-dd72a4c43515
 * 
 * up for enable application: php artisan up
 */

// 7. laravel cache clear command:
/**
 * cache for all optimize
 *  php artisan optimize
 * clear cache for all optimize
 *  php artisan optimize:clear
 * 
 * cache for item list:
 * - php artisan config:cache
 * - php artisan route:cache
 * - php artisan view:cache
 */
// 8. laravel archive doc lifecycle:
/**
 * 1: -> public/index.php
 * 2: 
 * 3: -> app/Providers/AppServiceProvider.php (register -> boot)
 * 5: -> app/Http/Controllers/...
 * -> bootstrap/app.php (\Illuminate\Foundation\Application nó extend container service) -> handleRequest(
 * 	$kernel = $this->make(HttpKernelContract::class); // Create HTTP kernel instance: \Illuminate\Contracts\Http\Kernel(\Illuminate\Foundation\Http\Kernel) 
 *  Trong hàm make: bao gồm các bước: tải provider, alias: $this->loadDeferredProviderIfNeeded($abstract = $this->getAlias($abstract));
 * 
 * 	$response = $kernel->handle($request)->send();    // Handle the incoming request and send the response(\Illuminate\Http\Response)
 * 	 trong handle sẽ gọi: ->sendRequestThroughRouter {
 * 		Tạo instance: request
 * 		Khởi động: $this->bootstrap();
 * 		Trả về 1 pieline vào pispatch:
 * return (new Pipeline($this->app))->send($request)->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)->then($this->dispatchToRouter());
 * Lưu ý: trong dispatchToRouter sẽ gọi đến router để xử lý route và controller tương ứng
 * Lưu ý: trong dispatchToRouter đã có: prepareResponse để chuẩn hóa response trả về : \Illuminate\Routing\Router(vendor/laravel/framework/src/Illuminate/Routing/Router.php)
 * }
 * 
 * 6: -> resources/views/...
 * 7: -> back to browser response
 * Sau khi chạy: handle trả về: \Symfony\Component\HttpFoundation\Response sẽ gọi tiếp send() để gửi response về browser
 * Bao gồm:  
 * 	$this->sendHeaders();
 * 	$this->sendContent(); trong sendContent sẽ in ra nội dung response(echo $this->content;)
 * 	$kernel->terminate($request, $response);  		  // Terminate the kernel after the response is sent
 * )
 */
// 9. laravel service container binding:
/**
 * Bộ chứa dịch vụ Laravel là một công cụ mạnh mẽ để quản lý các phụ thuộc lớp và thực hiện tiêm phụ thuộc
 * Vậy, khi nào bạn sẽ tương tác thủ công với container? Hãy cùng xem xét hai tình huống:
 * 1. Khi bạn cần đăng ký một ràng buộc tùy chỉnh trong container
 * 2. Khi bạn cần giải quyết một lớp khỏi container
 * 3. Khi bạn cần kiểm tra một lớp hoặc giao diện cụ thể
 * 4. Khi bạn cần tạo các thể hiện động của các lớp
 * 5. Khi bạn cần sử dụng các dịch vụ của container trong các lớp không được quản lý bởi container
 * 6. Khi bạn cần sử dụng các dịch vụ của container trong các hàm đóng
 * 7.Đầu tiên, nếu bạn viết một lớp triển khai một giao diện và muốn gợi ý kiểu cho giao diện đó trên một tuyến đường hoặc hàm tạo lớp, 
 *  bạn phải cho container biết cách giải quyết giao diện đó
 * 8. nếu bạn đang viết một gói Laravel mà bạn dự định chia sẻ với các nhà phát triển Laravel khác,
 *  bạn có thể cần liên kết các dịch vụ của gói đó vào container.
 * Ràng buộc: Trong một nhà cung cấp dịch vụ, bạn luôn có thể truy cập vào container thông qua $this->appthuộc tính
 * 
 * 		$this->app->bind(Transistor::class, function (Application $app) {
 * 			return new Transistor($app->make(PodcastParser::class));
 * 		});
 * c2:
 * 		App::bind(Transistor::class, function (Application $app) { ...});
 * 
 * Contextual Binding: https://laravel.com/docs/12.x/container#contextual-binding
 * https://laravel.com/docs/12.x/container#contextual-attributes danh sách các attributes có sẵn: Illuminate\Container\Attributes
 * Đôi khi bạn có thể có hai lớp sử dụng cùng một giao diện, nhưng bạn muốn inject các triển khai khác nhau vào mỗi lớp. 
 * Ví dụ: hai controller có thể phụ thuộc vào các triển khai khác nhau của Illuminate\Contracts\Filesystem\Filesystem hợp đồng .
 * Laravel cung cấp một giao diện đơn giản, trôi chảy để định nghĩa hành vi này:
 * 
 * Resolving:
 * 	$transistor = $this->app->make(\App\Services\Transistor::class); || $transistor = App::make(Transistor::class); || $transistor = app(Transistor::class);
 * 
 * 	$transistor = $this->app->makeWith(\App\Services\Transistor::class, ['id' => 1]);
 * 
 * Gọi và Tiêm phương thức:
 * Phương thức này callchấp nhận bất kỳ lệnh gọi PHP nào. 
 * Phương thức của container callthậm chí có thể được sử dụng để gọi một closure trong khi tự động inject các phần phụ thuộc của nó:
 * 		$stats = App::call([new PodcastStats, 'generate']);
 *  Or call a Closure:
 * 		$result = App::call(function (AppleMusic $apple) {// ...});
 * 
 * Sự kiện Container:
 * Bạn có thể đăng ký các trình nghe sự kiện để lắng nghe các sự kiện
 *  Bạn có thể lắng nghe sự kiện này bằng phương resolvingthức:
 * use App\Services\Transistor;
 * use Illuminate\Contracts\Foundation\Application;
 * $this->app->resolving(Transistor::class, function (Transistor $transistor, Application $app) {
 * 		Called when container resolves objects of type "Transistor"...
 * });
 * 
 * $this->app->resolving(function (mixed $object, Application $app) {
 *  Called when container resolves object of any type...
 * });
 * 
 */
// 10: service provider:
/**
 * php artisan make:provider RiakServiceProvider
 * trong registerphương thức này, bạn chỉ nên liên kết các thành phần vào vùng chứa dịch vụ . 
 * Bạn không bao giờ nên cố gắng đăng ký bất kỳ trình lắng nghe sự kiện, tuyến đường hoặc bất kỳ chức năng nào khác trong registerphương thức. 
 * Nếu không, bạn có thể vô tình sử dụng một dịch vụ do một nhà cung cấp dịch vụ chưa tải cung cấp.
 * 
 * Các bindingsvà singletons Thuộc tính: https://laravel.com/docs/12.x/providers#the-bindings-and-singletons-properties
 * 
 * Phương pháp khởi động:
 * Phương thức này được gọi sau khi tất cả các nhà cung cấp dịch vụ khác đã được đăng ký , 
 * nghĩa là bạn có thể truy cập vào tất cả các dịch vụ khác đã được khung đăng ký:
 * 
 * Đăng ký nhà cung cấp: Tất cả các nhà cung cấp dịch vụ đều được đăng ký trong bootstrap/providers.phptệp cấu hình
 * Khi bạn gọi make:providerlệnh Artisan, Laravel sẽ tự động thêm provider đã tạo vào bootstrap/providers.phptệp. Tuy nhiên, nếu bạn đã tạo thủ công lớp provider, bạn nên tự thêm lớp provider vào mảng:
 * 
 * Nhà cung cấp hoãn lại: https://laravel.com/docs/12.x/providers#deferred-providers
 * Nếu nhà cung cấp của bạn chỉ đăng ký các ràng buộc trong vùng chứa dịch vụ , 
 * bạn có thể chọn trì hoãn việc đăng ký cho đến khi một trong các ràng buộc đã đăng ký thực sự cần thiết. 
 * Việc trì hoãn việc tải nhà cung cấp như vậy sẽ cải thiện hiệu suất ứng dụng của bạn, vì nó không được tải từ hệ thống tệp trong mọi yêu cầu.
 * implement the \Illuminate\Contracts\Support\DeferrableProvider interface on the provider class:
 * 
 */
// Facades:
/**
 * Laravel's facades provide a "static" interface to classes that are available in the application's service container.
 * Tất cả các facade của Laravel đều được định nghĩa trong Illuminate\Support\Facadesnamespace. Vì vậy, chúng ta có thể dễ dàng truy cập facade như sau:
 * use Illuminate\Support\Facades\Cache;
 * 
 * Các hàm trợ giúp: https://laravel.com/docs/12.x/facades#helper-functions
 * Laravel cung cấp một số hàm trợ giúp để truy cập nhanh các facade phổ biến.
 * Ví dụ: hàm cache()trả về một instance của Cache facade:
 * $value = cache('key');
 * cache(['key' => 'value'], 600);
 * Ngoài ra, bạn có thể sử dụng hàm app()để truy cập vào service container của ứng dụng:
 * Trong ứng dụng Laravel, facade là một lớp cung cấp quyền truy cập vào một đối tượng từ container.
 *  Cơ chế thực hiện việc này nằm trong Facadelớp đó. 
 * facade của Laravel, và bất kỳ facade tùy chỉnh nào bạn tạo ra, sẽ mở rộng lớp cơ sở Illuminate\Support\Facades\Facade.
 * 
 * Lớp Facadecơ sở sử dụng phương __callStatic()thức magic-method để trì hoãn các lệnh gọi từ facade của bạn đến một đối tượng được giải quyết từ container. 
 * Trong ví dụ dưới đây, một lệnh gọi được thực hiện đến hệ thống bộ nhớ đệm Laravel.
 *  Nhìn vào đoạn mã này, có thể giả định rằng getphương thức tĩnh đang được gọi trên Cachelớp:
 * 
 * Lưu ý rằng gần đầu tệp, chúng ta đang "nhập" Cachefacade. 
 * Facade này đóng vai trò là proxy để truy cập vào phần triển khai cơ bản của Illuminate\Contracts\Cache\Factorygiao diện. 
 * Bất kỳ lệnh gọi nào chúng ta thực hiện bằng facade sẽ được chuyển đến instance cơ bản của dịch vụ cache của Laravel.
 * Nếu chúng ta nhìn vào Illuminate\Support\Facades\Cachelớp đó, bạn sẽ thấy rằng không có phương thức tĩnh nào get:
 * 
 * Thay vào đó, Cachefacade mở rộng lớp cơ sở Facadevà định nghĩa phương thức getFacadeAccessor().
 *  Nhiệm vụ của phương thức này là trả về tên của ràng buộc container dịch vụ.
 *  Khi người dùng tham chiếu bất kỳ phương thức tĩnh nào trên Cachefacade, 
 * Laravel sẽ giải quyết cacheràng buộc từ container dịch vụ và chạy phương thức được yêu cầu (trong trường hợp này là get) trên đối tượng đó.
 * 
 * all of the facades provided by Laravel are defined in the Illuminate\Support\Facadesnamespace.
 * https://laravel.com/docs/12.x/facades#facade-class-reference
 */
// 11: routing
/**
 * All of the route definitions for your application are stored in the routes directory.
 * 
 * php artisan route:list
 * php artisan route:list --path=api
 * 
 * Route Methods: https://laravel.com/docs/12.x/routing#route-methods
 * 
 * Route Parameters: https://laravel.com/docs/12.x/routing#route-parameters
 * 
 * Route Groups: https://laravel.com/docs/12.x/routing#route-groups
 * 
 * Route Model Binding: https://laravel.com/docs/12.x/routing#route-model-binding
 * 
 * Customizing The Default Key Name: https://laravel.com/docs/12.x/routing#customizing-the-default-key-name
 * 
 * Implicit Binding With Enums: https://laravel.com/docs/12.x/routing#implicit-binding-with-enums
 * 
 * Explicit Binding: https://laravel.com/docs/12.x/routing#explicit-binding
 * 
 * Constraining Route Parameters: https://laravel.com/docs/12.x/routing#constraining-route-parameters
 * 
 * Named Routes: https://laravel.com/docs/12.x/routing#named-routes
 * 
 * Route Caching: https://laravel.com/docs/12.x/routing#route-caching
 * Route::get($uri, $callback);
 * Route::post($uri, $callback);
 * Route::put($uri, $callback);
 * Route::patch($uri, $callback);
 * Route::delete($uri, $callback);
 * Route::options($uri, $callback);
 * Route::match(['get', 'post'], '/', function () {// ...
 * });
 * Route::any('/', function () {// ...
 * });
 * 
 * Ràng buộc toàn cầu cho các tham số tuyến đường: trong AppServiceProvider->boot() phương thức
 * bạn có thể định nghĩa các ràng buộc toàn cầu cho các tham số tuyến
 * use Illuminate\Support\Facades\Route;
 * public function boot(): void{
 *  	Route::pattern('id', '[0-9]+');
 * }
 * 
 * Tuyến đường được đặt tên(Tên tuyến đường phải luôn duy nhất.)
 * Tạo URL cho các tuyến đường được đặt tên:
 * 	$url = route('profile');
 * return redirect()->route('profile');
 * return to_route('profile');
 * Kiểm tra tuyến đường được đặt tên: if ($request->route()->named('profile')) {}
 * 
 * Liên kết ngầm:
 * Laravel tự động giải quyết các mô hình Eloquent được định nghĩa trong các tuyến đường hoặc hành động của bộ điều khiển có tên biến được gợi ý kiểu khớp với tên phân đoạn tuyến đường
 * Truy cập tuyến đường hiện tại:
 * $route = Route::current(); // Illuminate\Routing\Route
 * $name = Route::currentRouteName(); // string
 * $action = Route::currentRouteAction(); // string
 * 
 */
// 12: middleware
/**
 * create new middleware: php artisan make:middleware EnsureTokenIsValid
 * define middleware in: app/Http/Middleware folder
 * and register middleware in: bootrap/app.php -> withMiddleware method
 * Quản lý thủ công phần mềm trung gian toàn cầu mặc định của Laravel:
 * cung cấp ngăn xếp middleware toàn cục mặc định của Laravel cho usephương thức. Sau đó, bạn có thể điều chỉnh ngăn xếp middleware mặc định nếu cần:
 * 
 * ->withMiddleware(function (Middleware $middleware): void {
 * $middleware->use([
 * \Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks::class,
 * // \Illuminate\Http\Middleware\TrustHosts::class,
 * \Illuminate\Http\Middleware\TrustProxies::class,\Illuminate\Http\Middleware\HandleCors::class,
 * ]);
 * })
 * Nhóm phần mềm trung gian:
 * ->withMiddleware(function (Middleware $middleware): void {
 * $middleware->appendToGroup('group-name', [
 *	 	First::class,
 * 		Second::class,
 * ]);
 * $middleware->prependToGroup('group-name', [
 * 		First::class,
 * 		Second::class,
 * ]);
 * })
 * 
 * Các nhóm Middleware mặc định của Laravel: https://laravel.com/docs/12.x/middleware#laravels-default-middleware-groups
 * Phân loại phần mềm trung gian: https://laravel.com/docs/12.x/middleware#middleware-prioritization
 * bạn có thể cần phần mềm trung gian của mình thực thi theo một thứ tự cụ thể nhưng lại không thể kiểm soát thứ tự của chúng khi chúng được gán cho tuyến đường.
 *  Trong những trường hợp này
 * bạn có thể chỉ định mức độ ưu tiên của phần mềm trung gian bằng priorityphương thức trong tệp ứng dụng bootstrap/app.php:
 * ->withMiddleware(function (Middleware $middleware): void {
 * $middleware->priority([...]);
 * Tham số phần mềm trung gian: Phần mềm trung gian cũng có thể nhận thêm các tham số: https://laravel.com/docs/12.x/middleware#middleware-parameters
 * Các tham số phần mềm trung gian bổ sung sẽ được truyền tới phần mềm trung gian sau $nextđối số:
 * Các tham số phần mềm trung gian có thể được chỉ định khi xác định tuyến đường bằng cách phân tách tên phần mềm trung gian và các tham số bằng dấu :
 * 
 */
	 
	 // 13: controller:
/**
 * resource controller: php artisan make:controller PhotoController --resource
 * define resource route: Route::resource('photos', PhotoController::class);
 * Actions Handled by Resource Controllers: https://laravel.com/docs/12.x/controllers#actions-handled-by-resource-controllers
 * Tuyến tài nguyên API:
 * Route::apiResource('photos', PhotoController::class);
 * Tài nguyên lồng nhau: https://laravel.com/docs/12.x/controllers#nested-resources
 * 
 */
	// 14: request: \Illuminate\Http\Request
/**
 * get parameter value: $request->input('key');
 * get all parameter: $request->all();
 * 
 * Retrieving the Request URL: $request->url();
 * Retrieving the Full Request(with query parameter) URL: $request->fullUrl();
 * Retrieving the Request Path: $request->path();
 * checking the Request Method: $request->isMethod('post');
 * checking the Request URL: $request->is('admin/*');
 * checking for the Presence of Input Values: $request->has('name');
 * checking route: $request->routeIs('profile.*');
 * 
 * + header request: $request->header('X-Header-Name');
 * + bearerToken: $request->bearerToken();
 * + Request IP Address: $request->ip();
 * + Lấy đầu vào từ chuỗi truy vấn: $request->query('page', 1);(param in the url ?page=1)
 * + Truy xuất giá trị đầu vào JSON: $name = $request->input('user.name');
 * + Lấy lại một phần dữ liệu đầu vào: $input = $request->only(['username', 'password']); || $input = $request->except(['credit_card']);
 * + Hợp nhất đầu vào bổ sung: $request->merge(['key' => 'value']); || $request->mergeIfMissing(['votes' => 0]);
 * + Nhấp nháy đầu vào sau đó chuyển hướng: return redirect('dashboard')->withInput();
 * return redirect('/form')->withInput($request->except('password'));
 * + Lấy lại đầu vào cũ: old('username');  | $username = $request->old('username');
 * 
 * ++ Lấy Cookie từ Yêu cầu: $value = $request->cookie('name', 'default');
 * ++ Truy xuất các tệp đã tải lên: $file = $request->file('photo');  || $file = $request->photo;
 * ++ Kiểm tra xem tệp đã tải lên có hợp lệ không: $isValid = $request->file('photo')->isValid();
 * ++ Đường dẫn tệp và phần mở rộng: $path = $request->file('photo')->path(); || $extension = $request->file('photo')->extension();
 * ++ Lưu trữ các tệp đã tải lên: $path = $request->file('photo')->store('photos');
 * Nếu bạn không muốn tên tệp được tự động tạo, bạn có thể sử dụng storeAs: $path = $request->photo->storeAs('images', 'filename.jpg');
 * ++ Lấy tất cả các tệp đã tải lên: $files = $request->allFiles();
 * Cấu hình Proxy đáng tin cậy: https://laravel.com/docs/12.x/requests#configuring-trusted-proxies
 * ->withMiddleware(function (Middleware $middleware): void {
 * $middleware->trustProxies(at: [
 * '192.168.1.1',
 * '10.0.0.0/8',
 * ]);
 * })
 */ 
// 15: response: \Illuminate\Http\Response
/**
 * return response('Hello World', 200)->header('Content-Type', 'text/plain');
 * Đính kèm tiêu đề vào phản hồi: return response($content)
 * ->header('Content-Type', $type)
 * ->header('X-Header-One', 'Header Value')
 * ->header('X-Header-Two', 'Header Value'); || ->withHeaders(['Content-Type' => $type,'X-Header-One' => 'Header Value','X-Header-Two' => 'Header Value',]);
 * Đính kèm Cookie vào Phản hồi: return response('Hello World')->cookie('name', 'value', $minutes);
 * Phương thức này cookiecũng chấp nhận một số đối số ít được sử dụng hơn: 
 * 	return response('Hello World')->cookie('name', 'value', $minutes, $path, $domain, $secure, $httpOnly);
 * 
 * Bạn có thể xóa cookie bằng cách vô hiệu hóa nó thông qua withoutCookiephương pháp phản hồi gửi đi: return response('Hello World')->withoutCookie('name');
 * Nếu bạn chưa có phiên bản phản hồi gửi đi, bạn có thể sử dụng phương thức Cookiecủa facade expiređể hết hạn cookie: Cookie::expire('name');
 * Cookie và Mã hóa: bạn có thể sử dụng phương encryptCookiesthức trong tệp ứng dụng bootstrap/app.php:
 * ->withMiddleware(function (Middleware $middleware): void {
 * $middleware->encryptCookies(except: [
 * 'cookie_name',
 * ]);
 * })
 * Chuyển hướng: return redirect('/home/dashboard');
 * Chuyển hướng đến các tuyến đường được đặt tên: return redirect()->route('profile');
 * Chuyển hướng đến các miền bên ngoài: return redirect()->away('https://example.com');
 * Chuyển hướng với dữ liệu phiên được flash: return redirect('dashboard')->with('status', 'Profile updated!');
 * Các loại phản hồi khác:
 * return response()->json(['name' => 'Abigail', 'state' => 'CA
 * ']);
 * 
 * return response()->view('hello', $data, 200)->header('Content-Type', $type);
 * 
 * Tải xuống tệp
 * return response()->download($pathToFile, $name, $headers);
 * 
 * Phản hồi tập tin: return response()->file($pathToFile); || return response()->file($pathToFile, $headers);
 * Phản hồi được truyền phát: https://laravel.com/docs/12.x/responses#streamed-responses
 * Route::get('/stream', function () {
 * return response()->stream(function (): void {
 * foreach (['developer', 'admin'] as $string) {
 * echo $string;
 * ob_flush();
 * flush();
 * sleep(2); // Simulate delay between chunks...
 * }
 * }, 200, ['X-Accel-Buffering' => 'no']);
 * });
 * Phản hồi JSON được truyền phát: https://laravel.com/docs/12.x/responses#streamed-json-responses 
 */

// 16: Url Generation & Signing
/**
 * Generating URLs to Named Routes: $url = route('profile', ['id' => 1]);
 * with query: echo url()->query('/posts', ['search' => 'Laravel']);
 * echo url()->query('/posts?sort=latest', ['sort' => 'oldest']); => // http://example.com/posts?sort=oldest
 * echo $url = url()->query('/posts', ['columns' => ['title', 'body']]); => // http://example.com/posts?columns[0]=title&columns[1]=body
 * get current url: $url = url()->current(); || URL::current();
 * get full url: $url = url()->full();
 * 
 * Get the full URL for the previous request... $url = url()->previous();
 * // Get the path for the previous request... $path = url()->previousPath();
 * 
 * Generating URLs to Controller Actions: $url = action([UserController::class, 'show'], ['id' => 1]);
 * 
 * url signing: use Illuminate\Support\Facades\URL;
 * 
 * Generating Temporary Signed URLs: $url = URL::temporarySignedRoute('unsubscribe', now()->addMinutes(30), ['user' => 1]); // has timeout
 * 
 * Laravel cho phép bạn dễ dàng tạo URL "đã ký" cho các tuyến đường được đặt tên. 
 * Các URL này có một hàm băm "chữ ký" được thêm vào chuỗi truy vấn, cho phép Laravel xác minh rằng URL chưa bị sửa đổi kể từ khi được tạo
 * return URL::signedRoute('unsubscribe', ['user' => 1]);(create )
 */
// 17: session
/**
 * Accessing The Session: use Illuminate\Support\Facades\Session;
 * $value = Session::get('key'); || $value = session('key');
 * $session = request()->session();
 * 
 * Truy xuất tất cả dữ liệu phiên
 * $data = Session::all();
 * Truy xuất một phần dữ liệu phiên: 
 * $data = Session::only(['username', 'email']);
 * Để lưu trữ dữ liệu trong phiên, bạn thường sẽ sử dụng putphương thức của phiên yêu cầu hoặc sessiontrình trợ giúp toàn cục:
 * Session::put('key', 'value'); || session(['key' => 'value']);
 * 
 * Đẩy vào giá trị phiên mảng:
 * Session::push('user.teams', 'developers');
 *
 * Lấy và xóa một mục khỏi phiên:
 * $value = Session::pull('key', 'default');
 * 
 * Nếu dữ liệu phiên của bạn chứa số nguyên mà bạn muốn tăng hoặc giảm, bạn có thể sử dụng các phương thức incrementvà decrement:
 * Session::increment('page_views'); || Session::decrement('page_views', 5);
 * 
 * Đôi khi bạn có thể muốn lưu trữ các mục trong phiên cho yêu cầu tiếp theo:
 * return redirect('dashboard')->with('status', 'Profile updated !'); || $request->session()->flash('status', 'Task was successful!');
 * 
 * Phương pháp này forgetsẽ xóa một phần dữ liệu khỏi phiên. Nếu bạn muốn xóa toàn bộ dữ liệu khỏi phiên, bạn có thể sử dụng flushphương pháp:
 * Session::forget('key'); || Session::flush();
 *
 * Việc tạo lại ID phiên thường được thực hiện để ngăn chặn người dùng có mục đích xấu khai thác cuộc tấn công cố định phiên trên ứng dụng của bạn:
 * $request->session()->regenerate();
 * Laravel tự động tạo lại ID phiên trong quá trình xác thực
 * Nếu bạn cần tạo lại ID phiên và xóa toàn bộ dữ liệu khỏi phiên trong một câu lệnh duy nhất, bạn có thể sử dụng invalidatephương thức:
 * $request->session()->invalidate();
 * Chặn phiên(Session Blocking)
 * Laravel cung cấp một cơ chế đơn giản để chặn phiên người dùng hiện tại.
 */

// 18: validation:
/**
 * use Illuminate\Support\Facades\Validator;
 * 
 *  $validated = $request->validate(['title' => 'required|unique:posts|max:255','body' => 'required',]);
 * 
 * nếu các trường yêu cầu đến không vượt qua các quy tắc xác thực đã cho thì sao? Như đã đề cập trước đó, 
 * Laravel sẽ tự động chuyển hướng người dùng trở lại vị trí trước đó. 
 * Ngoài ra, tất cả các lỗi xác thực và yêu cầu đầu vào sẽ tự động được ghi vào phiên .
 * 
 * Tùy chỉnh thông báo lỗi
 * $validator = Validator::make($input, $rules, $messages = [
 * 'required' => 'The :attribute field is required.',
 * ]);
 * 
 * Thực hiện xác thực bổ sung: https://laravel.com/docs/12.x/validation#performing-additional-validation
 * $validator->after(function ($validator) {
 * if ($this->somethingElseIsInvalid()) {
 * $validator->errors()->add('field', 'Something is wrong with this field!');
 * }
 * });
 * 
 * Available Validation Rules: https://laravel.com/docs/12.x/validation#available-validation-rules
 */

// 19: Xử lý ngoại lệ:
/**
 * All exceptions are stored in: bootstrap/app.php
 * 
 */