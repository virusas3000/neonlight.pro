<?php
/**
 * Template Name: Hanfu
 *
 * @package NeonLightHK
 */

get_header();
$lang = nl_lang();
?>

<style>
.nl-customise-hero {
	position: relative;
	width: 100%;
	min-height: 420px;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #0a0a0a;
	overflow: hidden;
}
.nl-customise-hero__bg {
	position: absolute;
	inset: 0;
	background-size: cover;
	background-position: center;
	opacity: 0.45;
}
.nl-customise-hero__overlay {
	position: relative;
	z-index: 2;
	text-align: center;
	color: #fff;
	padding: 60px 20px;
}
.nl-customise-hero__subtitle {
	font-size: 15px;
	letter-spacing: 6px;
	text-transform: uppercase;
	margin-bottom: 12px;
	color: #fff;
	font-weight: 400;
}
.nl-customise-hero__title {
	font-size: clamp(36px, 7vw, 72px);
	font-weight: 700;
	letter-spacing: 10px;
	text-transform: uppercase;
	margin: 0 0 10px;
	line-height: 1.1;
}
.nl-customise-hero__tagline {
	font-size: 14px;
	letter-spacing: 5px;
	text-transform: uppercase;
	color: #ccc;
	font-weight: 400;
}

.nl-customise-cards {
	background: #f5f5f5;
	padding: 60px 20px;
}
.nl-customise-cards__wrap {
	max-width: 1100px;
	margin: 0 auto;
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 32px;
}
@media (max-width: 768px) {
	.nl-customise-cards__wrap {
		grid-template-columns: 1fr;
		gap: 24px;
	}
}

.nl-customise-card {
	background: #fff;
	padding: 36px 32px 40px;
	text-align: center;
	border: 1px solid #e5e5e5;
}
.nl-customise-card__heading-cn {
	font-size: 20px;
	font-weight: 600;
	margin-bottom: 4px;
	color: #111;
}
.nl-customise-card__heading-en {
	font-size: 13px;
	letter-spacing: 4px;
	text-transform: uppercase;
	color: #666;
	margin-bottom: 20px;
}
.nl-customise-card__image {
	width: 100%;
	height: 220px;
	object-fit: cover;
	margin-bottom: 20px;
	background: #eee;
}
.nl-customise-card__text {
	font-size: 14px;
	line-height: 1.8;
	color: #444;
	margin-bottom: 16px;
}
.nl-customise-card__text p {
	margin: 0 0 6px;
}
.nl-customise-card__divider {
	width: 40px;
	height: 2px;
	background: #111;
	margin: 16px auto;
}
.nl-customise-card__contact {
	font-size: 14px;
	color: #666;
	margin-bottom: 24px;
	line-height: 1.7;
}
.nl-customise-card__contact a {
	color: #666;
	text-decoration: none;
}
.nl-customise-card__btn {
	display: inline-block;
	background: #00d4aa;
	color: #000;
	font-size: 13px;
	font-weight: 700;
	letter-spacing: 3px;
	text-transform: uppercase;
	padding: 14px 36px;
	text-decoration: none;
	transition: background .2s;
}
.nl-customise-card__btn:hover {
	background: #00b892;
}
</style>

<div class="nl-customise-hero">
	<div class="nl-customise-hero__bg" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/hanfu-hero.jpg');"></div>
	<div class="nl-customise-hero__overlay">
		<div class="nl-customise-hero__subtitle"><?php echo $lang === 'en' ? 'Traditional Chinese Clothing' : '傳統漢服體驗'; ?></div>
		<h1 class="nl-customise-hero__title"><?php echo nl_t('nav_hanfu'); ?></h1>
		<div class="nl-customise-hero__tagline"><?php echo $lang === 'en' ? 'RENTAL · PHOTOSHOOT · EVENTS' : '租借 · 拍攝 · 活動'; ?></div>
	</div>
</div>

<!-- Page Title -->
<h1 class="nl-page__title"><?php echo nl_t('nav_hanfu'); ?></h1>

<div class="nl-customise-cards">
	<div class="nl-customise-cards__wrap">

		<!-- Card 1 -->
		<div class="nl-customise-card">
			<div class="nl-customise-card__heading-cn"><?php echo $lang === 'en' ? '來圖訂製' : '來圖訂製'; ?></div>
			<div class="nl-customise-card__heading-en"><?php echo $lang === 'en' ? 'SEND US YOUR DESIGN' : 'SEND US YOUR DESIGN'; ?></div>
			<img class="nl-customise-card__image" src="<?php echo get_template_directory_uri(); ?>/assets/images/hanfu-custom-1.jpg" alt="Hanfu custom design" onerror="this.style.display='none'">
			<div class="nl-customise-card__text">
				<?php if ($lang === 'en') : ?>
					<p>Welcome to send us your designs</p>
					<p>We will prepare the hanfu according to your artwork</p>
					<p>(File formats: .ai / .ps / .jpg)</p>
				<?php else : ?>
					<p>歡迎將設計圖發給我們</p>
					<p>我們會跟據你的圖檔製作漢服</p>
					<p>（檔案模式：.ai / .ps / .jpg）</p>
				<?php endif; ?>
			</div>
			<div class="nl-customise-card__divider"></div>
			<div class="nl-customise-card__contact">
				61319328<br>
				<a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a>
			</div>
			<a href="mailto:www.neonlight.pro@gmail.com?subject=Hanfu%20Quote%20Request" class="nl-customise-card__btn">
				<?php echo $lang === 'en' ? 'QUOTE' : '報價'; ?>
			</a>
		</div>

		<!-- Card 2 -->
		<div class="nl-customise-card">
			<div class="nl-customise-card__heading-cn"><?php echo $lang === 'en' ? '設計服務' : '設計服務'; ?></div>
			<div class="nl-customise-card__heading-en"><?php echo $lang === 'en' ? 'DESIGN SERVICE' : 'DESIGN SERVICE'; ?></div>
			<img class="nl-customise-card__image" src="<?php echo get_template_directory_uri(); ?>/assets/images/hanfu-custom-2.jpg" alt="Hanfu design service" onerror="this.style.display='none'">
			<div class="nl-customise-card__text">
				<?php if ($lang === 'en') : ?>
					<p>Welcome to send us references or let us know your concept idea</p>
					<p>We will custom design the hanfu for you</p>
				<?php else : ?>
					<p>歡迎跟我們說出你的概念或想法</p>
					<p>亦可以將參考圖發給我們</p>
					<p>我們的設計團隊會為你度身製作</p>
				<?php endif; ?>
			</div>
			<div class="nl-customise-card__divider"></div>
			<div class="nl-customise-card__contact">
				61319328<br>
				<a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a>
			</div>
			<a href="mailto:www.neonlight.pro@gmail.com?subject=Hanfu%20Design%20Service" class="nl-customise-card__btn">
				<?php echo $lang === 'en' ? 'QUOTE' : '報價'; ?>
			</a>
		</div>

	</div>
</div>

<?php get_footer(); ?>
