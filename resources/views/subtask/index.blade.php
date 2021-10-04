@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- @include('layouts.nav') --}}
        <subtask-component :data="{{ $data }}"/>
    </div>
@endsection
