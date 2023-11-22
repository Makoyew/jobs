@extends('base')

@section('content')
<div class="container p-5" style="max-width: 750px;">
    <div class="card shadow futuristic-card">
        <div class="card-header">
            <h1 class="text-center futuristic-text">Welcome!</h1>
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard') }}" method="POST">
                {{ csrf_field() }}
                <div class="mb-3">
                    <label for="email" class="futuristic-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control futuristic-input" placeholder="Email">
                    @error('email')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="futuristic-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control futuristic-input" placeholder="Password">
                    @error('password')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-flex mt-5">
                    <div class="flex-grow-1">
                        <a href="{{ route('registerForm') }}" class="futuristic-link">Create Account</a>
                    </div>
                    <button type="submit" class="btn btn-primary futuristic-button">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    /* Custom CSS for Futuristic Login Styling */

/* Card Styling */
.futuristic-card {
    background-color: #121212; /* Dark background color */
    border: none;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1); /* Soft white glow */
    color: #FFFFFF; /* Text color */
}

/* Header Text Styling */
.futuristic-text {
    font-size: 24px; /* Larger font size */
    font-weight: bold;
    color: #00BFFF; /* Bright blue text color */
}

/* Form Label Styling */
.futuristic-label {
    font-size: 16px; /* Font size */
    color: #FFFFFF; /* Text color */
}

/* Form Input Styling */
.futuristic-input {
    background-color: #1E1E1E; /* Dark input background color */
    border: 1px solid #00BFFF; /* Bright blue border */
    border-radius: 5px;
    color: #FFFFFF; /* Text color */
}

/* Form Input Focus Styling */
.futuristic-input:focus {
    outline: none;
    box-shadow: 0 0 5px #00BFFF; /* Bright blue box shadow on focus */
}

/* Link Styling */
.futuristic-link {
    color: #00BFFF; /* Bright blue link color */
    text-decoration: none;
}

/* Link Hover Styling */
.futuristic-link:hover {
    text-decoration: underline;
}

/* Button Styling */
.futuristic-button {
    background-color: #00BFFF; /* Bright blue button background color */
    color: #FFFFFF; /* Text color */
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

/* Button Hover Styling */
.futuristic-button:hover {
    background-color: #0073E6; /* Darker blue on hover */
}

</style>
@endsection
