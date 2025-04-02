<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Gestión | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(35, 153, 147) 0%,rgb(10, 100, 66) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 900px;
            max-width: 90%;
        }
        .login-row {
            min-height: 550px;
        }
        .login-left {
            background: linear-gradient(135deg,rgb(138, 255, 206), #86a8e7, #91eae4);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-right {
            padding: 40px;
        }
        .login-logo {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .login-logo i {
            font-size: 50px;
            color: #764ba2;
        }
        .login-form {
            max-width: 350px;
            margin: 0 auto;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            padding: 12px;
            width: 100%;
            margin-top: 15px;
        }
        .alert-danger {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="row login-row">
                <div class="col-md-6 login-left">
                    <div class="login-logo">
                        <i><img src="images/logo.png" alt=""></i>
                    </div>
                    <h2 class="mb-4">Sistema de Gestión</h2>
                    <p class="lead">¡Bienvenido! . Inicia sesión para acceder a tu cuenta.</p>
                </div>
                <div class="col-md-6 login-right">
                    <div class="login-form">
                        <h3 class="mb-4 text-center">Iniciar Sesión</h3>
                        
                        <div id="error-message" class="alert alert-danger d-none"></div>
                        
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa tu usuario" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                            <button type="submit" class="btn btn-login">
                                <span id="login-button-text">Iniciar Sesión</span>
                                <span id="login-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar/ocultar contraseña
            $('#toggle-password').click(function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const eyeIcon = $(this).find('i');
                
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Manejar envío del formulario con AJAX
            $('#login-form').submit(function(e) {
                e.preventDefault();
                
                const usuario = $('#nombre_usuario').val();
                const password = $('#password').val();
                const remember = $('#remember').prop('checked') ? 1 : 0;
                
                // Mostrar spinner y deshabilitar botón
                $('#login-button-text').text('Verificando...');
                $('#login-spinner').removeClass('d-none');
                $('.btn-login').prop('disabled', true);
                $('#error-message').addClass('d-none');
                
                $.ajax({
                    url: '/login',
                    type: 'POST',
                    data: {
                        nombre_usuario: usuario,
                        password: password,
                        remember: remember,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '/dashboard';
                        } else {
                            $('#error-message').text(response.message).removeClass('d-none');
                            $('#login-button-text').text('Iniciar Sesión');
                            $('#login-spinner').addClass('d-none');
                            $('.btn-login').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            let errorMessage = '';
                            Object.keys(response.errors).forEach(key => {
                                errorMessage += response.errors[key][0] + '<br>';
                            });
                            $('#error-message').html(errorMessage).removeClass('d-none');
                        } else {
                            $('#error-message').text('Error al iniciar sesión. Intente nuevamente.').removeClass('d-none');
                        }
                        $('#login-button-text').text('Iniciar Sesión');
                        $('#login-spinner').addClass('d-none');
                        $('.btn-login').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html>