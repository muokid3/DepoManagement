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
                                <li role="presentation" class="active"><a href="#adduser" data-toggle="tab">ORDER DETAILS</a></li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane fade in active" id="adduser">
                                    <b>Order Details</b>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="body">

                                                    <div class="row clearfix">
                                                        <div class="col-md-4 col-xs-12">
                                                            <h2 style="text-align: center;" class="card-inside-title">Order Details</h2>

                                                            <div class="row clearfix">

                                                                @if($order->loaded)
                                                                    <p style="text-align: center" class="font-italic col-teal">Order has been loaded</p>
                                                                @else
                                                                    <div class="col-md-12" style="margin-bottom: 30px">
                                                                        <a href="javascript:void(0);" onclick="mark('{{$order->id}}');">
                                                                            <span class="btn btn-success btn-block waves-effect m-r-20"><span class="glyphicon glyphicon-check"></span>
                                                                            &nbsp;Mark as loaded</span>
                                                                        </a>
                                                                    </div>
                                                                @endif



                                                                <div class="col-md-3" style="margin-bottom: 0px">
                                                                    Vehicle:

                                                                </div>

                                                                <div class="col-md-9">
                                                                    {{$order->vehicle->license_plate}}
                                                                </div>
                                                            </div>


                                                            <div class="row clearfix">
                                                                <div class="col-md-3" style="margin-bottom: 0px">
                                                                    Company:

                                                                </div>

                                                                <div class="col-md-9">
                                                                    {{optional($order->company)->company_name }}
                                                                </div>
                                                            </div>


                                                            <div class="row clearfix">
                                                                <div class="col-md-3" style="margin-bottom: 0px">
                                                                    Driver:

                                                                </div>

                                                                <div class="col-md-9">
                                                                    {{--<button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newVehicleModal">Create new vehicle</button>--}}

                                                                    {{$order->driver->name }} [ {{$order->driver->phone_no }}]
                                                                </div>
                                                            </div>

                                                            <div class="row clearfix">
                                                                <div class="col-md-4" style="margin-bottom: 0px">
                                                                    SMS Code:

                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{--<button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newVehicleModal">Create new vehicle</button>--}}

                                                                    {{$order->sms_code }}
                                                                </div>
                                                            </div>

                                                            <h2 class="card-inside-title">Products</h2>

                                                            <div class="row clearfix">
                                                                <div class="col-md-12" style="margin-bottom: 0px">

                                                                    <table class="table table-responsive">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Compartment</th>
                                                                            <th>Product</th>
                                                                            <th>Quantity</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach(json_decode($order->items, true) as $item)
                                                                            <tr>
                                                                                <td>{{\App\Compartment::find($item['compartment_id'])->name}}</td>
                                                                                <td>{{\App\Product::find($item['product'])->product_name}}</td>
                                                                                <td>{{$item['quantity']}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>

                                                                    </table>

                                                                    {{--{{$order->product->product_name }}--}}

                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{--<button type="button" class="btn btn-success waves-effect m-r-20" data-toggle="modal" data-target="#newVehicleModal">Create new vehicle</button>--}}

                                                                    {{--{{$order->quantity }} L--}}
                                                                </div>
                                                            </div>



                                                        </div>





                                                        <div style="border-left: 2px solid green; height: 100%;" class="col-md-8 col-xs-12">

                                                            <h2 style="text-align: center;" class="card-inside-title">Vehicle Details</h2>


                                                            <div class="row clearfix">
                                                                <div class="col-md-12 {{ $errors->has('image') ? ' has-error' : '' }}" style="margin-bottom: 0px">
                                                                    <div id="vehicle-body" class="input-group input-group-sm">
                                                                        <img id="vehicle-img" src="{{url($order->vehicle->image_link)}}" width="100%" class="img-responsive" alt="image">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                                <div class="row">
                                                                    <div id="calibration-body" class="col-sm-6">
                                                                        Calibration Chart: <strong><a id="calibration-chart" href="{{url($order->vehicle->calibration_chart)}}" target="_blank">View</a> </strong>
                                                                    </div>

                                                                    <div class="col-sm-6" id="company">
                                                                        {{--Company: <strong>{{$order->vehicle->company->company_name}}</strong>--}}
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-sm-6" id="capacity">
                                                                        Capacity: <strong>{{$order->vehicle->capacity}} L</strong>
                                                                    </div>

                                                                    <div class="col-sm-6" id="licence-plate">
                                                                        License Plate: {{$order->vehicle->license_plate}}
                                                                    </div>
                                                                </div>

                                                        </div>

                                                    </div>


                                                </div>
                                            </div>
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





@endsection

@section('scripts')
    <script src="{{url('/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <script>
        function mark(orderID){
            bootbox.confirm("Are you sure you want to mark this order as loaded?", function(result) {
                if(result) {

                    $.ajax({
                        url: '/orders/mark/' + orderID,
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




    </script>
@endsection
