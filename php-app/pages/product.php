<?php $prod=get_product((int)($_GET['id']??0)); ?><?php if(!$prod):?><section class="section container empty-state"><h1>We couldn't find that treat.</h1><a class="btn btn-primary" href="<?=h(url('shop'))?>">Back to menu</a></section><?php else:?><section class="container section"><p class="breadcrumbs"><a href="<?=h(url())?>">Home</a> / <a href="<?=h(url('shop'))?>">Menu</a> / <?=h($prod['name'])?></p><div class="detail-grid"><div class="detail-image"><img src="<?=h(slug_image($prod['image_key']))?>" alt="<?=h($prod['name'])?>"></div><div class="detail-copy"><span class="eyebrow"><?=h($prod['category_name'])?> · FRESHLY MADE</span><h1><?=h($prod['name'])?></h1><p class="detail-price"><?=h(money($prod['price']))?></p><p><?=h($prod['description'])?></p><div class="detail-perks"><span>✺ Baked with care</span><span>♡ Made in Irbid</span><span>♧ Great for sharing</span></div><form method="post" action="<?=h(url('cart'))?>" class="detail-form"><?=csrf_input()?><input type="hidden" name="action" value="add"><input type="hidden" name="id" value="<?=intval($prod['id'])?>"><label for="qty">Quantity</label><input type="number" id="qty" name="qty" value="1" min="1" max="<?=max(1,min(20,(int)$prod['stock']))?>" required><button class="btn btn-primary" type="submit" <?=$prod['stock']<1?'disabled':''?>><?=$prod['stock']<1?'Sold out':'Add to bag ↗'?></button></form><small class="muted"><?= $prod['stock']>0?'Available now':'Check back soon!'?></small></div></div></section><?php endif;?>

<?php if ($prod): ?>
<?php $related = array_slice(array_filter(recent_products(5), fn($item) => $item['id'] !== $prod['id']), 0, 4); ?>
<?php if ($related): ?>
<section class="container section related-products">
  <div class="section-heading"><h2>More from <em>the oven.</em></h2><a class="text-link" href="<?=h(url('shop'))?>">Full menu →</a></div>
  <div class="product-grid"><?php foreach ($related as $item) render_card($item); ?></div>
</section>
<?php endif; ?>
<?php endif; ?>
