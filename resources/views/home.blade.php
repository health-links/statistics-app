@extends('layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">

                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Statistics Card -->
                        <div class="col-xl-12 col-md-6 col-12">
                            <div class="card card-statistics">

                                <div class="card-body statistics-body">
                                    @include('charts.overall')
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                    <div class="row match-height parent">
                        <div class="col-lg-4 col-12">


                            <!-- Earnings Card -->
                            @include('charts.donut-chart')
                            <!--/ Earnings Card -->

                        </div>
                        <div class="col-lg-8 col-12">
                            <!-- Earnings Card -->
                            @include('charts.trend')
                            <!--/ Earnings Card -->

                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-6 col-12">
                            <!-- Earnings Card -->
                            @include('charts.categories')
                            <!--/ Earnings Card -->

                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="testimonial p-2">
                                <div class="header">

                                    <h3>Health-Links</h3>
                                    <h3>Comments Intelligence</h3>
                                </div>
                                <div class="body pt-1">

                                    <p>Health-Links Patient Experience Text Analytics model
                                        leverages Natural language Processing (NLP) and Artificial
                                        Intelligence (AI) capabilities to translate patient comments
                                        into actionable insights.</p>
                                    <p>The model will analyze each comment at two levels,
                                        Categories and Topics, to provide Health-Links clients with
                                        a robust and comprehensive analysis of their patients’
                                        comments</p>
                                    <p>The model breaks complex comments down into
                                        individual phrases or insights. These insights are assigned
                                        a sentiment rating (positive or negative) and then
                                        classified by “Topic”.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row match-height">
                        <div class="col-lg-12 col-12">


                            <!-- Earnings Card -->
                            @include('charts.topics')
                            <!--/ Earnings Card -->

                        </div>
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-12 col-12">
                            <!-- Earnings Card -->
                            @include('charts.heatmap')
                            <!--/ Earnings Card -->

                        </div>
                    </div>
                    <div class="row match-height">
                        <!-- Statistics Card -->
                            <div class="col-lg-6 col-12">
                                @include('charts.topPostiveTopic')
                            </div>
                            <div class="col-lg-6 col-12">
                                @include('charts.topNegativeTopic')
                            </div>

                        <!--/ Statistics Card -->
                    </div>
                    <div class="row match-height">
                        <div class="col-lg-12 col-12">


                            <!-- Earnings Card -->
                            @include('charts.table')
                            <!--/ Earnings Card -->

                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
@endsection
