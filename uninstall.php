<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('cookiego_widget_id');
delete_option('cookiego_email_exists');
delete_option('cookiego_user_session');
