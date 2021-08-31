@extends('layouts.layout_main')

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
<div class="container mt-4">
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div id="toast-container" class="position-fixed p-3"></div>
            <div class="card bg-light mt-5">
                <div class="card-body px-4 py-5">
                    <form class="form-signin">
                        @csrf
                        <div class="w-100 d-flex justify-content-center align-content-center mb-3">
                            <img class="align-self-center"
                                src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt=""
                                width="72" height="72">
                        </div>
                        <h1 class="h3 mb-3 font-weight-normal text-center">Please sign in</h1>
                        <label class="w-100">
                            Username
                            <input type="text" name="username" class="form-control" placeholder="Username" autofocus
                                autocomplete="off">
                            <div data-name="username" class="message-error valid-feedback"></div>
                        </label>
                        <label class="w-100 mt-2">
                            Password
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <div data-name="password" class="message-error valid-feedback"></div>
                        </label>
                        <button class="btn btn-block btn-primary w-100 mt-3" type="submit">Sign in</button>
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <span>Don't have account yet?</span><a href="/page/register" class="text-center ms-2">Sign
                                up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<!-- Taufiq Toast JS -->
<script src="{{ asset('modules/taufiq-toast/script.js') }}"></script>
<script>
    const formEl = $('form');
    const endpointSignIn = "{{ route('sign_in') }}";

    $(function () {
        handleSignIn();
        handleLogicToast();
    })

    function handleSignIn() {
        formEl.submit(async function (e) {
            e.preventDefault();
            resetValidation();
            const formData = new FormData(formEl[0]);
            const objForm = {};
            for (let [key, value] of formData) {
                objForm[key] = value;
            }

            try {
                let response = await fetch(endpointSignIn, {
                    method: 'post',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(objForm)
                })
                response = await response.json();
                if (response.success) {
                    formEl[0].reset();
                    showToast({
                        message: response.message,
                        type: 'success',
                        title: 'Login Success',
                        duration: 10000,
                    })
                    window.location = "/";
                } else if (!response.success) {
                    showToast({
                        message: response.message,
                        type: 'danger',
                        title: 'Login Failed',
                        duration: 10000,
                    })
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
        });
    }

    function resetValidation() {
        $('.form-control').removeClass('is-invalid');
        $('.message-error').removeClass('invalid-feedback');
    }
</script>
@endsection