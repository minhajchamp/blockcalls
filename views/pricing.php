<?php if (isset($_GET['err'], $_GET['msg'])): ?>
    <?php $class = ($_GET['err'] == "false") ? "alert alert-primary" : "alert alert-danger"; ?>
    <div class="<?= @$class ?>"><?= $_GET['msg'] ?></div>
<?php endif; ?>
<div class="row">
<div class="col-md-4 cstm-z">
    <ul class="price">
        <li class="header">TRIAL</li>
        <li>
            <div class="hundred">FREE</div>
            <div class="month">10 DAYS</div>
        </li>
        <li>Block Private Numbers</li>
        <li>Block Unknown Numbers</li>
        <li>Block Spam Likely Numbers</li>
        <li>Block International Numbers</li>
        <li>Block All Calls Except My Contacts</li>
        <li>Block All Calls</li>
        <li>Block Text Messages</li>
        <li>Has The Standard Generic Message</li>
        <li class="cntr-btn-subs">
            <a href="<?= site_url('/subscription?plan_id='.STRIPE_TRIAL_PLAN_ID) ?>" class="subs-btn">Subscribe Now!
            </a>
        </li>
    </ul>
</div>

<div class="col-md-4 cstm-z">
    <ul class="price">
        <li class="header">STANDARD PLAN</li>
        <li>
            <div class="hundred">$1.00</div>
            <div class="month">Per MONTH</div>
        </li>
        <li>Block Private Numbers</li>
        <li>Block Unknown Numbers</li>
        <li>Block Spam Likely Numbers</li>
        <li>Block International Numbers</li>
        <li>Block All Calls Except My Contacts</li>
        <li>Block All Calls</li>
        <li>Block Text Messages</li>
        <li>Has The Standard Generic Message</li>
        <li class="cntr-btn-subs"><a href="<?= site_url('/subscription?plan_id='.STRIPE_STANDARD_PLAN_ID) ?>" class="subs-btn">Subscribe Now!</a></li>
        <li  class="cntr-btn-subs"><?php// echo do_shortcode('[simpay id="6441"]');?></li>
    </ul>
</div>

<div class="col-md-4 cstm-z">
    <ul class="price">
        <li class="header">PRO PLAN</li>
        <li>
            <div class="hundred">$5.77</div>
            <div class="month">Per MONTH</div>
        </li>
        <li>Block Private Numbers</li>
        <li>Block Unknown Numbers</li>
        <li>Block Spam Likely Numbers</li>
        <li>Block International Numbers</li>
        <li>Block All Calls Except My Contacts</li>
        <li>Block All Calls</li>
        <li>Block Text Messages</li>
        <li>Has The Standard Generic Message</li>
        <li>Has The Option To Partial Block Or Full Block</li>
        <li>Can Customize The Generic Message Up To 100 Characters</li>
        <li>Can Customize The Voice Message Up To 100 Characters</li>
        <li>Can Customize The Text Message Up To 100 Characters</li>
        <li>Can Select A Man Or Lady Voice The Blocked Caller Will Hear</li>
        <li>Can Select Language The Blocked Caller Will Hear</li>
        <li class="cntr-btn-subs"><a href="<?= site_url('/subscription?plan_id='.STRIPE_PRO_PLAN_ID) ?>" class="subs-btn">Subscribe Now!</a></li>
        <li  class="cntr-btn-subs"><?php// echo do_shortcode('[simpay id="6442"]');?></li>
    </ul>
    </div>
</div>
