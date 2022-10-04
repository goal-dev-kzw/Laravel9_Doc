## Laravel Roles & Permissions (Spatie)

	composer require spatie/laravel-permission
<br>

	php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

<br>

	php artisan migrate

<br>

#### User.php

	class  User  extends  Authenticatable
	{
		use  HasApiTokens, HasFactory, Notifiable, HasRoles;  // HasRoles
	}
<br>

#### Kernel.php

	protected  $routeMiddleware  = [
		………
		………
		'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
		'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
		'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
	]

<br>

	php artisan make:seeder RoleSeeder
<br>

	php artisan make:seeder AdminSeeder
<br>
		
#### AdminSeeder.php

	
	use App\Models\User;

	public function run()
	{
	$user  =  User::create([
		'name' => 'admin',
		'email' => 'admin@gmail.com',
		'email_verified_at' => now(),
		'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',   	// password
	]);
	
	$user->assignRole('Writer','Admin');
	}
<br>
		
#### RoleSeeder.php

	use Spatie\Permission\Models\Role;
	
	public  function  run()
	{
		Role::create(['name'=>'Admin']);
		Role::create(['name'=>'Writer']);
		Role::create(['name'=>'User']);
	}

<br>

####  DatabaseSeeder.php


	public  function  run()
	{	
		$this->call(RoleSeeder::class);
		$this->call(AdminSeeder::class);
	}

<br>

####  web.php

	Route::get('/admin',function(){
		return  view('admin.index');
	})->middleware(['auth','role:admin'])->name('admin.index');

<br>

#### Check role in the blade template (admin/index.blade.php)
	
	@role('admin')
		<a  class="dropdown-item text-success"
		href="{{route('admin.index')}}">
			{{  __('Admin Dashboard') }}
		</a>
	@endrole

<br>

	
	php artisan make:controller Admin/PermissionController
<br>

	php artisan make:controller Admin/RoleController
	
<br>

	php artisan make:controller Admin/AdminController

<br>

	php artisan make:controller Admin/UserController

<br>


#### web.php   (// delete the previous route)


  

	Route::middleware(['auth','role:Admin'])->name('admin.')->prefix('admin')->group(function(){
		Route::get('/dashboard', [AdminController::class,'index'])->name('index');
		Route::resource('/roles',RoleController::class);
		Route::post('/roles/{role}/permissions',[RoleController::class,'givePermission'])->name('roles.permissions');
		Route::delete('/roles/{role}/permissions/{permission}',[RoleController::class,'revokePermission'])->name('roles.permissions.revoke');
		Route::resource('/permissions',PermissionController::class);
		Route::post('/permissions/{permission}/roles',[PermissionController::class,'assignRole'])->name('permissions.roles');
		Route::delete('/permissions/{permission}/roles/{role}',[PermissionController::class,'removeRole'])->name('permissions.roles.remove');
		Route::get('/users',[UserController::class,'index'])->name('users.index');
		Route::get('/users/{user}',[UserController::class,'show'])->name('users.show');
		Route::delete('/users/{user}',[UserController::class,'destroy'])->name('users.destroy');
		Route::post('/users/{user}/roles',[UserController::class,'assignRole'])->name('users.roles');
		Route::delete('/users/{user}/roles/{role}',[UserController::class,'removeRole'])->name('users.roles.remove');
		Route::post('/users/{user}/permissions',[UserController::class,'givePermission'])->name('users.permissions');
		Route::delete('/users/{user}/permissions/{permission}',[UserController::class,'revokePermission'])->name('users.permissions.revoke');
	});

<br>

#### AdminController.php

	class  AdminController  extends  Controller
	{
		public  function  index(){
			return  view('admin.index');
		}
	}
<br>

#### RoleController.php

	<?php
	namespace  App\Http\Controllers\Admin;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Spatie\Permission\Models\Role;
	use RealRashid\SweetAlert\Facades\Alert;
	use Spatie\Permission\Models\Permission;

	 
	class  RoleController  extends  Controller
	{
		public  function  index()
		{
			$roles  =  Role::whereNotIn('name',['admin'])->get();
			return  view('admin.roles.index',compact('roles'))->with('no', 1);
		}


		public  function  create()
		{
			return  view('admin.roles.create');
		}


		public  function  store(Request  $request)
		{
			$request->validate(['role'=> 'required|min:3']);
			Role::create([
			'name' => $request->role,
			]);
			Alert::success('Success', 'Role has created successfully');
			return  to_route('admin.roles.index')->with('status',"$request->role has successfully created");
		}

	  

		public  function  edit(Role  $role)
		{
			$permissions  =  Permission::all();
			return  view('admin.roles.edit',compact('role','permissions'));
		}

	  
	  
		public  function  update(Request  $request,Role  $role)
		{
			$request->validate(['role'=> 'required|min:3']);
			$role->update([
				'name' => $request->role,
			]);
			Alert::success('Created Successfully', 'nice');
			return  to_route('admin.roles.index')->with('status',"$request->role has successfully updated");	
		}

  

		public  function  destroy(Role  $role)
		{
			$role->delete();
			return  to_route('admin.roles.index')->with('status',"$role->id Deleted Successfully");
		}

	  

		public  function  givePermission(Request  $request,Role  $role)
		{
			if($role->hasPermissionTo($request->permission)){
			Alert::warning('Permission Exists', "$request->permission has been already exists!");
			return  back();
			}
			$role->givePermissionTo($request->permission);
			Alert::success('Permission Added Successfully', "The permission '$request->permission' added");	
			return  back();

		}

	  

		public  function  revokePermission(Role  $role,Permission  $permission){
			if($role->hasPermissionTo($permission)){
				$role->revokePermissionTo($permission);
				Alert::success('Remove permission',"The permission '$permission->name' has been removed from $role->name");
				return  back();
			}
		}

	}

<br>

#### Permission Controller

```
<?php
namespace  App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
**use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;**


class  PermissionController  extends  Controller
{

	public  function  index()
	{
		$permissions  =  Permission::all();
		return  view('admin.permissions.index',compact('permissions'))->with('no', 1);
	}

  
	public  function  create()
	{
		return  view('admin.permissions.create');
	}

  
	public  function  store(Request  $request)
	{
		$request->validate(['permission'=> 'required|min:3']);
		Permission::create([
			'name' => $request->permission,
		]);
		return  to_route('admin.permissions.index')->with('status',"$request->permission has successfully created");
	}

  
	public  function  edit(Permission  $permission)
	{
		$roles  =  Role::all();
		return  view('admin.permissions.edit',compact('permission','roles'));
	}


	public  function  update(Request  $request,Permission  $permission)
	{
		$request->validate(['permission'=> 'required|min:3']);
		$permission->update([
			'name' => $request->permission,
		]);
		return  to_route('admin.permissions.index')->with('status',"$request->permission has successfully updated");
	}

  
	public  function  destroy(Permission  $permission)
	{
		$permission->delete();
		return  to_route('admin.permissions.index')->with('status',"$permission->id Deleted Successfully");
	}

  
	public  function  assignRole(Request  $request,Permission  $permission)
	{
		if($permission->hasRole($request->role)){
			Alert::warning('Role Exists', "$request->role has been already exists!");
			return  back();
		}
		$permission->assignRole($request->role);
		Alert::success('Role Added Successfully', "'$request->role' added");
		return  back();
	}

  
	public  function  removeRole(Permission  $permission,Role  $role)
	{
		if($permission->hasRole($role)){
			$permission->removeRole($role);
			Alert::success('Remove Role',"The permission '$role->name' has been removed from $permission->name");
			return  back();
		}
	}
	
}
```
