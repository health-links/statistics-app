@push('css')
    <style>
        .view {
            height: 354px!important;
        }

        .flex {
            display: flex;
        }

        .flex-column {
            display: flex;
            flex-direction: column;
        }

        .col-negative {

            width: 100%;
            border-right: 1px solid #fff;
        }

        .col-negative p {
    min-width: 87px;
    color: #fff;
    padding: 4px 5px;
    align-self: end;
    font-size: 11px;
        }

        .col-negative-2 {
            border-bottom: 1px solid #fff;
            height: 100%;
        }

        .negative-1 {
            background: rgb(192, 35, 4, 1);
        }

        .negative-2 {
            background: rgb(192, 35, 4, 0.9);
        }

        .negative-3 {
            background: rgb(192, 35, 4, 0.8);
        }

        .negative-4 {
            background: rgb(192, 35, 4, 0.7);
        }

        .negative-5 {
            background: rgb(192, 35, 4, 0.6);
        }

        .negative-6 {
            background: rgb(192, 35, 4, 0.5);
        }

        .negative-7 {
            background: rgb(192, 35, 4, 0.4);
        }

        .negative-8 {
            background: rgb(192, 35, 4, 0.3);
        }

        .negative-9 {
            background: rgb(192, 35, 4, 0.2);
        }

        .negative-10 {
            background: rgb(192, 35, 4, 0.2);
        }
    </style>
@endpush


<div class="col-xl-12 col-md-6 col-12">
    <div class="card">
        <div class="card-header">
            <h6 >Key Strengths</h6>

        </div>
        @php

            $negativeData = $topTopics['topNegativeTopics'];
        @endphp
        <div class="card-body ">
            <div class="flex view">
                <div class="col-negative flex-column">
                    <div class="col-negative-2 flex">
                        <div class="col-negative negative-1"> <p>{{ $negativeData[0]->t_name }} {{ $negativeData[0]->negative_count }}</p></div>
                    </div>
                    <div class="col-negative-2 flex">
                        <div class="col-negative negative-2"> <p>{{ $negativeData[1]->t_name }} {{ $negativeData[1]->negative_count }}</p></div>
                    </div>
                </div>
                <div class="col-negative flex-column">
                    <div class="col-negative-2 flex">
                        <div class="col-negative negative-3"> <p>{{ $negativeData[2]->t_name }} {{ $negativeData[2]->negative_count }}</p></div>
                        <div class="col-negative negative-4"> <p>{{ $negativeData[3]->t_name }} {{ $negativeData[3]->negative_count }}</p></div>
                    </div>
                    <div class="col-negative-2 flex">
                        <div class="col-negative negative-3"> <p>{{ $negativeData[2]->t_name }} {{ $negativeData[4]->negative_count }}</p></div>
                        <div class="col-negative negative-4"> <p>{{ $negativeData[3]->t_name }} {{ $negativeData[5]->negative_count }}</p></div>
                    </div>
                    <div class="col-negative-2 flex">
                        <div class="col-negative negative-5"> <p>{{ $negativeData[4]->t_name }} {{ $negativeData[6]->negative_count }}</p></div>
                        <div class="col-negative negative-6"> <p>{{ $negativeData[5]->t_name }} {{ $negativeData[7]->negative_count }}</p></div>
                        <div class="col-negative flex-column">
                            <div class="col-negative-2 flex">
                                <div class="col-negative negative-7"> <p>{{ $negativeData[6]->t_name }} {{ $negativeData[8]->negative_count }}</p></div>

                            </div>
                            <div class="col-negative-2 flex">
                                <div class="col-negative negative-8"> <p>{{ $negativeData[7]->t_name }} {{ $negativeData[9]->negative_count }}</p></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
