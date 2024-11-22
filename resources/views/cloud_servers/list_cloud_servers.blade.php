@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="css/waitMe.min.css">
<style>
    .selected {
        background-color: rgb(0, 174, 255);
        color: white;
    }
</style>
    <div class="hor-content main-content">
        <div class="container">
            <div class="page-header">
            </div>
            <div class="row">
                <div class="col">
                    <div class="card" id="servers_card">
                        <div class="card-header">
                            <div class="card-title">Cloud Servers</div>
                            <div class="card-options">
                                <div class="btn btn-list text-right">
                                    @can('delete_cloud_server')
                                        <button class="btn btn-danger btn-sm" onclick="deleteServersModal()"><i class="fe fe-trash mr-1"></i>Delete Server(s)</button>
                                    @endcan
                                    @canany(['create_digitalocean_server', 'create_linode_server', 'create_hetzner_server','create_azure_server','create_kamatera_server','create_idcloudhost_server'])
                                        <button type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown">
                                            <i class="fe fe-plus mr-1"></i>New Server(s)
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('create_digitalocean_server')
                                                <a class="dropdown-item"  href="/CreateCloudServersIndex">New Digital Ocean Server</a>
                                            @endcan
                                            @can('create_linode_server')
                                                <a class="dropdown-item"  href="/CreateLinodeServersIndex">New Linode Server</a>
                                            @endcan
                                            @can('create_hetzner_server')
                                                <a class="dropdown-item"  href="/CreateHetznerServersIndex">New Hetzner Server</a>
                                            @endcan
                                            @can('create_azure_server')
                                                <a class="dropdown-item"  href="/CreateAzureVMsIndex">New Azure VM</a>
                                            @endcan
                                            @can('create_kamatera_server')
                                                <a class="dropdown-item"  href="/CreateKamateraServersIndex">New Kamatera Server</a>
                                            @endcan
                                            @can('create_idcloudhost_server') 
                                                <a class="dropdown-item"  href="/createIdCloudHostIndex">New IdCloudHost Server</a>
                                            @endcan 
                                        </div>
                                    @endcanany
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="servers" class="table table-bordered text-nowrap">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Main IP</th>
                                            <th>Main Domain</th>
                                            <th>Geo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($servers as $server)
                                            <tr>
                                                <td>{{ $server->id }}</td>
                                                <td>{{ $server->name }}</td>
                                                <td>{{ $server->cloud_type }}</td>

                                                @if($server->status == "new")
                                                    <td><span class="badge badge-warning">New</span></td>
                                                @elseif($server->status == "saved")
                                                    <td><span class="badge badge-primary">Saved</span></td>
                                                @elseif ($server->status == "active")
                                                    <td><span class="badge badge-success">Active</span></td>
                                                @elseif ($server->status == "inactive")
                                                    <td><span class="badge badge-danger">InActive</span></td>
                                                @else
                                                    <td><span class="badge badge-default">{{ $server->status }}</span></td>
                                                @endif

                                                <td>{{ $server->main_ip }}</td>
                                                <td>{{ $server->main_domain }}</td>
                                                <td>{{ $server->geo }}</td>
                                                <td>
                                                    <a href="#" class="text-warning" onclick="updateCloudServers({{ $server->id }})"><i class="ti-pencil-alt" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger">Warning!</h4>
                    <p class="mg-b-20 mg-x-20" id="modalDeleteMessage"></p>
                    <button aria-label="Close" class="btn btn-danger pd-x-25" data-dismiss="modal" type="button" onclick="deleteServers()">Continue</button>
                    <input type="hidden" id="modalDeleteArray">
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
    </script>

    <script>
        $('#servers').DataTable({
            'order': [[ 0, "desc" ]],
            'info': true,
            select: {
                style: 'multiple',
                selector: 'td:first-child',
            },
        });

        function updateCloudServers(server_id)
        {
            var data = {
                "_token": "{{ csrf_token() }}",
                server_id: [server_id],
                sleep: "false",
            };

            jQuery.ajax({
                url: "{{url('/updateCloudServers')}}",
                method: "POST",
                data: data,
                success: function(response)
                {

                },
                error: function(xhr)
                {

                }
            });
        }

        function deleteServersModal()
        {
            document.getElementById("modalDeleteArray").value = "";
            var table = $('#servers').DataTable();
            var data = table.rows({ selected: true }).data();

            if(data.length == 0)
            {
                toastr.options.timeOut = 5000;
                toastr.error("Please select at least one server");
                return;
            }
            else
            {
                var servers_id = [];
                var message = "Are you sure you want to delete ";

                for(var i=0; i<data.length; i++)
                {
                    servers_id.push(data[i][0]);

                    if(i != data.length-1) message += data[i][1] + ", ";
                    else message += data[i][1];
                }

                document.getElementById("modalDeleteMessage").innerHTML = message + ".";

                document.getElementById("modalDeleteArray").value = servers_id.toString();

                $('#modalDelete').modal('show');
            }
        }

        function deleteServers()
        {
            $("#servers_card").waitMe({
                effect: 'timer',
                text: 'Deleting ...',
                bg: 'rgba(255,255,255,0.7)',
                color: 'red',
                maxSize: '100',
                waitTime: -1,
                textPos: 'vertical',
                fontSize: '20px',
                source: ''
            });
            var data = {
                "_token": "{{ csrf_token() }}",
                servers_id: document.getElementById("modalDeleteArray").value,
            };

            jQuery.ajax({
                url: "{{url('/deleteServers')}}",
                method: "POST",
                data: data,
                success: function(response)
                {
                    if(response.success == true)
                    {
                        toastr.options.timeOut = 5000;
                        toastr.success(response.msg);
                        $('#servers_card').waitMe('hide');
                        setTimeout(() => {
                            location.reload();
                        },0);
                    }
                    else
                    {
                        toastr.options.timeOut = 5000;
                        toastr.error(response.msg);

                        $('#servers_card').waitMe('hide');
                    }
                },
                error: function(xhr)
                {
                    toastr.options.timeOut = 5000;
                    toastr.error(response.msg);

                    $('#servers_card').waitMe('hide');
                }
            });
        }
    </script>

@endsection