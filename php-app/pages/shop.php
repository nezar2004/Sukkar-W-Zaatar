<?php
$category = max(0, (int) ($_GET['category'] ?? 0));
$search = trim(mb_substr((string) ($_GET['q'] ?? ''), 0, 100));
$sortOptions = [
    'featured' => ['Featured', 'featured DESC, id DESC'],
    'price-low' => ['Price: low to high', 'price ASC, id DESC'],
    'price-high' => ['Price: high to low', 'price DESC, id DESC'],
    'newest' => ['Newest first', 'id DESC'],
];
$sort = (string) ($_GET['sort'] ?? 'featured');
if (!isset($sortOptions[$sort])) $sort = 'featured';
$categories = db()->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
$sql = 'SELECT * FROM products WHERE active = 1';
$params = [];
if ($category) {
    $sql .= ' AND category_id = ?';
    $params[] = $category;
}
if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR short_description LIKE ?)';
    $params[] = '%'.$search.'%';
    $params[] = '%'.$search.'%';
}
$statement = db()->prepare($sql.' ORDER BY '.$sortOptions[$sort][1]);
$statement->execute($params);
$products = $statement->fetchAll();
$filterArgs = ['q' => $search, 'sort' => $sort];
?>
<section class="page-heading catalog-heading">
  <div class="container">
    <p class="eyebrow">The bakery counter</p>
    <h1>Shop the <em>bakery.</em></h1>
    <p>Flaky layers, tender cakes and fresh bread. Find something for your table.</p>
  </div>
</section>
<section class="container section shop-section">
  <nav class="filter-chips" aria-label="Product categories">
    <a class="chip <?= $category === 0 ? 'selected' : '' ?>" href="<?=h(url('shop', $filterArgs))?>">All treats</a>
    <?php foreach ($categories as $item): ?>
      <a class="chip <?= $category === (int) $item['id'] ? 'selected' : '' ?>" href="<?=h(url('shop', $filterArgs + ['category' => $item['id']]))?>"><?=h($item['name'])?></a>
    <?php endforeach; ?>
  </nav>
  <div class="shop-controls">
    <p class="result-count"><?=count($products)?> <?=count($products) === 1 ? 'product' : 'products'?></p>
    <form method="get" class="catalog-tools">
      <input type="hidden" name="page" value="shop">
      <?php if ($category): ?><input type="hidden" name="category" value="<?=$category?>"><?php endif; ?>
      <label class="sr-only" for="catalog-search">Search menu</label>
      <input id="catalog-search" type="search" name="q" value="<?=h($search)?>" placeholder="Search the bakery">
      <label class="sr-only" for="catalog-sort">Sort products</label>
      <select id="catalog-sort" name="sort">
        <?php foreach ($sortOptions as $key => [$label]): ?>
          <option value="<?=h($key)?>" <?= $sort === $key ? 'selected' : '' ?>><?=h($label)?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="small-btn">Apply</button>
    </form>
  </div>
  <?php if ($products): ?>
    <div class="product-grid <?= count($products) >= 6 && !$category && $search === '' && $sort === 'featured' ? 'editorial-grid' : '' ?>">
      <?php foreach ($products as $product) render_card($product); ?>
    </div>
  <?php else: ?>
    <div class="empty-state"><h2>No treats found</h2><p>Try another search or browse the full menu.</p><a class="btn btn-primary" href="<?=h(url('shop'))?>">Browse all</a></div>
  <?php endif; ?>
</section>
