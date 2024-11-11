@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
  <style>
        .flags-container {
            display: flex;
            flex-wrap: nowrap; 
            gap: 20px; 
            padding: 20px;
            overflow-x: auto; 
            background-color: #f4f4f9;
        }

        .flag-card {
            min-width: 120px;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            flex-shrink: 0; /* 
        }

        .flag-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .flag-icon {
            font-size: 60px;
            border-radius: 5px;
        }

        .country-name {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .flags-container::-webkit-scrollbar {
            display: none;
        }
        .flags-container {
            -ms-overflow-style: none;  /* لـ IE و Edge */
            scrollbar-width: none;  /* لـ Firefox */
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
                <div id="get_geos_card">
                    <div class="card-header">
                        <div class="card-title">Create IdCloudHost Servers</div>
                        <button id="get_geos" class="btn btn-info card-options" onclick="showDropdowns()">Get
                            Options</button>
                    </div>
                    <div id="get_geos_body" class="card-body">
                        <div class="col-12">
                            <div id="dropdowns" style="display: none;">
                                <h3>Regions</h3>
                                <div>
                                    @php
                                        // Mapping from three-character to two-character country codes
                                        $countryCodeMapping = [
                                            'idn' => 'id', // Indonesia
                                            'usa' => 'us', // United States
                                            'fra' => 'fr', // France
                                        ];
                                    @endphp
                                    <div class="flags-container">

                                    @foreach ($regions as $region)
                                        @php
                                            // Convert the three-character code to a two-character code
                                            $countryCode =
                                                $countryCodeMapping[$region['country_code']] ??
                                                strtolower(substr($region['country_code'], 0, 2));
                                        @endphp

                                        <div class="flag-container">
                                            <!-- Display the flag based on the two-character code -->
                                            <span class="flag-icon flag-icon-{{ $countryCode }}"></span>
                                            <p>{{ $region['display_name'] }}</p>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDropdowns() {
            document.getElementById('dropdowns').style.display = 'block';
        }
    </script>
@endsection
