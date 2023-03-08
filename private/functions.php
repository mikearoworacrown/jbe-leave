<?php
    function url_for($script_path) {
        //add the leading '/' if not present
        if($script_path[0] != '/') {
            $script_path = '/' . $script_path;
        }
        return WWW_ROOT . $script_path;
    }

    function u($string) {
        return urlencode($script);
    }

    function raw_u($string) {
        return rawurlencode($string);
    }

    function h($string) {
        return htmlspecialchars($string);
    }

    function is_post_request(){
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    function is_get_request() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    function redirect_to($location) {
        header("Location: ", $location);
    }

    function sanitize_validate_email($email) {
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        // after sanitization use the email and check for valid email or not
        if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            // the email is valid and use it
            return filter_var($cleanEmail, FILTER_VALIDATE_EMAIL);
        }
    }
?>