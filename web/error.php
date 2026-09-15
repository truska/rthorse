<?php

declare(strict_types=1);

/**
 * Shared Apache error document.
 *
 * Apache internally routes each configured status here, so this keeps the
 * original response status while rendering the normal public site chrome.
 */
$status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT);
$messages = [
    400 => ['Bad request', 'The request could not be understood.'],
    401 => ['Sign in required', 'Please sign in to access this page.'],
    403 => ['Access denied', 'You do not have permission to access this page.'],
    404 => ['Page not found', 'The page you requested could not be found.'],
    405 => ['Method not allowed', 'This action is not available for this page.'],
    500 => ['Something went wrong', 'The server encountered an unexpected error.'],
    502 => ['Service temporarily unavailable', 'Please try again in a few moments.'],
    503 => ['Service temporarily unavailable', 'Please try again in a few moments.'],
];

if (!isset($messages[$status])) {
    $status = 500;
}

http_response_code($status);
[$heading, $message] = $messages[$status];

require_once __DIR__ . '/../private/database-legacy.php';
require_once __DIR__ . '/../private/site-url.php';
require_once __DIR__ . '/includes/functions.php';

$prefs = loadPrefs($conn);
$baseURL = rthBaseUrl();
$segs = ['error'];
$sort = 'sort ASC';
$rowpage = [
    'id' => 0,
    'name' => $status . ' – ' . $heading,
    'titletag' => $status . ' – ' . $heading,
    'metadescription' => $message,
    'metakeywords' => '',
    'pagesearch' => 'No',
];
?>
<!doctype html>
<html lang="en">
<head>
    <?php include __DIR__ . '/includes/header-code.php'; ?>
    <meta name="robots" content="noindex, nofollow">
    <style>
        .error-home-button {
            background: darkolivegreen;
            border-color: darkolivegreen;
            color: #fff;
        }

        .error-home-button:hover,
        .error-home-button:focus {
            background: olive;
            border-color: olive;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/includes/menu.php';
    ?>

    <main class="container py-5 my-4">
        <section class="mx-auto text-center" style="max-width: 44rem;">
            <p class="text-uppercase text-muted mb-2">Error <?php echo $status; ?></p>
            <h1 class="display-5 mb-3"><?php echo htmlspecialchars($heading, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="lead mb-4"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <a class="btn error-home-button" href="<?php echo htmlspecialchars($baseURL, ENT_QUOTES, 'UTF-8'); ?>/">Return to the homepage</a>
        </section>
    </main>

    <?php
    include __DIR__ . '/includes/footer.php';
    include __DIR__ . '/includes/footer-code.php';
    ?>
</body>
</html>
