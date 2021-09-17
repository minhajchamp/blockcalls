<?php if (isset($_GET['err'], $_GET['msg'])) : ?>

    <?php $class = ($_GET['err'] == "false") ? "alert alert-primary" : "alert alert-danger"; ?>
    <div class="<?= @$class ?>"><?= $_GET['msg'] ?></div>
<?php endif; ?>

<?php

if (isset($_POST['plan_id'])) {

    self::add_customer();
}

?>

<form action="#" method="POST" id="paymentFrm">
    <input type="hidden" name="action" value="add_customer">
    <input type="hidden" name="plan_id" value="<?= @$_GET['plan_id'] ?>">
    <input type="hidden" name="stripe_token" value="" id="stripe-token" />
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5">
                <div class="form-z">
                    <label for="first_name"><b>First Name</b></label>
                    <input type="text" placeholder="Enter First Name" name="first_name" required>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="form-z">
                    <label for="last_name"><b>Last Name</b></label>
                    <input type="text" placeholder="Enter Last Name" name="last_name" required>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="form-z">
                    <label for="email"><b>Email</b></label>
                    <input type="email" placeholder="Enter Email" name="email" required>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="form-z">
                    <label for="phone_no"><b>Phone No.</b></label>
                    <input type="text" placeholder="Enter Phone No." name="phone_no" required>
                </div>
            </div>
            <div class="col-md-6 mb-5">
                <div class="form-z">
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>
                </div>
            </div>
            <div class="col-md-6  credit-info">
                <label for="card-element">Credit or debit card</label>
                <div id="card-element" class="form-control"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            <div class="col-md-6">
                <!--                <div class="form-too">-->
                <!--                    <div class="form-z">-->
                <!--                        <label for="cc_no"><b>Credit Card No.</b></label>-->
                <!--                        <input type="text" placeholder="Enter Credit Card No." name="cc_no" required>-->
                <!--                    </div>-->
                <!---->
                <!--                    <div class="form-z">-->
                <!--                        <label for="cc_exp_month"><b>Expiry Month</b></label>-->
                <!--                        <input type="text" placeholder="Enter Expiry Month" name="cc_exp_month" required>-->
                <!--                    </div>-->
                <!---->
                <!--                    <div class="form-z">-->
                <!--                        <label for="cc_exp_year"><b>Expiry Year</b></label>-->
                <!--                        <input type="text" placeholder="Enter Expiry Year" name="cc_exp_year" required>-->
                <!--                    </div>-->
                <!---->
                <!--                    <div class="form-z">-->
                <!--                        <label for="cc_country"><b>Country</b></label>-->
                <!--                        <input type="text" placeholder="Enter Country" name="cc_country" required>-->
                <!--                    </div>-->
                <!--                </div>-->


            </div>
        </div>
        <div class="clearfix">
            <button type="submit" class="signupbtn" id="payBtn" name="submit_btn" value="submit_btn">Proceed</button>
        </div>
    </div>
</form>
<script src="https://js.stripe.com/v2/"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var stripePublishKey = "<?= STRIPE_PUBLISH_KEY ?>";
    var stripe = Stripe(stripePublishKey);
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    var elements = stripe.elements();
    var card = elements.create('card', {
        hidePostalCode: true,
        style: style
    });

    card.mount('#card-element');
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    // Callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
        if (response.error) {
            // Enable the submit button
            $('#payBtn').removeAttr("disabled");
            // Display the errors on the form
            $(".payment-status").html('<p>' + response.error.message + '</p>');
        } else {
            var form$ = $("#paymentFrm");
            // Get token id
            var token = response.id;
            // Insert the token into the form
            // Submit form to the server
            form$.get(0).submit();
        }
    }
    $(document).ready(function() {
        // On form submit
        var form = document.getElementById('paymentFrm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    document.getElementById('stripe-token').value = result.token.id
                    form.submit();
                }
            });
        });
    });
</script>