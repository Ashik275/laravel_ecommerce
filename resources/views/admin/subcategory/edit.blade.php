@extends('admin.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('subcategories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" id="subCategoryForm" name="subCategoryForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label for="name">Category Name</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option  value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option {{$subcategory->category_id == $category->id ?'selected':'' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Sub Category Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$subcategory->name}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text"  name="slug" id="slug" class="form-control"
                                    placeholder="Slug" value="{{$subcategory->slug}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="1" {{$subcategory->status==1?'selected':''}}>Active</option>
                                    <option value="0" {{$subcategory->status==0?'selected':''}}>Inactive</option>
                                </select>
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="name">Show Home</label>
                                <select name="showhome" id="showhome" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="Yes" {{$subcategory->showhome=='Yes'?'selected':''}}>Yes</option>
                                    <option value="No" {{$subcategory->showhome=='No'?'selected':''}}>No</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')
<script>
    $('#subCategoryForm').submit(function(e) {
       e.preventDefault();
       var element = $(this);

       $.ajax({
           url: '{{ route("subcategories.update",$subcategory->id) }}',
           type: 'put',
           data: element.serializeArray(),
           dataType: 'json',
           success: function(res) {
               if (res["status"] == true) {
                   Swal.fire({
                       title: "Updated!",
                       text: "Sub Category Updated syccessfully",
                       type: "success",
                       timer: 5000
                   }).then(function() {
                       window.location.href = "{{ route('subcategories.index') }}";
                   });
                   // window.location.href = "{{ route('categories.index') }}";
                   $('#slug').removeClass('is-invalid').siblings('p')
                       .removeClass('invalid-feedback').html("");
                   $('#name').removeClass('is-invalid').siblings('p')
                       .removeClass('invalid-feedback').html("");
               } else {
                   var errors = res['errors'];
                   if (errors['name']) {
                       $('#name').addClass('is-invalid').siblings('p')
                           .addClass('invalid-feedback').html(errors['name']);
                   } else {
                       $('#name').removeClass('is-invalid').siblings('p')
                           .removeClass('invalid-feedback').html("");
                   }
                   if (errors['slug']) {
                       $('#slug').addClass('is-invalid').siblings('p')
                           .addClass('invalid-feedback').html(errors['slug']);
                   } else {
                       $('#slug').removeClass('is-invalid').siblings('p')
                           .removeClass('invalid-feedback').html("");
                   }
                   if (errors['status']) {
                       $('#status').addClass('is-invalid').siblings('p')
                           .addClass('invalid-feedback').html(errors['status']);
                   } else {
                       $('#status').removeClass('is-invalid').siblings('p')
                           .removeClass('invalid-feedback').html("");
                   }
                   if (errors['category_id']) {
                       $('#category_id').addClass('is-invalid').siblings('p')
                           .addClass('invalid-feedback').html(errors['category_id']);
                   } else {
                       $('#category_id').removeClass('is-invalid').siblings('p')
                           .removeClass('invalid-feedback').html("");
                   }
               }

           },
           error: function(jqXHR, exception) {
               console.log('wrong');
           }
       })
   })
$('#name').change(function() {
       var element = $(this).val();
       // alert(element);
       $.ajax({
           url: '{{ route('getSlug') }}',
           type: 'get',
           data: {
               title: element
           },
           dataType: 'json',
           success: function(res) {
               // console.log(res);
               if (res['status'] == true) {
                   $('#slug').val(res['slug']);
               }
           }
       })
   })
</script>
@endsection