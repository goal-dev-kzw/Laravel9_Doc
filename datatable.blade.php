@extends('backend.layouts.app')

@section('content')
<div>
    <x-bread-crumb>
        <x-slot name="title">
            Rooms
        </x-slot>
        <li class="breadcrumb-item"><a href="#">
            Rooms
        </a></li>
            <li class="breadcrumb-item active" aria-current="pprice">View All</li>
    </x-bread-crumb>

    <div class="container card py-3">
        <h1 class="text-primary text-center mx-auto " style="font-family: 'Source Serif Pro', serif;">Rooms</h1>

        <a class="btn col-md-2  py-2 my-3 btn-primary btn-icon-text" href="javascript:void(0)" id="createNewRoom"><i class="mdi mdi-plus-one btn-icon-prepend mr-1 "></i>Create New</a>
        <div class="mx-auto  col-md-12 p-3 card border" style="overflow-x:auto;" >


            <div class="card">
                <div class="card-body row">
                    <form id="typeForm" name="typeForm" class="col-md-4">
                    <div class="form-group">
                        <label><strong>Filter Room :</strong></label>
                        <select id='selectRoom' name="selectRoom" class="text-white form-control" style="width: 200px">
                            <option value="All Room">All Room</option>
                            <option value="Single Room">Single Room</option>
                            <option value="Double Room">Double Room</option>
                            <option value="Family Room">Family Room</option>
                            <option value="Premium Room">Premium Room</option>
                            <option value="VIP Room">VIP Room</option>

                        </select>
                    </div>
                    </form>
                    <form id="statusForm" name="statusForm" class="col-md-4">
                        <div class="form-group">
                            <label><strong>Filter Status :</strong></label>
                            <select id='selectStatus' name="selectStatus" class="text-white form-control" style="width: 200px">
                                <option value="All Status">All</option>
                                <option value="Available">Available</option>
                                <option value="Booked">Booked</option>
                                <option value="Living">Living</option>
                            </select>
                        </div>
                        </form>
                        <div class="form-group col-md-4">
                            <label for="price" class=" control-label">Search Price</label>
                            <div class="">
                                <input type="number" class="form-control text-white" id="searchprice" style="width: 200px;" name="searchprice" placeholder="Enter Price" value="" width="200" required="">
                            </div>
                        </div>
                </div>
            </div>





            <table class="table data-table  text-white">
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
    </div>
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

$( window ).on( "load", function() {



});




    $(function () {

      /*------------------------------------------
       --------------------------------------------
       Pass Header Token
       --------------------------------------------
       --------------------------------------------*/
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
                // console.log(data);
                return data.length > 20 ?
                    data.substr(0,17) + '....' : data
            }
          },
          {
            targets:5,   // you can swap the order of column
            render: function(data,type,row){
                console.log(data,row.is_living);
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
                target: 6,
                visible: false,
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



      $('#assign').html("Create New Room");





      $('#searchprice').on( 'keyup', function () {
        room_table.columns( 4 ).search( this.value ).draw();    // check fix prize
        });


      $('#selectRoom').change(function(){
        var selectedVal = $("#selectRoom option:selected").val();
        if(selectedVal == 'Single Room'){
            room_table.search('Single Room').draw();  // check Single Room
        }else if(selectedVal == 'Double Room'){
            room_table.search('Double Room').draw();  // check Double Room
        }else if(selectedVal == 'Family Room'){
            room_table.search('Family Room').draw();  // check Family Room
        }else if(selectedVal == 'Premium Room'){
            room_table.search('Premium Room').draw();  // check Premium Room
        }else if(selectedVal == 'VIP Room'){
            room_table.search('VIP Room').draw();  // check VIP Room
        }else if(selectedVal == 'All Room'){
            room_table.search('').draw();  // check All Room
        }else{
            room_table.draw();
        }
    });

    $('#selectStatus').change(function(){
        var selectedVal = $("#selectStatus option:selected").val();
        if(selectedVal == 'Available'){
            room_table.columns( 5 ).search( 1 ).columns( 6 ).search( 0 ).draw();   // avialable
        }else if(selectedVal == 'Booked'){
            room_table.columns( 5 ).search( 0 ).columns( 6 ).search( 0 ).draw();   // booked
        }else if(selectedVal == 'Living'){
            room_table.columns( 5 ).search( 0 ).columns( 6 ).search( 1 ).draw();   // Check Living
        }else if(selectedVal == 'All Status'){
            room_table.columns( 5 ).search('').columns( 6 ).search( '' ).draw();  // check All Room
        }else{
            room_table.draw();
        }
    });

      /*------------------------------------------
    --------------------------------------------
    Click to Button
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
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.editRoom', function () {
      var room_id = $(this).data('id');
      $.get("{{ route('admin.rooms.index') }}" +'/' + room_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Room");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#room_id').val(data.id);
          $('#room_no').val(data.room_no);
          $('#type').val(data.type);
          $('#description').val(data.description);
          $('#price').val(data.price);
      })
    });



    $('body').on('click', '.booking', function () {
        var i = $('.booking').data('value');
        if(i == 0){
            $(".booking").html($(".booking").html().replace("Assign", "Check"))
        }else if(i==1){
       $(".booking").html($(".booking").html().replace("Assign", "Book Now"));
        }
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

        /*------------------------------------------
    --------------------------------------------
    Delete Room Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteRoom', function () {

     var room_id = $(this).data("id");
    //  confirm("Are You sure want to delete !");
     const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success ml-4 rounded',
    cancelButton: 'btn btn-danger rounded'
  },
  buttonsStyling: false
})

swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to retrieve this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, delete it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {

                if (result.value) {
                    $.ajax({
         type: "DELETE",
         url: "{{ route('admin.rooms.store') }}"+'/'+room_id,
         success: function (data) {
            room_table.draw();
         },
         error: function (data) {
             console.log('Error:', data);
         }
     });
                    swalWithBootstrapButtons.fire(
      'Deleted!',
      'Room Will Be Delete Right Now.',
      'success'
    )
                }else if(
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ){
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Room is safe :',
      'error'
    )
                }

            });

 });

});



  </script>
@endPushOnce

@pushOnce('css')
<style>

@media (min-width: 992px) {
  .modal-md{
    max-width: 50%; } }

.dataTables_paginate .paginate_button {
    font-size: 0.8em !important;
    padding: 0 !important;
    background: white !important;
}
.dataTables_wrapper .dataTables_filter input,#name,input,select, {
    color: white;
}



</style>
@endPushOnce


