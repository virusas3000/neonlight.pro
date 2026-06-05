<?php
/**
 * Template Name: Lookbook & About Us
 * @package NeonLightHK
 */
get_header();

$lang = nl_lang();
?>

<div class="nl-page nl-about">
	<h1 class="nl-page__title">
		<?php echo nl_t('lookbook_about_title'); ?>
	</h1>

	<!-- Language-specific content -->
	<?php if ( $lang === 'en' ) : ?>

		<div class="nl-page-content nl-about-content">
			<div class="nl-about-section">
				<h2>ABOUT US · 關於我們</h2>
				<p class="nl-intro-en"><strong>NEON LIGHT HK</strong> is a creative studio specializing in neon sign design and production. We are dedicated to crafting unique neon pieces for every client — whether it is commercial signage, event decor, or personal space decoration, we turn your ideas into glowing neon reality.</p>
				<p class="nl-intro-en">In addition to custom neon design, we also offer neon sign rental services and hands-on workshop experiences, allowing more people to discover and fall in love with this distinctive visual art form.</p>
			</div>
			<div class="nl-about-section">
				<h2 class="nl-heading-en">IRREGULart — Creative Experience Events</h2>
				<p class="nl-intro-en"><strong>IRREGULart Creative Experience Events</strong> is an artistic collaboration between JUST BE and IRREGULart. Through a variety of immersive art experiences, we invite you to leave behind the hustle and stress of daily life, reconnect with your purest self in the creative process, and enjoy the present moment.</p>
				<p class="nl-desc-en">「JUST BE」means <em>Just be yourself</em> — letting go of external expectations and limitations, returning to your most authentic state, focusing on the present, and doing what you love. We believe everyone holds unlimited creative potential; all it takes is a relaxed environment to unlock the artistic inspiration within.</p>
				<p class="nl-intro-en"><strong>IRREGULart</strong> stands for <em>irregular art</em> — breaking away from traditional artistic frameworks and constraints through free exploration and experimentation, inspiring participants to discover their own artistic language. We do not pursue cookie-cutter perfection; instead, we encourage everyone to find their own unique form of expression.</p>
				<p class="nl-desc-en">In a world full of rules and standards, we aim to provide a free, pressure-free creative space where you can relax and enjoy the joy and satisfaction that art brings. Every event is a fresh exploration — whether you are an art beginner or a seasoned creator, you will find inspiration and fun here.</p>
				<p class="nl-intro-en"><strong>IRREGULart</strong> will launch a series of exciting workshops covering multiple art forms, led by professional instructors in a relaxed and pleasant atmosphere. Learn new skills and meet like-minded friends. We believe art is not just creation — it is a way of life. Let art become part of your everyday routine and creativity a habit.</p>
				<p class="nl-desc-en">For more event details or to register, please visit our website or follow us on Instagram for the latest updates. We look forward to meeting you at our events and enjoying the creative journey together!</p>
				<div class="nl-cta-en">
					<a href="/workshop/?lang=en" class="wp-block-button__link">Explore Workshops</a>
				</div>
			</div>
		</div>

	<?php elseif ( $lang === 'cn' ) : ?>

		<div class="nl-page-content nl-about-content">
			<div class="nl-about-section">
				<h2>关于我们 · ABOUT US</h2>
				<p class="nl-intro-cn"><strong>NEON LIGHT HK</strong> 是专注于霓虹灯设计及制作的创意工作室。我们致力于为每位客人打造独一无二的霓虹灯作品，无论是商业招牌、活动布置，还是个人空间装饰，都能将你的创意化为闪耀的霓虹光芒。</p>
				<p class="nl-intro-cn">除了客制化霓虹灯设计，我们亦提供霓虹灯租借服务及工作坊体验，让更多人能够接触并喜爱这项独特的视觉艺术。</p>
			</div>
			<div class="nl-about-section">
				<h2 class="nl-heading-cn">IRREGULart 非典型美术体验活动</h2>
				<p class="nl-intro-cn"><strong>「IRREGULart 非典型美术体验活动」</strong> 是 JUST BE 联乘 IRREGULart 共同推出的艺术企划，期望透过不同类型的艺术体验活动，让大家放下日常的繁忙与压力，在创作过程中找回最纯粹的自我，专注当下，享受与自己相处的时光。</p>
				<p class="nl-desc-cn">「JUST BE」意指「Just be yourself」——放下外界的期望与束缚，回归最真实的状态，专注于当下，做自己喜欢的事。我们相信每个人都拥有无限的创意潜能，只需一个放松的环境，便能释放内心的艺术灵感。</p>
				<p class="nl-intro-cn"><strong>IRREGULart</strong> 代表「不规则的艺术」，象征打破传统艺术的框架与限制，透过自由探索与尝试，启发参加者发掘属于自己的艺术语言。我们不追求千篇一律的完美作品，而是鼓励每个人在创作中找到专属的表达方式。</p>
				<p class="nl-desc-cn">在这个充满规则与标准的世界里，我们希望提供一个自由、无压力的创作空间，让大家能够放松心情，享受艺术带来的愉悦与满足感。每次活动都是一次全新的探索，无论你是艺术新手还是创作达人，都能在这里找到属于你的灵感与乐趣。</p>
				<p class="nl-intro-cn"><strong>IRREGULart</strong> 更会推出一系列精彩的工作坊，涵盖多种艺术形式，由专业导师带领，让你在轻松愉快的氛围中学习新技能，并结识志同道合的朋友。我们相信，艺术不仅是一种创作，更是一种生活态度——让艺术融入日常，让创作成为习惯。</p>
				<p class="nl-desc-cn">如欲了解更多活动详情或报名参加，请浏览我们的网站或追踪 Instagram 获取最新资讯。期待在活动中与你见面，一起享受创作的乐趣！</p>
				<div class="nl-cta-cn">
					<a href="/workshop/?lang=cn" class="wp-block-button__link">浏览工作坊</a>
				</div>
			</div>
		</div>

	<?php else : // zh — default Traditional Chinese from DB ?>

		<?php while (have_posts()) : the_post(); ?>
			<div class="nl-page-content nl-about-content">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>

	<?php endif; ?>

	<!-- Lookbook / Gallery Section -->
	<section class="nl-about-section">
		<h2><?php echo nl_t('lookbook_heading'); ?></h2>
		<p class="nl-about-ig">
			<a href="https://instagram.com/neonlight.pro" target="_blank">INSTAGRAM @ NEONLIGHT.PRO</a>
		</p>
		<div class="nl-about-gallery">
			<div class="nl-gallery-grid">
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-1.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-2.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-3.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-4.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-5.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
				<div class="nl-gallery-item">
					<img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-6.jpg" alt="" loading="lazy" onerror="this.style.display='none'">
				</div>
			</div>
		</div>
	</section>

	<!-- Contact Section -->
	<section class="nl-about-section">
		<h2><?php echo nl_t('contact_title'); ?></h2>
		<div class="nl-about-contact">
			<p><strong>Tel:</strong> <a href="tel:+85261319328">61319328</a></p>
			<p><strong>Email:</strong> <a href="mailto:cantopopforyou@gmail.com">cantopopforyou@gmail.com</a></p>
			<p><strong>Address:</strong> <?php echo nl_t('visit_addr1'); ?></p>
		</div>
	</section>
</div>

<style>
.nl-about-content .nl-intro-en,
.nl-about-content .nl-intro-zh,
.nl-about-content .nl-intro-cn,
.nl-about-content .nl-desc-en,
.nl-about-content .nl-desc-zh,
.nl-about-content .nl-desc-cn,
.nl-about-content .nl-upcycle-en,
.nl-about-content .nl-upcycle-zh,
.nl-about-content .nl-upcycle-cn {
	font-size: 1.05rem;
	line-height: 1.7;
	margin-bottom: 16px;
}
.nl-about-content .nl-heading-en,
.nl-about-content .nl-heading-zh,
.nl-about-content .nl-heading-cn {
	font-size: 1.4rem;
	font-weight: 600;
	margin: 32px 0 16px;
	color: #111;
}
.nl-about-content .nl-programs-en,
.nl-about-content .nl-programs-zh,
.nl-about-content .nl-programs-cn {
	margin: 0 0 24px 20px;
	padding: 0;
	list-style: disc;
}
.nl-about-content .nl-programs-en li,
.nl-about-content .nl-programs-zh li,
.nl-about-content .nl-programs-cn li {
	margin-bottom: 6px;
	font-size: 1rem;
}
.nl-about-content .nl-cta-en,
.nl-about-content .nl-cta-zh,
.nl-about-content .nl-cta-cn {
	margin: 24px 0 32px;
}
.nl-about-content .wp-block-button__link {
	display: inline-block;
	padding: 12px 28px;
	background: #00a896;
	color: #fff;
	border-radius: 999px;
	font-weight: 500;
	text-decoration: none;
	font-size: 1rem;
}
.nl-about-content .wp-block-button__link:hover {
	background: #008c7a;
}
.nl-gallery-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 12px;
	margin: 24px 0;
}
.nl-gallery-item img {
	width: 100%;
	height: auto;
	border-radius: 8px;
	object-fit: cover;
	aspect-ratio: 4/3;
	transition: transform 0.2s;
}
.nl-gallery-item a:hover img {
	transform: scale(1.03);
}
@media (max-width: 768px) {
	.nl-gallery-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
	.nl-gallery-grid { grid-template-columns: 1fr; }
}
</style>

<?php get_footer(); ?>
