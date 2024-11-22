@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="assets/plugins/sweet-alert/sweetalert.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <div class="hor-content main-content">
        <div class="container">
            <div class="page-header">
            </div>
            <div class="row">
                <div class="col">
                    <div class="card" >
                        <div class="card-header">
                            <div class="card-title">Cloud Servers Providers</div>
                            <div class="card-options">
                                @can('create_cloud_provider')
                                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalNewProvider"><i class="fe fe-plus mr-1"></i>New Provider</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="providers00" class="table table-bordered text-nowrap datatable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Status</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($providers as $provider)
                                            <tr>
                                                <td>{{ $provider->id }}</td>
                                                <td>{{ $provider->name }}</td>
                                                <td>{{ $provider->cloud_email }}</td>
                                                <td>{{ $provider->cloud_password }}</td>
                                                @if($provider->status == 1)
                                                    <td><span class="badge badge-success">Active</span></td>
                                                @elseif($provider->status == 0)
                                                    <td><span class="badge badge-danger">Not Active</span></td>
                                                @endif
                                                <td>{{ $provider->comment }}</td>
                                                <td>
                                                    <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#modalEditProvider" onclick="getProviderById({{ $provider->id }})"><i class="ti-pencil-alt" aria-hidden="true"></i></a>
                                                    @can('delete_cloud_provider')
                                                        <a href="#" class="text-danger" onclick="deleteProvider({{ $provider->id }})"><i class="ti-trash" aria-hidden="true"></i></a>
                                                    @endcan
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

    {{-- modal new provider --}}
    <div id="ModalNewProvider" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{url('/addNewProvider')}}" method="post"> 
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add a New Provider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Name <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Type <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="type" id="type">
                                    <option selected disabled></option>
                                    <option value="digitalocean">Digital Occean</option>
                                    <option value="linode">Linode</option>
                                    <option value="hetzner">Hetzner</option>
                                    <option value="azure">Azure</option>
                                    <option value="kamatera">Kamatera</option>
                                    <option value="IdCloudHost">IdCloudHost</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Email <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Status <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="status" value="active" checked>
                                    <span class="custom-control-label">Active</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="status" value="inactive">
                                    <span class="custom-control-label">Not Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Password <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group row" id="api_key">
                            <label class="col-md-3 form-label">API key <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="api_key" rows="2"></textarea>
                            </div>
                        </div>

                        {{-- azure --}}
                        <div class="form-group row Oauth" style="display: none">
                            <label class="col-md-3 form-label">Application(client) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="client_id" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row Oauth" style="display: none">
                            <label class="col-md-3 form-label">Directory(tenant) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="tenant_id" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row Oauth" style="display: none">
                            <label class="col-md-3 form-label">Client secret <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="client_secret" rows="2"></textarea>
                            </div>
                        </div>

                        {{-- kamatera --}}
                        <div class="form-group row Kamatera" style="display: none">
                            <label class="col-md-3 form-label">Application(client) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="client_id" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row Kamatera" style="display: none">
                            <label class="col-md-3 form-label">Client secret <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="client_secret" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-label">Comment :</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="comment" rows="6"></textarea>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- modal edit provider --}}
    <div id="modalEditProvider" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{url('/updateProvider')}}" method="post"> 
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-provider-modal-title">Edit Provider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <input type="hidden" class="form-control" id="id_edit" name="id_edit">
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Name <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name_edit" id="name_edit">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Type <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="type_edit" id="type_edit">
                                    <option value="digitalocean">Digital ocean</option>
                                    <option value="linode">Linode</option>
                                    <option value="hetzner">Hetzner</option>
                                    <option value="azure">Azure</option>
                                    <option value="kamatera">Kamatera</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Email <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="email_edit" id="email_edit">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Status <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="status_edit" id="status_edit_active" value="active" checked>
                                    <span class="custom-control-label">Active</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="status_edit" id="status_edit_inactive" value="inactive">
                                    <span class="custom-control-label">Not Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Password <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="password_edit" id="password_edit">
                            </div>
                        </div>
                        <div class="form-group row" id="api_key_edit_div">
                            <label class="col-md-3 form-label">API key <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="api_key_edit" id="api_key_edit" rows="2"></textarea>
                            </div>
                        </div>
                        {{-- azure --}}
                        <div class="form-group row Oauth_edit" style="display: none">
                            <label class="col-md-3 form-label">Application(client) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="client_id_edit" name="client_id_edit" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row Oauth_edit" style="display: none">
                            <label class="col-md-3 form-label">Directory(tenant) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="tenant_id_edit" name="tenant_id_edit" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row Oauth_edit" style="display: none">
                            <label class="col-md-3 form-label">Client secret <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="client_secret_edit" name="client_secret_edit" rows="2"></textarea>
                            </div>
                        </div>
                        {{-- kamatera --}}
                        <div class="form-group row Kamatera_edit" style="display: none">
                            <label class="col-md-3 form-label">Application(client) ID <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="client_id_edit_k" name="client_id_edit" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row Kamatera_edit" style="display: none">
                            <label class="col-md-3 form-label">Client secret <span class="text-red">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="client_secret_edit_k" name="client_secret_edit" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-label">Comment :</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="comment_edit" name="comment_edit" id="comment_edit" rows="6"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- select datatable --}}
    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>

    {{-- <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script> --}}

    <script>
        $("#providers00").DataTable();

        function getProviderById(provider_id)
        {
            var data = {
                "_token": "{{ csrf_token() }}",
                provider_id: provider_id,
            };

            jQuery.ajax({
                url: "{{url('/getProviderById')}}",
                method: "POST",
                data: data,
                success: function(response)
                {
                    if(response.success)
                    {
                        document.getElementById("edit-provider-modal-title").innerHTML = "Edit Provider : "+response.provider.id;
                        $("#id_edit").val(response.provider.id);
                        $('#name_edit').val(response.provider.name);
                        $('#type_edit').val(response.provider.cloud_type).trigger('change');
                        $("#email_edit").val(response.provider.cloud_email);
                        $("#password_edit").val(response.provider.cloud_password);
                        $("#comment_edit").val(response.provider.comment);
                        if(response.provider.status == true) $("#status_edit_active").prop("checked", true); 
                        if(response.provider.status == false) $("#status_edit_inactive").prop("checked", true);

                        if(response.provider.cloud_type == "azure")
                        {
                            $("#client_id_edit").val(response.o_auth.client_id);
                            $("#tenant_id_edit").val(response.o_auth.tenant_id);
                            $("#client_secret_edit").val(response.o_auth.client_secret);

                            $('#api_key_edit_div').css('display', 'none');
                            $(".Kamatera_edit").css('display', 'none');
                            $('.Oauth_edit').css('display', '');
    
                        }
                        else if(response.provider.cloud_type == "kamatera")
                        {
                            $("#client_id_edit_k").val(response.o_auth.client_id);
                            $("#client_secret_edit_k").val(response.o_auth.client_secret);

                            $('#api_key_edit_div').css('display', 'none');
                            $(".Kamatera_edit").css('display', '');
                            $('.Oauth_edit').css('display', 'none');
                        }
                        else
                        {
                            $("#api_key_edit").val(response.provider.cloud_api_key);

                            $('.Oauth_edit').css('display', 'none');
                            $(".Kamatera_edit").css('display', 'none');
                            $('#api_key_edit_div').css('display', '');
                        }
                    }
                },
                error: function(xhr)
                {

                }
            });
        }

        function deleteProvider(provider_id)
        {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this provider!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                     var data = {
                        "_token": "{{ csrf_token() }}",
                        provider_id: provider_id,
                    };

                    $.ajax({
                        url: "/deleteProvider",
                        method: "POST",
                        data: data,
                        success: function(response)
                        {
                            if(response.success)
                            {
                                swal("Your provider has been deleted!", {
                                    icon: "success",
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr)
                        {

                        }
                    });
                }
            })
    
           
        }

        $(document).ready(function() {
            $('#type').on('select2:select', function (e) {
                var selectedOption = e.params.data;
                
                if(selectedOption.id == 'azure') 
                {
                    $('#api_key').hide();
                    $('.Kamatera').hide();
                    $('.Oauth').show();
                } 
                else if(selectedOption.id == 'kamatera') 
                {
                    $('#api_key').hide();
                    $('.Kamatera').show();
                    $('.Oauth').hide();
                }
                else 
                {
                    $('#api_key').show();
                    $('.Kamatera').hide();
                    $('.Oauth').hide();
                }
            });

            $('#type_edit').on('select2:select', function (e) {
                var selectedOption = e.params.data;
                if(selectedOption.id == 'azure') {
                    $('#api_key_edit_div').hide();
                    $('.Kamatera_edit').hide();
                    $('.Oauth_edit').show();

                } else if(selectedOption.id == 'kamatera') {
                    $('#api_key_edit_div').hide();
                    $('.Kamatera_edit').show();
                    $('.Oauth_edit').hide();
                }
                else {
                    $('#api_key_edit_div').show();
                    $('.Oauth_edit').hide();
                    $('.Kamatera_edit').hide();
                }
            });
        });
    </script>

@endsection