@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.nav')
        <chart-component :data="{{ $data }}" :users="{{ $users }}" />
    </div>
@endsection
