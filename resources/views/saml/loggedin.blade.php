@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Signed in as {{ $user->name }}</h1>
    <p>This page is a placeholder for SAML consent / selection UI.</p>
    <form method="post" action="{{ url('/saml/continue') }}">
        @csrf
        <button class="btn btn-primary">Continue</button>
    </form>
</div>
@endsection
