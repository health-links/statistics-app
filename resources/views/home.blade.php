@extends('./welcome')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Statistics Card -->
                        <div class="col-xl-12 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">Overall Statistics</h4>
                                    <div class="d-flex align-items-center">
                                        <p class="card-text font-small-2 me-25 mb-0"></p>
                                    </div>
                                </div>
                                <div class="card-body statistics-body">
                                    @include('charts.overall')
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                @include('charts.donut-chart')
                                <!--/ Earnings Card -->
                            </div>
                        </div>

                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                @include('charts.trend')
                                <!--/ Earnings Card -->
                            </div>
                        </div>

                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                @include('charts.chunks')
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                @include('charts.topics')
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <div class="row match-height">

                                <!-- Earnings Card -->
                                @include('charts.heatmap')
                                <!--/ Earnings Card -->
                            </div>
                        </div>
                    </div>



                </section>
            </div>
        </div>
    </div>
@endsection
