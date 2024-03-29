@extends('admin.app')
@section('content')
@include('admin.message')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        {{-- <button class="btn btn-default btn-sm" type="button" onclick="window.location.href=" {{
                            route('categories.index') }}">Reset</button> --}}
                        <button class="btn btn-default btn-sm" type="button"
                            onclick="window.location.href='{{ route('categories.index') }}'">Reset</button>

                    </div>
                </div>
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="keyword" value="{{ Request::get('keyword') }}"
                                class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isNotEmpty())
                        @foreach ($categories as $category)
                        <tr>

                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                <img style="width: 50px;height:50px" src="{{asset("uploads/category/$category->image")}}" alt="">
                            </td>
                            <td>
                                @if ($category->status == 1)
                                <svg onclick="updateStatus({{ $category->id }},{{$category->status}})"
                                    class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @else
                                <svg onclick="updateStatus({{ $category->id }},{{$category->status}})" class="text-danger h-6 w-6"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="#" onclick="deleteCategory({{ $category->id }})"
                                    class="text-danger w-4 h-4 mr-1">
                                    <svg wire:loading.remove.delay="" wire:target=""
                                        class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path ath fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $categories->links() }}
                {{-- <ul class="pagination pagination m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul> --}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')
<script>
    function deleteCategory(id) {
            var url = '{{ route('categories.delete', 'ID') }}';
            var newURL = url.replace("ID", id);
            // alert(newURL);

            Swal.fire({
                title: 'Are you sure you want to delete?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: newURL,
                        type: 'delete',
                        data: {},
                        dataType: 'json',
                        success: function(response) {
                            if (response['status']) {
                                Swal.fire(
                                    'Deleted!',
                                    'Category has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('categories.index') }}";
                                });
                            }
                        }
                    });
                }
            });
        }

        function updateStatus(id,status) {
            var url = '{{ route('categories.status', 'ID') }}';
            var newURL = url.replace("ID", id);
            if(status === 1){
             var msg = 'Are you sure you want to deactivate the status?';
             var add = "Deactivate";
            }else{
                var msg = 'Are you sure you want to activate the status?' ;
                var add = "Activate";
            }
            Swal.fire({
                title: msg,
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: newURL,
                        type: 'get',
                        // data: {},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response['status']) {
                                Swal.fire(
                                    'Updated!',
                                    'Category status has been updated.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('categories.index') }}";
                                });
                            }
                        }
                    });
                }
            });
        }
</script>
@endsection