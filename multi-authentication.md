## Laravel Multi-Auth (multiple guards)

    php artisan make:model AdminUser -m
<br>

**In migration**

    Schema::create('admin_users', function (Blueprint  $table) {
		$table->id();
		$table->string('name');
		$table->string('email')->unique();
		$table->string('phone')->unique();
		$table->string('password');
		$table->string('ip')->nullable();
		$table->text('user_agent')->nullable();
		$table->timestamps();
	});

<br>

    php artisan migrate
<br>
<br>

> Laravel မှာ default လုပ်ထားတဲ့ guard က web  ဆိုတဲ့ guard ဖြစ်ပါတယ်။ `web` guard က `users` table အတွက် လုပ်ထားတဲ့ guard ဖြစ်ပါတယ်။ အခု `admin_users` ဆိုတဲ့ table အတွက် guard အသစ်တစ်ခု ဖန်တီးရပါမယ်။

**config/auth.php**

    'guards' => [
		'web' => [
			'driver' => 'session',
			'provider' => 'users',
		],
		'admin_user'=> [
			'driver' => 'session',
			'provider'=> 'admin_users'
		]
	],

	--------------------------------------------------------------------

	'providers' => [
		'users' => [
			'driver' => 'eloquent',
			'model' => App\Models\User::class,
		],
		'admin_users' => [
			'driver' => 'eloquent',
			'model' => App\Models\AdminUser::class,
		],
	],
	
<br>


**In  AdminUser.php**

    <?php
	namespace App\Models;
	use Illuminate\Foundation\Auth\User  as  Authenticatable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Laravel\Sanctum\HasApiTokens;

	class  AdminUser  extends  Authenticatable
	{

		use  HasApiTokens, HasFactory, Notifiable;
		
		/**
		* The attributes that are mass assignable.
		*
		* @var  array<int, string>
		*/

		protected  $fillable  = [
			'name',
			'email',
			'password',
		];

		  
		/**
		* The attributes that should be hidden for serialization.
		*
		* @var  array<int, string>
		*/
		
		protected  $hidden  = [
			'password',
		];
	}
<br>

**AdminUserController.php**

    <?php
	namespace  App\Http\Controllers\Auth;
	use App\Http\Controllers\Controller;
	use App\Providers\RouteServiceProvider;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	use Illuminate\Support\Facades\Auth;

	class  AdminLoginController  extends  Controller

	{
		use  AuthenticatesUsers;

		/**
		* Where to redirect users after login.
		*
		* @var  string
		*/

		protected  $redirectTo  =  RouteServiceProvider::ADMINPANEL;

		/**
		* Create a new controller instance.
		*
		* @return  void
		*/

		public  function  __construct()
		{
			$this->middleware('guest:admin_user')->except('logout');
		}
  

		protected  function  guard()
		{
			return  Auth::guard('admin_user');
		}


		public  function  showLoginForm()
		{
			return  view('auth.admin.login');
		}
	}



<br>

**LoginController.php**

    <?php
	namespace  App\Http\Controllers\Auth;
	use App\Http\Controllers\Controller;
	use App\Providers\RouteServiceProvider;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	use Illuminate\Support\Facades\Auth;

	class  LoginController  extends  Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Login Controller
		|--------------------------------------------------------------------------
		|
		| This controller handles authenticating users for the application and
		| redirecting them to your home screen. The controller uses a trait
		| to conveniently provide its functionality to your applications.
		|
		*/

	  
	
		use  AuthenticatesUsers;

		/**
		* Where to redirect users after login.
		*
		* @var  string
		*/

		protected  $redirectTo  =  RouteServiceProvider::HOME;


		/**
		* Create a new controller instance.
		*
		* @return  void
		*/

		public  function  __construct()
		{
			$this->middleware('guest')->except('logout');
		}


		protected  function  guard()
		{
			return  Auth::guard();
		}

		  
		public  function  showLoginForm()
		{
		return  view('auth.login');
		}
	}
<br>
<br>

> Login ဝင်ပြီးရင် ရောက်မယ့် route path  ကို သတ်မှတ်တာဖြစ်ပါတယ်။

**app/Providers/RouteServiceProvider.php**

    public  const HOME =  '/';
	public  const ADMINPANEL =  'admin/dashboard';

<br>
<br>

> login ဝင်လာတဲ့ guard က `admin_user` ဆိုရင် ဘယ်ကို redirect လုပ်ပါမယ်ဆိုပြီး သတ်မှတ်ပေးထားတာဖြစ်ပါတယ်။

**app/Http/Middleware/RedirectIfAuthenticated.php**

    public  function  handle(Request  $request, Closure  $next, ...$guards)
	{
		$guards  =  empty($guards) ? [null] :  $guards;
		
		foreach ($guards  as  $guard) {
			if (Auth::guard($guard)->check()) {
				if($guard  ==  "admin_user"){
					return redirect(RouteServiceProvider::ADMINPANEL);
				}
			return  redirect(RouteServiceProvider::HOME);
			}
		}
		return  $next($request);
	}

<br>
<br>

> admin login မလုပ်ရသေးပဲ (authenticate မဖြစ်သေးဘဲ) url address မှာ admin သာပါခဲ့မယ်ဆိုရင် `admin login` route ကို redirect လုပ်ပေးသွားမှာဖြစ်ပါတယ်။

**app/Middleware/Authenticate.php**

    protected  function  redirectTo($request)
	{
		if($request->is('admin')){
			return  route('admin.login');
		}

		if (!$request->expectsJson()) {
			return  route('login');
		}
	}


<br>

**web.php**

    Route::get('admin/login',[AdminLoginController::class,'showLoginForm']);
	Route::post('admin/login',[AdminLoginController::class,'login'])->name('admin.login');
	Route::post('admin/logout',[AdminLoginController::class,'logout'])->name('admin.logout');

	Route::middleware(['auth:admin_user'])->name('admin.')->prefix('admin')->group(function(){
		Route::get('/dashboard', [AdminController::class,'index'])->name('index');
	});


***optional***

    middleware(['auth:admin_user']);   // check admin_user guard authenticated
	
	middleware(['auth']);			   // check web guard authenticated
	middleware(['auth:web']);		   // check web guard authenticated

	middleware(['auth:admin_user','auth:web','role:Admin'])   ;		// check both guard authenticated, & check also the "Admin" role of auth guard
 

<br><br>

> admin  အတွက် login ပဲလိုပါတယ်။ Register မထည့်သွင်းထားပါ။

**resources/views/auth/admin/login.blade.php**
	

    @extends('layouts.app')
    	@section('content')
    	<div  class="container">
    		<div  class="row justify-content-center">
    			<div  class="col-md-8">
    				<div  class="alert alert-info alert-dismissible fade show"  role="alert">
    					<strong>If You Are New To Hotel Ms, Please Register First</strong> <a  class="mx-3 btn btn-info"  href="{{  route('register')  }}">{{  __('Create An Account') }}</a>
				</div>
    
		    	<div  class="card">
			    	<div  class="card-header">Admin Login</div>
			    	<div  class="card-body">
				    	<form  method="POST"  action="{{  route('admin.login')  }}">
							@csrf   
							<div  class="row mb-3">
						    	<label  for="email"  class="col-md-4 col-form-label text-md-end">{{  __('Email Address') }}</label>
						    	<div  class="col-md-6">
							    	<input  id="email"  type="email"  class="form-control @error('email') is-invalid @enderror"  name="email"  value="{{  old('email')  }}"  required  autocomplete="email"  autofocus>
								    	@error('email')								    
								    	<span  class="invalid-feedback"  role="alert">								    
									    	<strong>{{  $message  }}</strong>								    
								    	</span>								    
								    	@enderror				    
						    	</div>    
					    	</div>
       	      
					    	<div  class="row mb-3">					    
						    	<label  for="password"  class="col-md-4 col-form-label text-md-end">{{  __('Password') }}</label>								 
						    	<div  class="col-md-6">					    
							    	<input  id="password"  type="password"  class="form-control @error('password') is-invalid @enderror"  name="password"  required  autocomplete="current-password">									  	
								    	@error('password')								    
									    	<span  class="invalid-feedback"  role="alert">								    
									    	<strong>{{  $message  }}</strong>								    
									    	</span>								    
								    	@enderror					    
						    	</div>					    
					    	</div>
      	     
					    	<div  class="row mb-3">					    
						    	<div  class="col-md-6 offset-md-4">					    
							    	<div  class="form-check">					    
								    	<input  class="form-check-input"  type="checkbox"  name="remember"  id="remember"  {{  old('remember') ? 'checked' : ''  }}>					    					    	  					    
								    	<label  class="form-check-label"  for="remember">					    
									    	{{  __('Remember Me') }}				    
								    	</label>					    
							    	</div>					    
						    	</div>					    
					    	</div>
    
    	  
    
					    	<div  class="row mb-0">					    
						    	<div  class="col-md-8 offset-md-4">					    
							    	<button  type="submit"  class="btn btn-primary">					    
								    	{{  __('Login') }}					    
							    	</button>					    							    
							    	@if (Route::has('password.request'))							    
								    	<a  class="btn btn-link"  href="{{  route('password.request')  }}">							    
									    	{{  __('Forgot Your Password?') }}							    
								    	</a>							    
							    	@endif					    
							    	</div>					    
						    	</div>  
					    	</form>
				    	</div>   
			    	</div>
			    </div>
		    </div>
	    </div>
	@endsection

<br>
<br>

**logout**

	
    <a  href="{{  route('admin.logout')  }}" 
    onclick="event.preventDefault();    
    document.getElementById('logout-form').submit();">    	      
	    {{  __('Logout') }}   
    </a>
	<form  id="logout-form"  action="{{  route('admin.logout')  }}"  method="POST"  class="d-none">	    
	    @csrf	    
	</form>

> Note:::: multi-auth or multi-guard မှာ Browser တစ်ခုထဲမှာ တစ်ချိန်ထဲ guard နှစ်ခုကို login ဝင်ထားရင်  logut လုပ်တာနဲ့ guard အကုန်လုံး logout ထွက်ပါတယ်။ guard တစ်ခုထဲကိုပဲ ရွေးပြီး logout လုပ်လို့မရပါဘူး။ အကြောင်းကတော့ logout လုပ်တာနဲ့ session တွေကို ဖျက်ပစ်ပြီး ပြန် generate လုပ်လို့ပါ။  solution ကိုတော့ ရှာမတွေ့သေးပေ...။  Browser 2ခုနဲ့ တစ်ခုစီ Login ဝင်ထားရင်တော့ သက်ဆိုင်ရာ guard ကိုပဲ logout လုပ်သွားမှာ ဖြစ်ပါတယ်။

<br><br>

**How to check guard in View (blade)**

    @auth("admin_user")  
      // Your logic here
    @endauth


	@if(Auth::guard('web')->check() && Auth::guard('admin_user')->check()) 
		// Your logic here 
	@endif


	{{ auth()->guard('admin_user')->user()->name }}		// get the current authenticated guard user 

		
