@extends('layouts.app')

@section('content')
    <div class="bg-overlay">
        <div class="container-fluid mt-4">
            <h1 class="h2 text-black">Dashboard</h1>

            <div class="row">
                <!-- Card 1: Total Services -->
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-cogs"></i> Total Services
                            </h5>
                            <p class="card-text display-4">{{ $totalServices }}</p>
                            <a href="{{ route('landscape') }}" class="btn btn-outline-info">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Bookings -->
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-calendar-check"></i> Total Bookings
                            </h5>
                            <p class="card-text display-4">{{ $totalBookings }}</p>
                            <a href="{{ route('booking.adminBooking') }}"class="btn btn-outline-info">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Projects -->
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-briefcase"></i> Total Projects
                            </h5>
                            <p class="card-text display-4">{{ $totalProjects }}</p>
                            <a href="{{ route('project.adminIndex') }}" class="btn btn-outline-info">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Revenue -->
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center">
                            <h5 class="card-title text-info">
                                <i class="fas fa-money-bill-wave"></i> Total Revenue
                            </h5>
                            <p class="card-text display-4">₱{{ number_format($totalRevenue, 2) }}</p>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-info">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Revenue Chart -->
                <div class="col-md-12 mb-3">
                    <div class="card shadow-sm border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Revenue Overview</h5>
                            <canvas id="revenueChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Activities Table -->
                <div class="col-md-12">
                    <div class="card shadow-sm border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Recent Activities</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Doe</td>
                                        <td><i class="fas fa-plus-circle text-success"></i> Created a new service</td>
                                        <td>2024-09-23</td>
                                    </tr>
                                    <tr>
                                        <td>Jane Smith</td>
                                        <td><i class="fas fa-user-edit text-warning"></i> Updated user profile</td>
                                        <td>2024-09-22</td>
                                    </tr>
                                    <tr>
                                        <td>Mark Johnson</td>
                                        <td><i class="fas fa-trash-alt text-danger"></i> Deleted a service</td>
                                        <td>2024-09-21</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 30000, 50000, 21000, 35000, 45000, 40000, 60000, 80000, 90000,
                            95000
                        ],
                        backgroundColor: 'rgba(23, 162, 184, 0.2)', // Semi-transparent info color
                        borderColor: 'rgba(23, 162, 184, 1)', // Solid info color
                        borderWidth: 2,
                        fill: true,
                        pointBackgroundColor: 'rgba(23, 162, 184, 1)', // Points color
                    },
                    {
                        label: 'Total Bookings',
                        data: [10, 15, 20, 25, 20, 30, 35, 40, 45, 50, 55, 60],
                        backgroundColor: 'rgba(255, 193, 7, 0.2)', // Semi-transparent warning color
                        borderColor: 'rgba(255, 193, 7, 1)', // Solid warning color
                        borderWidth: 2,
                        fill: true,
                        pointBackgroundColor: 'rgba(255, 193, 7, 1)', // Points color for bookings
                    },
                    {
                        label: 'Total Projects',
                        data: [5, 10, 15, 20, 15, 25, 30, 35, 30, 40, 45, 50],
                        backgroundColor: 'rgba(0, 123, 255, 0.2)', // Semi-transparent primary color
                        borderColor: 'rgba(0, 123, 255, 1)', // Solid primary color
                        borderWidth: 2,
                        fill: true,
                        pointBackgroundColor: 'rgba(0, 123, 255, 1)', // Points color for projects
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (₱)',
                            color: '#17a2b8'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months',
                            color: '#17a2b8'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#17a2b8'
                        }
                    }
                }
            }
        });
    </script>

    <style>
        .bg-overlay {
            background-color: #e0f7f9;
            padding: 15px;
            border-radius: 10px;
        }

        .card {
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .display-4 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #17a2b8;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line', // Change this to 'bar', 'pie', etc., if you want a different type of chart
            data: {
                labels: chartData.labels, // Month names
                datasets: [{
                    label: 'Total Revenue',
                    data: chartData.data, // Revenue data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
