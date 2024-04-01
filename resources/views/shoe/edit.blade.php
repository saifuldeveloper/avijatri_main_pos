<h5>{{ 'জুতা এডিট' }}</h5>
<form action="{{ route('shoe.update', ['shoe' => $shoe]) }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="row">
	{{ method_field('PUT') }}
	{{ csrf_field() }}
	<div class="form-group col-12">
		<label for="shoe-factory">মহাজন</label>
		<input type="text" name="factory" id="shoe-factory" class="form-control" value="{{ old('factory', $shoe->factory->name ?? '') }}" readonly list="factory-list">
		<input type="hidden" name="factory_id" value="{{ old('factory_id', $shoe->factory_id ?? '') }}">
	</div>
	<div class="form-group col-6">
		<label for="shoe-category">জুতার ধরন</label>
		<input type="text" name="category" id="shoe-category" class="form-control" value="{{ old('category', $shoe->category->full_name) }}" list="category-list">
		<input type="hidden" name="category_id" value="{{ old('category_id', $shoe->category_id) }}">
	</div>
	<div class="form-group col-6">
		<label for="shoe-color">জুতার রং</label>
		<input type="text" name="color" id="shoe-color" class="form-control" value="{{ old('color', $shoe->color->name) }}" list="color-list">
		<input type="hidden" name="color_id" value="{{ old('color_id', $shoe->color_id) }}">
	</div>
	<div class="form-group col-12">
		<label for="shoe-image">ছবি</label><br>
		<input type="file" name="image" id="shoe-image">
	</div>
	<div class="form-group col-6">
		<label for="shoe-retail-price">গায়ের দাম</label>
		<input type="text" name="retail_price" id="shoe-retail-price" class="form-control" value="{{ toFixed(old('retail_price', $shoe->retail_price)) }}">
	</div>
	<div class="form-group col-6">
		<label for="shoe-purchase-price">ডজন দাম</label>
		<input type="text" name="purchase_price" id="shoe-purchase-price" class="form-control" value="{{ toFixed(old('purchase_price', $shoe->purchase_price)) }}">
	</div>
	<div class="col-12">
		<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
	</div>
</form>