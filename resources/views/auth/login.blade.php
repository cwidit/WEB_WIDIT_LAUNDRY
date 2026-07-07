<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; Widit Laundry</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(255,255,255,.22), transparent 22%),
                        radial-gradient(circle at right, rgba(255,255,255,.12), transparent 16%),
                        linear-gradient(135deg, #0f766e 0%, #3b82f6 100%);
        }

        .section {
            padding-top: 70px;
        }

        .login-brand {
            position: relative;
            margin-bottom: 1rem;
            text-align: center;
        }

        .login-brand h5 {
            color: #fff;
            font-size: 1.5rem;
            letter-spacing: 0.08em;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .login-brand::after {
            content: '';
            position: absolute;
            left: 15%;
            top: 18%;
            width: 110px;
            height: 110px;
            background: rgba(255,255,255,.14);
            border-radius: 50%;
            filter: blur(22px);
            z-index: -1;
        }

        .card-primary {
            background: rgba(255,255,255,.96);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 24px 60px rgba(15,23,42,.15);
        }

        .card-primary .card-header {
            background: transparent;
            border-bottom: none;
            color: #111827;
        }

        .btn-primary {
            background: #0f766e;
            border-color: #0f766e;
        }

        .simple-footer {
            color: #e2e8f0;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <h5>Widit Laundry</h5>
                        </div>

                        <div class="card card-primary">
                            <div class="card-header"><h4>Login</h4></div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                        </div>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Masuk
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; {{ date('Y') }} Widit Laundry
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
