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
                                            <th>License Plate</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Loaded</th>
                                            <th>SMS Code</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{url('/vehicles/'.(optional($order->vehicle)->id))}}">{{optional($order->vehicle)->license_plate}}</a>
                                                </td>
                                                <td>{{optional($order->product)->product_name}}</td>
                                                <td>{{$order->quantity}}</td>
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
                                    <b>Add Vehicle</b>
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/orders/new') }}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="body">
                                                    <h2 class="card-inside-title">Order Details</h2>

                                                    <input type="hidden" name="depot_id" value="{{$depot->id}}">

                                                    <div class="row clearfix">
                                                        <div class="col-md-4 {{ $errors->has('vehicle_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class=" input-group-sm">
                                                                    <select name="vehicle_id" class="select2 show-tick" required data-live-search="true">
                                                                        <option value="">Select Vehicle</option>
                                                                        @foreach(\App\Vehicle::where('blacklisted', 0)->get() as $vehicle)
                                                                            <option value="{{$vehicle->id}}">{{$vehicle->license_plate}} - ({{$vehicle->capacity}})</option>
                                                                        @endforeach
                                                                    </select>
                                                                    {{$errors->first("vehicle_id") }}

                                                                OR

                                                            </div>

                                                        </div>

                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newVehicleModal">Create new vehicle</button>

                                                        </div>
                                                    </div>


                                                    <div class="row clearfix">
                                                        <div class="col-md-4 {{ $errors->has('product_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class=" input-group-sm">
                                                                    <select name="product_id" class="select2 show-tick" required data-live-search="true">
                                                                        <option value="">Select Product</option>
                                                                        @foreach(\App\Product::all() as $product)
                                                                            <option value="{{$product->id}}">{{$product->product_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    {{$errors->first("product_id") }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2" style="margin-bottom: 0px">
                                                            <input type="checkbox" id="basic_checkbox_2" name="loaded" class="filled-in" checked="">
                                                            <label for="basic_checkbox_2">Vehicle is loaded</label>
                                                        </div>

                                                    </div>


                                                    <div class="row clearfix" style="margin-top: 20px">
                                                        <div class="col-md-6 {{ $errors->has('quantity') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="number" class="form-control" name="quantity" required placeholder="Quantity">
                                                                    {{$errors->first("quantity") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row clearfix">
                                                        <div class="col-md-6" style="margin-bottom: 0px">
                                                            <div class="input-group input-group-sm">
                                                                <div class="form-line">
                                                                    <input type="text" name="sms_code"  class="form-control" placeholder="SMS Code">
                                                                    {{$errors->first("sms_code") }}
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
                                    <div class="col-md-8 {{ $errors->has('company_id') ? ' has-error' : '' }}" style="margin-bottom: 0px">
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


                                <div class="row clearfix" style="margin-top: 20px">
                                    <div class="col-md-8 {{ $errors->has('license_plate') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="license_plate" autofocus required placeholder="License Plate">
                                                {{$errors->first("license_plate") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-8" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <input type="text" name="capacity" required class="form-control" placeholder="Capacity">
                                                {{$errors->first("capacity") }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-8" style="margin-bottom: 0px">
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
                                    <div class="col-md-8" style="margin-bottom: 0px">
                                        <div class="input-group input-group-sm">
                                            <div class="form-line">
                                                <label>Vehicle Image</label>
                                                <input name="vehicle_image"  type="file" required />
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


        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label"
            });
        });
    </script>
@endsection
