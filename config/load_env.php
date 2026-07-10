<?php
/**
 * Simple Environment Configuration Loader
 */
function load_env($filePath) {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments
        if (strpos($line, '#') === 0 || empty($line)) {
            continue;
        }

        // Split by first equals sign
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Strip surrounding quotes if present
            $value = preg_replace('/^["\']|["\']$/', '', $value);

            // Only populate if not already set in the environment (e.g. by Docker)
            if (getenv($key) === false) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Automatically load .env from project root
load_env(dirname(__DIR__) . '/.env');
