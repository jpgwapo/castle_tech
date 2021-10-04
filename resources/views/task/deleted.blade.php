@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.nav')
        <deleted-task-component :data="{{ $data }}" />
    </div>
@endsection
