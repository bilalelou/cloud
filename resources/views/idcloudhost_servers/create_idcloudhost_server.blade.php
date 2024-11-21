@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link href="{{ asset('assets/css/ion.rangeSlider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/waitMe.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/waitme@1.19.0/waitMe.min.css">

    <link href="{{ asset('assets/css/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />





    <style>
        #systemContainer {
            display: flex;
            flex-wrap: wrap;

            gap: 10px;
            justify-content: space-between;
        }

        .card_os {
            flex: 1 1 calc(16.66% - 10px);
            box-sizing: border-box;
        }

        .input_ram {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .flag-container {
            background-color: #EBEAFF;
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
                        <div class="col-12">
                            <div id="droplet_settings_card" style="display:none ;padding: 0px !important;">
                                <div class="card-body" style="padding: 0px !important;">
                                    <div id="droplet_settings">
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
                                                class="grid grid-cols-6 sm:grid-cols-2 md:grid-cols-6 lg:grid-cols-6 gap-8 w-full ">

                                            </div>
                                        </div>
                                        <!-- Configuration Section -->

                                        <div class="flex items-center mb-4">
                                            <input type="radio" name="size" id="radio-size-custom" value="custom"
                                                class="hidden peer">
                                            <label for="radio-size-custom"
                                                class="flex items-center space-x-4 cursor-pointer">
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
                                                    <div class="absolute top-0 text-blue-500 font-bold right-0"
                                                        id="cpu-value">2</div>
                                                </div>
                                                <input type="hidden" id="cpu-hidden" name="cpu" value="2">
                                            </div>

                                            <!-- RAM Slider -->
                                            <div class="flex items-center space-x-4">
                                                <label class="w-20 text-gray-700 font-medium">GB RAM</label>
                                                <div class="flex-grow relative">
                                                    <input type="range" min="2" max="16" step="1"
                                                        value="2"
                                                        class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                        id="ram-input">
                                                    <div class="absolute top-0 text-blue-500 font-bold right-0"
                                                        id="ram-value">2</div>
                                                </div>
                                                <input type="hidden" id="ram-hidden" name="ram" value="2">
                                            </div>

                                            <!-- Disk Slider -->
                                            <div class="flex items-center space-x-4">
                                                <label class="w-20 text-gray-700 font-medium">GB DISK</label>
                                                <div class="flex-grow relative">
                                                    <input type="range" min="20" max="1000" step="10"
                                                        value="20"
                                                        class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                                        id="disk-input">
                                                    <div class="absolute top-0 text-blue-500 font-bold right-0"
                                                        id="disk-value">20</div>
                                                </div>
                                                <input type="hidden" id="disk-hidden" name="disk" value="20">
                                            </div>

                                            <!-- N° Server Slider -->
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
                                                <input type="hidden" id="numserver-hidden" name="numserver"
                                                    value="1">
                                            </div>
                                        </div>

                                        {{-- <div class="card-footer d-flex justify-content-between">
                                            <div id="pmta_settings"
                                                class="d-flex justify-content-start align-items-center"
                                                style="gap: 10px;opacity: 0;align-self: end;">
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="pmta_4.5"
                                                        name="pmta" value="pmta4_0" checked>
                                                    <span class="custom-control-label">PMTA 4.0</span>
                                                </label>
                                                <label class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="pmta_4.0"
                                                        name="pmta" value="pmta4_5">
                                                    <span class="custom-control-label">PMTA 4.5</span>
                                                </label>
                                            </div>
                                            <div class="d-flex justify-content-end" style="gap: 10px">
                                                <button class="btn btn-info" id="save_button"
                                                    onclick="checkFilledRows()">Create</button>
                                                
                                                <button class="btn btn-info" style="display: none;" id="store_button"
                                                    onclick="storeServers()">Store</button>
                                                <button id="check_spamhaus" class="btn btn-warning card-options"
                                                    style="display: none" onclick="checkSpamhaus()">Check
                                                    Spamhaus</button>
                                                <button class="btn btn-info" style="display: none;" id="reinstall_button"
                                                    onclick="reinstallServers()">Reinstall Server(s)</button>
                                                <input type="hidden" id="reinstall_servers_ids">
                                            </div>
                                        </div> --}}
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-between"style="display:none;">
                                <div id="pmta_settings"
                                    class="d-flex justify-content-start align-items-center"
                                    style="gap: 10px;opacity: 0;align-self: end;">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="pmta_4.5"
                                            name="pmta" value="pmta4_0" checked>
                                        <span class="custom-control-label">PMTA 4.0</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="pmta_4.0"
                                            name="pmta" value="pmta4_5">
                                        <span class="custom-control-label">PMTA 4.5</span>
                                    </label>
                                </div>
                                <div class="d-flex justify-content-end" style="gap: 10px display:none;">
                                    <button class="btn btn-info" id="save_button" style ="display:none;"
                                        onclick="checkFilledRows()">Create</button>
                                    
                                    <button class="btn btn-info" style="display: none;" id="store_button"
                                        onclick="storeServers()">Store</button>
                                    <button id="check_spamhaus" class="btn btn-warning card-options"
                                        style="display: none" onclick="checkSpamhaus()">Check
                                        Spamhaus</button>
                                    <button class="btn btn-info" style="display: none;" id="reinstall_button"
                                        onclick="reinstallServers()">Reinstall Server(s)</button>
                                    <input type="hidden" id="reinstall_servers_ids">
                                </div>
                            </div>
                            <div id="serverCreate" style="display: ">
                                
                             </div>
                        </div>
                    </div>
                </div>
            </div>





            <script src="{{ asset('assets/js/rangeslider.js') }}"></script>
            <script src="{{ asset('assets/js/ion.rangeSlider.min.js') }}"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/waitme@1.19.0/waitMe.min.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


            <script>
                function getOptions() {

                    $(document).ready(function() {
                        $('#get_geos_card').waitMe({
                            effect: 'timer',
                            text: 'Please wait, this provider may take up to 3 minutes to respond...',
                            bg: 'rgba(255,255,255,0.7)',
                            color: '#5b7fff',
                            maxSize: '',
                            waitTime: -1,
                            source: '',
                            textPos: 'vertical',
                            fontSize: '14px',
                        });
                    });

                    $.ajax({
                        url: "/getRegionsAndSystems",
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                showRegionsAndSystems(response);
                                $('#get_geos_card').waitMe('hide');
                                $("#get_geos").hide();
                                toastr.success("good");
                                displayCreateAndList();

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
                                            if (system.display_name === "Windows") {
                                                return;
                                            }
                                            const systemElement = document.createElement("div");
                                            systemElement.className =
                                                "card_os bg-[#EBEAFF] rounded shadow-sm hover:shadow transform transition-transform duration-300 p-4 w-100 d-flex flex-column align-items-center text-center cursor-pointer";

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
                                        $("#save_button").css("display", "block");
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

            <script></script>




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







                function createVm() {
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
                    $.ajax({
                        url: "/storeIdCloudHost",
                        type: "POST",
                        data: data,
                        success: function(response) {
                            console.log("one");
                            console.log("Data stored successfully:", response);
                            toastr.success('successfully!');

                        },
                        error: function(xhr, status, error) {
                            console.error("Error storing data:", error);
                            toastr.error('An error occurred while storing data.');
                        }
                    });



                }

                function getVmList() {
                                $.ajax({
                                    url: "/getVmList",
                                    method: "POST",
                                    data: {
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function(response) {

                                        console.log(response);             
                                            const listServer = document.getElementById("serverCreate");
                                            listServer.innerHTML = ""; 
                                            console.log("thosfds");  
                                            const table = document.createElement("table");
                                            table.className = "table text-nowrap";
                                            console.log("thdsfsdffosfds"); 
                                            const headerRow = document.createElement("tr");
                                            headerRow.innerHTML = `
                                                <th>Provider</th>
                                                <th>Name Server</th>
                                                <th>Status</th>
                                                <th>IP Address</th>
                                                <th>OS System</th>
                                                <th>Action</th>
                                            `;
                                            table.appendChild(headerRow);
                                            console.log("ghddfghhgfghdh"); 
                                            if (true) {     
                                                response.servers.forEach(server => {
                                                    const row = document.createElement("tr");
                                                    row.innerHTML = `
                                                        <td>${server.provider || "IdCloudHost"}</td>
                                                        <td>${server.name || "N/A"}</td>
                                                        <td><span style="font-size: 12px;" class="${server.status === 'saved' ? 'badge badge-success' : 'bg-red'}">
                                                        ${server.status || "N/A"}</span></td>
                                                        <td>${server.main_ip || "N/A"}</td>
                                                        <td>${server.os_installed || "N/A"}</td>
                                                        <td>
                                                            <button class="btn btn-primary" onclick="manageServer('${server.id}')">
                                                                Manage
                                                            </button>
                                                        </td>
                                                    `;
                                                    table.appendChild(row);
                                                    $('#get_geos_card').waitMe('hide');
                                                });
                                            } else {
                                                const noDataRow = document.createElement("tr");
                                                noDataRow.innerHTML = `<td colspan="6" class="text-center">No servers found</td>`;
                                                table.appendChild(noDataRow);
                                            }

                                            listServer.appendChild(table); 
                                            $("#save_button").css("display", "none");
                                            $('#store_button').css("display", "block");


                                        
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("AJAX error:", xhr.responseText || error);
                                        $('#serverCreate').html("<p class='text-center text-danger'>An unexpected error occurred: " + (xhr.responseText || error) + "</p>");
                                    }
                                });
                            }
                           
                function checkFilledRows() {
                    $(document).ready(function() {
                        $('#get_geos_card').waitMe({
                            effect: 'timer',
                            text: 'Please wait, this provider may take up to 3 minutes to respond...',
                            bg: 'rgba(255,255,255,0.7)',
                            color: '#5b7fff',
                            maxSize: '',
                            waitTime: -1,
                            source: '',
                            textPos: 'vertical',
                            fontSize: '14px',
                        });
                    });
                
                    // createVm();
                displayCreateAndList();

                getVmList();
                 console.log("this");
}

                function displayCreateAndList() {
                    const firstElement = document.getElementById('droplet_settings_card');
                    const secondElement = document.getElementById('serverCreate');

                    if (firstElement.style.display === 'none') {
                        firstElement.style.display = 'block';
                        secondElement.style.display = 'none';
                    } else {
                        firstElement.style.display = 'none';
                        secondElement.style.display = 'block';
                    }

                }

                function storeServers(){
                    $ajax({ url: "/storeServers",
                    method: "POST",               

                    });
                }
               
                  
                   
                
            </script>
        @endsection
