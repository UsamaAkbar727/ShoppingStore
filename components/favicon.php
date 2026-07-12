<?php
global $base_url;
// Fallback path logic
$fav_path = (isset($base_url) && !empty($base_url)) ? $base_url . 'favicon/' : '/favicon/';
?>
<link rel="icon" type="image/png" href="<?php echo $fav_path; ?>favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="<?php echo $fav_path; ?>favicon.svg" />
<link rel="shortcut icon" href="<?php echo (isset($base_url) && !empty($base_url)) ? $base_url . 'favicon.ico' : '/favicon.ico'; ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $fav_path; ?>apple-touch-icon.png" />
<link rel="manifest" href="<?php echo $fav_path; ?>site.webmanifest" />