@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="{{ asset('assets/css/ion.rangeSlider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />





    <style>
        .input_ram {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .flag-container {
            background-color: #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }



        .flag-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .text-gray-800 {
            color: #2d3748;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-center {
            text-align: center;
        }

        .checked {
            border: #075985 solid 2px;
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
                        <button id="get_geos" class="btn btn-info card-options" onclick="getOptions()">Get Options</button>
                    </div>
                    <div id="get_geos_body" class="card-body">
                        <div class="col-12 flex flex-col justify-center">
                            <div class=" flex flex-col justify-center w-full" id="dropdowns" style="display:; ">

                                <!-- Part Region -->
                                <div class="w-full max-w-5xl text-center mx-auto">
                                    <h2 class="card-title">Select Your Region</h2>
                                    <div id="regions-container"
                                        class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-8 justify-center items-center">
                                    </div>
                                </div>

                                <!-- part Os system -->
                                <div class="w-full max-w-6xl flex flex-col justify-center my-6 mx-auto">
                                    <h2 class="fs-3 fw-bold text-dark mx-auto card-title">Operating Systems</h2>
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
                                            <input type="hidden" name="cpu" value="2">
                                        </div>

                                        <!-- RAM Slider -->
                                        <div class="flex items-center space-x-4">
                                            <label class="w-20 text-gray-700 font-medium">GB RAM</label>
                                            <div class="flex-grow relative">
                                                <input type="range" min="2" max="16" step="1"
                                                    value="2"
                                                    class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                    id="ram-input">
                                                <div class="absolute top-0 text-blue-500 font-bold right-0" id="ram-value">2
                                                </div>
                                            </div>
                                            <input type="hidden" name="ram" value="2">
                                        </div>

                                        <!-- Disk Slider -->
                                        <div class="flex items-center space-x-4">
                                            <label class="w-20 text-gray-700 font-medium">GB DISK</label>
                                            <div class="flex-grow relative">
                                                <input type="range" min="20" max="1000" step="10"
                                                    value="20"
                                                    class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                    id="disk-input">
                                                <div class="absolute top-0 text-blue-500 font-bold right-0" id="disk-value">
                                                    20</div>
                                            </div>
                                            <input type="hidden" name="disk" value="20">
                                        </div>
                                    </div>
                                    <!-- Number servers -->
                                    <div class="flex items-center space-x-4">
                                        <label class="w-20 text-gray-700 font-medium">N° Server</label>
                                        <div class="flex-grow relative">
                                            <input type="range" min="1" max="10" step="1"
                                                value="20"
                                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                id="numserver-input">
                                            <div class="absolute top-0 text-blue-500 font-bold right-0"
                                                id="numserver-value">1
                                            </div>
                                        </div>
                                        <input type="hidden" name="numserver" value="1">
                                    </div>
                                </div>



                            </div>
                            <div class="flex justify-center mt-8">
                                <button id="get_geos" class="btn btn-info" onclick="nextStep()">Next Step</button>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="{{ asset('assets/js/rangeslider.js') }}"></script>
    <script src="{{ asset('assets/js/ion.rangeSlider.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    {{-- <script>
        let inputElement = document.querySelector(".rangeslider1.input_server");

        if (inputElement) {
            inputElement.value = "1"; // Set the desired value here
            console.log("Updated input value to:", inputElement.value);
        }
    </script> --}}


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

                        console.log("we good");
                        showRegionsAndSystems(response);
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
        function showRegionsAndSystems(data) {
            const container = document.getElementById('regions-container');
            container.innerHTML = "";
            const systemContainer = document.getElementById("os-container");

            data.regions.forEach(region => {
                const countryCodeMapping = {};
                const countryCode = countryCodeMapping[region.country_code] ||
                    region.country_code.substring(0, 2).toLowerCase();

                const regionElement = document.createElement('div');
                regionElement.className = "flag-container";
                regionElement.innerHTML = `
                    <span class="flag-icon flag-icon-${countryCode}"></span>
                    <p class="text-gray-800 font-semibold text-lg text-center">${region.display_name}</p>
                `;

                container.appendChild(regionElement);
            });

            if (systemContainer) {
                data.systems.forEach(system => {
                    console.log(system);

                    const systemElement = document.createElement("div");
                    systemElement.className =
                        "card_os bg-white rounded shadow-sm hover:shadow transform transition-transform duration-300 p-4 w-100 d-flex flex-column align-items-center text-center cursor-pointer";

                    const iconContainer = document.createElement("div");
                    iconContainer.className = "mb-4";

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

                    const osName = document.createElement("p");
                    osName.className = "text-lg font-semibold text-gray-800";
                    osName.textContent = system.display_name;
                    iconContainer.appendChild(osName);

                    systemElement.appendChild(iconContainer);

                    if (system.versions && system.versions.length > 0) {
                        const label = document.createElement("label");
                        label.className = "text-sm font-medium text-gray-600 mb-2";
                        label.textContent = "Select Version:";

                        const select = document.createElement("select");
                        select.className =
                            "select_version block w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500";

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

                    systemContainer.appendChild(systemElement);
                });
            }

            let flags = document.querySelectorAll(".flag-container");
            let systemCards = document.querySelectorAll(".card_os");

            function clearCheckedFlag() {
                flags.forEach((el) => el.classList.remove("checked"));
            }

            function clearCheckedSystem() {
                systemCards.forEach((el) => el.classList.remove("checked"));
            }


            flags.forEach((flag) => {
                flag.addEventListener("click", () => {
                    clearCheckedFlag();
                    flag.classList.add("checked");
                });
            });

            systemCards.forEach((systemCard) => {
                systemCard.addEventListener("click", () => {
                    clearCheckedSystem();
                    systemCard.classList.add("checked");
                });
            });
        }
    </script>




    <script>
        function updateValue(slider, display, hiddenInput) {


            slider.addEventListener('input', function() {
                document.getElementById(display).textContent = this.value;
                document.getElementById(hiddenInput).value = this.value;
            });
        }

        updateValue(document.getElementById('cpu-input'), 'cpu-value', 'cpu-hidden');
        updateValue(document.getElementById('ram-input'), 'ram-value', 'ram-hidden');
        updateValue(document.getElementById('disk-input'), 'disk-value', 'disk-hidden');
        updateValue(document.getElementById('numserver-input'), 'numserver-value', 'numserver-hidden');



        function nextStep() {

            const data = {
                "_token": "{{ csrf_token() }}",
                config: {},
                regions: [],
                system: []
            };

            const diskInput = document.getElementById('disk-input');
            const cpuInput = document.getElementById('cpu-input');
            const ramInput = document.getElementById('ram-input');
            const numServerInput = document.getElementById('numserver-input');

            data.config.cpu = cpuInput ? cpuInput.value : null;
            data.config.disk = diskInput ? diskInput.value : null;
            data.config.ram = ramInput && ramInput.value ? ramInput.value * 1024 : null;
            data.config.numServers = numServerInput ? numServerInput.value : null;

            // Object.keys(inputs).forEach(key => {
            //     const input = document.getElementById(inputs[key]);
            //     let value = input ? input.value : null;

            //     if (key === 'ram' && value) {
            //         value = value * 1024;
            //     }

            //     data.config[key] = value;
            // });


            const checkedRegions = document.querySelectorAll('.flag-container.checked');
            checkedRegions.forEach(region => {
                const regionName = region.getAttribute('data-region') || region.textContent.trim();
                data.regions.push(regionName);
            });

            const checkedOS = document.querySelectorAll('.card_os.checked');
            console.log(checkedOS);
            checkedOS.forEach(item => {
                const osName = item.getAttribute('data-name') || item.querySelector('p').textContent.trim();

                const versionSelect = item.querySelector('.select_version');
                const selectedVersion = versionSelect ? versionSelect.value : null;

                data.system.push({
                    name: osName.toLowerCase(),
                    version: selectedVersion
                });
                console.log(data);

            })

            // $.ajax({
            //     url: "/storeIdCloudHost",
            //     type: "POST",
            //     data: data,
            //     success: function(response) {
            //         console.log("one");
            //         console.log("Data stored successfully:", response);
            //         toastr.success('successfully!');

            //     },
            //     error: function(xhr, status, error) {
            //         console.error("Error storing data:", error);
            //         toastr.error('An error occurred while storing data.');
            //     }
            // });
            getVmList();

            function getVmList() {


                $.ajax({
                    url: "/getVmList", 
                    method: "POST", 
                    data: {
                        "_token": "{{ csrf_token() }}" 
                    },
                    success: function(response) {
                        console.log("Request successful:", response);

                        if (response && response.success) {
                            console.log("Data received:", response.data);

                            $('#result').html(`<pre>${JSON.stringify(response.data, null, 2)}</pre>`);
                        } else {
                            const errorMessage = response && response.msg ? response.msg :
                                "Unknown error occurred.";
                            console.log("Request failed:", errorMessage);

                            $('#result').html("Request failed: " + errorMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", xhr.responseText || error);

                        $('#result').html("An unexpected error occurred: " + (xhr.responseText || error));
                    }
                });
            }
        }
    </script>
@endsection
