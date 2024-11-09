@extends('layouts.layout')
@section('content')

    <div class="hor-content main-content">
        <div class="container">
            <div class="page-header">
                <div class="page-leftheader">
                </div>
                <div class="page-rightheader">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Cloud Servers Default Password</div>
                        </div>
                        <div class="card-body">
                            <form action="{{url('/update_default_password')}}" method="post"> @csrf
                                <div class="form-group row">
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="default_password" value="{{$password}}">
                                    </div>
                                    <div class="col-4">
                                        @can('change_servers_password')
                                            <button type="submit" class="btn btn-info pull-right">Update</button>
                                        @endcan
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Digital Ocean Default RSA Keys</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="col-md-3 form-label">Default public key</label>
                                <textarea class="form-control" rows="7"  id="publicKey" disabled>{{$ssh->public_key ?? ''}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 form-label">Default private key</label>
                                <textarea class="form-control" rows="7" id="privateKey" disabled>{{$ssh->private_key ?? ''}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 form-label">Default passphrase</label>
                                <input class="form-control" value="{{$ssh->passphrase ?? ''}}" id="passphrase" disabled>
                            </div>
                        </div>
                        <div class="card-footer">
                            @can('update_servers_rsa')
                                <button type="button" class="btn btn-info pull-right" onclick="new_keys_modal_check()">New Keys</button>
                                <button type="button" class="btn btn-success pull-right" onclick="update_providers_keys_check()" style="margin-right: 2%">Update Providers</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal new keys check --}}
    <div class="modal" id="modalNewKeysCheck">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    {{-- <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i> --}}
                    <i class="fe fe-alert-triangle fs-100 text-warning lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-warning">Warning!</h4>
                    <p class="mg-b-20 mg-x-20">By creating new RSA default keys the old ones will be deleted</p>
                    <button aria-label="Close" class="btn btn-warning pd-x-25" data-dismiss="modal" type="button" onclick="new_keys_modal()">Continue</button>
                    <input type="hidden" id="modalDeleteArray">
                </div>
            </div>
        </div>
    </div>

    {{-- modal update providers keys --}}
    <div class="modal" id="modalUpdateProvidersKeysCheck">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    {{-- <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i> --}}
                    <i class="fe fe-alert-triangle fs-100 text-warning lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-warning">Warning!</h4>
                    <p class="mg-b-20 mg-x-20">All default keys for digital ocean providers will be updated.</p>
                    <button aria-label="Close" class="btn btn-warning pd-x-25" data-dismiss="modal" type="button" onclick="updateProvidersKeys()">Continue</button>
                    <input type="hidden" id="modalDeleteArray">
                </div>
            </div>
        </div>
    </div>

    {{-- modal new keys --}}
    <div id="modalNewKeys" class="modal fade" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Digital Ocean Default RSA Keys</h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-label">Key Name <span class="text-red">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="key_name" id="key_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-label">Passphrase <span class="text-red">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="Passphrase" id="Passphrase">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" onclick="generateKeys()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

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

    {{-- select datatable --}}
    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>

    {{-- <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script> --}}

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

        function new_keys_modal_check()
        {
            $('#modalNewKeysCheck').modal('show');
        }

        function new_keys_modal()
        {
            $('#modalNewKeysCheck').modal('hide');
            $('#modalNewKeys').modal('show');
        }

        function update_providers_keys_check()
        {
            $('#modalUpdateProvidersKeysCheck').modal('show');
        }

        function updateProvidersKeys()
        {
            var passphrase =  $('#passphrase').val();
            var pubKey =  $('#publicKey').val();

            $.ajax({
                url: "{{url('/updateProvidersKeys')}}",
                method: 'POST',
                data: {
                    "passphrase": passphrase,
                    "pubKey": pubKey,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success == true)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                },
                error: function(response) {
                }
            });
        }

        function generateKeys(){
            $('#modalNewKeysCheck').modal('hide');
            $('#modalNewKeys').modal('show');

            var passphrase =  $('#Passphrase').val();
            var key_name =  $('#key_name').val();
            
            $.ajax({
                url: "{{url('/generateKeys')}}",
                method: 'POST',
                data: {
                    "passphrase": passphrase,
                    "key_name": key_name,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success == true){
                        toastr.success(response.message);
                        $('#modalNewKeys').modal('hide');

                        location.reload();
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(response) {
                }
            });
        }

    </script>

@endsection