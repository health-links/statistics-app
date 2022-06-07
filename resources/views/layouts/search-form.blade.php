<div class="mb-3">
    <form action="{{ route('home') }}" method="GET" class="px-2">
        <div class="mb-2">
            <label for="">Clients</label>
            <select name="filter[client_id]" class="form-control" id="">
                <option value="">Select Client</option>
                @foreach ($clients as $client)
                    <option @if (request()->filter && request()->filter['client_id'] && request()->filter['client_id'] == $client->c_id) selected @endif value="{{ $client->c_id }}">
                        {{ $client->c_acronym }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label for="">Service</label>
            <select name="filter[service_id]" class="form-control" id="">
                <option value="">Select service</option>
                @foreach ($services as $service)
                    <option @if (request()->filter && request()->filter['service_id'] && request()->filter['service_id'] == $service->s_id) selected @endif value="{{ $service->s_id }}">
                        {{ $service->s_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label" for="fp-default">Duration From Date</label>
            <input type="text" id="fp-default" class="form-control flatpickr flatpickr-input" placeholder="YYYY-MM-DD"
                readonly="readonly" name="filter[from]"
                value="{{ request()->filter && request()->filter['from'] && request()->filter['from'] ? request()->filter['from'] : '' }}">

        </div>
        <div class="mb-2">
            <label class="form-label" for="fp-default">Duration To Date</label>
            <input type="text" id="fp-default" class="form-control flatpickr flatpickr-input" placeholder="YYYY-MM-DD"
                readonly="readonly" name="filter[to]"
                value="{{ request()->filter && request()->filter['to'] && request()->filter['to'] ? request()->filter['to'] : '' }}">
        </div>

        <div>
            <button class="btn btn-success" type="submit">Filter</button>
        </div>
    </form>
</div>
