@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="welcome-section mb-5">
        <h1 class="display-4 text-center mb-3">Selamat Datang di Dashboard</h1>
        <p class="lead text-center">
            Halo, <span class="fw-bold text-primary">{{Auth::user()->name}}</span>! Apa yang ingin Anda lakukan hari ini?
        </p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-mail display-4 mb-3"></i>
                    <h5 class="card-title text-white">Surat Masuk Internal</h5>
                    <p class="card-text display-6 fw-bold">{{$smi}}</p>
                    <a href="{{ route('smi') }}" class="btn btn-light mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-send display-4 mb-3"></i>
                    <h5 class="card-title text-white">Surat Keluar Internal</h5>
                    <p class="card-text display-6 fw-bold">{{$ski}}</p>
                    <a href="{{route('ski')}}" class="btn btn-light mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-mail-forward display-4 mb-3"></i>
                    <h5 class="card-title text-white">Surat Masuk Eksternal</h5>
                    <p class="card-text display-6 fw-bold">{{$sme}}</p>
                    <a href="{{route('sme')}}" class="btn btn-light mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-mail-fast display-4 mb-3"></i>
                    <h5 class="card-title text-white">Surat Keluar Eksternal</h5>
                    <p class="card-text display-6 fw-bold">{{$ske}}</p>
                    <a href="{{route('ske')}}" class="btn btn-light mt-3 w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection