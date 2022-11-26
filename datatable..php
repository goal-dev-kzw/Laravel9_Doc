<?php

namespace App\Http\Controllers\Admin\pages;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $roles = Room::all();
            return DataTables::of($roles)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a style="font-family: \'Source Serif Pro\', serif;" href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'"   class=" btn btn-outline-info  text-center editRoomsss py-2 mr-4"> <i class="mdi mdi-eye mx-auto "></i></a>';

                           $btn = $btn. '<a style="font-family: \'Source Serif Pro\', serif;" href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'"  data-original-title="Edit" class="edit btn btn-outline-success  editRoom py-2 mr-4"> <i class="mdi mdi-pencil-box-outline mr-1"></i>Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-danger deleteRoom py-2 mr-4" ><i class="mdi mdi-delete mr-1 "></i>Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('backend.pages.rooms.index');
    }

    public function findRoomType(Request $request)
    {

            $roles = Room::where('type',$request->selectRoom)->get();
            return response()->json($roles);



    }

    public function store(Request $request)
    {
		// $request->validate(['name'=> 'required|min:3']);
        Room::updateOrCreate([
                    'id' => $request->room_id
                ],
                [
                    'room_no' => $request->room_no,
                    'type' => $request->type,
                    'description' => $request->description,
                    'price' => $request->price,

                ]);
        return response()->json(['success'=>'Room saved successfully.']);
    }

    public function edit($id)
    {
        $room = Room::find($id);
        return response()->json($room);
    }


    public function destroy($id)
    {
        Room::find($id)->delete();

        return response()->json(['success'=>'Room deleted successfully.']);
    }
}
