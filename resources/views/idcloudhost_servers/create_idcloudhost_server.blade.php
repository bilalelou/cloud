@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
   <style>
    

        /* Headers styling */
        h3 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Flags Container */
        .flags-container {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            justify-content: center; /* Center flags horizontally */
        }

        .flag-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 120px; /* Increase the width to make it larger */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .flag-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .flag-icon {
            font-size: 60px; /* Increased font size for larger flags */
            margin-bottom: 8px;
        }

        .flag-container p {
            margin: 0;
            font-size: 16px; /* Slightly larger font size for country name */
            font-weight: bold;
            color: #333;
        }

        /* OS Container with 3 cards in each row */
        .os-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px 0;
        }

        .os-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: calc(33.33% - 20px); /* Ensures 3 cards fit in a row with gap */
            box-sizing: border-box;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .os-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .os-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .os-card label {
            font-size: 14px;
            color: #555;
        }

        .os-card select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        .os-card select:focus {
            border-color: #007bff;
            outline: none;
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
                                    <h3>Operating Systems</h3>
                                    <div class="os-container">
                                        @foreach ($osSystems as $osSystem)
                                            <div class="os-card">
                                                <!-- Display the main OS system name -->
                                                <p class="os-name">{{ $osSystem['display_name'] }}</p>

                                                <!-- Check if the OS system has versions -->
                                                @if (isset($osSystem['versions']) && is_array($osSystem['versions']))
                                                    <label for="version-{{ $loop->index }}">version:</label>
                                                    <select id="version-{{ $loop->index }}" name="version">
                                                        @foreach ($osSystem['versions'] as $version)
                                                            <option value="{{ $version['os_version'] }}">
                                                                {{ $version['display_name'] }} 
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <p>No versions available</p>
                                                @endif
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
