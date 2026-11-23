<?php
declare(strict_types=1);
function db():PDO {static $pdo=null;global $config;if($pdo===null){$dsn='mysql:host='.$config['db_host'].';port='.$config['db_port'].';dbname='.$config['db_name'].';charset=utf8mb4';$pdo=new PDO($dsn,$config['db_user'],$config['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}return $pdo;}
function h(?string $s):string{return htmlspecialchars((string)$s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function url(string $page='home',array $args=[]):string{return 'index.php?'.http_build_query(array_merge(['page'=>$page],$args));}
function redirect(string $page='home',array $args=[]):never {header('Location: '.url($page,$args),true,303);exit;}
function csrf():string {if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_input():string{return '<input type="hidden" name="csrf" value="'.h(csrf()).'">';}
function verify_csrf():void {if(!hash_equals(csrf(),(string)($_POST['csrf']??''))){http_response_code(419);exit('Security token expired. Please go back, refresh and try again.');}}
function auth():?array{return $_SESSION['user']??null;}
function require_login():void{if(!auth()){flash('Please sign in to continue.','warn');redirect('login');}}
function require_admin():void{require_login();if(auth()['role']!=='admin'){http_response_code(403);exit('Access denied.');}}
function flash(string $msg,string $type='success'):void{$_SESSION['flashes'][]=['message'=>$msg,'type'=>$type];}
function flashes():array{$f=$_SESSION['flashes']??[];unset($_SESSION['flashes']);return $f;}
function money(float|int|string $v):string{return number_format((float)$v,2).' JOD';}
function input(string $key,int $max=255):string{return trim(mb_substr((string)($_POST[$key]??''),0,$max));}
function cart():array{return $_SESSION['cart']??[];}
function cart_count():int{return array_sum(array_map('intval',cart()));}
function products_by_ids(array $ids):array{if(!$ids)return []; $ids=array_values(array_filter(array_map('intval',$ids),fn($x)=>$x>0));if(!$ids)return[];$ph=implode(',',array_fill(0,count($ids),'?'));$st=db()->prepare("SELECT * FROM products WHERE active=1 AND id IN ($ph)");$st->execute($ids);return $st->fetchAll();}
function cart_details():array{$items=[];$subtotal=0.0;foreach(products_by_ids(array_keys(cart())) as $prod){$qty=max(1,min(20,(int)(cart()[$prod['id']]??0)));$line=(float)$prod['price']*$qty;$subtotal+=$line;$prod['qty']=$qty;$prod['line_total']=$line;$items[]=$prod;}return ['items'=>$items,'subtotal'=>$subtotal];}
function delivery_fee(string $method, float $subtotal): float {
    global $config;
    return $method === 'pickup' || $subtotal >= 25 ? 0.0 : (float)$config['delivery_fee'];
}

function order_quote(array $items, array $selection): string {
    $quote = [];
    foreach ($items as $item) {
        $quote[(int)$item['id']] = [(int)$selection[$item['id']], money($item['price'])];
    }
    ksort($quote);
    return hash('sha256', json_encode($quote, JSON_THROW_ON_ERROR));
}

function order_status_label(string $status, string $method): string {
    return match ($status) {
        'pending' => 'New',
        'ready' => $method === 'pickup' ? 'Ready for pickup' : 'Ready for delivery',
        default => ucfirst($status),
    };
}

function slug_image(string $key): string {
    $available = ['cake', 'croissant', 'cookies', 'bread', 'cupcake', 'brownie', 'coffee', 'baklava'];
    return 'assets/photos/'.(in_array($key, $available, true) ? $key : 'cake').'.jpg';
}
function get_product(int $id):?array{$st=db()->prepare('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=? AND p.active=1');$st->execute([$id]);return $st->fetch()?:null;}
function recent_products(int $limit=8):array{$st=db()->prepare('SELECT * FROM products WHERE active=1 ORDER BY featured DESC,id DESC LIMIT ?');$st->bindValue(1,$limit,PDO::PARAM_INT);$st->execute();return $st->fetchAll();}
function render_card(array $p):void { ?>
<article class="product-card"><a href="<?=h(url('product',['id'=>$p['id']]))?>" class="product-photo"><img loading="lazy" src="<?=h(slug_image($p['image_key']))?>" alt="<?=h($p['name'])?>"><?php if($p['stock'] < 1): ?><span class="card-tag">Sold out</span><?php endif; ?></a><div class="product-card-body"><a href="<?=h(url('product',['id'=>$p['id']]))?>"><h3><?=h($p['name'])?></h3></a><p class="micro"><?=h($p['short_description'])?></p><div class="product-bottom"><strong><?=h(money($p['price']))?></strong><form method="post" action="<?=h(url('cart'))?>"><?=csrf_input()?><input type="hidden" name="action" value="add"><input type="hidden" name="id" value="<?=intval($p['id'])?>"><button class="round-add" type="submit" <?= $p['stock'] < 1 ? 'disabled' : '' ?> aria-label="Add <?=h($p['name'])?> to cart">Add to bag <span aria-hidden="true">+</span></button></form></div></div></article>
<?php }
