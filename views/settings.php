<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Settings
 *
 * @category   Components
 * @package    CookieGo
 * @author     WordPress Dev <wordpress@privacylock.com>
 * @license GPL2 * @link https://privacylock.com * @since 1.0.1 */ ?>


<?php
wp_register_style( 'wpcp', $this->plugin->url . 'assets/css/style.css', null,
'1.0' ); wp_enqueue_script('cookiego-settings', $this->plugin->url . 'assets/js/settings.js', array('jquery'), '1.0', true);
wp_enqueue_style( 'wpcp' ); ?>

       <div class="my-container">
        <div class="flex items-center justify-center bg-[#f0f0f1] p-10">
          <div class="flex w-full max-w-[1800px] flex-col overflow-hidden rounded-lg bg-white pb-20 min-h-[500px]">
            <div class="flex w-full justify-center bg-[#906bbc] pl-20 pr-16 pt-14 max-md:max-w-full max-md:px-5">
              <div class="flex flex-col items-start w-full max-w-[1240px]">
              <div class="ml-8 text-center text-3xl font-bold leading-6 text-white max-md:ml-2.5">Welcome to the CookieGo 🎉</div>
              <div class="ml-8 mt-2 text-center text-xs leading-6 text-zinc-100 max-md:ml-2.5">A smarter way to manage cookies.</div>
              <div class="mt-9 w-full  self-center max-md:max-w-full">
                <div class="mb-[-60px] flex gap-5 max-md:flex-col max-md:gap-0">
                  <div class="flex w-6/12 flex-col max-md:ml-0 max-md:w-full">
                    <div class="flex w-full grow flex-col items-start rounded-lg bg-white px-5 py-5 shadow-sm max-md:mt-6">
                      <div class="text-4xl text-[#baa9dc]">01</div>
                      <div class="mt-5 text-sm font-medium text-[#906bbc]">Activate Cookies Banner</div>
                      <div class="mt-3.5 text-xs text-stone-300">Activate your cookie banner, this will embed the banner in your wordpress site.</div>
                      <button class="cursor-default border mt-5 w-full items-center justify-center self-stretch rounded-lg border-[#906bbc] bg-white px-4 py-3 text-center text-xs text-[#906bbc]">Completed</button>
                    </div>
                  </div>
                  <div class="ml-5 flex w-6/12 flex-col max-md:ml-0 max-md:w-full">
                    <div class="flex w-full grow flex-col rounded-lg bg-white px-5 py-5 text-stone-300 shadow-sm max-md:mt-6">
                      <div class="text-4xl">02</div>
                      <div class="mt-5 text-sm font-medium text-zinc-900">Connect Dashboard</div>
                      <div class="mt-3.5 text-xs">Connect your plugin to our web app. This allows you to customize your banner and view cookie analytics.</div>
                <button id="connectDashboardBtn" class="mt-5 w-full items-center justify-center self-stretch rounded-lg bg-[#906bbc] px-4 py-3 text-center text-xs text-white">Connect to Dashboard</button>

                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
        </div>
       </div>



