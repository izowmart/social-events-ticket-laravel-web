@extends('home.layout')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/smart_wizard_theme_arrows.min.css') }}">
    <style>
        .quantity {
            position: relative;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .quantity input {
            width: 45px;
            height: 42px;
            line-height: 1.65;
            float: left;
            display: block;
            padding: 0;
            margin: 0;
            padding-left: 20px;
            border: 1px solid #eee;
        }

        .quantity input:focus {
            outline: 0;
        }

        .quantity-nav {
            float: left;
            position: relative;
            height: 42px;
        }

        .quantity-button {
            position: relative;
            cursor: pointer;
            border-left: 1px solid #eee;
            width: 20px;
            text-align: center;
            color: #333;
            font-size: 13px;
            font-family: "Trebuchet MS", Helvetica, sans-serif !important;
            line-height: 1.7;
            -webkit-transform: translateX(-100%);
            transform: translateX(-100%);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;
            user-select: none;
        }

        .quantity-button.quantity-up {
            position: absolute;
            height: 50%;
            top: 0;
            border-bottom: 1px solid #eee;
        }

        .quantity-button.quantity-down {
            position: absolute;
            bottom: -1px;
            height: 50%;
        }

        .sold-out {
            background-color: red;
            color: #fff;
            position: absolute;
            padding: 0px 3px;
            margin-left: 5px;
        }
    </style>
@endsection
@section('content')
    <section class="parallax-window" data-parallax="scroll"
             data-image-src="{{url('storage/images/events/'.$event->media_url)}}"
             data-natural-width="1400" data-natural-height="">
        <div class="parallax-content-1">
            <div class="animated fadeInDown">
                <h1>{{$event->name}}</h1>
            </div>
        </div>
    </section>
    <!-- End section -->

    <main>
        <div id="position">
            <div class="container">
                <ul>
                    <li><a href="#">Home</a>
                    </li>
                    <li><a href="#">{{$event->name}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End position -->

        <div class="collapse" id="collapseMap">
            <div id="map" class="map"></div>
            <input type="hidden" value="{{$event->latitude}}" id="latitude">
            <input type="hidden" value="{{$event->longitude}}" id="longitude">
        </div>
        <!-- End Map -->

        <div class="container margin_60">
            <div class="row">
                <div class="col-lg-8" id="single_tour_desc">

                    <div id="single_tour_feat">
                        <ul>
                            {{--<li><i class="icon-money"></i>Kes 1500</li>--}}
                            <li>
                                <i class="icon-calendar"></i>{{\App\Http\Traits\UniversalMethods::getEventDateTimeStr($event->event_dates)}}
                            </li>
                            <li><i class="icon-location"></i>{{$event->location}}</li>
                            {{--<li><i class="icon_set_1_icon-83"></i>Starts at Midnight</li>--}}
                        </ul>
                    </div>

                    <p class="d-none d-md-block d-block d-lg-none"><a class="btn_map" data-toggle="collapse"
                                                                      href="#collapseMap" aria-expanded="false"
                                                                      aria-controls="collapseMap"
                                                                      data-text-swap="Hide map"
                                                                      data-text-original="View on map">View on map</a>
                    </p>
                    <!-- Map button for tablets/mobiles -->

                    <div class="row">
                        <div class="col-lg-3">
                            <h3>Description</h3>
                        </div>
                        <div class="col-lg-9">
                            <p>
                                {!!$event->description!!}
                            </p>
                        </div>
                    </div>

                    <hr>

                </div>
                <!--End  single_tour_desc-->

                <aside class="col-lg-4">
                    <p class="d-none d-xl-block d-lg-block d-xl-none">
                        <a class="btn_map" data-toggle="collapse" href="#collapseMap" aria-expanded="false"
                           aria-controls="collapseMap" data-text-swap="Hide map" data-text-original="View on map">View
                            on map</a>
                    </p>
                    @if($event->type == 2)
                    <div class="box_style_1 expose">
                        <h3 class="inner">- Tickets Info -</h3>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th colspan="2">
                                            Terms and Conditions Apply
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ticket_categories as $ticket_category)
                                        <tr>
                                            <td>
                                                {{$ticket_category->name}}
                                            </td>
                                            <td>
                                                Ksh {{$ticket_category->price}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <a class="btn_full" href="#purchase-dialog" id="purchase-btn">Buy now</a>
                        {{--<a class="btn_full_outline" href="#"><i class=" icon-heart"></i> Add to whislist</a>--}}
                    </div>
                    @endif

                    {{--<a class="btn_full" href="#purchase-dialog" id="purchase-btn">Buy Now</a>--}}
                </aside>
            </div>

            <!--End row -->
        </div>
        <!--End container -->

        <div id="overlay"></div>
        <!-- Mask on input focus -->

        <div id="purchase-dialog" class="zoom-anim-dialog mfp-hide">
            <div>
                <table>

                </table>
            </div>
            <form action="{{ route('encryption_url') }}" method="post" id="myForm">
            @csrf
            <!-- SmartWizard html -->
                <div id="smartwizard">
                    <ul>
                        <li><a href="#ticket_details">Step 1<br/>
                                <small>Grab your tickets!</small>
                            </a></li>
                        <li><a href="#personal_details">Step 2<br/>
                                <small>Tell us who you are</small>
                            </a></li>
                    </ul>

                    <div>
                        <div id="ticket_details">
                            <h2>Grab your tickets!</h2>
                            <div id="form-step-0" role="form" data-toggle="validator">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $total_categories = 0; ?>
                                    @foreach ($ticket_categories as $ticket_category)
                                        <?php $total_categories++ ?>
                                        @if (App\Http\Traits\UniversalMethods::getRemainingCategoryTickets($event->id,$ticket_category->category_id)>0)
                                            <tr>
                                                <td>{{$ticket_category->name}}</td>

                                                <td id="{{$ticket_category->slug}}_price">{{$ticket_category->price}}</td>
                                                <td>
                                                    <div class="form-group quantity">
                                                        <input type="number" class="form-control quantity"
                                                               style="width: 90px" min="0"
                                                               max="{{App\Http\Traits\UniversalMethods::getRemainingCategoryTickets($event->id,$ticket_category->category_id)}}"
                                                               name="{{$ticket_category->slug}}_quantity" value="0"
                                                               id="{{$ticket_category->slug}}_quantity">
                                                    </div>
                                                </td>
                                                <td class="total" id="{{$ticket_category->slug}}_total">0</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <h5>Subtotal: Ksh <input type="number" style="border:none" value="0"
                                                             data-error="Choose atleast one quantity to proceed" min="1"
                                                             name="subtotal" id="subtotal" readonly required></h5>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>
                        <div id="personal_details">
                            <h2>Tell us who you are</h2>
                            <div id="form-personal_details" role="form" data-toggle="validator">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                           data-error="Your first name is required" placeholder="Input your first name"
                                           required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                           data-error="Your last name is required" placeholder="Input your last name"
                                           required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" aria-describedby="emailHelp"
                                           data-error="Please input a valid email address" name="email" id="email"
                                           placeholder="input your email" required>
                                    <div class="help-block with-errors"></div>
                                    <small class="form-text text-muted" id="ImageHelp">We'll send the ticket(s) to this
                                        email.
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" class="form-control" name="phone" id="phone" max="13"
                                           data-error="A valid phone number is required"
                                           placeholder="Input your phone number" required>
                                    <div class="help-block with-errors"></div>
                                    <small class="form-text text-muted" id="ImageHelp">A number to reach you on
                                    </small>
                                </div>
                                <input type="hidden" name="event_id" value="{{$event->id}}">
                                <input type="hidden" name="ticket_sale_end_date_time"
                                       value="{{$event->ticket_sale_end_date}}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </main>


    <!-- End main -->
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script id="mula-checkout-library" type="text/javascript" src="https://mula.africa/v2/mula-checkout.js"></script>
    <script src="{{ asset('js/plugins/jquery.smartWizard.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.1/dist/sweetalert2.all.min.js"></script>
    <script>
        var main_total = 0;
                @if ($event->type==2)
        var total_categories = eval("{{$total_categories}}");
        if ($('.sold-out').length == total_categories) {
            $('#purchase-btn').remove();

        }
        @endif
        //  custom spinner script start
        jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
        jQuery('.quantity').each(function () {
            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.click(function () {
                var oldValue = parseFloat(input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

            btnDown.click(function () {
                var oldValue = parseFloat(input.val());
                if (oldValue <= min) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

        });
        //  custom spinner script end
    </script>
    @foreach ($ticket_categories as $ticket_category)
        <script>
            $(document).ready(function () {
                var quantity = "{{$ticket_category->slug}}_quantity";
                var price = "{{$ticket_category->slug}}_price";
                var total = "{{$ticket_category->slug}}_total";

                var number = document.getElementById(quantity);
                // no negative numbers
                number.onkeydown = function (e) {
                    if (!((e.keyCode > 95 && e.keyCode < 106)
                        || (e.keyCode > 47 && e.keyCode < 58)
                        || e.keyCode == 8)) {
                        return false;
                    }
                };

                $("#" + quantity).on("change paste keyup", function () {
                    $("#subtotal").val(0);
                    // console.log("start: "+main_total);
                    var get_price = $("#" + price).text();
                    var get_quantity = $("#" + quantity).val();
                    var get_total_price = get_price * get_quantity;
                    $("#" + total).text(get_total_price);

                    // iterate through each td based on class and add the values
                    var sum = 0;
                    $(".total").each(function () {

                        var value = $(this).text();
                        // add only if the value is number
                        if (!isNaN(value) && value.length != 0) {
                            sum += parseFloat(value);
                            // console.log("sum: " + sum);
                        }
                    });
                    $("#subtotal").val(sum);
                });
            });
        </script>
    @endforeach

    <script type="text/javascript">
        $(document).ready(function () {
            $("#subtotal").on('keydown paste', function (e) {
                e.preventDefault();
            });
            $('.total').on("change", function () {
                // console.log("On change triggered");
                $('.total').each(function (index) {
                    var value = $(this).val();
                    // console.log("Total: "+value);
                });
            });
            var encryptionURL = "{{route('encryption_url')}}";
            // Toolbar extra buttons
            var btnFinish = $('<div></div>').text('')
                .addClass('checkout-button')
                .on('click', function () {
                    if (!$(this).hasClass('disabled')) {
                        var elmForm = $("#myForm");
                        if (elmForm) {
                            elmForm.validator('validate');
                            var elmErr = elmForm.find('.has-error');
                            if (elmErr && elmErr.length > 0) {
                                swal({
                                    title: "Error!",
                                    text: "Please fill all the fields before proceeding",
                                    type: "warning",
                                    confirmButtonText: "Okay",
                                    closeOnCancel: true
                                });
                                return false;
                            } else {
                                // alert('Great! we are ready to submit form');
                                // elmForm.submit();

                                var params = $('#myForm').serialize();

                                // console.log(params);

                                function encrypt() {
                                    return fetch(encryptionURL, {
                                        method: 'POST',
                                        body: JSON.stringify(params),
                                        mode: 'cors'
                                    }).then(response => response.json())
                                }

                                // this closes the modal with its overlay
                                $('#exampleModal').modal('toggle');

                                encrypt().then(response => {
                                    console.log("response: " + JSON.stringify(response));
                                    MulaCheckout.renderMulaCheckout({
                                        merchantProperties: response,
                                        checkoutType: 'modal'
                                    })

                                });
                                return false;


                            }

                        }

                    }
                });


            var btnCancel = $('<button></button>').text('Cancel')
                .addClass('btn btn-danger')
                .on('click', function () {
                    $('#smartwizard').smartWizard("reset");
                    $('#myForm').find("input, textarea").val("");
                });


            // Smart Wizard
            $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'arrows',
                transitionEffect: 'slide',
                toolbarSettings: {
                    toolbarPosition: 'bottom',
                    toolbarExtraButtons: [btnFinish]
                },
                anchorSettings: {
                    markDoneStep: true, // add done css
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                }
            });

            $("#smartwizard").on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
                // console.log("leaveStep: \t"+"Step number: "+stepNumber+"\tStep direction: "+stepDirection+"\n")


                var elmForm = $("#form-step-" + stepNumber);
                // stepDirection === 'forward' :- this condition allows to do the form validation
                // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
                if (stepDirection === 'forward' && elmForm) {
                    if ($("#subtotal").val() < 1) {
                        swal({
                            title: "Quantity required!",
                            text: "Please select quantity of ticket type",
                            type: "info",
                            confirmButtonText: "Okay",
                            closeOnCancel: true
                        });
                        return false;
                    }
                } else {
                    $('.mula-checkout-button').remove();
                    $('.mula-logo').remove();
                }
                return true;
            });

            $("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
                // Enable finish button only on last step
                if ($('button.sw-btn-next').hasClass('disabled')) {
                    $('.sw-btn-group-extra').show(); // show the button extra only in the last page and hide next button
                    MulaCheckout.addPayWithMulaButton({className: 'checkout-button', checkoutType: 'modal'});
                    $('.sw-btn-next').hide();
                } else {
                    $('.sw-btn-group-extra').hide();
                    $('.sw-btn-next').show();
                }
                if (stepNumber == 2) {
                    $('.btn-finish').removeClass('disabled');
                } else {
                    $('.btn-finish').addClass('disabled');
                }
            });
            $('.sw-btn-group-extra').hide();

        });
        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();

        function myMap() {
            var myCenter = new google.maps.LatLng(latitude, longitude);
            var mapCanvas = document.getElementById("map");
            var mapOptions = {center: myCenter, zoom: 15};
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({position: myCenter});
            marker.setMap(map);

            var infowindow = new google.maps.InfoWindow({
                content: "{{$event->name}}"
            });
            infowindow.open(map, marker);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO5Else2rW4UNyXiCMp3y20JV7BseTMys&callback=myMap"></script>
@endsection

