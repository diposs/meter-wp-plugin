<?php
/**
 * @package Meter Pay
 * @version 1.0.0
 */
/*
Plugin Name: Meter Pay
Plugin URI: ""
Description: Shortcode that allow your customers to SignIn with Meter Wallet and make payments using Meter token. Meter is a decentralized application platform which is built atop the Meter Protocol
Author: DIPO
Version: 1.0.0
Author URI: ""
License: ""
*/

add_action('admin_menu', 'meter_pay_setup_menu');

function meter_pay_setup_menu()
{
    add_menu_page('Meter Paywall', 'Meter Paywall', 'manage_options', 'meter-pay', 'meter_pay_init');
}

function meter_pay_init()
{
    ?>
    <div class="wrap">
        <h2>Meter Paywall</h2>
        <div class="mt-description">
            <p>
                <a href="https://meter.org/" target="_blank"><b>Meter</b></a>
                is a decentralized application platform which is built atop the Meter Protocol.
            </p>
            <b>To use this Plugin:</b> <br>
            1. Provide your Meter wallet address, select networks and save changes.<br>
            2. Add shortcode to any Post or Page: <code>[meter_pay]</code><br>
            <p>Additionally you can customize payment amounts (5 MTR in this example): <code>[meter_pay amounts=5]</code> <br>
                and text on the button: <code>[meter_pay text="Make a Payment with Meter"]</code>.</p>
            <hr class="mt-divider">
        </div>

        <h2 class="mt-settings-subtitle">Settings</h2>
        <form action="options.php" method="post" class="mt-form"><?php
            settings_fields('meter_pay');
            do_settings_sections('meter_pay'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Meter networks: <sup>*</sup></th>
                    <td>
                        <fieldset>
                            <select name="networks">
                                <option value="test" <?php echo (esc_attr(get_option('networks')) == 'test') ? "selected" : ""; ?>>TestNet</option>
                                <option value="main" <?php echo (esc_attr(get_option('networks')) == 'main') ? "selected" : ""; ?>>MainNet</option>
                            </select>
                            <label>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Meter Wallet: <sup>*</sup></th>
                    <td>
                        <fieldset>
                            <label>
                                <input name="addressid" type="text" id="addressid" value="<?php echo esc_attr(get_option('addressid')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function meter_pay_settings()
{
    register_setting('meter_pay', 'addressid');
    register_setting('meter_pay', 'networks');
    register_setting('meter_pay', 'logout');
}

if (!is_admin() && !wp_is_json_request()) {
    function meter_pay_process_shortcode($args)
    {
        $amounts = $args['amounts'] ?? 1;
        $text = $args['text'] ?? 'Pay ' . $amounts . ' MTR';
        if (esc_attr(get_option('addressid'))) {
            if (!empty($_GET['transactionHashes'])) {
                ?>
                <a class="mt-sent"
                   target="_blank"
                   data-networks="<?php echo esc_attr(get_option('networks')); ?>"
                   data-transaction="<?php echo esc_html(urldecode($_GET['transactionHashes'])); ?>"
                >Transaction sent, check in Meter explorer.</a>
                <?php
            } else {
                if (!empty($_GET['errorMessage'])) {
                    ?><p class="mt-error"><?php echo esc_html(urldecode($_GET['errorMessage'])); ?>.</p><?php
                }
                ?>
                <button class="meter-payment-button"
                        data-amounts="<?php echo esc_html($amounts); ?>"
                        data-text="<?php echo esc_html($text); ?>"
                        data-addressid="<?php echo esc_attr(get_option('addressid')); ?>"
                        data-networks="<?php echo esc_attr(get_option('networks')); ?>"
						onclick="sendfunds();">...
                </button>
                <?php
            }

        }
    }

    add_shortcode("meter_pay", "meter_pay_process_shortcode");
}


function meter_pay_scripts_and_styles()
{
    wp_enqueue_style('meter_pay-style', plugin_dir_url(__FILE__) . 'css/meter-pay.css', false);
    
    wp_enqueue_script('meter_pay-api', plugin_dir_url(__FILE__) . 'js/meter-api-js.min.js', false, '0.41.0');
	wp_enqueue_script('meter_pay-script', plugin_dir_url(__FILE__) . 'js/meter-pay.js', false, '0.51.0');
	wp_enqueue_script('meter_pay-send', plugin_dir_url(__FILE__) . 'js/meter-send.js', false);
}

function meter_pay_admin_scripts_and_styles()
{
    wp_enqueue_style('meter_pay-admin-style', plugin_dir_url(__FILE__) . 'css/admin-meter-pay.css', false);
}

add_action('wp_enqueue_scripts', 'meter_pay_scripts_and_styles');
add_action('admin_enqueue_scripts', 'meter_pay_admin_scripts_and_styles');
add_action('admin_init', 'meter_pay_settings');
