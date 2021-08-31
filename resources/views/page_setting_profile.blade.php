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
                <div class="col-6">
                    <label class="w-100 mb-3">
                        New Password
                        <input name="new_password" type="password" class="form-control" placeholder="New Password"
                            autocomplete="off">
                        <div data-name="new_password" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Confirm New Password
                        <input name="new_password_confirmation" type="password" class="form-control"
                            placeholder="Confirm New Password" autocomplete="off">
                        <div data-name="new_password_confirmation" class="message-error valid-feedback"></div>
                    </label>
                </div>
                <div class="col-6">
                    <label class="w-100 mb-3">
                        Old Password
                        <input name="old_password" type="password" class="form-control" placeholder="Old Password"
                            autocomplete="off">
                        <div data-name="old_password" class="message-error valid-feedback"></div>
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
    let endpoint = "{{ url('api/users/profile/update') }}";
    let user = '{!! $user !!}';
    user = JSON.parse(user);
    let role = user.role;

    $(function () {
        handleLogicSignUp();
        handleLogicToast();
        handleLogicBasedActionType();
        handleLoadData();
    })

    function handleLoadData() {
        for (let key in user) {
            $(`[name=${key}]`).val(user[key]);
        }
    }

    function handleLogicBasedActionType() {
        $('select[name=role]').attr('disabled', true)
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
                    method: "put",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(objForm)
                })
                response = await response.json();
                if (response.success) {
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

                        if (message === "The new password confirmation does not match.") {
                            $(`.form-control[name=new_password_confirmation]`).addClass('is-invalid');
                            $(`.message-error[data-name=new_password_confirmation]`).addClass('invalid-feedback').text(message);
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