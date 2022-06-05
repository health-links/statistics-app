<div class="row">

    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-primary me-2">
                <div class="avatar-content">
                    <i data-feather="trending-up" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{  number_format($overAllComments->negative) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Negative</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-info me-2">
                <div class="avatar-content">
                    <i data-feather="user" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->positive) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Positive</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-danger me-2">
                <div class="avatar-content">
                    <i data-feather="box" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->neutral) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Neutral</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="d-flex flex-row">
            <div class="avatar bg-light-success me-2">
                <div class="avatar-content">
                    <i data-feather="dollar-sign" class="avatar-icon"></i>
                </div>
            </div>
            <div class="my-auto">
                <h4 class="fw-bolder mb-0">{{ number_format($overAllComments->mixed) ?? 0 }}</h4>
                <p class="card-text font-small-3 mb-0">Mixed</p>
            </div>
        </div>
    </div>
</div>
