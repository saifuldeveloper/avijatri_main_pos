@extends('layouts.app', ['title' => 'জুতা জোলাপ'])

@section('content')
 
    <h1>জুতা জোলাপ</h1>
    <form action="{{ route('waste.shoes') }}" method="post" class="row">
        @csrf
        <div class="col-md-2 form-group">
            <label for="waste-id">আইডি</label>
            <input type="text" name="shoe_id" id="waste-id" class="form-control number" required
                data-shoe-details="{{ route('ajax.shoe.show', ['shoe' => '#']) }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="waste-description">বিবরণ</label>
            <input type="text" name="description" id="waste-description" class="form-control">
        </div>
        <div class="col-md-2 form-group">
            <label for="waste-count">জোড়া</label>
            <input type="text" name="count" id="waste-count" class="form-control number" required>
        </div>
        <div class="col-md-2 form-group">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary form-control">জমা দিন</button>
        </div>
        <div class="col-md-12">
            <div id="shoe-description" class="alert alert-info d-none"></div>
        </div>
    </form>

    @if($retailstoreCount > 0)
    <h3>পার্টি  জোলাপ </h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width:8%"></th>
                <th style="width:7%" class="text-center">তারিখ</th>
                <th style="width:7%" class="text-center">আইডি</th>
                <th style="width:8%" class="text-center">টাইপ</th>
                <th style="width:8%" class="text-center">রং</th>
                <th style="width:10%" class="text-center">গায়ের দাম</th>
                <th style="width:20%">বিবরণ</th>
                <th style="width:15%">পার্টি </th>
                <th style="width:15%">মহাজন </th>
                <th style="width:8%" class="text-center">জোড়া</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wasteEntries as $wasteEntry)
                @if ($wasteEntry->entries_type == 'retail_store')
                    <tr>
                        <td>@include('templates.thumbnail-preview', [
                            'href' => $wasteEntry->shoe->full_image_url,
                            'small_thumbnail' => $wasteEntry->shoe->image_url,
                            'preview' => $wasteEntry->shoe->preview_url,
                        ])</td>
                        <td class="text-center">{{ $wasteEntry->created_at }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->id }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->category->full_name }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->color->name }}</td>
                        <td class="text-center">{{ toFixed($wasteEntry->shoe->retail_price) }}</td>
                        <td>{{ $wasteEntry->description }}</td>
                        <td>
                            @php
                                $retailStore = \App\Models\RetailStore::find($wasteEntry->entries_id);
                            @endphp
                            {{ $retailStore->shop_name }}-{{ $retailStore->address }}
                        </td>
                        <td>{{ $wasteEntry->shoe->factory->name }}-{{ $wasteEntry->shoe->factory->address }}</td>
                        </td>
                        <td class="text-center">{{ $wasteEntry->count }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

    @if($factoryCount > 0)

    <h3> মহাজন  জোলাপ </h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th style="width:13%" class="text-center">তারিখ</th>
                <th style="width:8%" class="text-center">আইডি</th>
                <th style="width:8%" class="text-center">টাইপ</th>
                <th style="width:8%" class="text-center">রং</th>
                <th style="width:10%" class="text-center">গায়ের দাম</th>
                <th style="width:35%">বিবরণ</th>
				<th style="width:15%">মহাজন </th>
                <th style="width:8%" class="text-center">জোড়া</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wasteEntries as $wasteEntry)
                @if ($wasteEntry->entries_type == 'factory')
                    <tr>
                        <td>@include('templates.thumbnail-preview', [
                            'href' => $wasteEntry->shoe->full_image_url,
                            'small_thumbnail' => $wasteEntry->shoe->image_url,
                            'preview' => $wasteEntry->shoe->preview_url,
                        ])</td>
                        <td class="text-center">{{ $wasteEntry->created_at }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->id }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->category->full_name }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->color->name }}</td>
                        <td class="text-center">{{ toFixed($wasteEntry->shoe->retail_price) }}</td>
                        <td>{{ $wasteEntry->description }}</td>
						<td>
							@php
							$factory = \App\Models\Factory::find($wasteEntry->entries_id);
						@endphp
						{{ $factory->name }}-{{$factory->address }}
						</td>
                        <td class="text-center">{{ $wasteEntry->count }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif
 
  @if($otherCount >0)
	<h3> অন্যানো   জোলাপ </h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th style="width:7%" class="text-center">তারিখ</th>
                <th style="width:8%" class="text-center">আইডি</th>
                <th style="width:8%" class="text-center">টাইপ</th>
                <th style="width:8%" class="text-center">রং</th>
                <th style="width:10%" class="text-center">গায়ের দাম</th>
                <th style="width:20%">বিবরণ</th>
                <th style="width:15%">মহাজন </th>
                <th style="width:8%" class="text-center">জোড়া</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wasteEntries as $wasteEntry)
                @if ($wasteEntry->entries_type !== 'factory'  &&  $wasteEntry->entries_type !== 'retail_store'  )
                    <tr>
                        <td>@include('templates.thumbnail-preview', [
                            'href' => $wasteEntry->shoe->full_image_url,
                            'small_thumbnail' => $wasteEntry->shoe->image_url,
                            'preview' => $wasteEntry->shoe->preview_url,
                        ])</td>
                        <td class="text-center">{{ $wasteEntry->created_at }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->id }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->category->full_name }}</td>
                        <td class="text-center">{{ $wasteEntry->shoe->color->name }}</td>
                        <td class="text-center">{{ toFixed($wasteEntry->shoe->retail_price) }}</td>
                        <td>{{ $wasteEntry->description }}</td>
                        <td>{{ $wasteEntry->shoe->factory->name }}-{{$wasteEntry->shoe->factory->address}}</td>
                        <td class="text-center">{{ $wasteEntry->count }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif


    {{ $wasteEntries->links('pagination.default') }}
@endsection

@section('page-script')
    <script src="{{ asset('js/waste/shoes.js') }}"></script>
@endsection
