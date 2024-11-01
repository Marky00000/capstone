@extends('layouts.apps')

@section('content')
    <div class="bg-overlay">
        <div class="container-fluid mt-2">
            <h1 class="h2 text-black">Dashboard</h1>

            <div class="row">
                <!-- Card 1: Total Services -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-cogs"></i> Total Services
                            </h5>
                            <p class="card-text display-4">{{ $totalServices }}</p>
                            <a href="{{ route('landscape') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Bookings -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-calendar-check"></i> Total Bookings
                            </h5>
                            <p class="card-text display-4">{{ $totalBookings }}</p>
                            <a href="{{ route('booking.adminBooking') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Projects -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-briefcase"></i> Total Projects
                            </h5>
                            <p class="card-text display-4">{{ $totalProjects }}</p>
                            <a href="{{ route('project.adminIndex') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Revenue -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-money-bill-wave"></i> Total Revenue
                            </h5>
                            <p class="card-text display-4">₱{{ number_format($totalRevenue, 2) }}</p>
                            <a href="{{ route('reports.rates') }}" class="btn btn-info btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Booking Status Overview Pie Chart -->
            <div class="row mb-4">
                <div class="col-md-6 mb-2" style="display: flex; justify-content: flex-start; padding-right: 10px;">
                    <!-- Adjusted padding -->
                    <div style="width: 100%; max-width: 550px;"> <!-- Keep max-width for size -->
                        <h4 class="col-12" style="font-weight: bold;">Bookings</h4>
                        <canvas id="bookingStatusChart" width="400" height="400"
                            style="max-width: 100%; height: auto;"></canvas> <!-- Size remains larger -->
                    </div>
                </div>

                <div class="col-md-6 mb-2" style="display: flex; justify-content: flex-start;">
                    <!-- Left-aligned for closer positioning -->
                    <div style="width: 100%; max-width: 700px;"> <!-- Keep max-width for size -->
                        <h4 class="col-12" style="font-weight: bold;">Projects</h4>
                        <canvas id="projectStatusChart" width="400" height="300"
                            style="max-width: 100%; height: auto;"></canvas> <!-- Size remains larger -->
                    </div>
                </div>
            </div>


            <div class="row mb-4" style="margin: 0;">
                <h4 class="col-12" style="font-weight: bold;">Booking & Payments</h4>
                <div class="col-12" style="padding: 0;">
                    <canvas id="bookingsPaymentsChart" style="width: 100%; height: 300px;"></canvas>
                    <!-- Height increased here -->
                    <!-- Bookings & Payments Chart -->
                </div>
            </div>




        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Booking Status Pie Chart
            const ctxBookingStatus = document.getElementById('bookingStatusChart').getContext('2d');
            const bookingStatusData = @json($bookingStatusData); // Pass PHP array to JavaScript

            const bookingStatusChart = new Chart(ctxBookingStatus, {
                type: 'pie',
                data: {
                    labels: bookingStatusData.labels,
                    datasets: [{
                        data: bookingStatusData.data,
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#17a2b8'
                            }
                        }
                    }
                }
            });

            // Project Status Bar Chart
            const ctxProjectStatus = document.getElementById('projectStatusChart').getContext('2d');
            const projectStatusData = @json($projectStatusData); // Pass PHP array to JavaScript

            const projectStatusChart = new Chart(ctxProjectStatus, {
                type: 'bar',
                data: {
                    labels: projectStatusData.labels,
                    datasets: [{
                        label: 'Project Status',
                        data: projectStatusData.data,
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });

            // Dual-axis chart for bookings and payments over time
            const ctxBookingsPayments = document.getElementById('bookingsPaymentsChart').getContext('2d');
            const bookingsPaymentsData = @json($bookingsPaymentsData); // Pass PHP array to JavaScript

            const bookingsPaymentsChart = new Chart(ctxBookingsPayments, {
                type: 'line',
                data: {
                    labels: bookingsPaymentsData.labels,
                    datasets: [{
                            label: 'Bookings',
                            data: bookingsPaymentsData.bookings,
                            borderColor: '#007bff',
                            yAxisID: 'y1',
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Payments',
                            data: bookingsPaymentsData.payments,
                            borderColor: '#28a745',
                            yAxisID: 'y2',
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y1: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Bookings'
                            }
                        },
                        y2: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false // only draw the grid for one axis
                            },
                            title: {
                                display: true,
                                text: 'Payments'
                            }
                        }
                    }
                }
            });
        </script>

        <style>
            .bg-overlay {
                background: linear-gradient(to bottom right, #e0f7f9, #ffffff);
                padding: 20px;
                /* Adjusted padding */
                border-radius: 10px;
            }

            .card {
                border-radius: 10px;
                transition: transform 0.2s, box-shadow 0.2s;
                border: 1px solid #dee2e6;
                /* Light border for modern look */
            }

            .card:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .display-4 {
                font-size: 2rem;
                /* Adjusted font size for modern look */
                font-weight: bold;
                color: #17a2b8;
            }

            .btn-info {
                background-color: #17a2b8;
                border-color: #17a2b8;
                transition: background-color 0.3s, border-color 0.3s;
            }

            .btn-info:hover {
                background-color: #138496;
                border-color: #117a8b;
            }
        </style>
    @endsection
