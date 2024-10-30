<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Email Exists Page
 *
 * @category   Components
 * @package    CookieGo
 * @license    GPL2
 * @link       https://privacylock.com
 * @since      1.0.1
 */

wp_register_style('wpcp', plugins_url('assets/css/style.css', __FILE__), null, '1.0');
wp_enqueue_style('wpcp');
wp_enqueue_script('cookiego-email-exists', $this->plugin->url . 'assets/js/email-exists.js', array('jquery'), '1.0', true);
wp_localize_script('cookiego-email-exists', 'cookiegoData', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('cookiego_nonce'),
    'custom_login_url' => esc_url('https://obcb26vr7i.execute-api.us-east-1.amazonaws.com/wordpress/login'),
    'widget_id' => esc_js(get_option('cookiego_widget_id')),
    'redirect_url' => esc_url(admin_url('options-general.php?page=CookieGo&view=connected'))
));

$sessionToken = get_option('cookiego_user_session');

$view = empty($sessionToken) ? 'settings' : 'connected';
?>

<div class="my-container">
    <div class="flex items-center justify-center bg-[#f0f0f1] p-10">
        <div class="flex w-full max-w-[1800px] flex-col overflow-hidden rounded-lg bg-white pb-20 min-h-[500px]">
            <div class="flex w-full justify-center bg-[#906bbc] pl-20 pr-16 pt-14 max-md:max-w-full max-md:px-5">
                <div class="flex flex-col items-start w-full max-w-[1240px]">
                    <div class="ml-8 text-center text-3xl font-bold leading-6 text-white max-md:ml-2.5">Connect Dashboard</div>
                    <div class="ml-8 mt-2 text-center text-xs leading-6 text-zinc-100 max-md:ml-2.5">Connect your plugin to our web app. This allows you to customize your banner and view cookie analytics.</div>
                    <div class="flex flex-col gap-8 mt-9 w-full self-center max-md:max-w-full mb-[-80px]">
                        <div class="flex items-baseline gap-5 max-md:flex-col max-md:gap-0">
                            <div class="ml-5 flex w-6/12 flex-col max-md:ml-0 max-md:w-full">
                                <div class="flex w-full grow flex-col rounded-lg bg-white px-5 py-5 text-stone-300 shadow-sm max-md:mt-6">
                                    <div class="text-sm font-medium text-zinc-900">Sign In</div>
                                    <div class="mt-3.5 text-xs">Sign into your existing Privacy Lock account.</div>
                                    <button id="signinBtn" class="border mt-5 w-full items-center justify-center self-stretch rounded-lg bg-white px-4 py-3 text-center text-xs text-zinc-900">Sign In</button>
                                </div>
                            </div>
                            <div class="ml-5 flex w-6/12 flex-col max-md:ml-0 max-md:w-full">
                                <div class="flex w-full grow flex-col rounded-lg bg-white px-5 py-5 text-stone-300 shadow-sm max-md:mt-6">
                                    <div class="text-sm font-medium text-zinc-900">Create Account</div>
                                    <div class="mt-3.5 text-xs">Create a new account with Privacy Lock.</div>
                                    <button id="connectDashboardBtn" class="mt-5 w-full items-center justify-center self-stretch rounded-lg bg-[#906bbc] px-4 py-3 text-center text-xs text-white">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
