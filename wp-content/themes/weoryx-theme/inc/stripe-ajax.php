<?php
/**
 * Stripe Payment AJAX Handlers
 */

add_action('wp_ajax_weoryx_create_payment_intent', 'weoryx_create_payment_intent');
add_action('wp_ajax_nopriv_weoryx_create_payment_intent', 'weoryx_create_payment_intent');

function weoryx_create_payment_intent() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'weoryx_stripe_pay')) {
        wp_send_json_error('Invalid security token. Please refresh the page.');
    }

    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
    $currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : 'usd';
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : 'WeOryx Payment';

    if ($amount < 100) { // Minimum 1 unit (100 cents/halalas)
        wp_send_json_error('Invalid amount.');
    }

    // Get Secret Key from options
    $stripe_secret_key = get_option('weoryx_stripe_secret_key', '');

    if (empty($stripe_secret_key)) {
        wp_send_json_error('Stripe is not configured completely. Please contact support.');
    }

    // Step 1: Create a Customer in Stripe to ensure info is pre-filled correctly
    $customer_url = 'https://api.stripe.com/v1/customers';
    $customer_data = array(
        'email' => $email,
        'name' => $name,
        'phone' => $phone,
        'description' => 'Customer for WeOryx Payment',
        'metadata' => array(
            'source' => 'Website Payment Page'
        )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $customer_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($customer_data));
    curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':');
    $customer_response = curl_exec($ch);
    $customer_json = json_decode($customer_response, true);
    curl_close($ch);

    $customer_id = isset($customer_json['id']) ? $customer_json['id'] : null;

    // Step 2: Create the Checkout Session
    $url = 'https://api.stripe.com/v1/checkout/sessions';
    
    // Build the Success URL with customer info for the Thank You page
    $success_url = home_url('/thank-you/') . '?session_id={CHECKOUT_SESSION_ID}' .
                  '&amount=' . ($amount / 100) .
                  '&currency=' . strtoupper($currency) .
                  '&name=' . urlencode($name) .
                  '&email=' . urlencode($email);

    $data = array(
        'payment_method_types' => array('card'),
        'line_items' => array(
            array(
                'price_data' => array(
                    'currency' => $currency,
                    'product_data' => array(
                        'name' => $description,
                    ),
                    'unit_amount' => $amount,
                ),
                'quantity' => 1,
            ),
        ),
        'mode' => 'payment',
        'success_url' => $success_url,
        'cancel_url' => wp_get_referer() ? wp_get_referer() : home_url('/'),
        'metadata' => array(
            'customer_name' => $name,
            'customer_email' => $email,
            'customer_phone' => $phone
        )
    );

    // If we have a customer ID, link it. This pre-fills name, email, and potentially phone on Stripe Checkout.
    if ($customer_id) {
        $data['customer'] = $customer_id;
    } else {
        $data['customer_email'] = $email;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        wp_send_json_error('Connection error: ' . $curl_error);
    }

    $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['url'])) {
        wp_send_json_success(array('url' => $result['url']));
    } else {
        $error_msg = isset($result['error']['message']) ? $result['error']['message'] : 'Failed to create checkout session.';
        wp_send_json_error($error_msg);
    }
}
