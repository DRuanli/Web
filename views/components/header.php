<?php
require_once MODELS_PATH . '/User.php';

// Default preferences
$theme = 'light';
$font_size = 'medium';
$note_color = 'white';

// Get user preferences if logged in
if (Session::isLoggedIn()) {
    $user_id = Session::getUserId();
    $userModel = new User();
    $preferences = $userModel->getUserPreferences($user_id);
    
    // Apply preferences
    $theme = $preferences['theme'] ?? 'light';
    $font_size = $preferences['font_size'] ?? 'medium';
    $note_color = $preferences['note_color'] ?? 'white';
}

// Font size classes
$font_size_class = '';
switch ($font_size) {
    case 'small':
        $font_size_class = 'font-size-small';
        break;
    case 'medium':
        $font_size_class = 'font-size-medium';
        break;
    case 'large':
        $font_size_class = 'font-size-large';
        break;
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= ASSETS_URL ?>/img/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
    
    <!-- Page-specific CSS -->
    <?php if (isset($pageStyles) && is_array($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- PWA support -->
    <?php if (defined('ENABLE_OFFLINE_MODE') && ENABLE_OFFLINE_MODE): ?>
        <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
        <meta name="theme-color" content="#4a89dc">
    <?php endif; ?>
    
    <!-- Custom preferences styles -->
    <style>
        /* Font size preferences */
        .font-size-small {
            font-size: 0.875rem !important;
        }
        .font-size-medium {
            font-size: 1rem !important;
        }
        .font-size-large {
            font-size: 1.125rem !important;
        }
        
        /* Note color preferences */
        .note-color-white .note-card {
            background-color: #ffffff !important;
        }
        .note-color-blue .note-card {
            background-color: #f0f5ff !important;
        }
        .note-color-green .note-card {
            background-color: #f0fff5 !important;
        }
        .note-color-yellow .note-card {
            background-color: #fffbeb !important;
        }
        .note-color-purple .note-card {
            background-color: #f8f0ff !important;
        }
        .note-color-pink .note-card {
            background-color: #fff0f7 !important;
        }
        
        /* Dark mode adjustments for note colors */
        [data-bs-theme="dark"] .note-color-white .note-card {
            background-color: #2b2b2b !important;
        }
        [data-bs-theme="dark"] .note-color-blue .note-card {
            background-color: #1a2035 !important;
        }
        [data-bs-theme="dark"] .note-color-green .note-card {
            background-color: #1a2e22 !important;
        }
        [data-bs-theme="dark"] .note-color-yellow .note-card {
            background-color: #2e2a1a !important;
        }
        [data-bs-theme="dark"] .note-color-purple .note-card {
            background-color: #25192e !important;
        }
        [data-bs-theme="dark"] .note-color-pink .note-card {
            background-color: #2e1923 !important;
        }
    </style>
</head>
<script>
    // Make PHP constants available to JavaScript
    const BASE_URL = "<?= BASE_URL ?>";
    
    // Make user preferences available to JavaScript
    const USER_PREFERENCES = {
        theme: "<?= $theme ?>",
        font_size: "<?= $font_size ?>",
        note_color: "<?= $note_color ?>"
    };
</script>
<body class="d-flex flex-column min-vh-100 bg-light <?= $font_size_class ?> note-color-<?= $note_color ?>" data-bs-theme="<?= $theme ?>">
    <?php if (Session::isLoggedIn()): ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?= BASE_URL ?>">
                    <i class="fas fa-sticky-note me-2"></i><?= APP_NAME ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/notes') !== false && !strpos($_SERVER['REQUEST_URI'], '/notes/shared') ? 'active' : '' ?>" href="<?= BASE_URL ?>/notes">
                                <i class="fas fa-sticky-note me-1"></i> My Notes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/notes/shared') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/notes/shared">
                                <i class="fas fa-share-alt me-1"></i> Shared Notes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/labels') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/labels">
                                <i class="fas fa-tags me-1"></i> Labels
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars(Session::get('user_display_name')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/profile">
                                        <i class="fas fa-user me-2"></i> My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/profile/preferences">
                                        <i class="fas fa-cog me-2"></i> Preferences
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php endif; ?>
    
    <?php 
    // Notification for unverified accounts
    if (Session::isLoggedIn()): 
        $user = (new User())->getUserById(Session::getUserId());
        if ($user && !$user['is_activated']):
    ?>
    <div class="alert alert-warning text-center mb-0 rounded-0">
        Your account is not verified. Please check your email to complete the activation process.
    </div>
    <?php 
        endif;
    endif;
    ?>
    
    <main class="flex-grow-1 py-4">
        <div class="container"><?= PHP_EOL ?>