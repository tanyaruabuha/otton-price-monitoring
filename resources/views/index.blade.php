{{--<html lang="en">--}}
{{--<head>--}}
{{--    <title>Price monitoring</title>--}}
{{--    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.4/js/uikit.min.js"></script>--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.4/css/uikit.min.css"/>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.4/js/components/notify.min.js"></script>--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.4/css/components/notify.min.css"/>--}}
{{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--    <style>--}}
{{--        .btn {--}}
{{--            font-size: 24px;--}}
{{--        }--}}

{{--        label {--}}
{{--            font-size: 18px;--}}
{{--        }--}}

{{--        .form-control {--}}
{{--            height: 54px;--}}
{{--            font-size: 24px;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}

{{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"--}}
{{--      integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">--}}

{{--<table class="table table-bordered" style="text-align: center">--}}
{{--    <thead class="thead-dark">--}}
{{--    <tr>--}}
{{--        <th scope="col">ID</th>--}}
{{--        <th scope="col">--}}
{{--            <button class="btn btn-dark btn-lg" data-toggle="modal" data-target="#exampleModal" style="position: absolute;--}}
{{--                margin-left: -15%;--}}
{{--                top: 0.5px;--}}
{{--                padding: 5px 50px;">Edit price--}}
{{--            </button>--}}
{{--            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
{{--                 aria-hidden="true">--}}
{{--                <div class="modal-dialog" role="document">--}}
{{--                    <div class="modal-content">--}}
{{--                        <div class="modal-header">--}}
{{--                            <h4 class="modal-title" id="exampleModalLabel" style="color: black">Edit--}}
{{--                                price-monitoring</h4>--}}
{{--                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                <span aria-hidden="true">&times;</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <div class="modal-body" style="text-align: left">--}}

{{--                            <form class="form-horizontal">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="productId" style="color:#000;">Product Number</label>--}}
{{--                                    <input type="text" class="form-control" name="orderId" id="productId"--}}
{{--                                           aria-describedby="emailHelp" placeholder="Enter product number">--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="number" style="color:#000;">Price</label>--}}
{{--                                    <input type="text" class="form-control" id="number" placeholder="Price"--}}
{{--                                           name="orderNum">--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0; color: black">--}}
{{--                                        <label>--}}
{{--                                            <input type="radio" name="service" value="UK" checked="checked" title="DHL"--}}
{{--                                                   class="service" style="color: black"> Racquet Depot UK--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;color: black">--}}
{{--                                        <label>--}}
{{--                                            <input type="radio" name="service" value="WD" title="Spring" class="service"--}}
{{--                                                   style="color: black"> W & D STRINGS--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <button class="btn btn-success" style="padding: 6px 50px;" id="priceForUK"--}}
{{--                                        name="priceForUK">UPDATE--}}
{{--                                </button>--}}
{{--                            </form>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <span style="margin-left: 40px">Product</span>--}}
{{--        </th>--}}
{{--        <th scope="col">RD</th>--}}
{{--        <th scope="col" colspan="2">WD</th>--}}
{{--        <th scope="col">SW</th>--}}

{{--        <th scope="col">TN</th>--}}

{{--        <th scope="col">AP</th>--}}
{{--        <th scope="col">TP</th>--}}
{{--        <th scope="col">SI</th>--}}
{{--        <th scope="col">TWE</th>--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    @foreach($data as $key => $product)--}}
{{--        <tr>--}}
{{--            <th scope="row">{{ $product["number"] }}</th>--}}
{{--            <td>{{ $product["name"] }}</td>--}}
{{--            <td>{{ $product["rd"] }}</td>--}}
{{--            <td style="color: darkgray; font-size: 14px"> {{ $product["wd1"] }}</td>--}}
{{--            @if($key - 1 != -1)--}}
{{--                <td style="{{ $product["wd2"] < $data[$key - 1]["wd2"] ? "background: #32CD32" : "background: #FF4500" }}"> {{ $product["wd2"] }} </td>--}}
{{--            @else--}}
{{--                <td style="background: #FF4500"> {{ $product["wd2"] }} </td>--}}
{{--            @endif--}}
{{--            <td>{{ $product["PCF_STRINGER"] }}</td>--}}
{{--            <td>{{ $product["PCF_TENNISNU"] }}</td>--}}
{{--            <td>{{ $product["PCF_APOLLOLE"] }}</td>--}}
{{--            <td>{{ $product["PCF_TENNISPO"] }}</td>--}}
{{--            <td>{{ $product["PCF_SMASHINN"] }}</td>--}}
{{--            <td>{{ $product["PCF_TENNISWA"] }}</td>--}}
{{--        </tr>--}}
{{--    @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}
{{--<div class="container">--}}
{{--    <div class="row">--}}
{{--        <div class="col-8">--}}
{{--            {{ $products->links() }}--}}
{{--        </div>--}}
{{--        <div class="col text-right">--}}
{{--            Last update: {{ $lastUpdate }} @if(isset($status)) ({{ $status }}) @endif--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script>--}}
{{--    $(function () {--}}
{{--        $("#priceForUK").on("click", function (e) {--}}
{{--                e.preventDefault();--}}
{{--                var productId = $("#productId").val();--}}
{{--                var number = $("#number").val();--}}
{{--                var service = $(".service:checked", ".form-horizontal").val();--}}
{{--                if (productId === "" || number === "") {--}}
{{--                    UIkit.notify({--}}
{{--                        message: "Please, enter product number",--}}
{{--                        status: "danger", timeout: 3000, pos: "top-right"--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    var formData = $("#trackForm").serializeArray();--}}

{{--                    console.log(formData);--}}

{{--                    $.ajax({--}}
{{--                        url: "edit-price",--}}
{{--                        type: "POST",--}}
{{--                        data: {"productId": productId, "price": number, "service": service},--}}
{{--                        success: function (data) {--}}

{{--                            if (data.length !== 0) {--}}
{{--                                console.log(data);--}}

{{--                                let message = `Brightpearl update: ${data.brightpearlUpdateSuccess} <br> Big commerce update: ${data.bigCommerceUpdateSuccess}`;--}}

{{--                                $("#debugData").html(data);--}}
{{--                                UIkit.notify({--}}
{{--                                    message: message,--}}
{{--                                    status: "primary",--}}
{{--                                    timeout: 3000,--}}
{{--                                    pos: "top-right"--}}
{{--                                });--}}
{{--                            } else {--}}
{{--                                UIkit.notify({--}}
{{--                                    message: "No files to delete",--}}
{{--                                    status: "warning",--}}
{{--                                    timeout: 3000,--}}
{{--                                    pos: "top-right"--}}
{{--                                });--}}
{{--                            }--}}
{{--                        }--}}
{{--                    });--}}
{{--                }--}}
{{--            }--}}
{{--        );--}}
{{--    })--}}
{{--</script>--}}
{{--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>--}}

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"--}}
{{--        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"--}}
{{--        crossorigin="anonymous"></script>--}}
{{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"--}}
{{--        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"--}}
{{--        crossorigin="anonymous"></script>--}}

{{--</body>--}}
{{--</html>--}}
