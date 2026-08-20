@extends('components.layout.OwnerLayout.body.index')
@section('title', 'Dashboard')
@section('admin')

    <div class="grid grid-cols-1 gap-6">

        {{-- ============================
             📈 Pendapatan
        ============================ --}}
        <div class="max-w-sm md:max-w-full bg-white rounded-lg shadow-sm ">
            <div class="flex justify-between p-4 md:p-6 pb-0 md:pb-0">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">
                        Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                    <p class="text-sm font-normal text-gray-500 ">
                        {{ $filterPendapatan == 'bulan' ? 'Pendapatan Bulan Ini' : 'Pendapatan Minggu Ini' }}</p>
                </div>
                <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 text-center">
                    <form id="filterFormPendapatan" method="get">
                        <button id="dropdownDefaultButtonPendapatan" data-dropdown-toggle="dropdownPendapatanMenu"
                            data-dropdown-placement="bottom" type="button"
                            class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                            {{ request('filter_pendapatan', 'minggu') == 'bulan' ? 'Bulan Ini' : 'Minggu Ini' }}
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <input type="hidden" name="filter_pendapatan" id="filterInputPendapatan"
                            value="{{ request('filter_pendapatan', 'minggu') }}">
                        <div id="dropdownPendapatanMenu"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 ">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                                <li>
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('filterInputPendapatan').value='minggu'; document.getElementById('filterFormPendapatan').submit();"
                                        class="block px-4 py-2 hover:bg-gray-100 ">
                                        Minggu Ini
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('filterInputPendapatan').value='bulan'; document.getElementById('filterFormPendapatan').submit();"
                                        class="block px-4 py-2 hover:bg-gray-100 ">
                                        Bulan Ini
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            <div id="labels-chart" class="px-2.5"></div>
            <!-- Legend Pendapatan -->
            <div id="legend-pendapatan" class="flex flex-wrap justify-center gap-4 p-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-4">
            {{-- ============================
     🏆 Tabel Menu Best Seller
        ============================ --}}
            <div class=" bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Menu Best Seller</h3>

                    <form id="filterBestSellerForm" method="get">
                        <button id="dropdownBestSellerButton" data-dropdown-toggle="dropdownBestSeller" type="button"
                            class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">
                            {{ $filterBestSeller == 'bulan' ? 'Bulanan' : ($filterBestSeller == 'minggu' ? 'Mingguan' : 'Harian') }}
                            <svg class="w-2.5 h-2.5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <input type="hidden" id="filterBestSellerInput" name="filter_best_seller"
                            value="{{ $filterBestSeller }}">

                        <div id="dropdownBestSeller"
                            class="hidden z-10 w-44 bg-white rounded shadow divide-y divide-gray-100">
                            <ul class="py-2 text-sm text-gray-700">
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setBestSellerFilter('hari')"
                                        class="block px-4 py-2 hover:bg-gray-100">Harian</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setBestSellerFilter('minggu')"
                                        class="block px-4 py-2 hover:bg-gray-100">Mingguan</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setBestSellerFilter('bulan')"
                                        class="block px-4 py-2 hover:bg-gray-100">Bulanan</a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-600 border-b">
                            <th class="py-2">Menu</th>
                            <th class="py-2">Jumlah Terjual</th>
                            <th class="py-2">jenis usaha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bestSeller as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $item['nama'] }}</td>
                                <td class="py-3 font-semibold">{{ $item['jumlah'] }}</td>
                                <td>{{ $item['business_name'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ============================
     📦 Stok yang Sering Keluar
  ============================ --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Stok yang Sering Keluar</h3>

                    <form id="filterStokKeluarForm" method="get">
                        <button id="dropdownStokKeluarButton" data-dropdown-toggle="dropdownStokKeluar" type="button"
                            class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">

                            {{ $filterStokKeluar == 'bulan' ? 'Bulanan' : ($filterStokKeluar == 'minggu' ? 'Mingguan' : 'Harian') }}

                            <svg class="w-2.5 h-2.5 ml-2" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <input type="hidden" id="filterStokKeluarInput" name="filter_stok_keluar"
                            value="{{ $filterStokKeluar }}">

                        <div id="dropdownStokKeluar"
                            class="hidden z-10 w-44 bg-white rounded shadow divide-y divide-gray-100">
                            <ul class="py-2 text-sm text-gray-700">
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setStokKeluarFilter('hari')"
                                        class="block px-4 py-2 hover:bg-gray-100">Harian</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setStokKeluarFilter('minggu')"
                                        class="block px-4 py-2 hover:bg-gray-100">Mingguan</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); setStokKeluarFilter('bulan')"
                                        class="block px-4 py-2 hover:bg-gray-100">Bulanan</a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-600 border-b">
                            <th class="py-2">Nama Bahan</th>
                            <th class="py-2">Jumlah Keluar</th>
                            <th class="py-2">Usaha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($stokSeringKeluar as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $item->nama_stock }}</td>
                                <td class="py-3 font-semibold">{{ $item->total_keluar }}</td>
                                <td>{{ $item->business_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-0">

            {{-- ============================
                 📊 Stok Keluar
            ============================ --}}
            <div class="max-w-sm md:max-w-lg w-full bg-white rounded-lg shadow-sm ">
                <div class="flex justify-between p-4 md:p-6 pb-0 md:pb-0">
                    <div>
                        <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">
                            Stok Keluar</h5>
                        <p class="text-sm font-normal text-gray-500 ">
                            {{ $filterStok == 'bulan' ? 'Bulan Ini: ' : 'Minggu Ini: ' }}{{ $totalStokKeluar }}</p>
                    </div>
                    <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 text-center">
                        <form id="filterFormStok" method="get">
                            <button id="dropdownDefaultButtonStok" data-dropdown-toggle="dropdownStokMenu"
                                data-dropdown-placement="bottom" type="button"
                                class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                                {{ request('filter_stok', 'minggu') == 'bulan' ? 'Bulan Ini' : 'Minggu Ini' }}
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <input type="hidden" name="filter_stok" id="filterInputStok"
                                value="{{ request('filter_stok', 'minggu') }}">
                            <div id="dropdownStokMenu"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 ">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('filterInputStok').value='minggu'; document.getElementById('filterFormStok').submit();"
                                            class="block px-4 py-2 hover:bg-gray-100 ">
                                            Minggu Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('filterInputStok').value='bulan'; document.getElementById('filterFormStok').submit();"
                                            class="block px-4 py-2 hover:bg-gray-100 ">
                                            Bulan Ini
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="stok-keluar-chart" class="px-2.5"></div>
                <div id="legend-stok" class="flex flex-wrap justify-center gap-4 p-4"></div>
            </div>

            {{-- ============================
                 🍽️ Menu Terjual
            ============================ --}}
            <div class="max-w-sm md:max-w-lg w-full bg-white rounded-lg shadow-sm ">
                <div class="flex justify-between p-4 md:p-6 pb-0 md:pb-0">
                    <div>
                        <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">
                            Menu Terjual</h5>
                        <p class="text-sm font-normal text-gray-500 ">
                            {{ $filterMenu == 'bulan' ? 'Bulan Ini: ' : 'Minggu Ini: ' }}{{ $totalMenuTerjual }}</p>
                    </div>
                    <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 text-center">
                        <form id="filterFormMenu" method="get">
                            <button id="dropdownDefaultButtonMenu" data-dropdown-toggle="dropdownMenuMenu"
                                data-dropdown-placement="bottom" type="button"
                                class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                                {{ request('filter_menu', 'minggu') == 'bulan' ? 'Bulan Ini' : 'Minggu Ini' }}
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <input type="hidden" name="filter_menu" id="filterInputMenu"
                                value="{{ request('filter_menu', 'minggu') }}">
                            <div id="dropdownMenuMenu"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 ">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('filterInputMenu').value='minggu'; document.getElementById('filterFormMenu').submit();"
                                            class="block px-4 py-2 hover:bg-gray-100 ">
                                            Minggu Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('filterInputMenu').value='bulan'; document.getElementById('filterFormMenu').submit();"
                                            class="block px-4 py-2 hover:bg-gray-100 ">
                                            Bulan Ini
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="menu-terjual-chart" class="px-2.5"></div>
                <div id="legend-menu" class="flex flex-wrap justify-center gap-4 p-4"></div>
            </div>

        </div>

        {{-- ============================
             📜 Script Chart
        ============================ --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const baseColors = [
                    "#1C64F2", "#16BDCA", "#FDBA8C", "#8B5CF6", "#10B981",
                    "#F59E0B", "#EF4444", "#EC4899", "#6366F1", "#14B8A6"
                ];

                const colorMap = new Map();

                function getColorForName(name) {
                    if (!colorMap.has(name)) {
                        const color = baseColors[colorMap.size % baseColors.length];
                        colorMap.set(name, color);
                    }
                    return colorMap.get(name);
                }

                function makeChart(elementId, legendId, rawSeries, categories, dataKey, valueFormatter, unit = '',
                    xLabel = 'Tanggal', yLabel = 'Jumlah') {
                    const el = document.getElementById(elementId);
                    if (!el) return;

                    // Render Chart
                    const options = {
                        series: rawSeries.map((s) => ({
                            name: s.name,
                            data: s.data.map(d => Number(d[dataKey]) || 0),
                            color: getColorForName(s.name)
                        })),
                        chart: {
                            height: 300,
                            type: "area",
                            toolbar: {
                                show: false
                            },
                            fontFamily: "Inter, sans-serif"
                        },
                        xaxis: {
                            categories: categories,
                            title: {
                                text: xLabel,
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 600
                                }
                            },
                            labels: {
                                style: {
                                    fontFamily: "Inter, sans-serif",
                                    cssClass: 'text-xs fill-gray-500'
                                },
                                formatter: function(value) {
                                    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                                        const [y, m, d] = value.split('-');
                                        const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul",
                                            "Agu", "Sep", "Okt", "Nov", "Des"
                                        ];
                                        return `${parseInt(d)} ${monthNames[parseInt(m) - 1]}`;
                                    }
                                    return value;
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: yLabel,
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 600
                                }
                            },
                            labels: {
                                formatter: function(value) {
                                    return valueFormatter ? valueFormatter(value) : value.toLocaleString(
                                        'id-ID');
                                }
                            }
                        },
                        tooltip: {
                            shared: true,
                            custom: function({
                                dataPointIndex
                            }) {
                                let html =
                                    `<div class="bg-gray-800 text-white p-2 rounded-lg shadow-md text-sm">`;
                                rawSeries.forEach((s) => {
                                    const color = getColorForName(s.name);
                                    const dataObj = s.data[dataPointIndex] || {};
                                    const val = dataObj[dataKey] ?? 0;
                                    html += `
                                        <div class="flex items-center space-x-2">
                                            <span style="background:${color}" class="w-3 h-3 rounded-full inline-block"></span>
                                            <span>${s.name}: <b>${unit}${new Intl.NumberFormat('id-ID').format(val)}</b></span>
                                        </div>`;
                                });
                                return html + `</div>`;
                            }
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                opacityFrom: 0.55,
                                opacityTo: 0
                            }
                        },
                        stroke: {
                            width: 6
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            show: true
                        },
                    };

                    new ApexCharts(el, options).render();

                    // Render Legend Custom
                    const legendContainer = document.getElementById(legendId);
                    legendContainer.innerHTML = rawSeries.map(s => {
                        const total = s.data.reduce((sum, d) => sum + (Number(d[dataKey]) || 0), 0);
                        const color = getColorForName(s.name);
                        return `
                            <div class="flex items-center gap-2 text-sm">
                                <span style="background:${color}" class="w-3 h-3 rounded-full inline-block"></span>
                                <span>${s.name}: <b>${unit}${new Intl.NumberFormat('id-ID').format(total)}</b></span>
                            </div>
                        `;
                    }).join('');
                }

                makeChart("labels-chart", "legend-pendapatan", @json($seriesPendapatan),
                    @json($categoriesPendapatan), "pendapatan",
                    (v) => "Rp" + v.toLocaleString("id-ID"), "Rp", "Tanggal", "Jumlah Pendapatan (Rp)");

                makeChart("stok-keluar-chart", "legend-stok", @json($stokSeries),
                    @json($categoriesStok), "stok_keluar", null, "",
                    "Tanggal", "Jumlah Stok yang Keluar");

                makeChart("menu-terjual-chart", "legend-menu", @json($menuSeries),
                    @json($categoriesMenu), "jumlah_menu", null, "",
                    "Tanggal", "Jumlah Menu yang Terjual");
            });
        </script>
        <script>
            function setBestSellerFilter(filter) {
                document.getElementById("filterBestSellerInput").value = filter;
                document.getElementById("filterBestSellerForm").submit();
            }
        </script>
        <script>
            function setStokKeluarFilter(filter) {
                document.getElementById("filterStokKeluarInput").value = filter;
                document.getElementById("filterStokKeluarForm").submit();
            }
        </script>
    </div>
@endsection
