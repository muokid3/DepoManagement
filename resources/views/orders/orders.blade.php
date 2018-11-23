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
                                Orders - {{$depot->depot_name}}
                                <small>Manage depot orders</small>

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
                                <li role="presentation" class="active"><a href="#home" data-toggle="tab">ORDERS</a></li>
                                <li role="presentation"><a href="#adduser" data-toggle="tab">CREATE ORDER</a></li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="home">

                                    <table class="table table-responsive">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>License Plate</th>
                                            <th>Driver</th>
                                            <th>Loaded</th>
                                            <th>SMS Code</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{url('/orders/'.$order->id)}}">
                                                        <i class="material-icons">remove_red_eye</i> <span class="icon-name">View Order</span>
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="{{url('/vehicles/'.(optional($order->vehicle)->id))}}">{{optional($order->vehicle)->license_plate}}</a>
                                                </td>
                                                <td>{{optional($order->driver)->name}}</td>
                                                <td>{{$order->loaded ? "Yes" : "No"}}</td>
                                                <td>{{$order->sms_code}}</td>
                                                <td>{{$order->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                    {!! $orders->render() !!}



                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="adduser">
                                    <b>Create new order</b>
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/orders/new') }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="body">

                                                    <div class="row clearfix">
                                                        <div class="col-md-6 col-xs-12">
                                                            <h2 style="text-align: center;" class="card-inside-title">Order Details</h2>

                                                            <input type="hidden" name="depot_id" value="{{$depot->id}}">

                                                            <div class="row clearfix">
                                                                <div class="col-md-8 {{ $errors->has('company_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div class=" input-group-sm">
                                                                        <select name="company_id" class="select2 show-tick" required data-live-search="true">
                                                                            <option value="" selected>Select Company</option>
                                                                            @foreach(\App\Company::all() as $company)
                                                                                <option value="{{$company->id}}">{{$company->company_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        {{$errors->first("company_id") }}

                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="row clearfix">
                                                                <div class="col-md-8 {{ $errors->has('vehicle_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div class=" input-group-sm">
                                                                        <select id="vehicle-drop" name="vehicle_id" class="select2 show-tick" required data-live-search="true">
                                                                            <option value="">Select Vehicle</option>
                                                                            @foreach(\App\Vehicle::where('blacklisted', 0)->get() as $vehicle)
                                                                                <option value="{{$vehicle->id}}">{{$vehicle->license_plate}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        {{$errors->first("vehicle_id") }}

                                                                        OR

                                                                    </div>

                                                                </div>

                                                                <div class="col-md-4">
                                                                    <button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newVehicleModal">Create new vehicle</button>

                                                                </div>
                                                            </div>


                                                            <div class="row clearfix">
                                                                <div class="col-md-8 {{ $errors->has('driver_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div class=" input-group-sm">
                                                                        <select name="driver_id" class="select2 show-tick" required data-live-search="true">
                                                                            <option value="">Select Driver</option>
                                                                            @foreach(\App\Driver::all() as $driver)
                                                                                <option value="{{$driver->id}}">{{$driver->name}} - ({{$driver->phone_no}})</option>
                                                                            @endforeach
                                                                        </select>
                                                                        {{$errors->first("driver_id") }}

                                                                        OR

                                                                    </div>

                                                                </div>

                                                                <div class="col-md-4">
                                                                    <button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newDriverModal">Create new driver.</button>

                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="input-group input-group-sm">
                                                                        <div class="form-line">
                                                                            <label>Upload Order Document</label>
                                                                            <input name="order_document"  type="file" />
                                                                            {{$errors->first("order_document") }}
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b>Compartments</b>

                                                                    <table class="table table-responsive" id="compartments">


                                                                    </table>

                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-6 col-xs-12">

                                                            <h2 style="text-align: center;" class="card-inside-title">Vehicle Details</h2>


                                                            <div class="row clearfix">
                                                                <div class="col-md-12 {{ $errors->has('image') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div id="vehicle-body" class="input-group input-group-sm">
                                                                        <img id="vehicle-img" src="" width="100%" class="img-responsive" alt="image">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                                <div class="row">

                                                                    <div class="col-sm-6" id="licence-plate">
                                                                        License Plate:
                                                                    </div>

                                                                    <div class="col-sm-6" id="trailer">
                                                                        Trailer Plate: <strong></strong>
                                                                    </div>
                                                                </div>


                                                                <div class="row">

                                                                    <div id="calibration-body" class="col-sm-6">
                                                                        Calibration Chart: <strong><a id="calibration-chart" href="" target="_blank">View</a> </strong>
                                                                    </div>

                                                                    <div class="col-sm-6" id="rfid">
                                                                        RFID Code: <strong></strong>
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



    <div class="modal fade" id="newVehicleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">New Vehicle</h4>
                </div>

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


    <div class="modal fade" id="newDriverModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">New Driver</h4>
                </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/drivers/new') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="card">
                            <div class="body">
                                <h2 class="card-inside-title">Driver Details</h2>

                                <div class="row clearfix">
                                    <div class="col-md-12 {{ $errors->has('driver_name') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="driver_name" autofocus required placeholder="Driver Name">
                                                {{$errors->first("driver_name") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-12" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="id_no" required class="form-control" placeholder="ID/Passport Number">
                                                {{$errors->first("id_no") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-12" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="number" name="phone_no" required class="form-control" placeholder="Phone Number">
                                                {{$errors->first("phone_no") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                    </div>

                </form>


            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script src="{{url('/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

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


        var vehicle = $("#vehicle-drop");

        var vehicleBody = $("#vehicle-body");
        var calibrationBody = $("#calibration-body");
        var trailer = $("#trailer");
        var licencePlate = $("#licence-plate");
        var rfid = $("#rfid");
        var driverLabel = $("#current-driver");
        var vehicleImg = document.getElementById("vehicle-img");
        var calibrationChart = document.getElementById("calibration-chart");
        var compartments = $("#compartments");


        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label"
            });

            licencePlate.hide();
            rfid.hide();
            vehicleBody.hide();
            calibrationBody.hide();
            trailer.hide();


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


        vehicle.on('change', function () {
            $.ajax("{{url('/get_vehicle')}}/" + vehicle.val(), {
                success: function (message) {
                    console.log(message);

                    trailer.show();
                    licencePlate.show();
                    rfid.show();
                    vehicleBody.show();
                    calibrationBody.show();

                    var temp = JSON.parse(message);
                    rfid.html('RFID Code: <strong>' + temp.rfid + '</strong>');
                    licencePlate.html('License Plate: <strong>' + temp.licence + '</strong>');
                    trailer.html('Trailer Plate: <strong>' + temp.trailer + '</strong>');
                    calibrationChart.setAttribute("href", temp.calibration_chart_link);

                    if (temp.image_link){
                        vehicleImg.setAttribute("src", temp.image_link);
                    }else {
                        vehicleBody.hide();
                    }

                },
                error: function (error) {
                    console.log(error);
                    trailer.hide();
                    licencePlate.hide();
                    rfid.hide();
                }
            });


            $.ajax("{{url('/get_vehicle_compartments')}}/" + vehicle.val(), {
                success: function (message) {
                    console.log(message);
                    var temp = JSON.parse(message);
//                    var listItems = "<option value='' disabled>--Select your sub county--</option>";
                    var listItems = "<tbody>";
                    $.each(temp, function (i, item) {

                        listItems += '<tr>' +
                                        '<td>' + item.name + '</td>' +
                                        '<td>' +
                                            '<div class=" input-group-sm"><select name="product_' + item.id + '" class="select2 show-tick" required data-live-search="true">'+
                                                '<option value="">Select Product</option>' +
                                                '@foreach(\App\Product::all() as $product)' +
                                                    '<option value="{{$product->id}}">{{$product->product_name}}</option>' +
                                                '@endforeach'+
                                            '</select></div>' +
                                        '</td>' +
                                        '<td>' +
                                            '<div class="input-group input-group-sm"><div class="form-line"><input type="text" class="form-control" name="quantity_' + item.id + '" required placeholder="Quantity"> </div></div>' +
                                        '</td>' +
                                    '</tr>';
                    });

                    listItems += '</tbody>';
                    compartments.html(listItems);
                    //scounty.attr("disabled", false);

                },
                error: function (error) {
                    console.log(error);
                    trailer.hide();
                    licencePlate.hide();
                    rfid.hide();
                    compartments.hide();
                }
            });
        });

        $("#add-comp").on("click", function (e) {
            e.preventDefault();
            var site = $("#link").val();
            var year = $("#year").val();
            var account = $("#account_name").val();
            var rating = $("#rating").val();
            var desc = $("#desc").val();
            $.ajax({
                    type: "POST",
                    url: "{{url('/add_site')}}",
                    data: {site: site, year: year, account: account, rating: rating, desc: desc, _token: "{!! csrf_token() !!}"},
                    success: function (message) {
                        $("#sites").html(message)
                    },
                    error: function (error) {
                        console.log(error);
                    }
                }
            );
        });




    </script>
@endsection
