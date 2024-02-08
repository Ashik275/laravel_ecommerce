@extends('admin.app')
@section('content')
    @include('admin.message')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="brandForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" readonly>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customJs')
    <script>
        $('#brandForm').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            console.log(element.serializeArray());
            $.ajax({
                url: '{{ route('brands.store') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(res) {
                    if (res["status"] == true) {
                        Swal.fire({
                            title: "Created!",
                            text: res['message'],
                            type: "success",
                            timer: 5000
                        }).then(function() {
                            window.location.href = "{{ route('brands.index') }}";
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
    </script>
@endsection
