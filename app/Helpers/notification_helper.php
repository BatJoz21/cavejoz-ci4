<?php

if(!function_exists('get_notification_url')) {
    function get_notification_url($n): string {
        switch($n['type']) {
            case "like":
                return base_url('/posts/' . $n['reference_id'] . '/comments');
            case "comment":
                return base_url('/posts/' . $n['reference_id'] . '/comments');
            case "friend_request":
                return base_url('/friends?status=pending');
            case "friend_accept":
                return base_url('/friends');
            default:
                return base_url('/');
        }
    }
}
