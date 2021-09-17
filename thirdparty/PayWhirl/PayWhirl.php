<?php
/**
 * PayWhirl API PHP Library.
 *
 * Use this library to interface with PayWhirl's API
 * https://api.paywhirl.com
 *
 *  Example Usage:
 *  =========================
 *  $payWhirl = new \PayWhirl\PayWhirl($api_key,$api_secret);
 *  $customer = $payWhirl->getCustomer($customer_id);
 */

namespace PayWhirl;

class PayWhirl {
    // @var string The PayWhirl API key and secret to be used for requests.
    protected $_api_key;
    protected $_api_secret;

    // @var bool Verify SSL certificate
    protected $_verify_ssl = true;

    // @var string The base URL for the PayWhirl API.
    protected $_api_base = 'https://api.paywhirl.com';

    /**
     * Prepare to make request.
     *
     * @param string $api_key    Your Paywhirl API Key
     * @param string $api_secret Your PayWhirl API Secret
     */
    public function __construct($api_key, $api_secret, $api_base = null, $verify_ssl = true) {
        //set API key and secret
        $this->_api_key = $api_key;
        $this->_api_secret = $api_secret;
        $this->_verify_ssl = $verify_ssl;

        if ($api_base) {
            $this->_api_base = $api_base;
        }
    }

    /**
     * Get a list of customers.
     *
     * @return Customer Array of Objects
     */
    public function getCustomers($data) {
        return $this->get('/customers', $data);
    }

    /**
     * Get a customer.
     *
     * @param int $id Customer ID
     *
     * @return Customer Object
     */
    public function getCustomer($id) {
        return $this->get('/customer/'.$id);
    }

    /**
     * Get all addresses associated with a customer.
     *
     * @param int $id Customer ID
     *
     * @return Customer Object
     */
    public function getAddresses($id) {
        return $this->get('/customer/addresses/'.$id);
    }

    /**
     * Get a single address associated with a customer.
     *
     * @param int $id Address ID
     *
     * @return Customer Object
     */
    public function getAddress($id) {
        return $this->get('/customer/address/'.$id);
    }

    /**
     * Create a customer address.
     *
     * @param array $data Address data
     *
     * @return Address Object
     */
    public function createAddress(array $data) {
        return $this->post('/customer/address/', $data);
    }

    /**
     * Update a customer address.
     *
     * @param int   $id   Address ID
     * @param array $data Address data
     *
     * @return Address Object
     */
    public function updateAddress(int $id, array $data) {
        return $this->patch('/customer/address/'.$id, $data);
    }

    /**
     * Delete a customer address.
     *
     * @param int $id Address ID
     *
     * @return bool
     */
    public function deleteAddress($id) {
        return $this->delete('/customer/address/'.$id);
    }

    /**
     * Get a full customer profile by customer ID or email (customer, addresses, and profile questions).
     *
     * @param int $id Address ID
     *
     * @return Customer Object
     */
    public function getProfile($id) {
        return $this->get('/customer/profile/'.$id);
    }

    /**
     * Create a customer.
     *
     * @param array $data Customer data
     *
     * @return Customer Object
     */
    public function createCustomer($data) {
        return  $this->post('/create/customer', $data);
    }

    /**
     * Authenticate a customer.
     *
     * @param string $email    email address
     * @param string $password password bcrypt hash or plain text
     *
     * @return array ['status' => 'success' or 'failure']
     */
    public function authCustomer($email, $password) {
        return $this->post('/auth/customer', compact('email', 'password'));
    }

    /**
     * Create a customer.
     *
     * @param array $data Customer data
     *
     * @return Customer Object
     */
    public function updateCustomer($data) {
        return $this->post('/update/customer', $data);
    }

    /**
     * Delete a customer.
     *
     * @param int  $id     Customer ID
     * @param bool $forget delete and obfuscate customer data
     *
     * @return bool
     */
    public function deleteCustomer($id, $forget = null) {
        $data = ['id' => $id];

        if (!is_null($forget)) {
            $data['forget'] = $forget;
        }

        return $this->post('/delete/customer', $data);
    }

    /**
     * Update a customer's answer to a profile questions.
     *
     * @param array $data Answer data
     *
     * @return Answer Object
     */
    public function updateAnswer($data) {
        return $this->post('/update/answer', $data);
    }

    /**
     * Get a list of profile questions.
     *
     * @return Questions Array of Objects
     */
    public function getQuestions($data_or_limit = 100) {
        $data = is_int($data_or_limit) ? ['limit' => $data_or_limit] : $data_or_limit;

        return $this->get('/questions', $data);
    }

    /**
     * Get a answers to a customer's questions.
     *
     * @return Answer Array of Objects
     */
    public function getAnswers($data_or_customer_id) {
        $data = is_int($data_or_customer_id) ? ['customer_id' => $data_or_customer_id] : $data_or_customer_id;

        return $this->get('/answers', $data);
    }

    /**
     * Get a list of plans.
     *
     * @return Plan Array of Objects
     */
    public function getPlans($data) {
        return $this->get('/plans', $data);
    }

    /**
     * Get a plan.
     *
     * @param int $id Plan ID
     *
     * @return Plan Object
     */
    public function getPlan($id) {
        return $this->get('/plan/'.$id);
    }

    /**
     * Create a plan.
     *
     * @param array $data Plan data
     *
     * @return Plan Object
     */
    public function createPlan($data) {
        return $this->post('/create/plan', $data);
    }

    /**
     * Update a plan.
     *
     * @param array $data Plan data
     *
     * @return Plan Object
     */
    public function updatePlan($data) {
        return $this->post('/update/plan', $data);
    }

    /**
     * Get a list of subscriptions for a customer.
     *
     * @param int $id Customer ID
     * @param string $status
     *
     * @return Subscription List Object
     */
    public function getSubscriptions($id, $status = 'active') {
        return $this->get('/subscriptions/'.$id, ['status' => $status]);
    }

    /**
     * Get a subscription.
     *
     * @param int $id Subscription ID
     *
     * @return Subscription Object
     */
    public function getSubscription($id) {
        return $this->get('/subscription/'.$id);
    }

    /**
     * Get a list of active subscribers.
     *
     * @param array $data Array of options
     *
     * @return Subscribers List
     */
    public function getSubscribers($data) {
        return $this->get('/subscribers', $data);
    }

    /**
     * Subscribe a customer to a plan.
     *
     * @param int $customerID
     * @param int $planID
     * @param int $trialEnd
     * @param int $promoID
     * @param int $quantity
     *
     * @return Subscription Object
     */
    public function subscribeCustomer($customerID, $planID, $trialEnd = false,
                                      $promoID = false, $quantity = 1) {
        $data = [
            'customer_id' => $customerID,
            'plan_id' => $planID,
            'quantity' => $quantity,
        ];
        if ($trialEnd) {
            $data['trial_end'] = $trialEnd;
        }
        if ($promoID) {
            $data['promo_id'] = $promoID;
        }

        return $this->post('/subscribe/customer', $data);
    }

    /**
     * Subscribe a customer to a plan.
     *
     * @param int $id Subscription ID
     *
     * @return Subscription Object
     */
    public function updateSubscription($subscription_id, $plan_id, $quantity = null, $address_id = null,
                                       $installments_left = null, $trial_end = null, $card_id = null) {
        $data = [
            'subscription_id' => $subscription_id,
            'plan_id' => $plan_id,
        ];
        if ($quantity != null) {
            $data['quantity'] = $quantity;
        }
        if ($address_id != null) {
            $data['address_id'] = $address_id;
        }
        if ($installments_left != null) {
            $data['installments_left'] = $installments_left;
        }
        if ($trial_end != null) {
            $data['trial_end'] = $trial_end;
        }
        if ($card_id != null) {
            $data['card_id'] = $card_id;
        }

        return $this->post('/update/subscription', $data);
    }

    /**
     * Unsubscribe a Customer.
     *
     * @param int $id Subscription ID
     *
     * @return Subscription Object
     */
    public function unsubscribeCustomer($subscription_id) {
        $data = [
            'subscription_id' => $subscription_id,
        ];

        return $this->post('/unsubscribe/customer', $data);
    }

    /**
     * Get a invoice.
     *
     * @param int $id Invoice ID
     *
     * @return Invoice Object
     */
    public function getInvoice($id) {
        return $this->get('/invoice/'.$id);
    }

    /**
     * Get a list of upcoming invoices for a customer.
     *
     * @param int $id Customer ID
     *
     * @return Invoices Object
     */
    public function getInvoices($id, $all = false) {
        $data = ['all' => $all ? '1' : null];

        return $this->get('/invoices/'.$id, $data);
    }

    /**
     * Process an upcoming invoice immediately.
     *
     * @param int   $invoice_id
     * @param array $data
     *
     * @return Invoice Object
     */
    public function processInvoice($invoice_id, $data = []) {
        return $this->post("/invoice/{$invoice_id}/process", $data);
    }

    /**
     * Mark an upcoming invoice as paid.
     *
     * @param int $invoice_id
     *
     * @return Invoice Object
     */
    public function markInvoiceAsPaid($invoice_id) {
        return $this->post("/invoice/{$invoice_id}/mark-as-paid");
    }

    /**
     * Add a promo code to an upcoming invoice.
     *
     * @param int    $invoice_id
     * @param string $promo_cde
     *
     * @return Invoice Object
     */
    public function addPromoCodeToInvoice($invoice_id, $promo_code) {
        return $this->post("/invoice/{$invoice_id}/add-promo", ['promo_code' => $promo_code]);
    }

    /**
     * Remove promo code from an upcoming invoice.
     *
     * @param int $invoice_id
     *
     * @return Invoice Object
     */
    public function removePromoCodeFromInvoice($invoice_id) {
        return $this->post("/invoice/{$invoice_id}/remove-promo");
    }

    /**
     * Update a card for an invoice.
     *
     * @param int $invoice_id
     * @param int $card_id
     *
     * @return Invoice Object
     */
    public function updateInvoiceCard($invoice_id, $card_id) {
        return $this->post("/invoice/{$invoice_id}/card", $card_id);
    }

    /**
     * Update a card for an invoice.
     *
     * @param int   $invoice_id
     * @param array $line_items
     *
     * @return Invoice Object
     */
    public function updateInvoiceItems($invoice_id, $line_items) {
        return $this->post("/invoice/{$invoice_id}/items", $line_items);
    }

    /**
     * Create a new invoice.
     *
     * @param array $data
     *
     * @return Invoice Object
     */
    public function createInvoice($data) {
        return $this->post('/invoices/', $data);
    }

    /**
     * Delete an invoice.
     *
     * @param int $id Invoice ID
     *
     * @return bool
     */
    public function deleteInvoice($id) {
        $data = ['id' => $id];

        return $this->post('/delete/invoice', $data);
    }

    /**
     * Get a list of payment gateways.
     *
     * @return Gateways Collection
     */
    public function getGateways() {
        return $this->get('/gateways');
    }

    /**
     * Get a payment gateway.
     *
     * @param int $id Gateway ID
     *
     * @return Gateway Object
     */
    public function getGateway($id) {
        return $this->get('/gateway/'.$id);
    }

    /**
     * Create an invoice with a single charge.
     *
     * @param array $data data
     *
     * @return Plan Object
     */
    public function createCharge($data) {
        return $this->post('/create/charge', $data);
    }

    /**
     * Get a charge.
     *
     * @param int $id Charge ID
     *
     * @return Charge Object
     */
    public function getCharge($id) {
        return $this->get('/charge/'.$id);
    }

    /**
     * Refund a charge.
     *
     * @param int   $id   Charge ID
     * @param array $data data
     *
     * @return Plan Object
     */
    public function refundCharge($id, $data) {
        return $this->post('/refund/charge/'.$id, $data);
    }

    /**
     * Get a card.
     *
     * @param int $id Card ID
     *
     * @return Gateway Object
     */
    public function getCard($id) {
        return $this->get('/card/'.$id);
    }

    /**
     * Get a customer's cards.
     *
     * @param int $id Customer ID
     *
     * @return Card List Object
     */
    public function getCards($id) {
        return $this->get('/cards/'.$id);
    }

    /**
     * Create a card.
     *
     * @param array $data Card Data
     *
     * @return Card Object
     */
    public function createCard($data) {
        return $this->post('/create/card', $data);
    }

    /**
     * Delete a card.
     *
     * @param int $id Card ID
     *
     * @return bool
     */
    public function deleteCard($id) {
        $data['id'] = $id;

        return $this->post('/delete/card', $data);
    }

    /**
     * Get all promo codes.
     *
     * @return Promo Code Object
     */
    public function getPromos() {
        return $this->get('/promo');
    }

    /**
     * Get a promo code.
     *
     * @param int $id Promo Code ID
     *
     * @return Promo Code Object
     */
    public function getPromo($id) {
        return $this->get('/promo/'.$id);
    }

    /**
     * Create a promo code.
     *
     * @param array $data data
     *
     * @return Plan Object
     */
    public function createPromo($data) {
        return $this->post('/create/promo', $data);
    }

    /**
     * Delete a promo code.
     *
     * @param array $id id
     *
     * @return Plan Object
     */
    public function deletePromo($id) {
        $data = ['id' => $id];

        return $this->post('/delete/promo', $data);
    }

    /**
     * Get an email template.
     *
     * @param int $id Email Template ID
     *
     * @return Email Template Object
     */
    public function getEmailTemplate($id) {
        return $this->get('/email/'.$id);
    }

    /**
     * Send a system generated email to a customer.
     *
     * @param array $data options
     *
     * @return success or error
     */
    public function sendEmail($data) {
        return $this->post('/send-email', $data);
    }

    /**
     * Get authenticated account's information.
     *
     * @return PayWhirl account object
     */
    public function getAccount() {
        return $this->get('/account');
    }

    /**
     * Get authenticated account's stats.
     *
     * @return PayWhirl account object
     */
    public function getStats() {
        return $this->get('/stats');
    }

    /**
     * Get all shipping rules.
     *
     * @return Shipping Rule Object
     */
    public function getShippingRules() {
        return $this->get('/shipping');
    }

    /**
     * Get a shipping rule.
     *
     * @param int $id Shipping Rule ID
     *
     * @return Shipping Rule Object
     */
    public function getShippingRule($id) {
        return $this->get('/shipping/'.$id);
    }

    /**
     * Get a tax rule.
     *
     * @return Tax Rule Object
     */
    public function getTaxRules() {
        return $this->get('/tax');
    }

    /**
     * Get a tax rule.
     *
     * @param int $id Tax Rule ID
     *
     * @return Tax Rule Object
     */
    public function getTaxRule($id) {
        return $this->get('/tax/'.$id);
    }

    /**
     * Get MultiAuth token.
     *
     * @param array $data Options
     *
     * @return bool
     */
    public function getMultiAuthToken($data) {
        return $this->post('/multiauth', $data);
    }

    /**
     * Send API request.
     */
    protected function request($method, $endpoint, $params = []) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'api-key: '.$this->_api_key,
            'api-secret: '.$this->_api_secret,
        ]);

        if ($method === 'get') {
            curl_setopt($ch, CURLOPT_URL, $this->_api_base.$endpoint.'?'.http_build_query($params));
        } elseif ($method === 'post') {
            curl_setopt($ch, CURLOPT_URL, $this->_api_base.$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'patch') {
            curl_setopt($ch, CURLOPT_URL, $this->_api_base.$endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'delete') {
            curl_setopt($ch, CURLOPT_URL, $this->_api_base.$endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else {
            throw new InvalidArgumentException("Method must be 'get', 'post', 'patch' or 'delete'");
        }

        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->_verify_ssl ? 2 : 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->_verify_ssl ? 2 : 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);
    }

    /**
     * Send POST request.
     */
    protected function post($endpoint, $params = []) {
        return $this->request('post', $endpoint, $params);
    }

    /**
     * Send PATCH request.
     */
    protected function patch($endpoint, $params = []) {
        return $this->request('patch', $endpoint, $params);
    }

    /**
     * Send DELETE request.
     */
    protected function delete($endpoint) {
        return $this->request('delete', $endpoint);
    }

    /**
     * Send GET request.
     */
    protected function get($endpoint, $params = []) {
        return $this->request('get', $endpoint, $params);
    }
}
