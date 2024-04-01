<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>

    <link href="{{ asset('css/lib/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a href="{{ route('home') }}" class="navbar-brand">{{ config('app.name') }}</a>
    @if(Auth::check())
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-menu" aria-controls="nav-menu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div id="nav-menu" class="collapse navbar-collapse">
        <ul class="navbar-nav">
            {{-- @can('manage purchases') --}}
            <li class="nav-item"><a href="{{ route('purchase.create') }}" class="nav-link">ক্রয়</a></li>
            {{-- @endcan --}}
            {{-- @can('manage invoices') --}}
            <li class="nav-item"><a href="{{ route('invoice.create') }}" class="nav-link">বিক্রয়</a></li>
            {{-- @endcan --}}
            {{-- @can('manage shoes') --}}
            <li class="nav-item"><a href="{{ route('shoe.index') }}" class="nav-link">ইনভেন্টরি</a></li>
            <li class="nav-item"><a href="{{ route('inventory-check.index') }}" class="nav-link">ইনভেন্টরি চেক</a></li>
            {{-- @endcan --}}
            {{-- @canany(['manage returns to factory', 'manage returns from retail stores', 'manage pending returns']) --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown-others" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ফেরত</a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown-others">
                    {{-- @can('manage returns to factory') --}}
                    <a href="{{ route('return.factory') }}" class="dropdown-item">মহাজন ফেরত</a>
                    {{-- @endcan --}}
                    {{-- @can('manage returns from retail stores') --}}
                    <a href="{{ route('return.retail-store') }}" class="dropdown-item">পার্টি ফেরত</a>
                    {{-- @endcan --}}
                    {{-- @can('manage pending returns') --}}
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('return.pending') }}" class="dropdown-item">পেন্ডিং ফেরত</a>
                    {{-- @endcan --}}
                </div>
            </li>
            {{-- @endcanany --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown-others" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">জোলাপ</a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown-others">
                    <a href="{{ route('waste.shoes-page') }}" class="dropdown-item">জুতা জোলাপ</a>
                    <a href="{{ route('waste.gifts-page') }}" class="dropdown-item">গিফট জোলাপ</a>
                </div>
            </li>
            {{-- @canany(['manage transactions', 'manage factories', 'manage retail stores', 'manage bank accounts', 'manage cheques', 'manage employees', 'manage loans', 'manage expenses']) --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown-others" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">খাতা</a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown-others">
                    {{-- @can('manage transactions') --}}
                    <a href="{{ route('transaction.index') }}" class="dropdown-item">বিক্রি খাতা</a>
                    {{-- <a href="" class="dropdown-item">বিক্রি খাতা</a>w --}}
                    <div class="dropdown-divider"></div>
                    {{-- @endcan --}}
                    {{-- @can('manage factories') --}}
                    <a href="{{ route('factory.index') }}" class="dropdown-item">মহাজন খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage retail stores') --}}
                    <a href="{{ route('retail-store.index') }}" class="dropdown-item">বাকী খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage bank accounts') --}}
                    <a href="{{ route('bank-account.index') }}" class="dropdown-item">ব্যাংক খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage cheques') --}}
                    <a href="{{ route('cheque.index') }}" class="dropdown-item">চেক খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage gift suppliers') --}}
                    <a href="{{ route('gift-supplier.index') }}" class="dropdown-item">গিফট মহাজন খাতা</a>
                    {{-- @endcan --}}
                    {{-- @canany(['manage employees', 'manage loans', 'manage expenses']) --}}
                    <div class="dropdown-divider"></div>
                    {{-- @endcanany --}}
                    {{-- @can('manage employees') --}}
                    <a href="{{ route('employee.index') }}" class="dropdown-item">স্টাফ খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage loans') --}}
                    <a href="{{ route('loan.index') }}" class="dropdown-item">হাওলাত খাতা</a>
                    {{-- @endcan --}}
                    {{-- @can('manage expenses') --}}
                    <a href="{{ route('expense.index') }}" class="dropdown-item">অন্যান্য খরচ খাতা</a>
                    {{-- @endcan --}}
                </div>
            </li>
            {{-- @endcanany --}}
            {{-- @can('manage reports') --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown-report" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">রিপোর্ট</a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown-report">
                    <a href="{{ route('report.transaction-page') }}" class="dropdown-item">লেনদেন</a>
                    <a href="{{ route('report.cash') }}" class="dropdown-item">ক্যাশ আদান-প্রদান</a>
                </div>
            </li>
            {{-- @endcan --}}
            {{-- @canany(['manage categories', 'manage colors', 'manage gift suppliers', 'manage gifts']) --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown-others" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">অন্যান্য</a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown-others">
                    {{-- @can('manage categories') --}}
                    <a href="{{ route('category.index') }}" class="dropdown-item">জুতার ধরণ</a>
                    {{-- @endcan --}}
                    {{-- @can('manage colors') --}}
                    <a href="{{ route('color.index') }}" class="dropdown-item">জুতার রং</a>
                    {{-- @endcan --}}
                    {{-- @canany(['manage gift suppliers', 'manage gifts']) --}}
                    <div class="dropdown-divider"></div>
                    {{-- @endcanany --}}
                    {{-- @can('manage gifts') --}}
                    <a href="{{ route('gift.index') }}" class="dropdown-item">গিফট ইনভেন্টরি</a>
                    {{-- @endcan --}}
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('shoe.barcode') }}" class="dropdown-item">বারকোড প্রিন্ট</a>
                </div>
            </li>
            {{-- @endcanany --}}
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="#" class="nav-link">পাসওয়ার্ড পরিবর্তন</a></li>
            <li class="nav-item"><a href="{{ route('app.logout') }}" class="nav-link">লগআউট</a></li>
        </ul>
    </div>
    @endif
</nav>

<main id="site-content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(session()->has('success-alert'))
                <div class="alert alert-success d-print-none">{{ session()->get('success-alert') }}</div>
                @endif
                @if(session()->has('info-alert'))
                <div class="alert alert-info d-print-none">{{ session()->get('info-alert') }}</div>
                @endif
                @if(session()->has('console-alert'))
                <div class="alert alert-dark d-print-none">{{ session()->get('console-alert') }}</div>
                @endif
                @if(session()->has('error-alert'))
                <div class="alert alert-danger d-print-none">{{ session()->get('error-alert') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</main>

</body>

<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/lib/popper.min.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('page-script')

</html>
