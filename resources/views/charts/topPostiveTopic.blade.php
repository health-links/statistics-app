@push('css')
    <style>
      .view {
            height: 200px;
            width: 30vw;
        }
        .flex {
            display: flex;
        }
        .flex-column {
            display: flex;
            flex-direction: column;
        }
        .col-div {
            background: green;
            width:  100%;
            border-right: 1px solid #fff;
        }
        .col-div-2 {
            border-bottom: 1px solid #fff;
            height: 100%;
        }
    </style>
@endpush
<div class="col-lg-12 col-md-6 col-12" style="height: 200px;">
    <div class="flex view">
        <div class="col-div"></div>
        <div class="col-div flex-column">
            <div class="col-div-2 flex">
                <div class="col-div"></div>
                <div class="col-div"></div>
            </div>
            <div class="col-div-2 flex">
                <div class="col-div"></div>
                <div class="col-div"></div>
                <div class="col-div flex-column">
                    <div class="col-div-2 flex">
                        <div class="col-div"></div>
                        <div class="col-div"></div>
                    </div>
                    <div class="col-div-2 flex">
                        <div class="col-div"></div>
                        <div class="col-div flex-column">
                            <div class="col-div-2"></div>
                            <div class="col-div-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
