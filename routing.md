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
***
