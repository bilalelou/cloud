@extends('layouts.layout')
@section('content')
<style>

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
            <div id="get_geos_card" id="get_geos_card">
                <div class="card-header">
                    <div class="card-title">Create Azure VM</div>
                </div>
                <div id="get_geos_body" class="card-body">
                    <div class="col-12" id="form1">
                        <div class="row form-group">
                            <div class="col-3"><label for="providers">Providers</label></div>
                            <div class="col-9">
                                <select id="providers" class="form-control select2 select2-show-search" onchange="getSubscriptions(this)">
                                    <option value="" selected disabled>Select provider</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group" id="subscription_div">
                            <div class="col-3"><label for="subscription">Subscription</label></div>
                            <div class="col-9">
                                <select id="subscription" class="form-control select2 select2-show-search"> {{-- onchange="getResourceGroups(this , 'providers')" --}}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" id="form2" style="display: none;">
                        <table style="width: 100%" class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Image</th>
                                    <th>Size</th>
                                    <th>Number of servers</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width=20%>
                                        <select id="region00" class="form-control select2 select2-show-search" onchange="getSizes(this)">
                                        </select>
                                    </td>
                                    <td width=20%>
                                        <select id="image" class="form-control select2 select2-show-search">
                                            <option value="centos7.9">Centos 7.9</option>
                                            <option value="centos8.0">Centos 8.0</option>
                                            <option value="centos8.2">Centos 8.2</option>
                                            <option value="ubuntu20.04">Ubuntu 20.04</option>
                                        </select>
                                    </td>
                                    <td width=20%>
                                        <select id="size" class="form-control select2 select2-show-search">
                                        </select>
                                    </td>
                                    <td width=20%>
                                        <input type="number" id="number_of_servers" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12" id="form33" style="display: none;">
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div id="pmta_settings" class="d-flex justify-content-start align-items-center" style="gap: 10px;opacity: 0;">
                        <label class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="pmta_4.5" name="pmta" value="pmta4_0" checked>
                            <span class="custom-control-label">PMTA 4.0</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="pmta_4.0" name="pmta" value="pmta4_5">
                            <span class="custom-control-label">PMTA 4.5</span>
                        </label>
                    </div>
                    <button id="get_geos" class="btn btn-info card-options" onclick="getRegions()">Get Options</button>
                    <button id="create_vm" style="display: none;" class="btn btn-info card-options" onclick="createVirtualMachines()">Create VMs</button>
                    <button class="btn btn-info" style="display: none;" id="reinstall_button" onclick="reinstallServers()">Reinstall Server(s)</button>
                    <input type="hidden" id="reinstall_servers_ids">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal new resource group --}}
<div id="ModalNewProvider" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="contentRg">
            <div class="modal-header">
                <h5 class="modal-title">Create ressource group</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12" id="form3">
                    <div class="row form-group">
                        <div class="col-3"><label for="providers_rg">Providers</label></div>
                        <div class="col-9">
                            <select id="providers_rg" class="form-control select2 select2-show-search" onchange="getSubscriptions(this)">
                                <option value="" selected disabled>Select provider</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" id="subscription_rg_div">
                        <div class="col-3"><label for="subscription_rg">Subscription</label></div>
                        <div class="col-9">
                            <select id="subscription_rg" onchange="getLocations(this)" class="form-control select2 select2-show-search">
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" id="region_rg_div">
                        <div class="col-3"><label for="region_rg">Region</label></div>
                        <div class="col-9">
                            <select id="region_rg" class="form-control select2 select2-show-search">
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" id="resource_group_rg_div">
                        <div class="col-3"><label for="resource_group_rg">Ressource group name</label></div>
                        <div class="col-9">
                            <input type="text" id="resource_group_rg" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="createResourceGroup()">Create</button>
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
    });
    var providers = @json($providers);

    function getSubscriptions(element)
    {
        if(element.id == "providers") $('#subscription_div').waitMe({effect: 'win8_linear',bg: 'rgba(255,255,255,0.7)',color: '#5b7fff',maxSize: '',waitTime: -1,source: '',textPos: 'vertical',fontSize: '25px',});
        if(element.id == "providers_rg") $('#subscription_rg_div').waitMe({effect: 'win8_linear',bg: 'rgba(255,255,255,0.7)',color: '#5b7fff',maxSize: '',waitTime: -1,source: '',textPos: 'vertical',fontSize: '25px',});

        var providerId = $(element).val();

        if(providerId)
        {
            $.ajax({
                url: "/getSubscriptions",
                method: "POST",
                data: {
                    "providerId": providerId,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response)
                {
                    if(response.success)
                    {
                        var subs = response.subs;
                        var selectSub = document.getElementById("subscription");
                        selectSub.innerHTML = "";
                        var firstIteration = true;
                        
                        for(var key in subs)
                        {
                            sub = subs[key];
                            selectSub.appendChild(new Option(sub.displayName, sub.subscriptionId));
                        }

                        $(selectSub).closest('.form-group').waitMe('hide');
                    }
                    else
                    {
                        toastr.error(response.message);
                        $('#subscription_div').waitMe('hide');
                    }
                }
            })
        }
    }

    // function getResourceGroups(subscription)
    // {
    //     $('#resource_group_div').waitMe({
    //         effect: 'win8_linear',
    //         bg: 'rgba(255,255,255,0.7)',
    //         color: '#5b7fff',
    //         maxSize: '',
    //         waitTime: -1,
    //         source: '',
    //         textPos: 'vertical',
    //         fontSize: '25px',
    //     });

    //     var subscriptionId = $(subscription).val();
    //     var providerId = $('#providers').val();

    //     var data = {
    //         "providerId": providerId,
    //         "subscriptionId": subscriptionId,
    //         "_token": "{{ csrf_token() }}",
    //     };

    //     $.ajax({
    //         url: "/getResourceGroups",
    //         method: "POST",
    //         data: data,
    //         success: function(response)
    //         {
    //             if(response.success)
    //             {
    //                 var selectResourceGroup = document.getElementById("resource_group");
    //                 var resourceGroups = response.groupResources;
    //                 selectResourceGroup.innerHTML = '';
    //                 for(var key in resourceGroups)
    //                 {
    //                     resourceGroup = resourceGroups[key];
    //                     selectResourceGroup.appendChild(new Option(resourceGroup["name"], resourceGroup["id"]));
    //                 }
    //                 $('#resource_group_div').waitMe('hide');
    //             }
    //             else
    //             {
    //                 toastr.error(response.message);
    //                 $('#resource_group_div').waitMe('hide');
    //             }
    //         }

    //     })
    // }

    function getRegions()
    {
        $('#form1').waitMe({effect: 'timer',bg: 'rgba(255,255,255,0.7)',color: '#5b7fff',maxSize: '',waitTime: -1,source: '',textPos: 'vertical',fontSize: '25px',});

        var data = {
            "providerId": $("#providers").val(),
            "subscriptionId": $("#subscription").val(),
            "resource_group": $("#resource_group").val(),
            "_token": "{{ csrf_token() }}",
        }

        $.ajax({
            url: "/getRegions",
            method: "POST",
            data: data,
            success: function(response)
            {
                var regions = response.regions;
                var selectRegion = $("#region00");
                var firstIteration = true;
                for(var key in regions)
                {
                    region = regions[key];
                    selectRegion.append(new Option(region.displayName, region.location));
                    if(firstIteration){
                        getSizes(selectRegion);
                        firstIteration = false;
                    }
                }

                $("#form1").waitMe('hide');
                $("#form1").css("display", "none");
                $("#form2").css("display", "block");
                $("#create_vm").css("display", "block");
                $("#get_geos").css("display", "none");
            }
        })
    }

    function getSizes(element)
    {
        var region = $(element).val();
        var providerId = $("#providers").val();
        var subscriptionId = $("#subscription").val();

        var data = {
            "region": region,
            "providerId": providerId,
            "subscriptionId": subscriptionId,
            "_token": "{{ csrf_token() }}",
        };

        $.ajax({
            url: "/getSizes",
            method: "POST",
            data: data,
            success: function(response)
            {
                sizes = response.sizes;
                for(var key in sizes)
                {
                    size = sizes[key];
                    $("#size").append(new Option(size["name"] + " - " + size["numberOfCores"] + " vcpus - " + size["memoryInMB"] + " MB RAM", size["name"]));
                }
            }
        })
    }

    function getLocations(providerId, selectSub)
    {
        $('#region_rg_div').waitMe({effect: 'win8_linear',bg: 'rgba(255,255,255,0.7)',color: '#5b7fff',maxSize: '',waitTime: -1,source: '',textPos: 'vertical',fontSize: '25px',});
        var data = {
            "providerId": providerId,
            "subscriptionId": $('#' + selectSub).val(),
            "_token": "{{ csrf_token() }}"
        }

        $.ajax({
            url: "/getRegions",
            method: "POST",
            data: data,
            success: function(response)
            {
                var regions = response.regions;
                var selectRegion = $("#region_rg");
                var firstIteration = true;
                for(var key in regions){
                    region = regions[key];
                    selectRegion.append(new Option(region["displayName"], region["name"]));                                
                }

                $('#region_rg_div').waitMe('hide');
            }
        })   

    }

    function createResourceGroup()
    {
        $('#contentRg').waitMe({effect: 'timer',bg: 'rgba(255,255,255,0.7)',color: '#5b7fff',maxSize: '',waitTime: -1,source: '',textPos: 'vertical',fontSize: '25px',});

        var data = {
            "providerId": $("#providers_rg").val(),
            "subscriptionId": $("#subscription_rg").val(),
            "resourceGroupName": $("#resource_group_rg").val(),
            "location": $("#region_rg").val(),
            "_token": "{{ csrf_token() }}"
        };

        $.ajax({
            url: "/createResourceGroup",
            method: "POST",
            data: data,
            success: function(response)
            {
                if(response.success)
                {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
                else
                {
                    toastr.error(response.message);
                    $('#contentRg').waitMe('hide');
                }
            }
        })
    }  
    
    function createVirtualMachines()
    {
        var region = $("#region00").val();
        var image = $("#image").val();
        var size = $("#size").val();
        var numberOfVMs = $("#number_of_servers").val();
        var resourceGroupName = $("#resource_group").val();
        var subscriptionId = $("#subscription").val();
        var providerId = $("#providers").val();

        if(region == "" || image == "" || size == "" || numberOfVMs == "" || resourceGroupName == "" || subscriptionId == "" || providerId == "")
        {
            toastr.error("Please fill all the required fields");
            return;
        }

        $('#get_geos_card').waitMe({
            effect: 'progressBar',
            text: 'Creating Virtual Machines...',
            bg: 'rgba(255,255,255,0.7)',
            color: '#5b7fff',
            maxSize: '',
            waitTime: -1,
            source: '',
            textPos: 'vertical',
            fontSize: '25px',
        });
        
        $.ajax({
            url: "/createVirtualMachines",
            method: "POST",
            data: {
                "region": $("#region00").val(),
                "image": $("#image").val(),
                "size": $("#size").val(),
                "numberOfVMs": $("#number_of_servers").val(),
                "resourceGroupName": $("#resource_group").val(),
                "subscriptionId": $("#subscription").val(),
                "providerId": $("#providers").val(),
                "_token": "{{ csrf_token() }}",
            },
            success: function(response)
            {
                if(response.success)
                {
                    toastr.success(response.message);
                    $('#get_geos_card').waitMe('hide');
                    $('#reinstall_servers_ids').val(response.successful_vms);
                    $('#create_vm').css("display", "none");
                    $('#reinstall_button').css("display", "block");
                    $('#form2').css("display", "none");

                    $('#form33').html('');
                    var installedServers = `<div class="col-12">
                                                <ul>`;
                    for(let i = 0; i < response.servers.length; i++)
                    {
                        installedServers +=         `<li style="${i < response.servers.length - 1 ? "margin-bottom: 7px;" : ""}">
                                                        <div class="col-12" style="display: flex;justify-content: space-between;align-items: center;padding: 15px;border-radius: 5px; border: 1px solid #0069ff;">
                                                            <div>
                                                                <i class="fa fa-desktop" style="color: #2e83e7; margin-right: 5px;" aria-hidden="true"></i>
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

                    $('#form33').append(installedServers);
                    $('#form33').css("display", "block");
                    $('#pmta_settings').css("opacity", "1");
                    
                }
                else
                {
                    toastr.error(response.message);
                    $('#forget_geos_cardm2').waitMe('hide');
                }
            }
        })
    }

    function reinstallServers()
    {
        $('#get_geos_card').waitMe({
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
                var results = response.results;
                for(i = 0; i < results.length; i++)
                {
                    toastr.options.timeOut = 5000;
                    if(results[i]["msg"].includes("successfully")) toastr.success(results[i]["msg"]);
                    else toastr.error(results[i]["msg"]);
                }
                
                $('#get_geos_card').waitMe('hide');
            }
        })
    }
</script>

@endsection