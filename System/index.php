<?php
require_once 'conexion/conexion.php';

$sql = "SELECT * FROM `organizations` LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$org = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    $org = [
        'name' => 'CRM Pro',
        'logo_url' => 'assets/images/collab.png',
        'primary_color' => '#10b981',
        'secondary_color' => '#059669'
    ];
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $org['logo_url']; ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Iniciar Sesión | <?php echo $org['name']; ?></title>
    <style>
        :root {
            --primary: <?php echo $org['primary_color']; ?>;
            --primary-dark: <?php echo $org['secondary_color']; ?>;
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --white: #ffffff;
            
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-sans);
            background: var(--gray-50);
            min-height: 100vh;
            color: var(--gray-900);
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Efecto de fondo global existente - Patrón de puntos (Mantenido) */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(16, 185, 129, 0.1) 1px, transparent 0);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }
        
        /* Gradiente sutil existente (Mantenido) */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* --- NUEVO EFECTO DE CÍRCULOS SUTILES --- */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            /* El desenfoque alto (blur) es la clave para la sutileza */
            filter: blur(100px); 
            z-index: 0; /* Se mantienen detrás del contenido principal */
            pointer-events: none; /* No interfieren con los clicks */
            opacity: 0.15; /* Muy transparentes */
        }

        .shape-1 {
            width: 600px;
            height: 600px;
            background: var(--primary);
            top: -200px;
            left: -200px;
        }

        .shape-2 {
            width: 500px;
            height: 500px;
            background: var(--primary-dark);
            bottom: -150px;
            right: -100px;
            opacity: 0.12;
        }
        /* ----------------------------------------- */
        
        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            position: relative;
            z-index: 1; /* Importante: Mantiene el contenido sobre el fondo */
        }
        
        /* LADO IZQUIERDO - Formulario */
        .form-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        /* Tarjeta del formulario */
        .form-card {
            width: 100%;
            max-width: 420px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .company-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .form-header {
            margin-bottom: 2rem;
        }
        
        .form-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }
        
        .form-subtitle {
            font-size: 0.9375rem;
            color: var(--gray-600);
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
            font-family: var(--font-sans);
            color: var(--gray-900);
            background: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            transition: all 0.15s ease;
            outline: none;
        }
        
        .form-input:hover {
            border-color: var(--gray-400);
        }
        
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .form-input::placeholder {
            color: var(--gray-400);
        }
        
        .btn-primary {
            width: 100%;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--white);
            background: var(--primary);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-top: 1.5rem;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .form-link {
            font-size: 0.875rem;
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.15s ease;
        }
        
        .form-link:hover {
            color: var(--primary);
        }
        
        /* LADO DERECHO - Brand */
        .brand-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
        }
        
        .brand-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .brand-illustration {
            max-width: 480px;
            width: 100%;
            height: auto;
            margin-bottom: 2rem;
        }
        
        .brand-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.5;
        }
        
        /* Tarjetas flotantes */
        .floating-card {
            position: absolute;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 0.875rem;
            max-width: 240px;
        }
        
        .floating-card-1 {
            top: 15%;
            left: 5%;
        }
        
        .floating-card-2 {
            bottom: 20%;
            right: 8%;
        }
        
        .card-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            color: var(--primary);
            font-size: 1.125rem;
            flex-shrink: 0;
        }
        
        .card-content {
            flex: 1;
            min-width: 0;
        }
        
        .card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.125rem;
        }
        
        .card-description {
            font-size: 0.8125rem;
            color: var(--gray-600);
            line-height: 1.3;
        }

        .alert-message {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .brand-section {
                display: none;
            }
            
            .form-card {
                padding: 2rem 1.5rem;
            }
        }
        
        @media (max-width: 640px) {
            .form-section {
                padding: 1.5rem;
            }
            
            .form-card {
                max-width: 100%;
                padding: 1.5rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
            
            .logo-container {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-circle shape-1"></div>
    <div class="bg-circle shape-2"></div>
    <div class="login-container">
        <div class="form-section">
            <div class="form-card">
                <div class="logo-container">
                    <img src="<?php echo $org['logo_url']; ?>" alt="<?php echo $org['name']; ?>" class="logo">
                    <span class="company-name"><?php echo $org['name']; ?></span>
                </div>
                
                <div class="form-header">
                    <h1 class="form-title">Iniciar Sesión</h1>
                    <p class="form-subtitle">Ingresa tus credenciales para continuar</p>
                </div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="tu@ejemplo.com"
                            required
                            autocomplete="email"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        Iniciar Sesión
                    </button>
                </form>
                
                <div class="form-footer">
                    <a href="#" class="form-link">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </div>
        
        <div class="brand-section">
            <div class="brand-content">
                <img src="assets/images/loginPerson.png" alt="Ilustración" class="brand-illustration">
                <h2 class="brand-title">Gestiona tu negocio</h2>
                <p class="brand-subtitle">CRM simple y poderoso para hacer crecer tu empresa</p>
            </div>
            
            <div class="floating-card floating-card-1">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Gestión de Clientes</div>
                    <div class="card-description">Organiza todos tus contactos</div>
                </div>
            </div>
            
            <div class="floating-card floating-card-2">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Análisis en Tiempo Real</div>
                    <div class="card-description">Métricas actualizadas</div>
                </div>
            </div>
        </div>
    </div>

<script>
    const loginForm = document.getElementById('loginForm');
    const btnSubmit = loginForm.querySelector('.btn-primary');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            showMessage('Todos los campos son requeridos', 'error');
            return;
        }
        
        // Deshabilitar botón
        const originalText = btnSubmit.textContent;
        btnSubmit.textContent = 'Verificando...';
        btnSubmit.disabled = true;
        
        try {
            const response = await fetch('actions/login/authenticate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 500);
            } else {
                showMessage(data.message, 'error');
                btnSubmit.textContent = originalText;
                btnSubmit.disabled = false;
            }
            
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error de conexión. Intenta nuevamente', 'error');
            btnSubmit.textContent = originalText;
            btnSubmit.disabled = false;
        }
    });

    function showMessage(message, type) {
        // Remover mensaje anterior si existe
        const existingMsg = document.querySelector('.alert-message');
        if (existingMsg) existingMsg.remove();
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-message alert-${type}`;
        alertDiv.textContent = message;
        
        const formCard = document.querySelector('.form-card');
        formCard.insertBefore(alertDiv, loginForm);
        
        setTimeout(() => alertDiv.remove(), 5000);
    }
</script>
</body>
</html>