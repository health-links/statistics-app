
text/x-generic topPostiveTopic.blade.php ( HTML document, ASCII text )
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

        .col-postive {
            width: 100%;
            border-right: 1px solid #fff;

        }

        .col-postive p {
    min-width: 87px;
    color: #fff;
    padding: 4px 5px;
    align-self: end;
    font-size: 11px;
        }

        .col-postive-2 {
            border-bottom: 1px solid #fff;
            height: 100%;
        }

        .postive-1 {
            background: rgb(7, 175, 149, 1);
        }

        .postive-2 {
            background: rgb(7, 175, 149, 0.9);
        }

        .postive-3 {
            background: rgb(7, 175, 149, 0.8);
        }

        .postive-4 {
            background: rgb(7, 175, 149, 0.7);
        }

        .postive-5 {
            background: rgb(7, 175, 149, 0.6);
        }

        .postive-6 {
            background: rgb(7, 175, 149, 0.5);
        }

        .postive-7 {
            background: rgb(7, 175, 149, 0.4);
        }

        .postive-8 {
            background: rgb(7, 175, 149, 0.3);
        }

        .postive-9 {
            background: rgb(7, 175, 149, 0.2);
        }

        .postive-10 {
            background: rgb(7, 175, 149, 0.2);
        }
    </style>
@endpush
<div class="col-xl-12 col-md-6 col-12">
    <div class="card">
        <div class="card-header">
            <h6>Key Strengths</h6>
        </div>
        @php

            $positiveData = $topTopics['topPositiveTopics'];
        @endphp
        <div class="card-body">
            <div class="flex view">
                <div class="col-postive postive-1">
                    <p>{{ $positiveData[0]->t_name }} {{ $positiveData[0]->positive_count }}</p>

                </div>
                <div class="col-postive flex-column">
                    <div class="col-postive-2 flex">
                        <div class="col-postive postive-2">
                            <p>{{ $positiveData[1]->t_name }}
                                {{ $positiveData[1]->positive_count }}</p>

                        </div>
                        <div class="col-postive postive-3">
                            <p>{{ $positiveData[2]->t_name }}
                                {{ $positiveData[2]->positive_count }}</p>
                        </div>
                    </div>
                    <div class="col-postive-2 flex">
                        <div class="col-postive postive-4">
                            <p>{{ $positiveData[3]->t_name }} {{ $positiveData[3]->positive_count }}</p>
                        </div>
                        <div class="col-postive postive-5">
                            <p>{{ $positiveData[4]->t_name }} {{ $positiveData[4]->positive_count }}</p>
                        </div>
                        <div class="col-postive flex-column">
                            <div class="col-postive-2 flex">
                                <div class="col-postive postive-6">
                                    <p>{{ $positiveData[5]->t_name }} {{ $positiveData[5]->positive_count }}</p>
                                </div>
                                <div class="col-postive postive-7">
                                    <p>{{ $positiveData[6]->t_name }} {{ $positiveData[6]->positive_count }}</p>
                                </div>
                            </div>
                            <div class="col-postive-2 flex">
                                <div class="col-postive postive-8">
                                    <p>{{ $positiveData[7]->t_name }} {{ $positiveData[7]->positive_count }}</p>
                                </div>
                                <div class="col-postive flex-column">
                                    <div class="col-postive-2 postive-9">
                                        <p>{{ $positiveData[8]->t_name }} {{ $positiveData[8]->positive_count }}
                                        </p>
                                    </div>
                                    <div class="col-postive-2 postive-10">
                                        <p>{{ $positiveData[9]->t_name }} {{ $positiveData[9]->positive_count }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
