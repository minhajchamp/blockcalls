<?php

if (isset($_POST['submitCancelSub'])) {

    self::cancel_subs();
}

if (isset($_POST['submitReActive'])) {

    self::reactivate_subs();
}
?>

<div class="container my-4">
    <h1 class="font-weight-bold">All <span style="color: purple">Subscriptions</span></h1>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Current</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Cancelled</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <table id="example">
                        <thead>
                            <th>Subscription ID</th>
                            <th width="30%">Customer</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th width="12%">Action</th>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptionListCurrent as $row) : ?>
                                <tr>
                                    <td><?= $row->id; ?></td>
                                    <td><?= \Stripe\Customer::retrieve($row->customer)->email ?? ""; ?></td>
                                    <td><?= $row->items->data[0]->plan->product; ?></td>
                                    <td><?= $row->status == 'active' ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Cancelled</span>'; ?></td>
                                    <td>
                                        <form action="#" method="post">
                                            <input type="hidden" name="action" value="cancel_subs">
                                            <input type="hidden" name="subs_id" value="<?= $row->id; ?>">
                                            <?php if ($row->status == 'active') : ?>
                                                <button type="submit" name="submitCancelSub" class='btn btn-danger cancelSubs'>Cancel</button>
                                            <?php endif; ?>
                                            <?php if ($row->status !== 'active') : ?>
                                                -
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <table id="exampleCancelled">
                        <thead>
                            <th>Subscription ID</th>
                            <th width="30%">Customer</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th width="12%">Action</th>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptionListCancelled as $row) : ?>
                                <tr>
                                    <td><?= $row->id; ?></td>
                                    <td><?= \Stripe\Customer::retrieve($row->customer)->email ?? ""; ?></td>
                                    <td><?= $row->items->data[0]->plan->product; ?></td>
                                    <td><?= $row->status == 'active' ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Cancelled</span>'; ?></td>
                                    <td>
                                        <form action="#" method="post">
                                            <input type="hidden" name="action" value="cancel_subs">
                                            <input type="hidden" name="subs_id" value="<?= $row->id; ?>">
                                            <?php if ($row->status == 'active') : ?>
                                                <button type="submit" name="submitCancelSub" class='btn btn-danger cancelSubs'>Cancel</button>
                                            <?php endif; ?>
                                            <?php if ($row->status !== 'active') : ?>
                                                -
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        $('#example').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false
        });
        $('#exampleCancelled').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false
        });
    }
</script>