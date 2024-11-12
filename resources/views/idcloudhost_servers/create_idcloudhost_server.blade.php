@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
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
                <div id="get_geos_card">
                    <div class="card-header">
                        <div class="card-title">Create IdCloudHost Servers</div>
                        <button id="get_geos" class="btn btn-info card-options" onclick="getOptions()">Get Options</button>
                    </div>
                    <div id="get_geos_body" class="card-body">
                        <div class="col-12 flex flex-col justify-center">
                            <div class=" flex flex-col justify-center w-full" id="dropdowns" style="display:; ">

                                <!-- Part Region -->
                                <div class="w-full max-w-5xl text-center mx-auto">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-8">Select Your Region</h2>
                                    <div id="regions-container"
                                        class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-8 justify-center items-center">
                                    </div>
                                </div>

                                <!-- part Os system -->
                                <div class="w-full max-w-6xl flex flex-col justify-center my-6 mx-auto">
                                    <h2 class="text-2xl font-bold text-gray-800 mx-auto ">Operating Systems</h2>
                                    <div id="os-container"
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 w-full ">

                                    </div>
                                </div>
                                <!-- Configuration Section -->

                                <div class="flex items-center mb-4">
                                    <input type="radio" name="size" id="radio-size-custom" value="custom"
                                        class="hidden peer">
                                    <label for="radio-size-custom" class="flex items-center space-x-4 cursor-pointer">
                                        <span class="block text-lg font-semibold text-gray-700">Custom Plan</span>
                                    </label>
                                </div>

                                <!-- Sliders Section -->
                                <div class="space-y-6">
                                    <!-- CPU Slider -->
                                    <div class="flex items-center space-x-4">
                                        <label class="w-20 text-gray-700 font-medium">CPU</label>
                                        <div class="flex-grow relative">
                                            <input type="range" min="2" max="16" step="1"
                                                value="2"
                                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                id="cpu-input">
                                            <div class="absolute top-0 text-blue-500 font-bold right-0" id="cpu-value">2
                                            </div>
                                        </div>
                                        <input type="hidden" name="cpu" id="cpu-hidden" value="2">
                                    </div>

                                    <!-- RAM Slider -->
                                    <div class="flex items-center space-x-4">
                                        <label class="w-20 text-gray-700 font-medium">GB RAM</label>
                                        <div class="flex-grow relative">
                                            <input type="range" min="2" max="16" step="1"
                                                value="2"
                                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                id="ram-input">
                                            <div class="absolute top-0 text-blue-500 font-bold right-0" id="ram-value">
                                                2
                                            </div>
                                        </div>
                                        <input type="hidden" name="ram" id="ram-hidden" value="2">
                                    </div>

                                    <!-- Disk Slider -->
                                    <div class="flex items-center space-x-4">
                                        <label class="w-20 text-gray-700 font-medium">GB DISK</label>
                                        <div class="flex-grow relative">
                                            <input type="range" min="20" max="1000" step="10"
                                                value="20"
                                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                id="disk-input">
                                            <div class="absolute top-0 text-blue-500 font-bold right-0" id="disk-value">20
                                            </div>
                                        </div>
                                        <input type="hidden" name="disk" id="disk-hidden" value="20">
                                    </div>

                                    <!-- Number of Servers -->
                                    <div class="flex items-center space-x-4">
                                        <label class="w-20 text-gray-700 font-medium">N° Server</label>
                                        <div class="flex-grow relative">
                                            <input type="range" min="1" max="10" step="1"
                                                value="1"
                                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                id="numserver-input">
                                            <div class="absolute top-0 text-blue-500 font-bold right-0"
                                                id="numserver-value">1</div>
                                        </div>
                                        <input type="hidden" name="numserver" id="numserver-hidden" value="1">
                                    </div>
                                </div>
                                <div class="flex justify-center mt-8">
                                    <button id="get_geos"
                                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200 ease-in-out">
                                        Next Step
                                    </button>
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
        function getOptions() {
            $.ajax({
                url: "/getRegionsAndSystems",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {

                        console.log(response);
                        console.log("we good");
                        afficeregion(response);
                    } else {
                        console.log("fail !!!");
                    }
                },
                error: function() {
                    console.log(error);
                }
            });
        }
    </script>
    <script>
        function afficeregion(data) {


            // Get the container element where the data will be displayed
            const container = document.getElementById('regions-container');
            container.innerHTML = ""; // Clear any existing content
            const systemContainer = document.getElementById("os-container");




            data.regions.forEach(region => {
                // Convert the country code to lowercase for the flag class
                const countryCodeMapping = {};
                const countryCode = countryCodeMapping[region.country_code] ||
                    region.country_code.substring(0, 2).toLowerCase();


                // Create a new div element for each region
                const regionElement = document.createElement('div');
                regionElement.className =
                    "flag-container group bg-white rounded-lg shadow-md p-6 flex flex-col items-center transition duration-300 transform hover:scale-105 hover:shadow-xl cursor-pointer";

                // Set the inner HTML of the new div to include region details
                regionElement.innerHTML = `
            <span class="flag-icon flag-icon-${countryCode} text-5xl mb-4 transition-transform duration-300 group-hover:scale-110"></span>
            <p class="text-gray-800 font-semibold text-lg text-center">${region.display_name}</p>
        `;

                // Append the new div element to the container
                container.appendChild(regionElement);
            });


            // Check if the container is available before appending to it
            if (systemContainer) {
                data.systems.forEach(system => {
                    // Log the system object for debugging
                    console.log(system);

                    // Main card div
                    const systemElement = document.createElement("div");
                    systemElement.className =
                        "os-card bg-white rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition duration-300 p-6 w-full flex flex-col items-center text-center cursor-pointer";

                    // Icon section
                    const iconContainer = document.createElement("div");
                    iconContainer.className = "mb-4";

                    // Check if system.icon exists
                    if (system.icon) {
                        const img = document.createElement("img");
                        img.src = `data:image/svg+xml;base64,${system.icon}`;
                        img.alt = `${system.display_name} Logo`;
                        img.className = "w-12 h-12 mb-4";
                        iconContainer.appendChild(img);
                    } else {
                        const noIconDiv = document.createElement("div");
                        noIconDiv.className =
                            "w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 mb-4";
                        noIconDiv.textContent = "No Icon";
                        iconContainer.appendChild(noIconDiv);
                    }

                    // OS Name
                    const osName = document.createElement("p");
                    osName.className = "text-lg font-semibold text-gray-800";
                    osName.textContent = system.display_name;
                    iconContainer.appendChild(osName);

                    systemElement.appendChild(iconContainer);

                    // Version Dropdown
                    if (system.versions && system.versions.length > 0) {
                        const label = document.createElement("label");
                        label.className = "text-sm font-medium text-gray-600 mb-2";
                        label.textContent = "Select Version:";

                        const select = document.createElement("select");
                        select.className =
                            "block w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500";

                        system.versions.forEach(version => {
                            const option = document.createElement("option");
                            option.value = version.os_version;
                            option.textContent = version.display_name;
                            select.appendChild(option);
                        });

                        systemElement.appendChild(label);
                        systemElement.appendChild(select);
                    } else {
                        const noVersionText = document.createElement("p");
                        noVersionText.className = "text-sm text-red-500 mt-4";
                        noVersionText.textContent = "No versions available";
                        systemElement.appendChild(noVersionText);
                    }

                    // Append each OS card to the container
                    systemContainer.appendChild(systemElement);
                });
            } else {
                console.error("Element with id 'os-container' not found.");
            }
        }
    </script>

    <script>
        function updateValue(slider, display, hiddenInput) {
            slider.addEventListener('input', function() {
                document.getElementById(display).textContent = this.value;
                document.getElementById(hiddenInput).value = this.value; // Update hidden input value
            });
        }

        // Initialize all sliders with their respective display and hidden input fields
        updateValue(document.getElementById('cpu-input'), 'cpu-value', 'cpu-hidden');
        updateValue(document.getElementById('ram-input'), 'ram-value', 'ram-hidden');
        updateValue(document.getElementById('disk-input'), 'disk-value', 'disk-hidden');
        updateValue(document.getElementById('numserver-input'), 'numserver-value', 'numserver-hidden');
    </script>
@endsection
