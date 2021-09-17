<?php

if (!session_id()) {
    session_start();
}

class BlockCallNow
{
    protected static $payWhirl;
    protected static $plans;

    public static function init()
    {
        $checkPageExist = get_page_by_title('pricing', 'OBJECT', 'page');
        require_once 'stripe-php/init.php';
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        self::create_pricing_page();
        self::create_customer_page();
    }

    public static function testtt()
    {
        $subscriptionListCurrent = \Stripe\Subscription::all(['status' => 'active']);
        $subscriptionListCancelled = \Stripe\Subscription::all(['status' => 'canceled']);
        return self::view('main_page', [
            'subscriptionListCurrent' => $subscriptionListCurrent, 
            'subscriptionListCancelled' => $subscriptionListCancelled
        ]);
    }

    public static function plugin_activation()
    {
        set_transient('activation-notice', true, 5);
    }

    public function activation_notice_func()
    {
        /* Check transient, if available display notice */
        if (get_transient('activation-notice')) {
            echo '<div class="updated notice is-dismissible">
                <p>Thank you for using this plugin! <strong>You are awesome</strong>.</p>
            </div>';
            delete_transient('activation-notice');
        }
    }

    public static function plugin_deactivation()
    {
    }

    public static function displayMenu()
    {
        add_menu_page('Stripe Subscription', 'Stripe Subscription', 'manage_options', 'bcn-subs-plugin', array(__CLASS__, 'testtt'), plugin_dir_url(__FILE__) . 'img/icon-resize.gif', '15');
    }

    public function create_pricing_page()
    {
        $checkPageExist = get_page_by_title('pricing', 'OBJECT', 'page');

        // Check if the page already exists
        if (empty($checkPageExist)) {

            $pageId = wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status' => 'close',
                    'post_author' => 1,
                    'post_title' => ucwords('pricing'),
                    'post_name' => strtolower(str_replace(' ', '-', trim('pricing'))),
                    'post_status' => 'publish',
                    'post_content' => "[blockcallnow_pricing]",
                    'post_type' => 'page',
                )
            );
        } else {

            $post = array(
                'ID' => $checkPageExist->ID,
                'post_content' => "[blockcallnow_pricing]"
            );

            $result = wp_update_post($post, true);
        }
    }

    public function create_customer_page()
    {
        $checkPageExist = get_page_by_title('subscription', 'OBJECT', 'page');

        // Check if the page already exists
        if (empty($checkPageExist)) {

            $pageId = wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status' => 'close',
                    'post_author' => 1,
                    'post_title' => ucwords('subscription'),
                    'post_name' => strtolower(str_replace(' ', '-', trim('subscription'))),
                    'post_status' => 'publish',
                    'post_content' => "[blockcallnow_create_customer_form]",
                    'post_type' => 'page',
                )
            );
        } else {

            $post = array(
                'ID' => $checkPageExist->ID,
                'post_content' => "[blockcallnow_create_customer_form]",
            );

            $result = wp_update_post($post, true);
        }
    }

    public static function view($name, array $args = array())
    {
        $args = apply_filters('blockcallnow_view_arguments', $args, $name);

        foreach ($args as $key => $val) {
            $$key = $val;
        }

        // FOR MULTI LANGUAGE
        //load_plugin_textdomain('blockcallnow');

        $file = BLOCKCALLNOW__PLUGIN_DIR . 'views/' . $name . '.php';

        include($file);
    }

    public static function load_resources()
    {
        wp_register_style('bootstrap.css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', array(), BLOCKCALLNOW_VERSION);
        wp_enqueue_style('bootstrap.css');

        wp_register_style('blockcallnow.css', plugin_dir_url(__FILE__) . '_inc/blockcallnow.css', array(), BLOCKCALLNOW_VERSION);
        wp_enqueue_style('blockcallnow.css');

        wp_register_script('stripe.js', "https://js.stripe.com/v3/", array(), BLOCKCALLNOW_VERSION);
        wp_enqueue_script('stripe.js');

        wp_register_script('blockcallnow.js', plugin_dir_url(__FILE__) . '_inc/blockcallnow.js', array(), BLOCKCALLNOW_VERSION);
        wp_enqueue_script('blockcallnow.js');

        wp_register_script('jqueryScript',  plugin_dir_url(__FILE__) . "js/jquery.min.js", array('jquery'), '1.0', true);
        wp_register_script('popper',  plugin_dir_url(__FILE__) . "js/popper.min.js", array('jquery'), '1.0', true);
        wp_register_script('bootstrap',  plugin_dir_url(__FILE__) . "js/bootstrap.min.js", array('jquery'), '1.0', true);
        wp_register_script('datatable',  plugin_dir_url(__FILE__) . "js/jquery.dataTables.min.js", array('jquery'), '1.0', true);
        wp_register_script('toastrJS',  plugin_dir_url(__FILE__) . "js/toastr.min.js", array('jquery'), '1.0', true);
        wp_enqueue_script('jqueryScript');
        wp_enqueue_script('popper');
        wp_enqueue_script('bootstrap');
        wp_enqueue_script('datatable');
        wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__) . "css/bootstrap.min.css", '', '1.0');
        wp_enqueue_style('mystyle', plugin_dir_url(__FILE__) . "css/custom.css", '', '1.0');
        wp_enqueue_style('datatableCss', plugin_dir_url(__FILE__) . "css/jquery.dataTables.min.css", '', '');
        wp_enqueue_style('toastrcss', plugin_dir_url(__FILE__) . "css/toastr.css", '', '');
    }

    public static function pricing_shortcode()
    {
        return self::view('pricing', ['plans' => self::$plans]);
    }

    public static function create_customer_form_shortcode()
    {
        return self::view('create_customer');
    }

    public function cancel_subs()
    {
        $stripes = \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $subscription = \Stripe\Subscription::retrieve($_POST['subs_id']);
        $subscription->cancel();
    }

    public function reactivate_subs()
    {
        $subscription = \Stripe\Subscription::retrieve($_POST['subs_id']);
        \Stripe\Subscription::update($_POST['subs_id'], [
            'cancel_at_period_end' => false,
            'proration_behavior' => 'create_prorations',
            'items' => [
                [
                    'id' => $subscription->items->data[0]->id,
                ],
            ],
        ]);
    }

    public function add_customer()
    {
        $ordStatus = 'error';
        // Check whether stripe token is not empty 
        if ($_POST['plan_id'] == '' || $_POST['plan_id'] == null || !isset($_POST['plan_id'])) {

            wp_redirect('pricing?err=true&msg=Please select plan!');
            exit();
        }
        $response = wp_remote_get(API_BASE_URL . "api/guest/get-user?phone_no=" . $_POST['phone_no']);
        $decode = json_decode($response['body'], true);

        if ($decode['status'] == false || $decode['status'] == '' || $decode['status'] == null || empty($decode['data'])) {


            $body = array(
                'name' => $_POST['first_name'] . ' ' . $_POST['last_name'],
                'email' => $_POST['email'],
                'phone_no' => $_POST['phone_no'],
                'password' => $_POST['password'],
                'password_confirmation' => $_POST['password'],
            );

            $args = array(
                'body' => $body,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(),
            );

            $response = wp_remote_post(API_BASE_URL . 'api/auth/signup', $args);

            $decode = json_decode($response['body'], true);

            if ($decode['status'] == false || $decode['status'] == '' || $decode['status'] == null || empty($decode['data'])) {

                wp_redirect('pricing?err=true&msg=' . @$decode['message']);
                exit();
            }

            $user = $decode['data']['user'];
        }


        if ($user['paywhirl_customer_id'] == null || $user['paywhirl_customer_id'] == '') {

            // Retrieve stripe token, card and user info from the submitted form data 
            $token  = $_POST['stripe_token'];
            $name = $_POST['name'];
            $email = $_POST['email'];

            // Set API key 
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            // Add customer to stripe 
            $customer = \Stripe\Customer::create(array(
                "email" => $_POST['email'],
                'source' => $token
            ));

            if (isset($customer->error)) {
                wp_redirect('pricing?err=true&msg=' . @$customer->error);
                exit();
            }

            // Creates a new subscription 
            $seconds_per_minute = 60;
            $minutes_per_hour = 60;
            $hours_per_day = 24;
            $seconds_per_day = $seconds_per_minute * $minutes_per_hour * $hours_per_day;

            $durationOfPlanInDays = 90;
            $secondsToAddToTimestamp = ($durationOfPlanInDays * $seconds_per_day);
            $trialEnd = time() + $secondsToAddToTimestamp;

            try {

                $subscribe = \Stripe\Subscription::create(array(
                    "customer" => $customer->id,
                    "items" => array(
                        array(
                            "plan" => STRIPE_PRO_PLAN_ID,
                        ),
                    ),
                ));

                // $invoices = \Stripe\Invoice::all(array(
                //     "limit" => 1,
                //     "customer" => $customer->id,
                //     "expand" => array("data.charge")
                // ));
                
                // $invoice = $invoices->data[0];
                // $invoiceId = $invoice->id;
                // $chargeId = $invoice->charge->id;


                $url = API_BASE_URL . 'api/guest/update-user?id=' . $user['id'] . '&customer_id=' . $customer->id . '&plan_id=' . $subscribe->plan_id . '&subscription_id=' . $subscribe->id . '&period_end=' . $subscribe->current_period_end;
                wp_remote_get($url);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $subscribe) {

                // Retrieve subscription data 
                $subsData = $subscribe->jsonSerialize();

                // Check whether the subscription activation is successful 
                if ($subsData['status'] == 'active') {

                    $planID = $subsData['plan']['id'];

                    $ordStatus = 'success';
                    $statusMsg = 'Your Subscription Payment has been Successful!';
                    wp_redirect('pricing?err=false&msg=' . $statusMsg);
                    exit;
                } else {
                    $statusMsg = "Subscription activation failed!";
                    wp_redirect('pricing?err=false&msg=' . $statusMsg);
                    exit;
                }
            } else {
                $statusMsg = "Subscription creation failed! " . $api_error;
                wp_redirect('pricing?err=true&msg=' . $statusMsg);
                exit();
            }
        } else {
            $statusMsg = "Error on form submission, please try again.";
            wp_redirect('pricing?err=true&msg=' . $statusMsg);
            exit();
        }
    }
}
