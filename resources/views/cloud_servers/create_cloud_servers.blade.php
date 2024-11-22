@extends('layouts.layout')
@section('content')
<style>
    .drop {
        position: absolute;
        width: 4px;
        height: 80px;
        background-color: #3498db;
        animation: dropAnimation 2s ease forwards;
        transform-origin: bottom;
        opacity: 0;
    }
    .progress {
        width: 96%;
    }
    .prg{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .list-group-item{
        padding: 5px;
        border: #3498db 1px solid;
        border-radius: 10px;
    }
</style>
<link rel="stylesheet" href="css/waitMe.min.css">
<div class="hor-content main-content">
    <div class="container">
        <div class="page-header">
            <div class="page-leftheader">
            </div>
            <div class="page-rightheader px-20">
                
            </div>
        </div>

        <div class="card col-12 p-0">
            <div id="get_geos_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Create DigitalOcean Servers</div>
                    <button id="get_geos" class="btn btn-info card-options" onclick="getAvailableGeos()">Get Options</button>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div id="droplet_settings_card" style="display: none;padding: 0px !important;">
                            <div class="card-body" style="padding: 0px !important;">
                                <div id="droplet_settings"></div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <div id="pmta_settings" class="d-flex justify-content-start align-items-center" style="gap: 10px;opacity: 0;">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="pmta_4.5" name="pmta" value="pmta4_06" checked>
                                        <span class="custom-control-label">PMTA 4.0</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="pmta_4.0" name="pmta" value="pmta4_06">
                                        <span class="custom-control-label">PMTA 4.5</span>
                                    </label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-info" id="save_button" onclick="checkFilledRows()">Save</button>
                                    <button class="btn btn-info" style="display: none;" id="reinstall_button" onclick="reinstallServers()">Reinstall Server(s)</button>
                                    <input type="hidden" id="reinstall_servers_ids">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />

    <script src="{{ asset('assets/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/rating/jquery.rating-stars.js') }}"></script>
    <script src="{{ asset('assets/plugins/horizontal-menu/horizontal-menu.js') }}"></script>
    <script src="{{ asset('assets/js/stiky.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scrollbar/p-scroll.js') }}"></script>

    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>    
    <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <script src="{{ asset('assets/plugins/prism/prism.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>

    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="js/waitMe.min.js"></script>

<script>
    $(document).ready(function() {
        toastr.options.timeOut = 5000;
        @if (Session::has('failed'))
            toastr.error('{{ Session::get('failed') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @elseif(count($errors) > 0)
            @foreach($errors->all() as $error)
                toastr.error('{{$error}}');
            @endforeach
        @endif

        $("#get_geos_card").slideDown(800);
    });
</script>

<script>
    var providers = @json($providers);

    var allRegions = [];
    var allSizes = [];
    var allImages = [];

    function getAvailableGeos()
    {
        allRegions = [];
        allSizes = [];
        allImages = [];
        $('#get_geos_card').waitMe({
            effect: 'timer',
            text: 'Please wait...',
            bg: 'rgba(255,255,255,0.7)',
            color: '#5b7fff',
            maxSize: '',
            waitTime: -1,
            source: '',
            textPos: 'vertical',
            fontSize: '14px',
        });

        jQuery.ajax({
            url: "{{url('/getAvailableGeos')}}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response)
            {   
                if(response.success)
                {   
                    $("#droplet_settings").html('');
                    
                    allRegions = response.region_results.regions;
                    allSizes = response.size_results.sizes;
                    allImages = response.image_results.images;
                    
                    var table = document.createElement("table");
                    table.className = "table text-nowrap";

                    var trh = document.createElement("tr");

                    var th0 = document.createElement("th");
                    th0.innerHTML = "Regions";
                    th0.setAttribute("width", "20%");

                    var th1 = document.createElement("th");
                    th1.innerHTML = "Provider";
                    th1.setAttribute("width", "20%");

                    var th2 = document.createElement("th");
                    th2.innerHTML = "Sizes";
                    th2.setAttribute("width", "20%");

                    var th3 = document.createElement("th");
                    th3.innerHTML = "Images";
                    th3.setAttribute("width", "20%");

                    var th4 = document.createElement("th");
                    th4.innerHTML = "Number of servers";
                    th4.setAttribute("width", "10%");
                
                    trh.appendChild(th0);
                    trh.appendChild(th1);
                    trh.appendChild(th2);
                    trh.appendChild(th3);
                    trh.appendChild(th4);

                    table.appendChild(trh);

                    for (var i in allRegions) {
                        var tr = document.createElement("tr");
                        tr.id = "region_" + allRegions[i].slug;
                        tr.className = "regions";

                        var td0 = document.createElement("td");
                        var td1 = document.createElement("td");
                        var td2 = document.createElement("td");
                        var td3 = document.createElement("td");
                        var td4 = document.createElement("td");

                        var selectPV = document.createElement("select");
                        selectPV.className = "form-control select2 pv";
                        selectPV.id = "pv_" + allRegions[i].slug;
                        selectPV.appendChild(new Option("None", "None"));

                        var selectSize = document.createElement("select");
                        selectSize.className = "form-control select2 size";
                        selectSize.id = "size_" + allRegions[i].slug;
                        selectSize.appendChild(new Option("None", "None"));

                        var selectImage = document.createElement("select");
                        selectImage.className = "form-control select2 image";
                        selectImage.id = "image_" + allRegions[i].slug;
                        selectImage.appendChild(new Option("None", "None"));

                        for(provider of providers)
                        {
                            selectPV.appendChild(new Option(provider["name"] + "-" + provider["cloud_email"] + "-" + provider["id"], provider["id"]));
                        }

                        for(var j in allSizes)
                        {
                            if(allSizes[j]["regions"].indexOf(allRegions[i].slug) != -1)
                            {
                                selectSize.appendChild(new Option(allSizes[j]["slug"], allSizes[j]["slug"]));
                            }
                        }

                        for(var j in allImages)
                        {
                            if(allImages[j]["regions"].indexOf(allRegions[i].slug) != -1)
                            {
                                selectImage.appendChild(new Option(allImages[j]["slug"], allImages[j]["slug"]));
                            }
                        }

                        var inputNumber = document.createElement("input");
                        inputNumber.type = "number";
                        inputNumber.className = "form-control number";
                        inputNumber.id = "number_" + allRegions[i].slug;

                        td0.innerHTML = allRegions[i].name;
                        td1.appendChild(selectPV);
                        td2.appendChild(selectSize);
                        td3.appendChild(selectImage);
                        td4.appendChild(inputNumber);

                        tr.appendChild(td0);
                        tr.appendChild(td1);
                        tr.appendChild(td2);
                        tr.appendChild(td3);
                        tr.appendChild(td4);

                        tr.className = "regions";
                        table.appendChild(tr); 
                    }

                    document.getElementById("droplet_settings").appendChild(table);
                    $("#droplet_settings_card").slideDown(500); 
                    setTimeout(() => {$('#get_geos_card').waitMe('hide');}, 1000);
                    $("#get_geos").css("display", "none");
                }
                else
                {
                    toastr.options.timeOut = 5000;
                    toastr.error(response.msg);

                    $('#get_geos_card').waitMe('hide');
                }
            },
            error: function(xhr)
            {

            }
        });
    }

    function checkFilledRows()
    {
        $('#droplet_settings_card').waitMe({
            effect: 'progressBar',
            text: 'Creating Servers...',
            bg: 'rgba(255,255,255,0.7)',
            color: '#5b7fff',
            maxSize: '',
            waitTime: -1,
            source: '',
            textPos: 'vertical',
            fontSize: '25px',
        });

        var filledRowsData = [];

        for(let key in allRegions)
        {
            var selectPV = document.getElementById("pv_" + allRegions[key].slug);
            var selectSize = document.getElementById("size_" + allRegions[key].slug);
            var selectImage = document.getElementById("image_" + allRegions[key].slug);
            var inputNumber = document.getElementById("number_" + allRegions[key].slug);

            if (selectPV.value !== "None" || selectSize.value !== "None" || selectImage.value !== "None" || inputNumber.value.trim() !== "") 
            {
                var rowData = {
                    region: allRegions[key].slug,
                    provider_id: selectPV.value,
                    size: selectSize.value,
                    image: selectImage.value,
                    numberOfServers: inputNumber.value.trim()
                };

                filledRowsData.push(rowData);
            }
        }
        createCloudServers(filledRowsData);
    }

    function createCloudServers(filledRowsData)
    {
        var data = {
            "_token": "{{ csrf_token() }}",
            "filledRowsData": filledRowsData
        };

        jQuery.ajax({
            url: "{{url('/createCloudServers')}}",
            method: "POST",
            data: data,
            success: function(response)
            {
                if(response.success)
                {
                    toastr.options.timeOut = 5000;
                    toastr.success(response.msg);
                    var servers_id = response.servers_id;
                    $('#droplet_settings_card').waitMe('hide');
                    checkServer(servers_id);
                }
                else
                {
                    toastr.options.timeOut = 5000;
                    toastr.error(response.msg);
                    $('#droplet_settings_card').waitMe('hide');
                }
            },
            error: function(xhr)
            {
                toastr.options.timeOut = 5000;
                toastr.error("Error");
                $('#droplet_settings_card').waitMe('hide');
            }
        });
    }

    function checkServer(servers_id)
    {
        $('#droplet_settings_card').waitMe({
            effect: 'progressBar',
            text: "Please don't refresh, we are setting up your servers...",
            bg: 'rgba(255,255,255,0.7)',
            color: '#5b7fff',
            maxSize: '',
            waitTime: -1,
            source: '',
            textPos: 'vertical',
            fontSize: '30px',
        });

        var data = {
            "_token": "{{ csrf_token() }}",
            "servers_id": servers_id,
        };

        $.ajax({
            url: "{{url('/checkServer')}}",
            method: "POST",
            data: data,
            success: function(response)
            {
                if(response.success)
                {
                    toastr.options.timeOut = 5000;
                    toastr.success(response.msg);
                    $('#droplet_settings_card').waitMe('hide');
                    
                    $('#reinstall_servers_ids').val(response.success_droplets);
                    $('#save_button').css("display", "none");
                    $('#reinstall_button').css("display", "block");

                    $('#droplet_settings').html('');
                    var installedServers = `<div class="col-12">
                                                <ul>`;
                    for(let i = 0; i < response.servers.length; i++)
                    {
                        installedServers +=         `<li style="${i < response.servers.length - 1 ? "margin-bottom: 7px;" : ""}">
                                                        <div class="col-12" style="display: flex;justify-content: space-between;align-items: center;padding: 15px;border-radius: 5px; border: 1px solid #0069ff;">
                                                            <div>
                                                                <i class="fa fa-tint" style="color: #2dce89; margin-right: 5px;" aria-hidden="true"></i>
                                                                <span style="font-size: 13px;color: #0069ff; font-weight: 600;">${response.servers[i]["name"]}</span>
                                                            </div>
                                                            <span style="font-size: 12px;">${response.servers[i]["main_ip"]}</span>
                                                            <span style="font-size: 12px;">${response.servers[i]["os_installed"]}</span>
                                                            <span style="font-size: 12px;" class="${response.servers[i]["status"] == "saved" ? "badge badge-success" : "bg-red"}">${response.servers[i]["status"]}</span>
                                                        </div>
                                                    </li>`;  
                    }

                    installedServers += `       </ul>
                                            </div>
                                            `;

                    $('#droplet_settings').append(installedServers);
                    $('#pmta_settings').css("opacity", "1");
                }
                else
                {
                    toastr.options.timeOut = 5000;
                    toastr.error("Error");
                }
            }
        });
    }

    function reinstallServers()
    {
        $('#droplet_settings_card').waitMe({
            effect: 'progressBar',
            text: "Please wait, we are reinstalling your servers...",
            bg: 'rgba(255,255,255,0.7)',
            color: '#5b7fff',
            maxSize: '',
            waitTime: -1,
            source: '',
            textPos: 'vertical',
            fontSize: '25px',
        });

        const pmta = document.getElementsByName('pmta');
        let selectedOption;
        pmta.forEach(option => {if (option.checked) selectedOption = option.value;});

        var data = {
            "_token": "{{ csrf_token() }}",
            "servers_ids": $('#reinstall_servers_ids').val(),
            "pmta": selectedOption
        };

        $.ajax({
            url: "{{url('/reinstallServers')}}",
            method: "POST",
            data: data,
            success: function(response)
            {
                toastr.options.timeOut = 5000;

                if(response.success)
                {
                    toastr.success(response.msg);
                }
                else
                {
                    toastr.error(response.msg);
                }

                $('#droplet_settings_card').waitMe('hide');
            }
        })
    }
</script>

@endsection