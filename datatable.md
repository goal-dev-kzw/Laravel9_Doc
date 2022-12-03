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


    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class=" btn btn-outline-info text-center viewRoom py-2 mr-4"> <i class="mdi mdi-eye mx-auto "></i></a>';

row တစ်ခုချင်းစီရဲ့ id ကို  data-id ဆိုတဲ့ attribute နဲ့ Button 3 ခု လုံးမှာထည့်ပေးထားတယ်။ id ပါမှ edit , delete အလုပ်တွေကို လုပ်နိုင်မှာဖြစ်တာကြောင့် Button မှာ တစ်ခါထဲ ထည့်ပေးထားခြင်းဖြစ်ပါတယ်။


     addColumn('action', function($row){
    	    $btn  =  '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class=" btn btn-outline-info text-center viewRoom py-2 mr-4"> <i class="mdi mdi-eye mx-auto "></i></a>';
    	    $btn  =  $btn.  '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class="edit btn btn-outline-success editRoom py-2 mr-4"> <i class="mdi mdi-pencil-box-outline mr-1"></i>Edit</a>'; 
    	    $btn  =  $btn.' <a href="javascript:void(0)"  data-toggle="tooltip" data-id="'.$row->id.'"  class="btn btn-outline-danger deleteRoom py-2 mr-4" ><i class="mdi mdi-delete mr-1 "></i>Delete</a>'; 
    	    return  $btn;
        })


Button 3 ခု ကို String အဖြစ်နဲ့ variable ထဲမှာ သိမ်းပြီး return ပြန်ပေးထားပါတယ်။ return ပြန်ထားတဲ့ Button 3 ခုကို actions column ထဲမှာ ထည့်ပေးမှာဖြစ်ပါတယ်။

	->rawColumns(['action'])
rawColumns() က HTML content တွေကို table ရဲ့ column ထဲမှာ ပြပေးနိုင်ဖို့သုံးတယ်လို့ အပေါ်မှာပြောခဲ့ပါတယ်။  

အခု actions column ဟာလဲ Button 3 ခုက  HTML Content တွေပဲဖြစ်ပါတယ်။ rawColumns() ထဲမှာသာ မကြေညာဘူးဆို String အနေနဲ့ပဲပြပေးမှာပါ။ HTML Button ဖြစ်မှာမဟုတ်ပါဘူး ဒါကြောင့် actions ကို rawColumns() အဖြစ်သုံးမယ် ပြောပေးရတာဖြစ်ပါတယ်။ 

	->make(true);
ကတော့  datatable make လုပ်မယ် လို့ ပြောလိုက်တာပါ။

    return view('backend.pages.rooms.index');

နောက်ဆုံးမှာ datatable ကို blade ဆီ return ပြန်ပေးထားပါတယ်။
<br>
___

### jquery setup from blade template

အခုဆက်ပြီး blade template မှာ datatable အတွက် jquery သုံးပြီး setup လုပ်ပါမယ်။

<u> In backend/pages/rooms/index.blade.php</u>

    @extends('backend.layouts.app')
    @section('content')        
    <div  class="card p-3"  style="overflow-x:auto;">  
	    <table  class="table data-table text-white" >    
		    <thead>    
			    <tr>    
				    <th>No</th>    
				    <th>Room</th>    
				    <th>Type</th>    
				    <th>Description</th>    
				    <th>Price</th>    
				    <th>Status</th>    
				    <th>Living</th>     
				    <th  width="280px">Action</th>  
			    </tr>  
		    </thead>  
		    <tbody>    
		    </tbody>    
	    </table>   
    </div>
    @endsection

table heading `<th>` တွေပဲ ပေးပြီး table body `<tbody>` ကို တော့ အလွတ်ထားထားပါတယ်။ jquery ajax သုံးပြီး  `<tbody>` ထဲမှာ rows တွေထည့်သွင်းသွားမှာဖြစ်ပါတယ်။

***

<u> In backend/pages/rooms/index.blade.php</u>

	@pushOnce('js')
	<script>
		$(function () {
		
		});
	</script>
	@endPushOnce


ဒီ code လေးက jquery code ပါ။ document or website ကြီး တစ်ခုလုံး loading လုပ်ပြီးနောက် ready ဖြစ်ရင် အလုပ်လုပ်မယ်ဆိုတဲ့  function ပါ။
ဒီ function ထဲမှာ ရေးသမျှ code က website loading လုပ်ပြီးတာနဲ့ တန်းအလုပ်လုပ်မှာပါ။


	@pushOnce('js')
	<script>
		$(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		});
	</script>
	@endPushOnce

ခုနက document ready ဖြစ်ရင် အလုပ်လုပ်မယ်ဆိုတဲ့ Function ထဲမှာ ajaxSetup() ဆိုတဲ့ method လေးတစ်ခုရေးလိုက်ပါတယ်။

    headers: {
    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }

ဒီ code လေးက http header ကို setup လုပ်ပေးထားတာပါ။
ajax ရဲ့ သဘောက website ကို loading မဖြစ်စေပဲ နောက်ကွယ်ကနေ url request တစ်ခုပို့ပေးတာဖြစ်ပါတယ်။

get request ဆိုပြဿနာမရှိပေမယ့် အခြား post,delete တို့လို method တွေပို့တဲ့အခါ laravel မှာ csrf ဆိုတဲ့ token လေးတစ်ခုလိုအပ်ပါတယ်။ ဒါကြောင့် ajax သုံးတဲ့အခါ မှာလဲ csrf ကို အသုံးပြုဖို့လိုလာပါတယ်။ laravel မှာ @csrf ဆိုပြီးသုံးပေမယ့် ajax မှာတော့ http header ထဲမှာပဲ အပေါ်က code အတိုင်း csrf token ကို ထည့်ပေးလိုက်ခြင်းဖြစ်ပါတယ်။

	@pushOnce('js')
	<script>
		$(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			/*------------------------------------------

			--------------------------------------------

			Render DataTable

			--------------------------------------------

			--------------------------------------------*/



			let  room_table  =  $('.data-table').DataTable({
				processing: true,
				serverSide: true,
				retrieve: true,
				ajax: {
				url: "{{ route('rooms.index') }}",
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex'},
					{data: 'room_no', name: 'room_no'},
					{data: 'type', name: 'type',},
					{data: 'description', name: 'description',},
					{data: 'price', name: 'price'},			
					{data: 'status', name: 'status',},
					{data: 'is_living', name: 'is_living',},
					{data: 'action', name: 'action', orderable: false, searchable: false},
				],
			});
		});
	</script>
	@endPushOnce

<br>

    let  room_table  =  $('.data-table').DataTable({

.data-table လို့ပေးထားတဲ့ class ရှိတဲ့ table ကို  datatable အဖြစ်အသုံးပြုမယ်လို့ကြေညာလိုက်ပါတယ်။

    processing: true,
    serverSide: true,
	retrieve: true,
    
   processing:true ပေးရတာက loading လေးပြပေးအောင်လို့ဖြစ်ပါတယ်။
serverSide: true,
retrieve: true,
sql လို serverside မျိုးကနေ data ကို ရယူမှာဖြစ်တာကြောင့် ပေးရတာဖြစ်ပါတယ်။

    ajax: { url: "{{ route('rooms.index') }}", },

room.index ဆိုတာက route name ပါ။ RoomController ထဲက index method ကို လှမ်းခေါ်ပေးတဲ့ route name ဖြစ်ပါတယ်။ controller ကို 
Route::resource() နဲ့ register လုပ်တုန်းက သူ့ဘာသာဖန်တီးပေးထားတဲ့ route name ဖြစ်ပါတယ်။

ajax နဲ့ RoomController ရဲ့ index method ကို ခေါ်တဲ့ route ကို 
request ပို့မယ်ပြောလိုက်တာဖြစ်ပါတယ်။

	columns: [
		{data: 'DT_RowIndex', name: 'DT_RowIndex'},
		{data: 'room_no', name: 'room_no',},
		{data: 'type', name: 'type',},
		{data: 'description', name: 'description',},
		{data: 'price', name: 'price'},
		{data: 'status', name: 'status',},
		{data: 'is_living', name: 'is_living',},
		{data: 'action', name: 'action', orderable: false, searchable: false},
	],

ဒီ code လေးက ခုနက request လုပ်လိုက်လို့ index method ကနေ return ပြန်လာတဲ့ data column တွေကို table ထဲမှာ ထည့်ပေးလိုက်တာဖြစ်ပါတယ်။
___

အပေါ်က code အထိ ရေးပြီးသွားပြီဆိုရင် အခုလို table ရလာမှာဖြစ်ပါတယ်။

![image](https://github.com/goal-dev-kzw/Laravel9_Doc/blob/main/Screenshot%20(105).png)

အခု Column တွေကို ဆက်ပြင်ကြပါမယ်။ ပထမဆုံး description column ကိုကြည့်ပါ။ စာပိုဒ်ကြီးတစ်ခုလုံးကို table ထဲမှာ ဖော်ပြနေတော့ ကြည့်ရဆိုးစေပါတယ်။ column တွေကို ပြုပြင် (customize) လုပ်ဖို့အတွက် `columnDefs: [  ]` property ကို သုံးပေးရမှာဖြစ်ပါတယ်။

	let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [

					]
				});
			});

 columnDefs: [  ] မှာ column တွေကို ပြုပြင်မယ့် object { }  လေးတွေပေးရပါတယ်။  အဲ့ဒီ object ထဲမှာ `target` ဆိုတဲ့ property ရယ် `render` ဆိုတဲ့ method ရယ် လိုပါတယ်။


	let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
					]
				});
			});
`target` ဆိုတာက ကိုယ်ပြင်ချင်တဲ့ column ရဲ့ order ပါ။  description column က ၃ ခုမြောက် column ဖြစ်ပါတယ်။ "No" Column ကို ထည့်မရေတွက်ပါဘူး။ သူက db ထဲက ဆွဲထုတ်ထားတဲ့ data column မဟုတ်လို့ပါ။

`render()` method မှာတော့ target ပေးထားတဲ့ column ကို ဘယ်လိုပြုပြင်ချင်လဲ သတ်မှတ်ပေးရပါမယ်။ `data ,type , row` ဆိုပြီး 
parameter 3 ခုနဲ့ လက်ခံထားပါတယ်။ 



	render:function(data,type,row){
		 return data.length > 20 ?
				  data.substr(0,17) + '....' : data
	   }

`data` ဆိုတာက column ရဲ့ data ပါ။ ဥပမာ target : 3 ဆိုရင် column 3 ဖြစ်တဲ့ description column ရဲ့ data ကို ဒီ parameter က ဖမ်းပေးတာပါ။ `row` ဆိုတာကတော့ data row တစ်ခုချင်းစီကို ဆိုလိုပါတယ်။

 အပေါ်က code ရဲ့ အလုပ်လုပ်ပုံကိုရှင်းပါမယ်။ `description` column ထဲကို ဝင်လာတဲ့ data ရဲ့ length က 20 ထက် ကျော်မယ်ဆိုရင် ( description က  အလုံး ၂၀ ထက်ကျော်နေမယ်ဆိုရင်) 17 လုံးပဲပြပြီး `....` ပေါင်းပြီးပြမယ်။ `....` ဆိုတာကတော့ ပြရန် ကျန်သေးတယ် ဆိုတဲ့သဘောပါ။
 
အပေါ်က code ထည့်သွင်းပြီးရင် table က အခုလိုဖြစ်သွားမှာပါ။

![image](https://github.com/goal-dev-kzw/Laravel9_Doc/blob/main/Screenshot%20(107).png)

အခု နောက် column ဆက်ပြင်ပါမယ်။ Living Column နဲ့  Status Column တို့ကို ပြင်ပါမယ်။ Living Column ကို table မှာမပေါ်စေချင်ပါဘူး။ Status Column မှာပဲ Living ရဲ့ boolean နဲ့  Status ရဲ့ boolean ကိုပဲပေါင်းပြီး
`Living`  `Booked`  `Available` ဆိုတဲ့ Status 3ခုကိုပြပေးမှာဖြစ်ပါတယ်။ အရင်ဆုံး Living Column ကို table မှာ မပြအောင်လုပ်ကြပါမယ်။

	let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
						{
							target: 6,
							visible: false,
						},
					]
				});
			});

order number 6 column ဖြစ်တဲ့ Living Column ကို `visible:false` လို့ပေးပြီး hide လုပ်လိုက်ပါမယ်။ 

အခု `Status` Column မှာ  `Living` `Booked` `Available` ဆိုတဲ့ Status 3ခုကို  ပြပေးအောင် ရေးပါမယ်။

	let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
						{
							target: 6,
							visible: false,
						},
						{
							targets:5,
							render: function(data,type,row){
								return  data  ==  false  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:">Booked</a>'
								:  data  ==  false  &&  row.is_living  ==  true  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-warning" style="background:; color:;"> Living </a>'
								:  data  ==  true  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-primary" style="background:; color:;"> Available </a>'
								:  '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:; color:;"> Wrong Something </a>';
							}
						},
					]
				});
			});

အပေါ်က code မှာ data ဆိုတာက target:5 (column no 5, `status` column) ရဲ့ data ဖြစ်ပါတယ်။

`status => false && is_living => false` ဆိုရင်  `Booked` Button
`status => false && is_living => true`  ဆိုရင်  `Living` Button
`status => true && is_living => false` ဆိုရင်  `Available` Button 
ပြပေးမယ်လို့ ternary operator နဲ့ condition စစ်ပြီး render လုပ်ပေးထားတာဖြစ်ပါတယ်။


![image](https://github.com/goal-dev-kzw/Laravel9_Doc/blob/main/Screenshot%20(109).png)

ဒီအထိ ရေးပြီးသွားရင် table က အပေါ်ကလိုပြပေးနေမှာပါ။

___


အခု action column မှာပဲ button တစ်ခု ထပ်ထည့်ပါမယ်။ နဂိုမူလက action မှာ button 3 ခုရှိပြီးသားပါ။ အခု အောက်ပါပုံအတိုင်း Button 4 ခုဖြစ်အောင် ရေးပါမယ်။
အသစ်ထည့်တဲ့ Button က Living နဲ့ Booked Status မှာဆို Check Booking
Available Status မှာဆို Booking Now ပြပေးမှာပါ။

	let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
						{
							target: 6,
							visible: false,
						},
						{
							targets:5,
							render: function(data,type,row){
								return  data  ==  false  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:">Booked</a>'
								:  data  ==  false  &&  row.is_living  ==  true  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-warning" style="background:; color:;"> Living </a>'
								:  data  ==  true  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-primary" style="background:; color:;"> Available </a>'
								:  '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:; color:;"> Wrong Something </a>';
							}
						},
						{
							
							targets:7, 
							render: function(data,type,row){
								let  btn  =  row.status  ==  0  ?
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-warning booking py-2 ml-1" ><i class="mdi mdi-account-check mr-2"></i>Check Booking</a>`
								:
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-info booking py-2 ml-1" ><i class="mdi mdi-book-open-pprice-variant mr-2 "></i>Booking Now</a>`
								return  data+btn;
							}
						}
					]
				});
			});

<br>

___

အခု ဆက်ပြီးတော့ room create လုပ်တာရေးကြပါမယ်။ 
table အပေါ်မှာ Create Button လေးထည့်ပါမယ်။

    <a  class="btn col-md-2 py-2 my-3 btn-primary btn-icon-text"  href="javascript:void(0)"  id="createNewRoom"><i  class="mdi mdi-plus-one btn-icon-prepend mr-1 "></i>Create New</a>

Ajax ကို သုံးပြီး create လုပ်မှာဖြစ်တာကြောင့် create form ကို နောက် page တစ်ခုမှာ သက်သက်မရေးတော့ပါဘူး။ 
ဒီ page မှာပဲ create button click တဲ့အခါ create form ကို modal box လေးနဲ့ပြပေးအောင်ရေးမှာဖြစ်ပါတယ်။ အဲ့ဒီအတွက် modal box လေးတစ်ခုရေးကြပါမယ်။


    {{-- create form modal --}}    
    <div  class="modal fade"  id="ajaxModel"  aria-hidden="true">    
	    <div  class="modal-dialog modal-md">	    
		    <div  class="modal-content">		    
			    <div  class="modal-header">			    
				    <h4  class="modal-title"  id="modelHeading"></h4>			    
			    </div>		    
			    <div  class="modal-body">
			    
				    <form  id="roomForm"  name="roomForm"  class="text-white form-horizontal">				    
					    @csrf 
					    <input  type="hidden"  name="room_id"  id="room_id">					    
					    <div  class="form-group">					    
						    <label  for="room_no"  class="col-sm-4 control-label">Room Number</label>					    
						    <div  class="col-sm-12">					    
							    <input  type="text"  class="form-control text-white"  id="room_no"  name="room_no"  placeholder="Enter Room Number"  value=""  maxlength="50"  required="">					    
						    </div>					    
					    </div>
					    
					    <div  class="form-group">					    
						    <label  for="type"  class="col-sm-4 control-label">Room Type</label>					    
						    <div  class="col-sm-12">					    
							    <select  class="form-control text-white"  id="type"  name="type">					    
							    <option  value="Single Room"  selected>Single Room</option>					    
							    <option  value="Double Room">Double Room</option>					    
							    <option  value="Family Room">Family Room</option>					    
							    <option  value="Premium Room">Premium Room</option>					    
							    <option  value="VIP Room">VIP Room</option>					    
							    </select>					    
						    </div>					    
					    </div>
					    
					    <div  class="form-group">					    
						    <label  for="description"  class="col-sm-4 control-label">Enter Description</label>					    
						    <div  class="col-sm-12">					    
							    <textarea  class="form-control text-white"  id="description"  name="description"  rows="5"  placeholder="Description"></textarea>					    
						    </div>					    
					    </div>
					    
					    <div  class="form-group">					    
						    <label  for="price"  class="col-sm-4 control-label">Enter Price</label>					    
						    <div  class="col-sm-12">					    
							    <input  type="number"  class="form-control text-white"  id="price"  name="price"  placeholder="Enter Price"  value=""  required="">					    
						    </div>					    
					    </div>
			    
					    <div  class="col-sm-offset-2 col-sm-10">	    
						    <button  type="submit"  class="btn btn-primary"  id="saveBtn"  value="create">Save changes </button>					    
					    </div>					    
				    </form>				    
			    </div>			    
		    </div>		    
	    </div>	    
    </div>    
    {{-- create form modal --}}    

အပေါ်က modal လေးကို table ကို အုပ်ထားတဲ့ div tag ရဲ့အောက် @endsection အပေါ်မှာပဲရေးလိုက်ပါ။ room id ပါတဲ့ input field ကို hidden လုပ်ပေးထားတာ သတိပြုပါ။

modal လဲပြီးပြီ buttton လဲရေးပြီးပြီဆိုတော့ button နှိပ်ရင် create from modal ပေါ်လာအောင် javascript (jquery) code ရေးကြပါမယ်။


	@pushOnce('js')
	<script>
		$(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			/*------------------------------------------

			--------------------------------------------

			Render DataTable

			--------------------------------------------

			--------------------------------------------*/
			
			let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
						{
							target: 6,
							visible: false,
						},
						{
							targets:5,
							render: function(data,type,row){
								return  data  ==  false  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:">Booked</a>'
								:  data  ==  false  &&  row.is_living  ==  true  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-warning" style="background:; color:;"> Living </a>'
								:  data  ==  true  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-primary" style="background:; color:;"> Available </a>'
								:  '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:; color:;"> Wrong Something </a>';
							}
						},
						{
							
							targets:7, 
							render: function(data,type,row){
								let  btn  =  row.status  ==  0  ?
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-warning booking py-2 ml-1" ><i class="mdi mdi-account-check mr-2"></i>Check Booking</a>`
								:
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-info booking py-2 ml-1" ><i class="mdi mdi-book-open-pprice-variant mr-2 "></i>Booking Now</a>`
								return  data+btn;
							}
						}
					]
				});
				
		/*------------------------------------------

			--------------------------------------------

			Here CRUD Operation Start

			--------------------------------------------

			--------------------------------------------*/
			
			$('#createNewRoom').click(function () {
				$('#saveBtn').val("create-room");
				$('#room_id').val('');
				$('#roomForm').trigger("reset");
				$('#modelHeading').html("Create New room");
				$('#ajaxModel').modal('show');
			});

		});
	</script>
	@endPushOnce

create button မှာ createNewRoom ဆိုတဲ့ id ပေးထားပါတယ်။ 

အပေါ်က code ရဲ့ meaning က 
create button ကိုသာ click ခဲ့ရင် modal ကို ပြပေးမယ်။ modal ရဲ့ heading ကို Create New Room လို့ပြပေးမယ်။ create လုပ်တဲ့အခါ မှာ id မလိုတာကြောင့် hidden လုပ်ထားတဲ့ room id input value လေးကို empty string ပေးလိုက်ပါတယ်။

ဒီအဆင့်ထိ ရေးပြီးပြီဆိုရင် button click တဲ့အခါ form ပေါ်လာပါပြီ
အခု form ထဲက save changes button(submit) ကို နှိပ်တဲ့အခါ  input ထဲက data တွေကို database ထဲမှာသွားသိမ်းအောင် ထပ်ရေးပါမယ်။

	@pushOnce('js')
	<script>
		$(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			/*------------------------------------------

			--------------------------------------------

			Render DataTable

			--------------------------------------------

			--------------------------------------------*/
			
			let  room_table  =  $('.data-table').DataTable({
					processing: true,
					serverSide: true,
					retrieve: true,
					ajax: {
					url: "{{ route('rooms.index') }}",
					},
					columns: [
						{data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{data: 'room_no', name: 'room_no'},
						{data: 'type', name: 'type',},
						{data: 'description', name: 'description',},
						{data: 'price', name: 'price'},			
						{data: 'status', name: 'status',},
						{data: 'is_living', name: 'is_living',},
						{data: 'action', name: 'action', orderable: false, searchable: false},
					],
					columnDefs: [
						{
							targets:3, 		// the order of column 
							render:function(data,type,row){
								return  data.length  >  20  ?
								data.substr(0,17) +  '....'  :  data
							}
						},
						{
							target: 6,
							visible: false,
						},
						{
							targets:5,
							render: function(data,type,row){
								return  data  ==  false  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:">Booked</a>'
								:  data  ==  false  &&  row.is_living  ==  true  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-warning" style="background:; color:;"> Living </a>'
								:  data  ==  true  &&  row.is_living  ==  false  ?
								'<a class="btn py-1 px-3 btn-rounded btn-outline-primary" style="background:; color:;"> Available </a>'
								:  '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:; color:;"> Wrong Something </a>';
							}
						},
						{
							
							targets:7, 
							render: function(data,type,row){
								let  btn  =  row.status  ==  0  ?
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-warning booking py-2 ml-1" ><i class="mdi mdi-account-check mr-2"></i>Check Booking</a>`
								:
								`<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-info booking py-2 ml-1" ><i class="mdi mdi-book-open-pprice-variant mr-2 "></i>Booking Now</a>`
								return  data+btn;
							}
						}
					]
				});
				
				
		/*------------------------------------------

			--------------------------------------------

			Here CRUD Operation Start

			--------------------------------------------

			--------------------------------------------*/
			
			$('#createNewRoom').click(function () {
				$('#saveBtn').val("create-room");
				$('#room_id').val('');
				$('#roomForm').trigger("reset");
				$('#modelHeading').html("Create New room");
				$('#ajaxModel').modal('show');
			});
			/*------------------------------------------

			Create Room Code

			--------------------------------------------*/

			$('#saveBtn').click(function (e) {	
				e.preventDefault();
				$(this).html('Save Changes...');
				$.ajax({
					data: $('#roomForm').serialize(),
					url: "{{ route('rooms.store') }}",
					type: "POST",
					dataType: 'json',
					success: function (data) {				  
						$('#roomForm').trigger("reset");
						$('#ajaxModel').modal('hide');
						room_table.draw();
					},
					error: function (data) {
						console.log('Error:', data);
						$('#saveBtn').html('Save Changes');
					}
				});
			});

		});
	</script>
	@endPushOnce

saveBtn id ရှိတဲ့ submit button ကို click လိုက်တာနဲ့  Room Controller ထဲက store method ကို သွားခေါ်ပေးမှာဖြစ်ပါတယ်။

    url: "{{ route('rooms.store') }}",


rooms.store ဆိုတာက RoomController ထဲက store method ကို ခေါ်တဲ့ route name ပဲဖြစ်ပါတယ်။ store method ကို POST method သုံးပြီး form ထဲက data တွေကို ပို့လိုက်ပါတယ်။


success ဖြစ်ခဲ့ရင် (data save လုပ်တာအောင်မြင်ခဲ့ရင်) 

    success: function (data) {				  
    		$('#roomForm').trigger("reset");
	    	$('#ajaxModel').modal('hide');
    		room_table.draw();
    },

modal ကိုhideမယ်
form ကို reset ပြန်လုပ်မယ်။
room table ကို draw( ) ပြန်လုပ်မယ်။ draw() ဆိုတာက loading  ပြန်လုပ်မယ်ပြောတာပါ။


ဆက်ပြီးတော့ store method ကို RoomController ထဲမှာမရေးရသေးတာဖြစ်တာကြောင့် ရေးရပါမယ်။


In RoomController.php

    public  function  store(Request  $request)
    {
	    Room::updateOrCreate([	    
	    '	id' => $request->room_id	    
	    ],	    
	    [	    
		    'room_no' => $request->room_no,    
		    'type' => $request->type,	    
		    'description' => $request->description,	    
		    'price' => $request->price,	    	      	    
	    ]);
	    
	    return  response()->json(['success'=>'Room saved successfully.']);   
    }

`updateOrCreate()` သုံးထားပါတယ်။

အလုပ်လုပ်ပုံက create လုပ်တဲ့ id က  database ထဲမှာရှိလား စစ်ပါတယ်။ id ရှိပြီးသားဆိုရင် update လုပ်မှာဖြစ်ပြီး id မရှိသေးဘူးဆိုရင် အသစ်ဖန်တီးပေးသွားမှာဖြစ်ပါတယ်။ ပြီးသွားတော့ json response ပြန်ပေးထားပါတယ်။


create() နဲ့ insert လုပ်ထားတာကြောင့် mass assignment expression error မတက်အောင် `$fillable` ကို `Room` model ထဲမှာ ကြေညာပေးဖို့လိုပါတယ်။


    protected  $fillable  = [
	    'room_no',
	    'type',
	    'description',
	    'price'
    ];


ဒါဆိို room create လုပ်တာပြီးဆုံးသွားပါပြီ


