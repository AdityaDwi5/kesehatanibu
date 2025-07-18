@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="fw-light mb-0">
                         <img src="{{ asset('logo.jpg') }}" alt="Logo" style="width: 35px; height: 35px;"> 

                            Login
                        </h3>
                    </div>

                 <div class="card-body p-4">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label fw-medium">

                {{ __('Email Address') }}
            </label>
            <div class="input-group">
                <span class="input-group-text">
                   <i class="bi bi-person-fill"></i>
                </span>
                <input id="email" 
                       type="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email" 
                       autofocus
                       placeholder="Enter your email address">
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-4">
            <label for="password" class="form-label fw-medium">
                <i class="fas fa-lock me-2 text-muted"></i>
                {{ __('Password') }}
            </label>
            <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-eye"></i>
                </span>
                <input id="password" 
                       type="password" 
                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="Enter your password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
               <i class="bi bi-eye-fill"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <!-- Login Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>
                {{ __('Login') }}
            </button>
        </div>
    </form>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
 .bg-light {
    background: linear-gradient(to bottom, #145A32, #117A4D) !important;
}

.card {
    background: white;
    border-radius: 12px 12px 20px 20px;
    border: none;
    position: relative;
    padding-top: 80px;
}

.card::before {
    content: "";
    width: 100px;
    height: 100px;
    background: #fff;
    border-radius: 50%;
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid #117A4D;
    z-index: 1;
    background-image: url('{{ asset('logo.jpg') }}');
    background-size: 60%;
    background-position: center;
    background-repeat: no-repeat;
}

.card-header {
    display: none; /* sembunyikan header karena logo sudah di atas */
}

.btn-primary {
    background-color: #E74C3C;
    border: none;
    transition: transform 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.5);
}

.form-control:focus {
    border-color: #27ae60;
    box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
}

    
    .form-control:focus {
        border-color: #27ae60;
        box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        border: none;
        transition: transform 0.2s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
    }
    
    .card-header {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
    }
    
    .input-group .btn {
        border-color: #ced4da;
    }
    
    .input-group .btn:hover {
        background-color: #f8f9fa;
    }
</style>


<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const toggleIcon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });
</script>
@endsection