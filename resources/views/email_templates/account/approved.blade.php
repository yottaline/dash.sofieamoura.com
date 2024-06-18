@extends('email_templates.master')
@section('message')
    <h5>Hi {{ $name }},</h5>
    <br>
    <p>Thank you for signing up for Sofie Amoura B2B.</p>
    <p>Your Account has been activated</p>
    <br>
    <p>Your login details</p>
    <p>email: <i>{{ $email }}</i></p>
    <p>password: <i>{{ $password }}</i></p>
    <br>
    <p>Your sign in page</p>
    <p><a href="https://b2b.sofieamoura.com">LOG IN TO YOUR ACCOUNT</a></p>
@endsection
