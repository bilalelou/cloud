<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <!-- Meta data -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta content="Admitro - Admin Panel HTML template" name="description">
        <meta content="Spruko Technologies Private Limited" name="author">
        <meta name="keywords" content="admin panel ui, user dashboard template, web application templates, premium admin templates, html css admin templates, premium admin templates, best admin template bootstrap 4, dark admin template, bootstrap 4 template admin, responsive admin template, bootstrap panel template, bootstrap simple dashboard, html web app template, bootstrap report template, modern admin template, nice admin template"/>

        <!-- Title -->
        <title>E-impact Cloud</title>

        <link rel="icon" href="{{ asset('assets/images/brand/favicon.ico') }}" type="image/x-icon"/>
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/skin-modes.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/animated.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/p-scrollbar/p-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}"  rel="stylesheet">
        <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
        <link id="theme" href="{{ asset('assets/colors/color1.css') }}" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css" rel="stylesheet" type="text/css">{{-- css for select cloud table makhdamch --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>

    <body class="app main-body">

        <!-- <div id="global-loader" >
            <img src="{{ asset('assets/images/svgs/loader.svg') }}" alt="loader">
        </div> -->

        <div class="page">
            <div class="page-main">

                <div class="hor-header header top-header">
                    <div class="container">
                        <div class="d-flex">
                            <a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>
                            <a class="header-brand" href="http://{{ env('domain2') }}/">
                                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo" alt="E-impact Logo">
                                <img src="{{ asset('assets/images/brand/favicon.png') }}" class="header-brand-img mobile-logo" alt="E-impact Logo">
                            </a>
                            <div class="mt-1">
                                <form class="form-inline">
                                    <div class="search-element">
                                        <input type="search" class="form-control header-search" placeholder="Search…" aria-label="Search" tabindex="1">
                                        <button class="btn btn-primary-color" type="submit">
                                            <svg class="header-icon search-icon" x="1008" y="1248" viewBox="0 0 24 24"  height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                <path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex order-lg-2 ml-auto">
                                <a href="#" data-toggle="search" class="nav-link nav-link-lg d-md-none navsearch">
                                    <svg class="header-icon search-icon" x="1008" y="1248" viewBox="0 0 24 24"  height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                        <path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                    </svg>
                                </a>
                                <div class="dropdown   header-fullscreen" >
                                    <a  class="nav-link icon full-screen-link p-0"  id="fullscreen-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 4L8 4 8 8 4 8 4 10 10 10zM8 20L10 20 10 14 4 14 4 16 8 16zM20 14L14 14 14 20 16 20 16 16 20 16zM20 8L16 8 16 4 14 4 14 10 20 10z"/></svg>
                                    </a>
                                </div>
                                <div class="dropdown header-message">
                                    <a class="nav-link icon" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20,2H4C2.897,2,2,2.897,2,4v12c0,1.103,0.897,2,2,2h3v3.767L13.277,18H20c1.103,0,2-0.897,2-2V4C22,2.897,21.103,2,20,2z M20,16h-7.277L9,18.233V16H4V4h16V16z"/><path d="M7 7H17V9H7zM7 11H14V13H7z"/></svg>
                                        <span class="badge badge-success side-badge">3</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow  animated">
                                        <div class="dropdown-header">
                                            <h6 class="mb-0">Messages</h6>
                                            <span class="badge badge-pill badge-primary ml-auto">View all</span>
                                        </div>
                                        <div class="header-dropdown-list message-menu" id="message-menu">
                                            <a class="dropdown-item border-bottom" href="#">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="assets/images/users/1.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Jack Wright</h6>
                                                            <p class="fs-13 mb-1">All the best your template awesome</p>
                                                            <div class="small text-muted">
                                                                3 hours ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="dropdown-item border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="../../assets/images/users/2.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Lisa Rutherford</h6>
                                                            <p class="fs-13 mb-1">Hey! there I'm available</p>
                                                            <div class="small text-muted">
                                                                5 hour ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="dropdown-item border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="../../assets/images/users/3.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Blake Walker</h6>
                                                            <p class="fs-13 mb-1">Just created a new blog post</p>
                                                            <div class="small text-muted">
                                                                45 mintues ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="dropdown-item border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="../../assets/images/users/4.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Fiona Morrison</h6>
                                                            <p class="fs-13 mb-1">Added new comment on your photo</p>
                                                            <div class="small text-muted">
                                                                2 days ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="dropdown-item border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="../../assets/images/users/6.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Stewart Bond</h6>
                                                            <p class="fs-13 mb-1">Your payment invoice is generated</p>
                                                            <div class="small text-muted">
                                                                3 days ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="dropdown-item border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <span class="avatar avatar-md brround align-self-center cover-image" data-image-src="../../assets/images/users/7.jpg"></span>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="pl-3">
                                                            <h6 class="mb-1">Faith Dickens</h6>
                                                            <p class="fs-13 mb-1">Please check your mail....</p>
                                                            <div class="small text-muted">
                                                                4 days ago
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class=" text-center p-2 border-top">
                                            <a href="#" class="">See All Messages</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown header-notify">
                                    <a class="nav-link icon" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707C3.105 15.48 3 15.734 3 16v2c0 .553.447 1 1 1h16c.553 0 1-.447 1-1v-2c0-.266-.105-.52-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707C6.895 14.52 7 14.266 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zM12 22c1.311 0 2.407-.834 2.818-2H9.182C9.593 21.166 10.689 22 12 22z"/></svg>
                                        <span class="pulse "></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow  animated">
                                        <div class="dropdown-header">
                                            <h6 class="mb-0">Notifications</h6>
                                            <span class="badge badge-pill badge-primary ml-auto">View all</span>
                                        </div>
                                        <div class="notify-menu">
                                            <a href="#" class="dropdown-item border-bottom d-flex pl-4">
                                                <div class="notifyimg bg-info-transparent text-info"> <i class="ti-comment-alt"></i> </div>
                                                <div>
                                                    <div class="font-weight-normal1">Message Sent.</div>
                                                    <div class="small text-muted">3 hours ago</div>
                                                </div>
                                            </a>
                                            <a href="#" class="dropdown-item border-bottom d-flex pl-4">
                                                <div class="notifyimg bg-primary-transparent text-primary"> <i class="ti-shopping-cart-full"></i> </div>
                                                <div>
                                                    <div class="font-weight-normal1"> Order Placed</div>
                                                    <div class="small text-muted">5  hour ago</div>
                                                </div>
                                            </a>
                                            <a href="#" class="dropdown-item border-bottom d-flex pl-4">
                                                <div class="notifyimg bg-warning-transparent text-warning"> <i class="ti-calendar"></i> </div>
                                                <div>
                                                    <div class="font-weight-normal1"> Event Started</div>
                                                    <div class="small text-muted">45 mintues ago</div>
                                                </div>
                                            </a>
                                            <a href="#" class="dropdown-item border-bottom d-flex pl-4">
                                                <div class="notifyimg bg-success-transparent text-success"> <i class="ti-desktop"></i> </div>
                                                <div>
                                                    <div class="font-weight-normal1">Your Admin launched</div>
                                                    <div class="small text-muted">1 daya ago</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class=" text-center p-2 border-top">
                                            <a href="#" class="">View All Notifications</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown profile-dropdown">
                                    <a href="#" data-toggle="dropdown">
                                        <span class="icons-list-item avatar-md brround">
                                            {{-- <img src="{{ asset('assets/images/users/user-1.png') }}" alt="img" class="avatar-md brround"> --}}
                                            <i class="fe fe-user"></i>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                                        <div class="text-center">
                                            <a href="#" class="dropdown-item text-center user pb-0 font-weight-bold">Jessica</a>
                                            <span class="text-center user-semi-title">Web Designer</span>
                                            <div class="dropdown-divider"></div>
                                        </div>
                                        <a class="dropdown-item d-flex" href="#">
                                            <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg>
                                            <div class="">Profile</div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="#">
                                            <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.09-.16-.26-.25-.44-.25-.06 0-.12.01-.17.03l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.06-.02-.12-.03-.18-.03-.17 0-.34.09-.43.25l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.09.16.26.25.44.25.06 0 .12-.01.17-.03l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.06.02.12.03.18.03.17 0 .34-.09.43-.25l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zm-1.98-1.71c.04.31.05.52.05.73 0 .21-.02.43-.05.73l-.14 1.13.89.7 1.08.84-.7 1.21-1.27-.51-1.04-.42-.9.68c-.43.32-.84.56-1.25.73l-1.06.43-.16 1.13-.2 1.35h-1.4l-.19-1.35-.16-1.13-1.06-.43c-.43-.18-.83-.41-1.23-.71l-.91-.7-1.06.43-1.27.51-.7-1.21 1.08-.84.89-.7-.14-1.13c-.03-.31-.05-.54-.05-.74s.02-.43.05-.73l.14-1.13-.89-.7-1.08-.84.7-1.21 1.27.51 1.04.42.9-.68c.43-.32.84-.56 1.25-.73l1.06-.43.16-1.13.2-1.35h1.39l.19 1.35.16 1.13 1.06.43c.43.18.83.41 1.23.71l.91.7 1.06-.43 1.27-.51.7 1.21-1.07.85-.89.7.14 1.13zM12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
                                            <div class="">Settings</div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="#">
                                            <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M4 4h16v12H5.17L4 17.17V4m0-2c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4zm2 10h12v2H6v-2zm0-3h12v2H6V9zm0-3h12v2H6V6z"/></svg>
                                            <div class="">Messages</div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="#">
                                            <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/></g></svg>
                                            <div class="">Sign Out</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sticky">
                    <div class="horizontal-main hor-menu clearfix">
                        <div class="horizontal-mainwrapper container clearfix">
                            <nav class="horizontalMenu clearfix">
                                <ul class="horizontalMenu-list d-flex align-items-center justify-content-center">
                                    @can('see_providers')
                                        <li aria-haspopup="true">
                                            <a href="{{url('/CloudServersProviders')}}" class="sub-icon">
                                                <svg class="hor-icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"/></svg>
                                                Providers
                                            </a>
                                        </li>
                                    @endcan
                                    @can('see_servers')
                                        <li aria-haspopup="true">
                                            <a href="{{url('/CloudServers')}}" class="">
                                                <svg class="hor-icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z"/></svg>
                                                Cloud Servers
                                            </a>
                                        </li>
                                    @endcan
                                    @can('see_settings')
                                        <li aria-haspopup="true">
                                            <a href="{{url('/settings')}}" class="sub-icon">
                                                <svg class="hor-icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"/></svg>
                                                Settings
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Show_Logs')
                                        <li aria-haspopup="true">
                                            <a href="{{url('/logs')}}" target="_blank">
                                                <svg class="hor-icon" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z"/></svg>
                                                Logs
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['create_digitalocean_server', 'create_linode_server', 'create_hetzner_server','create_azure_server','create_kamatera_server'])
                                    <li aria-haspopup="true">
                                      
                                        <a href="#" class="btn dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg class="hor-icon" height="24" viewBox="0 0 24 24" width="24">
                                                <path d="M0 0h24v24H0V0z" fill="none"/>
                                                <path d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z"/>
                                            </svg>
                                            New Servers
                                        </a>
                                        <div class="dropdown-menu">
                                            @can('create_digitalocean_server')
                                                <a class="dropdown-item" href="/CreateCloudServersIndex">New Digital Ocean Server</a>
                                            @endcan
                                            @can('create_linode_server')
                                                <a class="dropdown-item" href="/CreateLinodeServersIndex">New Linode Server</a>
                                            @endcan
                                            @can('create_hetzner_server')
                                                <a class="dropdown-item" href="/CreateHetznerServersIndex">New Hetzner Server</a>
                                            @endcan
                                            @can('create_azure_server')
                                                <a class="dropdown-item" href="/CreateAzureVMsIndex">New Azure VM</a>
                                            @endcan
                                            @can('create_kamatera_server')
                                                <a class="dropdown-item" href="/CreateKamateraServersIndex">New Kamatera Server</a>
                                            @endcan
                                            @can('create_idcloudhost_server') 
                                                <a class="dropdown-item" href="/createIdCloudHostIndex">New IdCloudHost Server</a>
                                            @endcan 
                                        </div>
                                        
                                    </li>
                                    @endcan
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                @yield('content')

            </div>

            <footer class="footer main-footer">
                <div class="container">
                    <div class="row align-items-center flex-row-reverse">
                        <div class="col-md-12 col-sm-12 text-center">
                            Copyright © <?php echo date("Y");?> <a href="#">E-impact Pro</a>.<i>All rights reserved.</i>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

        <a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

    </body>
</html>