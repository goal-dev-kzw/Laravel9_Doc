@extends('backend.layouts.app')

@section('content')


<div class="card p-3" style="overflow-x:auto;">
<a class="btn col-md-2  py-2 my-3 btn-primary btn-icon-text" href="javascript:void(0)" id="createNewRoom"><i class="mdi mdi-plus-one btn-icon-prepend mr-1 "></i>Create New</a>
    <table class="table data-table  text-white" >
        <thead>
            <tr>
                <th>No</th>
                <th>Room</th>
                <th>Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th>Living</th>

                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- create form modal --}}
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="roomForm" name="roomForm" class="text-white form-horizontal">
                    @csrf

                    <input type="hidden" name="room_id" id="room_id">
                    <div class="form-group">
                        <label for="room_no" class="col-sm-4 control-label">Room Number</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control text-white" id="room_no" name="room_no" placeholder="Enter Room Number" value="" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-4 control-label">Room Type</label>
                        <div class="col-sm-12">
                            <select class="form-control text-white" id="type" name="type">
                                <option value="Single Room" selected>Single Room</option>
                                <option value="Double Room">Double Room</option>
                                <option value="Family Room">Family Room</option>
                                <option value="Premium Room">Premium Room</option>
                                <option value="VIP Room">VIP Room</option>

                              </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-4 control-label">Enter Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control text-white" id="description" name="description" rows="5" placeholder="Description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-4 control-label">Enter Price</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control text-white" id="price" name="price" placeholder="Enter Price" value=""  required="">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- create form modal --}}
@endsection

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
      let room_table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          retrieve: true,
          ajax: {
            url: "{{ route('admin.rooms.index') }}",
        },
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'room_no', name: 'room_no', searchable: true},
              {data: 'type', name: 'type',},
              {data: 'description', name: 'description',},
              {data: 'price', name: 'price',searchable: true},
              {data: 'status', name: 'status',},
              {data: 'is_living', name: 'is_living',},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
         columnDefs: [
            {
                targets:3,   // you can swap the order of column
                render: function(data,type,row){
                    console.log(type);
                    return data.length > 20 ?
                        data.substr(0,17) + '....' : data
                }
            },
            {
                target: 6,
                visible: false,
            },
            {
                targets:5,
                render: function(data,type,row){
                    return data == false && row.is_living == false ?
                        '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:">Booked</a>'
                        : data == false && row.is_living == true ?
                        '<a class="btn py-1 px-3 btn-rounded btn-outline-warning" style="background:; color:;"> Living </a>'
                        : data == true && row.is_living == false ?
                        '<a class="btn py-1 px-3 btn-rounded btn-outline-primary" style="background:; color:;"> Available </a>'
                        : '<a class="btn py-1 px-3 btn-rounded btn-outline-danger" style="background:; color:;"> Wrong Something </a>';
                }
            },
            {
      targets:7,   // you can swap the order of column
      render: function(data,type,row){
          let btn = row.status == 0 ?
          `<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-outline-warning booking py-2 ml-1" ><i class="mdi mdi-account-check mr-2"></i>Check Booking</a>`
          :
          `<a href="javascript:void(0)" style="font-family: \'Source Serif Pro\', serif;" data-toggle="tooltip"  data-id="'.$row->id.'"  data-original-title="Delete" class="btn btn-outline-info booking py-2 ml-1" ><i class="mdi mdi-book-open-pprice-variant mr-2 "></i>Booking Now</a>`

          return  data+btn;

      }
    }
         ]
      });

      $('#createNewRoom').click(function () {
				$('#saveBtn').val("create-room");
				$('#room_id').val('');
				$('#roomForm').trigger("reset");
				$('#modelHeading').html("Create New room");
				$('#ajaxModel').modal('show');
			});

            /*------------------------------------------
    --------------------------------------------
    Create Room Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save Changes...');

        $.ajax({
          data: $('#roomForm').serialize(),
          url: "{{ route('admin.rooms.store') }}",
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
