@extends('layouts.layout_app')

@section('header')
<!-- Taufiq Toast CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('modules/taufiq-toast/style.css') }}">
<style>
</style>
@endsection

@section('title')
| {{ $title }}
@endsection

@section('content')
<div class="p-3 content-container">
    <div id="toast-container" class="position-fixed p-3"></div>
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $title }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0 ms-3">
            <a href="{{ url('page/admin/books/add') }}">
                <button type="button" class="btn btn-sm btn-dark">
                    <i class="fas fa-plus me-1"></i>
                    Add Book
                </button>
            </a>
        </div>
    </div>
    <div class="card p-3">
        <table class="table text-center">
            <thead class="table-dark">
                <tr>
                    <th>Action</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                <tr>
                    <th>
                        <div class="w-100 d-flex justify-content-center align-items-center">
                            <a href="{{ url('page/admin/books/' . $book->book_id) }}">
                                <button class="btn btn-sm btn-success d-flex mx-1" style="font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </a>
                            <button data-id="{{ $book->book_id }}"
                                class="btn btn-sm btn-danger d-flex mx-1 delete-button" style="font-size: 11px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </th>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author_name }}</td>
                    <td>{{ $book->description }}</td>
                    <td>
                        <div class="w-100 d-flex justify-content-center align-items-center"
                            style="width: 100px;height: 25px;">
                            <img src="{{ asset($book->image) }}" alt="" class="w-100 h-100"
                                style="object-fit: scale-down;">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer')
<!-- Taufiq Toast JS -->
<script src="{{ asset('modules/taufiq-toast/script.js') }}"></script>
<script>
    let endpoint = "{{ url('api/books') }}"

    $(function () {
        handleDeleteAction()
    })

    function handleDeleteAction() {
        $(document).on('click', '.delete-button', async function () {
            const confirmed = confirm("Are your sure to delete this data!");

            if (confirmed) {
                const id = $(this).data('id');

                try {
                    let response = await fetch(`${endpoint}/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        method: "delete",
                    })
                    response = await response.json();
                    if (response.success) {
                        showToast({
                            message: response.message,
                            type: 'success',
                            title: 'Success',
                            duration: 10000,
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else if (!response.success) {
                        showToast({
                            message: response.error,
                            type: 'danger',
                            title: 'Failed',
                            duration: 10000,
                        });
                    }
                } catch (error) {
                    console.log(error);
                    showToast({
                        message: error,
                        type: 'danger',
                        title: 'Something Wrong',
                        duration: 10000,
                    });
                }
            }
        })
    }
</script>
@endsection