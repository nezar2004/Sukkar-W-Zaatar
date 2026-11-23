<section class="page-heading compact"><div class="container">
    <h1>Track your <em>order.</em></h1>
</div></section>
<section class="container section">
<?php if(!$trackingOrder): ?>
    <p>This tracking link could not be found.</p>
<?php else: ?>
    <article class="summary-card">
        <h2>Order #<?=intval($trackingOrder['id'])?></h2>
        <p><strong><?=h(order_status_label($trackingOrder['status'],$trackingOrder['payment_method']))?></strong></p>
        <?php if($trackingOrder['status']!=='cancelled'): ?>
        <ol aria-label="Order progress">
            <?php foreach(['pending','preparing','ready','completed'] as $status): ?>
            <li <?=$status===$trackingOrder['status']?'aria-current="step"':''?>><?=h(order_status_label($status,$trackingOrder['payment_method']))?></li>
            <?php endforeach; ?>
        </ol>
        <?php endif; ?>
        <a class="btn btn-secondary" href="<?=h(url('track',['token'=>$token]))?>">Refresh status</a>
        <p>Save this private link to check your order from another device.</p>
    </article>
<?php endif; ?>
</section>
