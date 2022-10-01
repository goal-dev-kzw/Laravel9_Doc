## Sweet Alert 2 In Laravel

![enter image description here](https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/SuccessAlert.png)

![enter image description here](https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/SuccessToast.png)
#### <u>Run Command Line</u>
	composer require realrashid/sweet-alert

<br>

#### <u> In master.blade.php or app.blade.php</u>
	
	<body>
	    @include('sweetalert::alert')
	</body>
<br>

#### <u> In Controller </u>
	
	use RealRashid\SweetAlert\Facades\Alert;
	
	public function index(){
		Alert::success('Success', 'Users has been created successfully');
		return  to_route('admin.index');
	}

***

### <u> Available Alerts </u>

```
Alert::alert('Title', 'Message', 'Type');
```
```
Alert::success('Success Title', 'Success Message');
```

```
Alert::info('Info Title', 'Info Message');
```

```
Alert::warning('Warning Title', 'Warning Message');
```

```
Alert::error('Error Title', 'Error Message');
```

```
Alert::question('Question Title', 'Question Message');
```

```
Alert::image('Image Title!','Image Description','Image URL','Image Width','Image Height', 'Image Alt');
```

```
Alert::html('Html Title', 'Html Code', 'Type');
```

```
Alert::toast('Toast Message', 'Toast Type');
```
### [Using the helper function](https://realrashid.github.io/sweet-alert/usage?id=using-the-helper-function)

#### [Alert](https://realrashid.github.io/sweet-alert/usage?id=alert)

```
alert('Title','Lorem Lorem Lorem', 'success');
```

```
alert()->success('Title','Lorem Lorem Lorem');
```

```
alert()->info('Title','Lorem Lorem Lorem');
```

```
alert()->warning('Title','Lorem Lorem Lorem');
```

```
alert()->error('Title','Lorem Lorem Lorem');
```

```
alert()->question('Title','Lorem Lorem Lorem');
```

```
alert()->image('Image Title!','Image Description','Image URL','Image Width','Image Height', 'Image Alt');
```

```
alert()->html('<i>HTML</i> <u>example</u>'," You can use <b>bold text</b>, <a href='//github.com'>links</a> and other HTML tags ",'success');
```

#### [Toast](https://realrashid.github.io/sweet-alert/usage?id=toast)

```
toast('Your Post as been submited!','success');
```
```
toast('Info Toast','info');
```
```
toast('Warning Toast','warning');
```
```
toast('Question Toast','question');
```
```
toast('Error Toast','error');
```
