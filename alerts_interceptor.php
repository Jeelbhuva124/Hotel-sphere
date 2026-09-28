<?php
// This script dynamically intercepts all PHP-generated <script>alert(...)</script> tags
// and converts them to use the Custom Glass Toasts system seamlessly.

function apply_dynamic_alerts($buffer) {
    // Inject the CSS/JS for toasts & modals before </head> if not already present
    if (stripos($buffer, '</head>') !== false && stripos($buffer, 'custom-toast') === false) {
        $alerts_code = file_get_contents(__DIR__ . '/custom_alerts.php');
        $buffer = str_ireplace('</head>', $alerts_code . "\n</head>", $buffer);
    }

    // 1. Intercept alerts with redirects: <script>alert('Msg'); window.location='url';</script>
    $buffer = preg_replace(
        '/<script>\s*alert\([\'"]([^\'"]+)[\'"]\);\s*window\.location(?:\.href)?\s*=\s*[\'"]([^\'"]+)[\'"];\s*<\/script>/i',
        '<script>sessionStorage.setItem("pendingToast", "$1"); window.location.href="$2";</script>',
        $buffer
    );

    // 2. Intercept alerts without redirects: <script>alert('Msg');</script>
    $buffer = preg_replace(
        '/<script>\s*alert\([\'"]([^\'"]+)[\'"]\);\s*<\/script>/i',
        '<script>
            sessionStorage.setItem("pendingToast", "$1");
            document.addEventListener("DOMContentLoaded", function() {
                if(typeof showToast === "function") {
                    showToast(sessionStorage.getItem("pendingToast"));
                    sessionStorage.removeItem("pendingToast");
                }
            });
        </script>',
        $buffer
    );

    return $buffer;
}

// Start output buffering with our interceptor callback
ob_start('apply_dynamic_alerts');
?>
