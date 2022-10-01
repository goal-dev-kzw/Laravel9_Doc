## Routing

Routing ကိုအလွယ်ပြောရရင် လမ်းကြောင်းဆွဲခြင်းဖြစ်ပါတယ်။ ဘယ်လမ်းကိုသွားရင် ဘယ်ရောက်မယ်ဆိုတာ သတ်မှတ်ပေးတဲ့သဘောပါ။ ဘယ် URL Address ကိုသွားရင် ဘာအလုပ်လုပ်ရမယ်ဆိုတာကို သတ်မှတ်ပေးခြင်းဖြစ်ပါတယ်။

- [Returning Text](#returning-text)
- [Returning JSON](#returning-json)
- [Routing With Controller](#routing-with-controller)
-  [Available Router Methods](#available-router-methods)
-  [Getting Current HTTP Request From Route](#getting-current-http-request)
- [Dynamic Routes   (Route Parameters)](#dynamic-routes---route-parameters)
- [Optional Route Parameter](#optional-route-parameter)
- [Defining Route Name](#defining-route-name)
- [Redirect Routes](#redirect-routes)
- [Generating URLs To Named Routes](#generating-urls-to-named-routes)
- [Route Groups](#route-groups)
- [Route Lists](#the-route-list)
<br><br>

####  Returning Text

    use Illuminate\Support\Facades\Route;

	Route::get('/greeting', function () {
    return 'Hello World';
	});

	Route::get('articles/{id}',function($id){ 
		return  "Article Detail _ $id"; 
	});

> <span>Note::</span> Laravel ရဲ့ `get()` Route Method ကို အသုံးပြုထားခြင်းဖြစ်ပါတယ်။ ပထမ parameter က `URL Address` ဖြစ်ပြီး ဒုတိယက လုပ်ရမယ့်အလုပ် `callback function` ဖြစ်ပါတယ်။ အခြား Route Method တွေလဲရှိပါတယ်။
***
<br>

####  Returning JSON

    Route::get('/person/info',function(){ 
	    return ["name"=>"Josh","age"=>24]; 
    });


	output =>
    {
	  "name": "Josh",
	  "age": 24
	}
```
Route::get('/person/info',function(){
	return [
		["name"=>"Josh","age"=>24],
		["name"=>"Bob","age"=>19],
		["name"=>"Diana","age"=>27]
	];
});


output =>
[
  {
    "name": "Josh",
    "age": 24
  },
  {
    "name": "Bob",
    "age": 19
  },
  {
    "name": "Diana",
    "age": 27
  }
]

```
***
<br>

####  Returning View

#####  <u> return view with  get() method </u>

    Route::get('/welcome', function(){
	    return view('welcome');
    });
#####  <u> view() method </u>

    Route::view('/welcome', 'welcome');
    
   

> `resources/views/welcome.blade.php `  ကို return ပြန်ပေးပါတယ်။
> resources/views/greeting/welcome.blade.php ဆိုပြီး subfolder ထဲမှာရှိနေရင်
> `return view('greeting.welcome');`  or `return view('greeting/welcome');`
***
<br>

####  Routing With Controller

    use App\Http\Controllers\UserController;

	Route::get('/user', [UserController::class, 'index']);
***
<br>

#### Available Router Methods
The router allows you to register routes that respond to any HTTP verb:

```
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
```

You may do so using the `match` method. Or, you may even register a route that responds to all HTTP verbs using the `any` method:

    Route::match(['get', 'post'], '/', function () {
	    //
	});

	Route::any('/', function () {
	    //
	});

***
<br>

#### Getting Current HTTP Request
`Illuminate\Http\Request` class to have the current HTTP request automatically injected into your route callback:

```
use Illuminate\Http\Request;

Route::get('/users', function (Request $request) {
    // ...
});
```

***

<br>



#### Dynamic Routes   (Route Parameters)
Route လို့ပြောရင် Static Route နဲ့ Dynamic Route ဆိုပြီး 2 မျိုးရှိပါတယ်။ Static Route က ပုံသေသတ်မှတ်ထားတဲ့ Route လမ်းကြောင်းပါ။ Dynamic Route ကတော့ပါလာတဲ့ Route Parameter အပေါ်မူတည်ပြီး ပြောင်းလဲနိုင်တဲ့ Route လမ်းကြောင်းဘဲဖြစ်ပါတယ်။

```
Route::get('/articles/detail/{$id}',function($id){ 
	return  "Article Detail - $id" 
});
```
Note:: PHP မှာ String အတွင်း Variable တွေ ထည့်သွင်းအသုံးပြုလိုရင်  `Double Quote` ကိုအသုံးပြုရပါတယ်။ Single Quote အတွင်းမှာတော့ Variable ရေးလဲ အလုပ်မလုပ်ပါဘူး။
***
<br>

#### Optional Route Parameter
```
Route::get('/user/{name?}',function($name='John'){ 
	return  $name; 
});
```

***

<br>


#### Defining Route Name
```
Route::get('/article',function(){ 
	return  'I show Article Detail'; 
})->name('article.detail.show');
```

***

<br>

#### Redirect Routes
If you are defining a route that redirects to another URI, you may use the `Route::redirect` method.

```
Route::redirect('/here', '/there');
```
By default,  `Route::redirect`  returns a  `302`  status code. You may customize the status code using the optional third parameter:

```
Route::redirect('/here', '/there', 301);
```

```
Route::redirect('/here', '/there', 301);

```

Or, you may use the  `Route::permanentRedirect`  method to return a  `301`  status code:

```
Route::permanentRedirect('/here', '/there');
```
<br>

##### <u>Redirecting With Route Path</u>

    Route::get('/article/show',function(){ 
	    return redirect('/article'); 
	 });
<br>

##### <u>Redirecting With Route Name</u>

    Route::get('/article/show',function(){
	   return redirect()->route('article.detail.show'); 
	});

##### <u>Redirecting With Route Parameter</u>

    Route::get('/articles/custom',function(){
	    return redirect()->route('profile', ['name' => 'john']);
	});


***
<br>

####  Generating URLs To Named Routes

route() method က သတ်မှတ်ပေးထားခဲ့တဲ့ route name တွေကို url အဖြစ်ပြောင်းလဲပေးတဲ့ method ဖြစ်ပါတယ်။

Generating Url
```
Route::get('/user/show', function  ($id) {
	//
})->name('user');

$url  =  route('user');    // $url=> /user/show
```
<br>

Generating Redirects
```
return  redirect()->route('profile');

return  to_route('profile');
```

<br>

Giving Route Parameters Using route()
```
Route::get('/user/{id}/profile', function  ($id) {
	
})->name('profile');

$url  =  route('profile',  ['id'  =>  1]);

// /user/1/profile
```
<br>

If you pass additional parameters in the array, those key / value pairs will automatically be added to the generated URL's query string:
```
Route::get('/user/{id}/profile', function  ($id) {

})->name('profile');

$url  =  route('profile',  ['id'  =>  1,  'photos'  =>  'yes']);

// /user/1/profile?photos=yes
```

***
<br>


#### Route Groups
##### <u>Middleware Group</u>
```
Route::middleware(['first', 'second'])->group(function  () {
	Route::get('/', function  () {
		// Uses first & second middleware...
	});

	Route::get('/user/profile', function  () {
	// Uses first & second middleware...
	});
});
```
<br>

#####   <u>Controller Group</u>

```
use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)->group(function  () {
	Route::get('/orders/{id}', 'show');
	Route::post('/orders', 'store');
});
```
<br>

#####   <u>Route URL Prefix & Name Prefix In Route Group</u>
```
Route::prefix('admin')->name('admin.')->group(function  () {
	Route::get('/users', function  () {
		// Matches The "/admin/users" URL
	});
	
	Route::get('/articles', function  () {
		// Route assigned name "admin.articles"...
	})->name('articles');
});
```

***

<br>

#### The Route List

The  `route:list`  Artisan command can easily provide an overview of all of the routes that are defined by your application:


    php artisan  route:list
