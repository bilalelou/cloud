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
            justify-content: center;
            /* Center flags horizontally */
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
            width: 120px;
            /* Increase the width to make it larger */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .flag-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .flag-icon {
            font-size: 60px;
            /* Increased font size for larger flags */
            margin-bottom: 8px;
        }

        .flag-container p {
            margin: 0;
            font-size: 16px;
            /* Slightly larger font size for country name */
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
            width: calc(33.33% - 20px);
            /* Ensures 3 cards fit in a row with gap */
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

        input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 10px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }
    .jp-Radio-button {
            display: flex;
            align-items: flex-start;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .jp-Radio-button-input {
            display: none;
        }
        .jp-Radio-button-label {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            width: 100%;
        }
        .jp-Radio-button-content.price {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .jp-Radio-button-content.info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .price-monthly, .price-hourly {
            font-weight: bold;
            color: #333;
        }
        .jp-Slider {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            min-width: 50px;
        }
        .rangeslider {
            position: relative;
            width: 100%;
            height: 8px;
            background-color: #ddd;
            border-radius: 4px;
        }
        .rangeslider__fill {
            background-color: #007bff;
            height: 100%;
            border-radius: 4px 0 0 4px;
        }
        .rangeslider__handle {
            position: absolute;
            top: -6px;
            width: 20px;
            height: 20px;
            background-color: #007bff;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 12px;
        }
        .amount {
            font-size: 14px;
            color: #555;
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
                                    <!-- Configuration Section -->
                     <form action="/your-backend-endpoint" method="POST">
    <div class="jp-Radio-button wide">
        <input type="radio" name="size" id="radio-size-custom" class="jp-Radio-button-input" value="custom">
        <label for="radio-size-custom" class="jp-Radio-button-label">
            <div class="jp-Radio-button-content price">
                <span class="price-monthly" data-notranslate="true">Rp 87.000</span> / month<br>
                <span class="price-hourly" data-notranslate="true">Rp 120</span> / hour
            </div>
            
            <div class="jp-Radio-button-content info">
                <!-- CPU Slider -->
                <div class="jp-Slider">
                    <div class="label">CPU</div>
                    <div class="rangeslider" data-min="2" data-max="16" data-step="1" data-input="cpu-input">
                        <div class="rangeslider__fill"></div>
                        <div class="rangeslider__handle"><div class="rangeslider__handle-label">2</div></div>
                    </div>
                    <input type="hidden" name="cpu" id="cpu-input" value="2">
                    <div class="amount">2-16</div>
                </div>
                
                <!-- RAM Slider -->
                <div class="jp-Slider">
                    <div class="label">GB RAM</div>
                    <div class="rangeslider" data-min="2" data-max="16" data-step="1" data-input="ram-input">
                        <div class="rangeslider__fill"></div>
                        <div class="rangeslider__handle"><div class="rangeslider__handle-label">2</div></div>
                    </div>
                    <input type="hidden" name="ram" id="ram-input" value="2">
                    <div class="amount">2-16</div>
                </div>
                
                <!-- Disk Slider -->
                <div class="jp-Slider">
                    <div class="label">GB DISK</div>
                    <div class="rangeslider" data-min="20" data-max="1000" data-step="10" data-input="disk-input">
                        <div class="rangeslider__fill"></div>
                        <div class="rangeslider__handle"><div class="rangeslider__handle-label">20</div></div>
                    </div>
                    <input type="hidden" name="disk" id="disk-input" value="20">
                    <div class="amount">20-1000</div>
                </div>
            </div>
        </label>
    </div>
</form>


                                    <label for="servers">How many servers do you need?</label>
                                    <input type="number" id="servers" name="servers" min="1"
                                        placeholder="Enter quantity" />
                                </div>
                                <button id="get_geos" class="btn btn-info card-options">Next Step</button>
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

    <script>
        document.querySelectorAll('.rangeslider').forEach(slider => {
            const handle = slider.querySelector('.rangeslider__handle');
            const fill = slider.querySelector('.rangeslider__fill');
            const handleLabel = handle.querySelector('.rangeslider__handle-label');
            const min = parseInt(slider.getAttribute('data-min'));
            const max = parseInt(slider.getAttribute('data-max'));
            const step = parseInt(slider.getAttribute('data-step'));
            let value = min;

            function updateSlider(x) {
                const rect = slider.getBoundingClientRect();
                const percent = Math.min(Math.max((x - rect.left) / rect.width, 0), 1);
                value = Math.round((percent * (max - min) + min) / step) * step;
                const handlePosition = ((value - min) / (max - min)) * 100;

                fill.style.width = `${handlePosition}%`;
                handle.style.left = `${handlePosition}%`;
                handleLabel.textContent = value;
            }

            handle.addEventListener('mousedown', function(event) {
                event.preventDefault();
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            function onMouseMove(event) {
                updateSlider(event.clientX);
            }

            function onMouseUp() {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            }

            // Initialize the slider with min value
            updateSlider(slider.getBoundingClientRect().left);
        });
    </script>
@endsection
