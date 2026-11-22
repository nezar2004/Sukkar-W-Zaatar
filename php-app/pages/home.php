<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <p class="eyebrow">Artisan bakery · Irbid, Jordan</p>
      <h1>Fresh from<br> <em>our oven.</em></h1>
      <p>Buttery pastries, generous slices and the sweet things worth sharing. Baked in Irbid and delivered to your door.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?=h(url('shop'))?>">Explore the menu <span aria-hidden="true">↗</span></a>
        <a class="text-link" href="<?=h(url('about'))?>">Our story <span aria-hidden="true">→</span></a>
      </div>
    </div>
    <div class="hero-art">
      <img src="assets/photos/bakery-table.jpg" alt="Golden croissants and pistachio pastries on a bakery table" fetchpriority="high">
    </div>
  </div>
</section>

<?php
$categoryPhotos = ['cakes' => 'cake', 'pastries' => 'croissant', 'cookies' => 'cookies', 'bread' => 'bread', 'drinks' => 'coffee'];
$collections = db()->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
?>
<?php if ($collections): ?>
<section class="container section collections">
  <div class="section-heading"><div><p class="eyebrow">The bakery counter</p><h2>Browse by <em>collection.</em></h2></div></div>
  <div class="collection-grid">
    <?php foreach ($collections as $collection): ?>
      <a href="<?=h(url('shop', ['category' => $collection['id']]))?>" class="collection">
        <img loading="lazy" src="<?=h(slug_image($categoryPhotos[strtolower($collection['name'])] ?? 'cake'))?>" alt="">
        <span><?=h($collection['name'])?> <span aria-hidden="true">↗</span></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="container section favorites">
  <div class="section-heading">
    <div><p class="eyebrow">From the bakery</p><h2>Freshly baked <em>favorites.</em></h2></div>
    <a class="text-link" href="<?=h(url('shop'))?>">See the full menu <span aria-hidden="true">→</span></a>
  </div>
  <div class="product-grid"><?php foreach(recent_products(4) as $p) render_card($p); ?></div>
</section>

<section class="story-band">
  <div class="container story-grid">
    <div class="story-photo"><img loading="lazy" src="assets/photos/bread.jpg" alt="Flour-dusted country loaf with a golden crust"></div>
    <div class="story-copy">
      <p class="eyebrow">Our story</p>
      <h2>Made for <em>sharing.</em></h2>
      <p>Inspired by the neighborhood bakeries of Jordan, Sukkar &amp; Zaatar brings familiar flavors and freshly baked favorites to the table.</p>
      <a class="btn btn-secondary" href="<?=h(url('about'))?>">Meet the bakery <span aria-hidden="true">↗</span></a>
    </div>
  </div>
</section>

<section class="container section service-grid" id="delivery">
  <article>
    <h2>Delivery &amp; <em>pickup.</em></h2>
    <p>Pay when your order arrives in Irbid. Delivery is 2 JOD and free on orders of 25 JOD or more. Store pickup is free.</p>
    <a class="text-link" href="<?=h(url('shop'))?>">Start an order <span aria-hidden="true">→</span></a>
  </article>
  <article>
    <h2>Something to <em>celebrate?</em></h2>
    <p>Tell us what you're celebrating, how many guests you're expecting and when you need it.</p>
    <a class="text-link" href="<?=h(url('contact', ['topic' => 'custom']))?>#contact-form">Ask about a custom order <span aria-hidden="true">→</span></a>
  </article>
</section>
<section class="container section bakery-faq">
  <div><p class="eyebrow">Before you order</p><h2>Good to <em>know.</em></h2></div>
  <div class="faq-items">
    <details><summary>How much is delivery?</summary><p>Delivery in Irbid is 2 JOD. Orders of 25 JOD or more qualify for free delivery.</p></details>
    <details><summary>Can I pick up my order?</summary><p>Yes. Choose store pickup at checkout to remove the delivery fee.</p></details>
    <details><summary>Can I request something for an occasion?</summary><p><a href="<?=h(url('contact', ['topic' => 'custom']))?>#contact-form">Send us a message</a> with your preferred date, number of guests and what you have in mind.</p></details>
  </div>
</section>

