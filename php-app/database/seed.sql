USE sukkar_zaatar;
INSERT INTO categories (id,name) VALUES (1,'Cakes'),(2,'Pastries'),(3,'Cookies'),(4,'Bread'),(5,'Drinks') ON DUPLICATE KEY UPDATE name=VALUES(name);
INSERT INTO products (id,category_id,name,short_description,description,price,stock,image_key,featured) VALUES
(1,1,'Strawberry Cloud Cake','Light, fluffy & berry lovely','Soft vanilla sponge, silky cream and a bright layer of fresh strawberry flavor. A celebration in every slice.',8.50,25,'cake',1),
(2,2,'Butter Kiss Croissant','Golden & perfectly flaky','All-butter croissant with beautiful crisp layers and a soft heart. Your morning deserves this.',2.25,48,'croissant',1),
(3,3,'Chocolate Chunk Cookies','Soft center, happy heart','Fresh-baked cookies with generous chocolate chunks and a golden edge.',3.00,60,'cookies',1),
(4,4,'Rustic Country Loaf','Simple & wholesome','Slow-risen artisan loaf with a crisp crust and a satisfying, tender center.',3.75,19,'bread',0),
(5,1,'Vanilla Dream Cupcake','A little cloud of joy','Tender vanilla cupcake crowned with a swirl of smooth buttercream.',2.00,35,'cupcake',0),
(6,1,'Fudge Brownie Bliss','Rich, fudgy indulgence','Deep chocolate brownie with a decadent soft center. Perfect with a warm drink.',2.75,40,'brownie',1),
(7,5,'Signature Iced Latte','Your daily pick-me-up','Chilled espresso with velvety milk for a refreshing little coffee break.',2.50,40,'coffee',0),
(8,2,'Pistachio Baklava Bites','A classic with a twist','Crisp golden pastry, delicate syrup and delicious pistachio-inspired notes.',4.25,30,'baklava',0)
ON DUPLICATE KEY UPDATE name=VALUES(name),short_description=VALUES(short_description),description=VALUES(description),price=VALUES(price),stock=VALUES(stock),image_key=VALUES(image_key),featured=VALUES(featured);
