@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <!-- Cards Statistik -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <div class="media-body me-3">
                                        <h2 class="fs-34 text-success font-w600">Rp. <span
                                                class="counter">{{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</span>
                                        </h2>
                                        <p class="fs-16 mb-0">Pendapatan Hari Ini</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-money-bill text-success" style="font-size:40px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <div class="media-body me-3">
                                        <h2 class="fs-34 text-primary font-w600"><span
                                                class="counter">{{ $totalTransaksiHariIni }}</span></h2>
                                        <p class="fs-16 mb-0">Transaksi Hari Ini</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-shopping-cart text-primary" style="font-size:40px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <div class="media-body me-3">
                                        <h2 class="fs-34 text-info font-w600"><span
                                                class="counter">{{ $totalPelanggan }}</span></h2>
                                        <p class="fs-16 mb-0">Total Pelanggan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users text-info" style="font-size:40px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <div class="media-body me-3">
                                        <h2 class="fs-34 text-warning font-w600"><span
                                                class="counter">{{ $laundryBelumDiambil }}</span></h2>
                                        <p class="fs-16 mb-0">Laundry Belum Diambil</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clock text-warning" style="font-size:40px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik -->
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header border-0 flex-wrap pb-0">
                                <div class="mb-3">
                                    <h4 class="fs-20 font-w700">Statistik Pendapatan</h4>
                                    <span class="fs-14">Pendapatan per bulan dalam tahun {{ date('Y') }}</span>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div id="revenueChart" style="min-height: 365px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="fs-20 font-w700">Status Cucian</h4>
                                <span class="fs-14 font-w400 d-block">Jumlah cucian berdasarkan status</span>
                            </div>
                            <div class="card-body">
                                <div id="statusChart"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard script loaded');

            // Pastikan ApexCharts sudah ter-load
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts tidak ditemukan. Pastikan library sudah di-load.');
                return;
            }

            console.log('ApexCharts berhasil ditemukan');

            // Data untuk grafik pendapatan bulanan (12 bulan)
            const pendapatanData = Array(12).fill(0);
            @foreach ($pendapatanBulanan as $data)
                pendapatanData[{{ $data->bulan - 1 }}] = {{ $data->total }};
            @endforeach

            // Grafik Pendapatan
            const revenueOptions = {
                series: [{
                    name: 'Pendapatan',
                    data: pendapatanData
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#886CC0'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function(val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }
                    }
                }
            };

            // Cek apakah element chart ada
            const revenueElement = document.querySelector("#revenueChart");
            if (revenueElement) {
                console.log('Rendering revenue chart...');
                const revenueChart = new ApexCharts(revenueElement, revenueOptions);
                revenueChart.render();
            } else {
                console.error('Element #revenueChart tidak ditemukan');
            }

            // Data untuk grafik status
            const statusData = [];
            const statusLabels = [];
            @if ($statusCucian->count() > 0)
                @foreach ($statusCucian as $status)
                    statusData.push({{ $status->total }});
                    statusLabels.push('{{ $status->status_pengerjaan }}');
                @endforeach
            @else
                // Data default jika tidak ada transaksi
                statusData.push(0);
                statusLabels.push('Belum ada data');
            @endif

            // Grafik Status
            const statusOptions = {
                series: statusData,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: statusLabels,
                colors: ['#FF9F00', '#00E396', '#008FFB'],
                legend: {
                    show: false
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " transaksi"
                        }
                    }
                }
            };

            // Cek apakah element chart ada
            const statusElement = document.querySelector("#statusChart");
            if (statusElement) {
                console.log('Rendering status chart...');
                const statusChart = new ApexCharts(statusElement, statusOptions);
                statusChart.render();
            } else {
                console.error('Element #statusChart tidak ditemukan');
            }
        });
    </script>
@endpush