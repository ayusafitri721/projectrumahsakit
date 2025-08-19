<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <h2>Reset Password</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
        </div>
        
        <div class="form-group">
            <label>Password Baru:</label>
            <input type="password" class="form-control" name="password" required minlength="8" placeholder="Minimal 8 karakter">
        </div>
        
        <div class="form-group">
            <label>Konfirmasi Password:</label>
            <input type="password" class="form-control" name="password_confirmation" required minlength="8" placeholder="Ulangi password baru">
        </div>
        
        <button type="submit" class="btn btn-primary">Reet Password</button>
    </form>
</div>
</body>
</html>