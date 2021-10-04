@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.nav')
        <task-component :data="{{ $data }}" :users="{{ $users }}" :tasks="{{ $tasks }}" />
    </div>
@endsection
