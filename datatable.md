## Laravel Datatable
<br>

<u>**Installing Datatable**</u>

[Follow These Stack ](https://yajrabox.com/docs/laravel-datatables/master/installation)

(or)

    composer require  yajra/laravel-datatables-oracle:"^10.0"

In config/app.php

    'providers'  => [
	    // ...    
	    // ...
	    Yajra\DataTables\DataTablesServiceProvider::class,  
    ],
<br>

    php  artisan  vendor:publish  --tag=datatables
    
 ***
 

<u>**Creating Room Model,Migration & Controller**</u>

    php artisan make:model Room -m -c
<br>

<u>**Register Route For RoomController**</u>

    Route::resource('/rooms',RoomController::class);

<br>

***
### Showing Datatable



	// ...Attention ::: This is not to copy , Just explaining...


		$rooms= Room::all(); 				
		return DataTables::of($rooms)
				->addIndexColumn()
				->addColumn('action', 'These are action buttons')
			    ->rawColumns(['action'])
				->make(true);
	
	
Datatable တစ်ခုဖန်တီးဖို့ Eloquent (Query) တစ်ခုပေးရပါတယ်။

အဲ့ဒီ Query ကနေ return ပြန်ပေးလာတဲ့ data response ကို ယူပြီးမှ table ထဲမှာ ထည့်သွင်းတည်ဆောက်သွားမှာဖြစ်ပါတယ်။ ဒီ example မှာဆိုရင် `$rooms= Room::all();`    Room table ကပြန်ပေးတဲ့ data response ကို အခြေခံပြီး datatable တည်ဆောက်ပေးသွားမှာဖြစ်ပါတယ်။

 - **addIndexColumn** ဆိုတာက $roles = Room::all();  ဆိုတဲ့ eloquent ကနေ ပြန်ပေးလိုက်တဲ့ response တွေရဲ့ column တွေအတိုင်း Column ထည့်မယ်ပြောတာပါ။  ဆိုလိုတာက $roles က room data တွေဖြစ်တဲ့ room_no တွေ၊ type တွေ၊ description တွေ ၊ price ၊ status ၊ is_living စတဲ့ data တွေကို response ပြန်တဲ့အခါ အဲ့ဒီ data column တွေအတိုင်း column တွေကို ဖန်တီးသွားမယ်လို့ဆိုလိုပါတယ်။ 

> ဒါတွေက library ကသတ်မှတ်ထားတဲ့ စည်းကမ်းပါ။ ဒီလိုပေါ်အောင် ဒီလိုရေးရမယ်လို့သတ်မှတ်ထားလို့ ရေးပေးရတာဖြစ်ပါတယ်။
[about addIndexColumn() in Documentation Here](https://yajrabox.com/docs/laravel-datatables/master/index-column)

Table မှာ addIndexColumn() နဲ့ database ထဲက column တွေအတိုင်း column တွေ ပေါင်းထည့်ခဲ့ပါတယ်။
<br>
 - **addColumn()**  ကိုတော့ table ထဲမှာ `Custom Column` ပေါင်းထည့်ဖို့အတွက် သုံးပါတယ်။

> [about addColumn() in Documentation Here](https://yajrabox.com/docs/laravel-datatables/master/add-column)

 - **rawColumns()** ကိုတော့ table ရဲ့ Column ထဲမှာ `HTML Content တွေကို render` ပြပေးနိုင်အောင်သုံးပါတယ်။

> [about rawColumns() in Documentation Here](https://yajrabox.com/docs/laravel-datatables/master/raw-columns)
		     
---
Code ရေးရင်းဆက်ရှင်းပါမယ်။		   
ပထမဆုံး Room Controller မှာ  Index method ကို စဖန်တီးပါမယ်။

<u>In Controler</u>

    public function index(Request $request) 
    {    
	    if ($request->ajax()) {    
		    $rooms= Room::all();    
		    return DataTables::of($rooms)   
				    ->addIndexColumn()    
				    ->addColumn('action', function($row){    
					    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class=" btn btn-outline-info text-center viewRoom py-2 mr-4"> <i class="mdi mdi-eye mx-auto "></i></a>';    
					    $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class="edit btn btn-outline-success editRoom py-2 mr-4"> <i class="mdi mdi-pencil-box-outline mr-1"></i>Edit</a>';    
					    $btn = $btn.' <a href="javascript:void(0)"  data-toggle="tooltip" data-id="'.$row->id.'" class="btn btn-outline-danger deleteRoom py-2 mr-4" ><i class="mdi mdi-delete mr-1 "></i>Delete</a>';    
					    return $btn;    
				    })    
				    ->rawColumns(['action'])    
				    ->make(true);  
		    }
	    
	    return view('backend.pages.rooms.index');
    
    }



$request က Request ကို ဖမ်းတာပါ။   

`if ($request->ajax()) {`  
   ဒီ index method ကို request လုပ်တဲ့အခါ ajax() ကို သုံးထားလား လို့ Condition စစ်ထားတာဖြစ်ပါတယ်။

index() ကို ajax() ကိုသုံးပြီး Request လုပ်တာဆိုရင် Datatable ကို return ပြန်ပေးမယ်။
	

    $rooms= Room::all();
    return DataTables::of($rooms)

Room table records တွေအားလုံး ယူထားတဲ့ query eloquent ကို datatable function မှာ argument အနေနဲ့ ပေးလိုက်တယ်။

`addIndexColumn()` နဲ့ `$rooms` က response ပြန်တဲ့ data column တွေအားလုံးကို datatable ရဲ့ Column အဖြစ် ထည့်သွင်းမယ်။

    addColumn('action', function($row){
	    $btn  =  '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class=" btn btn-outline-info text-center viewRoom py-2 mr-4"> <i class="mdi mdi-eye mx-auto "></i></a>';
	    $btn  =  $btn.  '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class="edit btn btn-outline-success editRoom py-2 mr-4"> <i class="mdi mdi-pencil-box-outline mr-1"></i>Edit</a>'; 
	    $btn  =  $btn.' <a href="javascript:void(0)"  data-toggle="tooltip" data-id="'.$row->id.'"  class="btn btn-outline-danger deleteRoom py-2 mr-4" ><i class="mdi mdi-delete mr-1 "></i>Delete</a>'; 
	    return  $btn;
    })

`addColumn()` နဲ့ custom column တစ်ခု ထည့်ထားတယ်။ custom column ကို actions လို့နာမည်ပေးထားတယ်။ actions column မှာ ထည့်မှာက Button တွေ။  Show Button , Edit Button , Delete Button သုံးခုရှိတာမလို့

    ->addColumn('action', 'These are action buttons')

ဒီလိုလေးပဲရေးလို့မရတော့ဘူး။ 

    ->addColumn('action', function($row){ 

function နဲ့ return ပြန်ပေးဖို့လိုလာပါတယ်။ ပြီးတော့ function မှာ `$row` ကို လက်ခံထားတယ်။ အဲ့ဒီ `$row` လေးက eloquent က response ပြန်လာတဲ့ data row တစ်ခုချင်းစီကို လက်ခံထားတဲ့ Parameter 

သေချာရှင်းအောင်ပုံကိုကြည့်ပါ။

![image](https://github.com/goal-dev-kzw/Laravel9_Doc/blob/main/Screenshot%20%28103%29.png)

