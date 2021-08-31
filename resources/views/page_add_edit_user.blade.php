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
            <a href="{{ url('page/admin/users') }}"><i class="fas fa-chevron-circle-left me-2 text-dark"></i></a>
            {{ $title }}
        </h1>
    </div>
    <div class="card p-3">
        <form>
            @csrf
            <div class="row">
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Username
                        <input name="username" type="text" class="form-control" placeholder="Username" autofocus
                            autocomplete="off">
                        <div data-name="username" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Role
                        <select name="role" class="form-control">
                            <option value="">Choose Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Non Admin">Non Admin</option>
                        </select>
                        <div data-name="role" class="message-error valid-feedback"></div>
                    </label>
                </div>
                @if ($method === "post")
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Password
                        <input name="password" type="password" class="form-control" placeholder="Password"
                            autocomplete="off">
                        <div data-name="password" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Confirm Password
                        <input name="password_confirmation" type="password" class="form-control"
                            placeholder="Confirm Password" autocomplete="off">
                        <div data-name="password_confirmation" class="message-error valid-feedback"></div>
                    </label>
                </div>
                @endif
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
    let endpoint = "{{ url('api/users') }}";
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
            let user = '{!! $user !!}';
            user = JSON.parse(user);
            endpoint = `${endpoint}/${user.user_id}`;
            for (let key in user) {
                $(`[name=${key}]`).val(user[key]);
            }
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