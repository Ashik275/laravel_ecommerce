@extends('admin.app')
@section('content')
@include('admin.message')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" id="categoryForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name"
                                    value="{{$category->name}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug"
                                    value="{{$category->slug}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" id="image_id" name="image_id" value="">
                                <label for="image">Image</label>
                                <div id="image" class="dropzone dz-clickable" name='image'>
                                    <div class="dz-message needsclick">
                                        <br>Drop Files here or click to upload <br><br>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($category->image))
                            <div>
                                <img width="200px" src="{{asset('uploads/category/'.$category->image)}}" alt="">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="1" {{$category->status==1?'selected':''}}>Active</option>
                                    <option value="0" {{$category->status==0?'selected':''}}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name">Show Home</label>
                                <select name="showhome" id="showhome" class="form-control">
                                    <option value="">Select Show  Home</option>
                                    <option value="Yes" {{$category->showhome=='Yes'?'selected':''}}>Yes</option>
                                    <option value="No" {{$category->showhome=='No'?'selected':''}}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" type="submit">Edit</button>
                <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')

<script>
    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        var element = $(this);

        $.ajax({
            url: '{{ route('categories.update',$category->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(res) {
                if (res["status"] == true) {
                    Swal.fire({
                        title: "Updated!",
                        text: "Category updated syccessfully",
                        type: "success",
                        timer: 5000
                    }).then(function() {
                        window.location.href = "{{ route('categories.index') }}";
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
                if (res['status'] == true) {
                    $('#slug').val(res['slug']);
                }
            }
        })
    })
    Dropzone.autoDiscover = false;
    const dropzone = $('#image').dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            })
        },
        url: "{{ route('temp-images.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,i,image/png, image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $('#image_id').val(response.image_id);
            // console.log(response);
        }
    })
</script>
@endsection