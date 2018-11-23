@extends('layouts.app')

@section('content')

    <section class="content">
        <div class="container-fluid">
        <div class="block-header">
            <h2>
                Depo Manager
            </h2>
        </div>

            <!-- Tab -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Vehicles
                                <small>Add, remove, edit vehicles to the system</small>

                                <br>

                                @if (Session::has('message'))
                                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                                @endif
                                @if (Session::has('error'))
                                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif
                                @if (Session::has('success'))
                                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif


                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li role="presentation" class="active"><a href="#home" data-toggle="tab">VEHICLES</a></li>
                                <li role="presentation"><a href="#adduser" data-toggle="tab">ADD VEHICLES</a></li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="home">
                                    <b>All Vehicles</b>

                                    <table class="table table-responsive">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>License Plate</th>
                                            <th>Compartments</th>
                                            <th>Blacklisted</th>
                                            <th>Calibration Chart</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vehicles as $vehicle)
                                            <tr>
                                                <td>
                                                    @if(!is_null($vehicle->image_link))
                                                        <a href="{{url($vehicle->image_link)}}" target="_blank">
                                                            <img src="{{url($vehicle->image_link)}}" width="50" alt="Vehicle">
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{url('/vehicles/'.($vehicle->id))}}">{{$vehicle->license_plate}}</a>
                                                </td>
                                                <td>{{$vehicle->compartments->count()}}</td>
                                                <td>{{$vehicle->blacklisted ? "Yes" : "No"}}</td>
                                                <td>
                                                    <a href="{{url($vehicle->calibration_chart)}}" target="_blank">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                    {!! $vehicles->render() !!}



                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="adduser">
                                    <b>Add Vehicle</b>
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/vehicles/new') }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="body">
                                                    <h2 class="card-inside-title">Vehicle Details</h2>


                                                    <div class="row clearfix">
                                                        <div class="col-md-4 {{ $errors->has('company_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class=" input-group-sm">
                                                                <select name="company_id" class="select2 show-tick" required data-live-search="true">
                                                                    <option value="">Select Company</option>
                                                                    @foreach(\App\Company::all() as $company)
                                                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{$errors->first("company_id") }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row clearfix" >
                                                        <div class="col-md-6 {{ $errors->has('license_plate') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" class="form-control" name="license_plate" autofocus required placeholder="Vehicle License Plate">
                                                                    {{$errors->first("license_plate") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix" >
                                                        <div class="col-md-6 {{ $errors->has('trailer_plate') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" class="form-control" name="trailer_plate"  placeholder="Trailer License Plate">
                                                                    {{$errors->first("trailer_plate") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix field_wrapper" >

                                                        <div class="col-md-12" >
                                                            <label>Vehicle Compartments</label>
                                                        </div>


                                                        <div class="col-md-6" >
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <label>Compartment Name</label>
                                                                    <input type="text" name="comp_name[]" required class="form-control"/>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-6" >
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <label>Capacity</label>
                                                                    <input type="text" name="capacity[]" required class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-primary pull-right add-comp">
                                                                    Add Another Compartment
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row clearfix">
                                                        <div class="col-md-6">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" name="rfid_code" class="form-control" placeholder="RFID Code">
                                                                    {{$errors->first("rfid_code") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-6" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <label>Calibration Chart</label>
                                                                    <input name="calibration_chart"  type="file" required />
                                                                    {{$errors->first("calibration_chart") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-6" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <label>Vehicle Image</label>
                                                                    <input name="vehicle_image"  type="file" />
                                                                    {{$errors->first("vehicle_image") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-success waves-effect">Save</button>


                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Tab -->


    </div>
    </section>



@endsection

@section('scripts')
    <script>
        function rm(nm,artistID){
            bootbox.confirm("Are you sure you want to delete \"" + nm + "\" ? ", function(result) {
                if(result) {

                    $.ajax({
                        url: 'users/delete/' + artistID,
                        type: 'get',
                        headers: {
                            'X-XSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        success: function (html) {
                            location.reload();
                        }
                    });
                }
            });
        }


        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label"
            });




            var maxField = 5; //Input fields increment limitation
            var addButton = $('.add-comp'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div class="col-md-6" >'+
                '<div class="input-group input-group-sm">' +
                ' <div class="form-line">' +
                '<label>Compartment Name</label>' +
                '<input type="text" name="comp_name[]" required class="form-control"/>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6" >' +
                '<div class="input-group input-group-sm">' +
                '<div class="form-line">' +
                '<label>Capacity</label>' +
                '<input type="text" name="capacity[]" required class="form-control"/>' +
                '</div>' +
                '</div>' +
                '</div>'; //New input field html
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });




        });
    </script>
@endsection
