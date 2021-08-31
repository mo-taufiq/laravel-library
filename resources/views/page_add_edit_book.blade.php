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
        <h1 class="h2">
            <a href="{{ url('page/admin/books') }}"><i class="fas fa-chevron-circle-left me-2 text-dark"></i></a>
            {{ $title }}
        </h1>
    </div>
    <div class="card p-3">
        <form>
            <input type="hidden" name="author_id">
            @csrf
            <div class="row">
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Title
                        <input name="title" type="text" class="form-control" placeholder="Title" autofocus
                            autocomplete="off">
                        <div data-name="title" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Author
                        <select name="author_id" class="form-control">
                            <option value="">Choose Author</option>
                            @foreach($authors as $author)
                            <option value="{{ $author->author_id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                        <div data-name="author_id" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Description
                        <textarea name="description" class="form-control" placeholder="Description"
                            autocomplete="off"></textarea>
                        <div data-name="description" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-12">
                    <button class="btn btn-dark">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<!-- Taufiq Toast JS -->
<script src="{{ asset('modules/taufiq-toast/script.js') }}"></script>
<script>
    const formEl = $('form');
    let endpoint = "{{ url('api/books') }}";
    const method = "{{ $method }}";
    const role = "{{ $role }}";

    $(function () {
        handleLogicSignUp();
        handleLogicToast();
        handleLogicBasedActionType();
        handleLoadData();
    })

    function handleLoadData() {
        if (method === "put") {
            let book = '{!! $book !!}';
            book = JSON.parse(book);
            endpoint = `${endpoint}/${book.book_id}`;
            for (let key in book) {
                $(`[name=${key}]`).val(book[key]);
            }
            $('input[type=hidden][name=author_id]').val(book.author_id)
        }
    }

    function handleLogicBasedActionType() {
        if (role === "Non Admin") {
            $('select[name=author_id]').attr('disabled', true)
        }
    }

    function handleLogicSignUp() {
        formEl.submit(async function (e) {
            e.preventDefault();
            resetValidation();
            const formData = new FormData(formEl[0]);
            const objForm = {};
            for (let [key, value] of formData) {
                objForm[key] = value;
            }

            try {
                let response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(objForm)
                })
                response = await response.json();
                if (response.success) {
                    if (method === "post") {
                        formEl[0].reset();
                    }
                    showToast({
                        message: response.message,
                        type: 'success',
                        title: 'Success',
                        duration: 10000,
                    });
                } else if (!response.success) {
                    showToast({
                        message: response.message,
                        type: 'danger',
                        title: 'Failed',
                        duration: 10000,
                    });
                    for (let key in response.data) {
                        let message = response.data[key][0]
                        $(`.form-control[name=${key}]`).addClass('is-invalid');
                        $(`.message-error[data-name=${key}]`).addClass('invalid-feedback').text(message);

                        if (message === "The password confirmation does not match.") {
                            $(`.form-control[name=password_confirmation]`).addClass('is-invalid');
                            $(`.message-error[data-name=password_confirmation]`).addClass('invalid-feedback').text(message);
                        }
                    }
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
        })
    }

    function resetValidation() {
        $('.form-control').removeClass('is-invalid');
        $('.message-error').removeClass('invalid-feedback');
    }
</script>
@endsection