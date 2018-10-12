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



                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <h2>
                                        Vehicle Details
                                        <br>
                                    </h2>
                                </div>


                                <div class="body">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                        <li role="presentation" class="active"><a href="#vehicle-details" data-toggle="tab">VEHICLE DETAILS</a></li>
                                        <li role="presentation"><a href="#edit-vehicle" data-toggle="tab">EDIT VEHICLE</a></li>

                                    </ul>

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="vehicle-details">
                                            <b>Vehicle Details</b>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img src="{{url($vehicle->image_link)}}" width="100%" class="img-responsive" alt="image">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    Blacklisted:  <strong>{{$vehicle->blacklisted ? "Yes" : "No"}}</strong>
                                                </div>

                                                <div class="col-sm-6">
                                                    Calibration Chart: <strong><a href="{{url($vehicle->calibration_chart)}}" target="_blank">View</a> </strong>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-sm-6">
                                                    Capacity: <strong>{{$vehicle->capacity}}</strong>
                                                </div>

                                                <div class="col-sm-6">
                                                    License Plate: <strong>{{$vehicle->license_plate}}</strong>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    Company: <strong>{{$vehicle->company->company_name}}</strong>
                                                </div>
                                            </div>

                                            @if($vehicle->blacklisted)
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Blacklist Reason:
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{optional(\App\BlackList::where('vehicle_id',$vehicle->id)->orderBy('id', 'desc')->first())->reason}}
                                                    </div>
                                                </div>
                                            @endif


                                        </div>

                                        <div role="tabpanel" class="tab-pane fade in" id="edit-vehicle">
                                            <b>Edit Vehicle</b>
                                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/vehicles/update') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="vehicle_id" value="{{$vehicle->id}}">
                                                <div class="modal-body">

                                                    <div class="row clearfix">
                                                        <div class="col-md-12 {{ $errors->has('image') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <img src="{{url($vehicle->image_link)}}" width="100%" class="img-responsive" alt="image">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-6" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                Blacklisted: <strong>{{$vehicle->blacklisted ? "Yes" : "No"}}</strong>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                Calibration Chart: <strong><a href="{{url($vehicle->calibration_chart)}}" target="_blank">View</a> </strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-12 {{ $errors->has('company_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class=" input-group-sm">
                                                                <select name="company_id" class="select2 show-tick" required data-live-search="true">
                                                                    <option value="{{$vehicle->company_id}}">{{$vehicle->company->company_name}}</option>
                                                                    @foreach(\App\Company::all() as $company)
                                                                        <option value="{{$company->id}}">{{$company->company_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{$errors->first("company_id") }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row clearfix">
                                                        <div class="col-md-12 {{ $errors->has('license_plate') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" class="form-control" value="{{$vehicle->license_plate}}" name="license_plate" autofocus required >
                                                                    {{$errors->first("license_plate") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-12 {{ $errors->has('capacity') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" class="form-control" value="{{$vehicle->capacity}}" name="capacity" required >
                                                                    {{$errors->first("capacity") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-12 {{ $errors->has('phone_no') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <button type="submit" class="btn btn-success waves-effect">Update</button>
                                                            @if(!$vehicle->blacklisted)
                                                                <button type="button" class="btn btn-danger waves-effect pull-right" data-toggle="modal" data-target="#blackListVehicle">Blacklist</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class=" col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <h2>
                                        Vehicle Drivers
                                        <br>
                                    </h2>
                                </div>
                                <div class="body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                        <li role="presentation" class="active"><a href="#present" data-toggle="tab">PRESENT/PREVIOUS</a></li>
                                        <li role="presentation"><a href="#reasign" data-toggle="tab">ASSIGN/REASSIGN DRIVER</a></li>

                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="present">
                                            <b>Current Driver</b>

                                            <table class="table table-responsive">

                                                <tbody>
                                                    <tr>
                                                        @if(is_null($vehicle->currentDriver()))
                                                           <td>
                                                               --Vehicle has no current driver--
                                                           </td>
                                                        @else
                                                                <td>{{optional($vehicle->currentDriver()->driver)->name}}</td>
                                                                <td>{{optional($vehicle->currentDriver()->driver)->phone_no}}</td>
                                                                <td>
                                                                    <a href="{{url('/drivers/'.optional($vehicle->currentDriver()->driver)->id)}}">
                                                                        <span class="btn btn-success btn-sm"><span class="glyphicon glyphicon-search"></span>
                                                                        &nbsp;View</span>
                                                                    </a>

                                                                    <a href="#" onclick="rm('{{optional($vehicle->currentDriver()->driver)->name}}','{{optional($vehicle->currentDriver()->driver)->id}}','{{$vehicle->license_plate}}','{{$vehicle->id}}')">
                                                                        <span class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span>
                                                                        &nbsp;Revoke</span>
                                                                    </a>
                                                                </td>
                                                        @endif

                                                    </tr>
                                                </tbody>

                                            </table>


                                            <b>Previous Drivers</b>

                                            <table class="table tabe-responsive">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($vehicle->previousDrivers() as $previousDriver)
                                                    <tr>
                                                        <td>{{optional($previousDriver->driver)->name}}</td>
                                                        <td>{{optional($previousDriver->driver)->phone_no}}</td>
                                                        <td>
                                                            <a href="{{url('/drivers/'.optional($previousDriver->driver)->id)}}">
                                                                        <span class="btn btn-success btn-sm"><span class="glyphicon glyphicon-search"></span>
                                                                        &nbsp;View</span></a>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                                </tbody>

                                            </table>


                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="reasign">
                                            <b>Assign/Reassign Driver</b>
                                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/vehicles/assign_driver') }}">
                                                {{ csrf_field() }}

                                                <div class="modal-body">
                                                        <div class="body">
                                                            <h2 class="card-inside-title">Pick a driver</h2>

                                                            <input type="hidden" name="vehicle_id" value="{{$vehicle->id}}">

                                                            <div class="row clearfix">
                                                                <div class="col-md-12 {{ $errors->has('driver_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div class="input-group input-group-sm">
                                                            <span class="input-group-addon">
                                                                <i class="material-icons">N</i>
                                                            </span>
                                                                        <div class="form-line">
                                                                            <select id="driver_id" name="driver_id" class="populate">
                                                                                @foreach(\App\Driver::all() as $driver)
                                                                                        <option value="{{$driver->id}}">{{$driver->name}}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success waves-effect">Assign</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <!-- #END# Tab -->

    </div>
    </section>



    <div class="modal fade" id="blackListVehicle" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/vehicles/blacklist') }}">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="largeModalLabel">Blacklist {{$vehicle->license_plate}}</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="vehicle_id" value="{{$vehicle->id}}">

                        <div class="row clearfix" style="margin-top: 10px">
                            <div class="col-md-12 ">
                                <div>
                                    <label>Blacklist Reason<span style="color: red">*</span></label>
                                    <textarea name="reason"  rows="5" class="form-control" placeholder="Please provide a valid reason for blacklisting this vehicle" required></textarea>
                                </div>
                                <p style="color: red">{{$errors->first("reason") }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-link waves-effect">BLACKLIST VEHICLE</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </form>

        </div>
    </div>




@endsection

@section('scripts')
    <script>
        function rm(driverName,driverId,vehicleReg,vehicleId){
            bootbox.confirm("Revoke \"" + driverName + "\" from \""+ vehicleReg + "\"?", function(result) {
                if(result) {

                    $.ajax({
                        url: '/vehicles/revoke_driver/' + vehicleId + '/' + driverId,
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
        });
    </script>
@endsection
