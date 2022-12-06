
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

`resources` folder ထဲမှာ lang ဆိုတဲ့ folder တစ်ခုဖန်တီးပြီး `lang` folder ထဲမှာ `en , jp , mm` ဆိုတဲ့ folder 3 ခုထပ်မံဖန်တီးပါမယ်။
en, jp, mm folder 3 ခုလုံးမှာ `index.php` ကိုဖန်တီးရပါမယ်။

---
index.php 3 file လုံးထဲမှာ အောက်ပါ code တွေ ရေးထည့်လိုက်ပါမယ်။ 


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

___

index.php တိုင်းမှာ associated array တစ်ခု return ပြန်ပေးရပါတယ်။

    return [
       'key' => 'value',
       'key' => 'value',
       'key' => 'value',
    ]

အပေါ်က index.php 3 ခုမှာ title ဆိုတဲ့ key ကို မြန်မာလို ဘယ်လိုခေါ်မယ်။ english လိုဆို ဘယ်လိုပေါ်မယ်။ japan လို ဘယ်လိုပြပေးမယ်ဆိုတာကို သတ်မှတ်ပေးထားတာဖြစ်ပါတယ်။

    php artisan make:controller LangController

**routes/web.php**

    Route::get('lang/change/{lang}', [LangController::class, 'change'])->name('changeLang');
    
    
`lang/change/{lang}` ဆိုတဲ့ url (eg...  `lang/change/mm`  `lang/change/eng` or  `lang/change/jp`) ရိုက်ခဲ့ရင် LangController ရဲ့ change method ကိုခေါ်မယ်လို့ကြေညာထားပါတယ်။

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

controller method `change` ထဲက code တွေကို ရှင်းပါမယ်။ method က parameter တစ်ခုလက်ခံပေးထားပါတယ်။ `$lang` က lang/change/<mark>eng</mark>     ဒါကို ဖမ်းမှာပါ။

    App::setLocale($lang);

ဒီ code လေးရဲ့ meaning က 

resources/lang folder ထဲက သက်ဆိုင်ရာ language folder ကို ရှာပေးမယ်ဆိုတဲ့သဘောပါ။

ဥပမာ user က `lang/change/mm` လို့ရိုက်ခဲ့ရင် `resources/lang/mm/` ထဲမှာ သွားရှာပေးမယ်လို့ set လုပ်လိုက်တာပါ။ user က `lang/change/eng` လို့ရိုက်ခဲ့ရင် `resources/lang/eng/` ထဲမှာ သွားရှာပေးမယ်လို့ set လုပ်လိုက်တာပါ။

ပြီးတော့
`session()->put()` ကိုသုံးပြီး<mark> locale</mark> ဆိုတဲ့ နာမည်နဲ့ session တစ်ခုသိမ်းလိုက်ပါတယ်။ session တစ်ခုနဲ့ သိမ်းရတဲ့ ရည်ရွယ်ချက်က ကိုယ်က ဘာ langauge နဲ့ သုံးတယ်ဆိုတာ မှတ်မိစေချင်တဲ့ ရည်ရွယ်ချက်ဖြစ်ပါတယ်။ ဥပမာ ကိုယ်က myanmar language နဲ့ပြစေချင်တယ်။ ပြလဲပြတယ်။ ဒါမယ့် browser ကို ပိတ်ပြီးပြန်ဖွင့်လိုက်တဲ့အခါမှာ default english ကိုပဲပြနေလိမ့်မယ်။ ဒါကြောင့် ကိုယ် ပြစေချင်တဲ့ langauge ကို session ထဲမှာသိမ်းထားပြီး နောက်တစ်ခါ browser ပြန်ဖွင့်လဲ myanmar စာပြနေအောင်လို့  session ကို အသုံးပြုရခြင်းဖြစ်ပါတယ်။
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

middleware ဆိုတာကတော့ website တစ်ခုကို ဝင်တဲ့ အခါ request filter စစ်တာလို့ အလွယ်မှတ်နိုင်ပါတယ်။ gate စောင့် သဘောမျိုးပါ။ ဝင်ခွင့်ကတ်ပါလား ဝင်ခွင့်ရောရှိလား ဝင်ဖို့ ဘာတွေလိုအပ်မလဲဆိုတဲ့ စစ်ဆေးချက်တွေရေးပြီး filter လုပ်ပါတယ်။

ဒီ middleware မှာတော့ ခုနက <mark>locale</mark> ဆိုတဲ့ နာမည်နဲ့သိမ်းထားတဲ့ session ရှိလားလို့ website ကို ဝင်ဝင်ချင်းစစ်လိုက်ပါတယ်။ ဥပမာ session ထဲမှာ mm လို့ရှိပြီးသားသာဆိုရင် `App::setLocale(session()->get('locale'));` လို့ရေးထားတာကြောင့် `resources/lang/mm` folder ထဲမှာ သွားရှာပေးတော့မှာဖြစ်ပါတယ်။

<br>

LanguageManager ဆိုတဲ့ middleware သုံးမယ်ဆိုတဲ့အကြောင်း Kernel.php ထဲမှာ ကြေညာပေးပါမယ်။

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
ဘယ် language နဲ့ပြပေးပါဆိုပြီး ရွေးချယ်လို့ရမယ့် dropdown လေးကို nav bar ထဲမှာ ထည့်ပါမယ်။ 

အပေါ်က code ကိုပဲခွဲရှင်းတာပါ။ အသစ်ထပ်ထည့်ဖို့မဟုတ်ပါဘူး

		 @if (  session()->get('locale') ==  'en')    
		    English    
	    @elseif(  session()->get('locale') ==  'mm')   
		    မြန်မာ    
	    @elseif(  session()->get('locale') ==  'jp')    
		    日本    
	    @else    
		    English   
	    @endif


local session ထဲက value ကို ယူမယ်။ 
value က mm ဆိုရင် dropdown မှာ မြန်မာ ၊ 
value က eng ဆို dropdown မှာ English
value က jp ဆို dropdown မှာ 日本 ပြပေးမယ်လို့ ပြောလိုက်တာပါ။

<br>

    <a class="dropdown-item" href="{{ route('changeLang',['lang'  =>  'mm']) }}">Myanmar</a>


`route('changeLang',['lang'  =>  'mm'])` ဆိုတဲ့ link ကို click ရင်
 `lang/change/mm`   ကို သွားမှာပါ။

changeLange ဆိုတာက `lang/change/{lang}` ရဲ့ route name ပါ။
`['lang'  =>  'mm']` ဆိုတာက route parameter ထည့်ပေးတာပါ။  ဆိုလိုတာက `lang/change/{lang}` မှာ lang ကို `mm` အဖြစ်ပေးမယ်ပြောတာဖြစ်ပါတယ်။
___
dropdown ရေးပြီသွားရင် ကြိုက်တဲ့ view blade မှာ ဒါလေးရေးလို့ရပါပြီ။


**home.blade.php**

    <h3>{{  __('index.title') }}</h3>


middleware က website ဝင်ဝင်ချင်းထဲက  `App::setLocale(session()->get('locale'));` ဆိုပြီး resources/lang/ ထဲက ဘယ် folder ကိုသွားရမယ်ဆိုတာကြိုလမ်းညွှန်ပြီးသားမလို့

    {{  __('index.title') }}

index ထဲက title ဆိုတဲ့ key ကို ပြပေးမှာဖြစ်ပါတယ်။

dropdown ကနေ japan ရွေးလိုက်တာနဲ့ setLocale က resources/lang/jp folder ထဲကို သွားရှာမယ်။ index file ထဲက title key ရဲ့ value ကိုပြပေးမယ်။ ဒါဆိုရင် Localization ရပါပြီ။

