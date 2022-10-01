### <u>Toastr In Laravel</u>
![enter image description here](https://websolutionstuff.com/adminTheme/assets/img/toastr_notifications_example_in_laravel_8.png)
#### <u>1. Link With JS</u>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>


#### <u>2. Link With CSS</u>
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"  rel="stylesheet" />


#### <u>3.  In Controller's Method</u>
    return redirect()->route('admin.index')->with('message','Data added Successfully');


#### <u>3.  In JS</u>
	<script>
	    toastr.options.progressBar  =  true;
	    toastr.options.closeButton  =  true;
		toastr.options.showEasing  =  'easeOutBounce';
		toastr.options.hideEasing  =  'easeInBack';
		toastr.options.closeEasing  =  'easeInBack';
		toastr.options.showMethod  =  'slideDown';
		toastr.options.hideMethod  =  'slideUp';
		toastr.options.closeMethod  =  'slideUp';
		
		$(document).ready(function() {
			@if(Session::has('message'))
				toastr.success('{{ Session::get('message') }}');
			@endif
		});
	</script>
	
<br>

> `toastr.info()` `toastr.error()`	`toastr.success()` `toastr.warning()`  ဆိုပြီးရှိပါတယ်။
