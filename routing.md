## Routing

Routing ကိုအလွယ်ပြောရရင် လမ်းကြောင်းဆွဲခြင်းဖြစ်ပါတယ်။ ဘယ်လမ်းကိုသွားရင် သတ်မှတ်ပေးတဲ့သဘောပါ။ ဘယ် URL Address ကိုသွားရင် ဘာအလုပ်လုပ်ရမယ်ဆိုတာကို သတ်မှတ်ပေးခြင်းဖြစ်ပါတယ်။

- Basic Routing
	- Redirecting Routes
	- View Routes 
	- Route List
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

#### CSRF Protection
`POST`, `PUT`, `PATCH`, or `DELETE` routes that are defined in the `web` routes file should include a CSRF token field. Otherwise, the request will be rejected.

```
<form method="POST" action="/profile">
    @csrf
    ...
</form>
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
***
