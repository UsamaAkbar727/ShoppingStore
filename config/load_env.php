<?php
/**
 * Simple Environment Configuration Loader
 */
function load_env($filePath) {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $parsedEnv = [];

    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments and empty lines
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
            
            // The last defined value in the file wins
            $parsedEnv[$key] = $value;
        }
    }

    // Populate $_ENV, $_SERVER, and putenv only if not already defined in the system environment
    foreach ($parsedEnv as $key => $value) {
        if (getenv($key) === false) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Automatically load .env from project root
load_env(dirname(__DIR__) . '/.env');

/**
 * Safe helper to get environment variables across various server configurations.
 * Checks $_ENV, $_SERVER, and fallback getenv().
 */
function safe_getenv($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    $val = getenv($key);
    return $val !== false ? $val : $default;
}
