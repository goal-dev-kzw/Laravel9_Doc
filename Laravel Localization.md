## Laravel Localization


#### <u> **Creating Lang Files**</u>

 - resources
			*  views
			*  lang
	 * en
		* index.php
	 * mm
		 * index.php
	 * jp
		* index.php
	 


---

**resources/lang/en/index.php**

    <?php
      
    return  [    
      'title'  =>  'This is English Language Title.',    
    ];

<br>

**resources/lang/mm/index.php**

    <?php
    
    return  [    
      'title'  =>  'မြန်မာဘာသာ ခေါင်းစဉ်ဖြစ်ပါတယ်',    
    ];

<br>

**resources/lang/jp/index.php**

    <?php
    
    return  [    
      'title'  =>  'これは英語のタイトルです。',    
    ];

<br>

**routes/web.php**

    Route::get('lang/change/{lang}', [LangController::class, 'change'])->name('changeLang');
    

<br>

**LangController.php**

    <?php
	namespace  App\Http\Controllers;
	use Illuminate\Support\Facades\App;
	use Illuminate\Http\Request;
	
	class  LangController  extends  Controller
	{
	
		public  function  change($lang)
		{
			App::setLocale($lang);
			session()->put('locale', $lang);	  
			return  redirect()->back();
		}
	}


<br>

    php artisan make:middleware LanguageManager
<br>

**app/Http/Middleware/LanguageManager.php**

   

     <?php    
    namespace  App\Http\Middleware;    
    use  Closure;    
    use Illuminate\Support\Facades\App;
        
    class  LanguageManager   
    {         
	      public  function handle($request,  Closure $next)	    
	      {	    
		      if  (session()->has('locale'))  {	    
			      App::setLocale(session()->get('locale'));	    
		      }
	    
		      return $next($request);	    
	      }	    
    }

<br>

**app/Http/Kernel.php**
	

          protected $middlewareGroups =  [    
		      'web'  =>  [
		    	 ......
				 ......
				 ......
			   \App\Http\Middleware\LanguageManager::class,	    
	      ],   
      ];
<br>

**nav.blade.php**

    
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		 @if (  session()->get('locale') ==  'en')    
		    English    
	    @elseif(  session()->get('locale') ==  'mm')   
		    မြန်မာ    
	    @elseif(  session()->get('locale') ==  'jp')    
		    日本    
	    @else    
	    English   
	    @endif
     </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('changeLang',['lang'  =>  'en']) }}>English</a>
        <a class="dropdown-item" href="{{ route('changeLang',['lang'  =>  'mm']) }}">Myanmar</a>
        <a class="dropdown-item" href="{{ route('changeLang',['lang'  =>  'jp']) }}">Japan</a>
      </div>
    </div>
    
<br>

**home.blade.php**

    <h3>{{  __('index.title') }}</h3>


