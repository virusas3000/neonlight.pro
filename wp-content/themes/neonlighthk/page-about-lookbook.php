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
		<?php if ($lang === 'en') : ?>LOOKBOOK &amp; ABOUT US
		<?php elseif ($lang === 'cn') : ?>范例 &amp; 关于我们
		<?php else : ?>範例 &amp; 關於我們
		<?php endif; ?>
	</h1>

	<!-- About Us Section -->
	<section class="nl-about-section">
		<h2 class="nl-heading">
			<?php if ($lang === 'en') : ?>ABOUT US
			<?php elseif ($lang === 'cn') : ?>关于我们
			<?php else : ?>關於我們
			<?php endif; ?>
		</h2>

<?php if ($lang === 'en') : ?>
		<p class="nl-body">JUST BE (Art and Culture Workshop) is located in the 1868 Art Village in Ma Wan, while IRREGULart is located in Tsim Sha Tsui, Central, and Stanley. The workshops offer a variety of diverse art courses, guiding participants to unleash their creativity and enhance their artistic expression. They also provide one-stop services such as neon light customization, event coordination, venue balloon decoration, and magic shows, making them popular with families, couples, friends' gatherings, birthday parties, schools, businesses, and various organizations.</p>

		<h3 class="nl-subheading">ART WORKSHOP PROGRAMS</h3>
		<p class="nl-body">JUST BE and IRREGULart offer a diverse range of art and culture workshops:</p>
		<p class="nl-programs-list">Tie-dyeing | Turkish mosaic lamp making | Turkish coffee experience | Art jamming | Fluid painting | Stained glass nightlights | Floating vases | Eco-friendly art creation | TUFTING | Neon light art | Perler bead crafts | Film photography and developing | Hanfu experience | Chinese painting | Fashion design | Professional makeup courses | Painting, ink painting, and watercolor courses | Handicrafts | Paper crafts | Handmade weaving | Parent-child workshops | STEM courses | Electronic keyboard, handpan, guitar, Hawaiian guitar, Indian accordion, Indian mridangam drum experience | Intangible cultural heritage lacquer fan making | Parent-child cooking classes | Professional coffee roasting | Cookie, specialty cheesecake, traditional mooncake, and snow skin mooncake making</p>
		<p class="nl-body">Outreach services, customized solutions, and on-site customization services are also available, covering various intangible cultural heritage themed workshops.</p>

		<p class="nl-body">The workshop regularly offers various themed workshops, allowing participants to create unique crafts by hand and connect with diverse global art and culture through hands-on experience. A complete set of craft materials is provided, enabling participants to personally experience the entire process from material selection and design to the finished product. Completed pieces can be taken home, adding a personal touch to daily life.</p>
		<p class="nl-body">Customers can also bring their own old utensils for upcycling. Through simple craft techniques, old items are given a new look, giving furniture and old objects a second life, allowing meaningful items to retain unique memories.</p>
		<p class="nl-cta">Interested? Inquire and register now!</p>

<?php elseif ($lang === 'cn') : ?>
		<p class="nl-body">JUST BE（艺术文化工作坊）位于马湾1868的艺术村、IRREGULart位于尖沙咀、中环以及赤柱，工作坊开设各式多元化艺术课程，既可引导学员发挥创意、提升艺术表达能力，亦提供霓虹灯订制、活动统筹、场地气球布置及魔术表演等一站式服务，深受亲子活动、情侣约会、好友聚会、生日派对、学校、企业及各类团体机构青睐。</p>

		<h3 class="nl-subheading">课程项目</h3>
		<p class="nl-body">JUST BE、IRREGULart艺术文化工作坊课程种类丰富多元：</p>
		<p class="nl-programs-list">彩色扎染｜土耳其马赛克灯制作｜土耳其咖啡体验｜Art jamming｜流体画｜玻璃彩绘小夜灯｜浮游花瓶｜环保艺术创作｜TUFTING簇绒｜霓虹冷光线艺术｜拼豆手作｜菲林摄影连冲晒｜汉服体验｜中国画｜服装设计｜专业化妆课程｜绘画、水墨画、水彩课程｜手工劳作｜纸艺工艺｜手作编织｜亲子专属工作坊｜STEM科创课程｜电子琴、手碟、结他、夏威夷小结他、印度手风琴、印度密当加鼓乐器体验｜非遗飘漆扇制作｜亲子小厨师烹饪课｜职人咖啡烘焙｜曲奇｜特色芝士蛋糕｜传统月饼及冰皮月饼手作</p>
		<p class="nl-body">另备外展服务、客制化方案及上门定制服务，同时涵盖各类非遗主题工作坊。</p>

		<p class="nl-body">工作坊定期推出各式主题体验课，学员可在课堂亲手打造独一无二的特色工艺，藉手作接触全球多元艺术文化。场内备齐全套手作原材料，由选料、设计到成品全流程亲自动手制作，完成作品可自行带走，为日常增添专属仪式感。</p>
		<p class="nl-body">顾客亦可自备旧器皿到场改造，透过简单手作技法为旧物重塑全新样貌，赋予家俬、旧物件二次价值，让具纪念意义的旧物重焕生机、留存独特回忆。</p>
		<p class="nl-cta">心动就快查询报名啦</p>

<?php else : // zh ?>
		<p class="nl-body">JUST BE（藝術文化工作坊）位於馬灣1868的藝術村、IRREGULart位於尖沙咀、中環以及赤柱，工作坊開設各式多元化藝術課程，既可引導學員發揮創意、提升藝術表達能力，亦提供霓虹燈訂製、活動統籌、場地氣球佈置及魔術表演等一站式服務，深受親子活動、情侶約會、好友聚會、生日派對、學校、企業及各類團體機構青睞。</p>

		<h3 class="nl-subheading">課程項目</h3>
		<p class="nl-body">JUST BE、IRREGULart藝術文化工作坊課程種類豐富多元：</p>
		<p class="nl-programs-list">彩色扎染｜土耳其馬賽克燈製作｜土耳其咖啡體驗｜Art jamming｜流體畫｜玻璃彩繪小夜燈｜浮游花瓶｜環保藝術創作｜TUFTING簇絨｜霓虹冷光線藝術｜拼豆手作｜菲林攝影連沖曬｜漢服體驗｜中國畫｜服裝設計｜專業化妝課程｜繪畫、水墨畫、水彩課程｜手工勞作｜紙藝工藝｜手作編織｜親子專屬工作坊｜STEM科創課程｜電子琴、手碟、結他、夏威夷小結他、印度手風琴、印度密當加鼓樂器體驗｜非遺飄漆扇製作｜親子小廚師烹飪課｜職人咖啡烘焙｜曲奇｜特色芝士蛋糕｜傳統月餅及冰皮月餅手作</p>
		<p class="nl-body">另備外展服務、客製化方案及上門定制服務，同時涵蓋各類非遺主題工作坊。</p>

		<p class="nl-body">工作坊定期推出各式主題體驗課，學員可在課堂親手打造獨一無二的特色工藝，藉手作接觸全球多元藝術文化。場內備齊全套手作原材料，由選料、設計到成品全流程親自動手製作，完成作品可自行帶走，為日常增添專屬儀式感。</p>
		<p class="nl-body">顧客亦可自備舊器皿到場改造，透過簡單手作技法為舊物重塑全新樣貌，賦予傢俬、舊物件二次價值，讓具紀念意義的舊物重煥生機、留存獨特回憶。</p>
		<p class="nl-cta">心動就快查詢報名啦</p>
<?php endif; ?>
	</section>

	<!-- Lookbook / Gallery Section -->
	<section class="nl-about-section">
		<p class="nl-about-ig">
			<a href="https://instagram.com/neonlight.pro" target="_blank">INSTAGRAM @ NEONLIGHT.PRO</a>
		</p>
		<div class="nl-about-gallery">
			<div class="nl-gallery-grid">
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-1.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-2.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-3.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-4.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-5.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
				<div class="nl-gallery-item"><img src="/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-6.jpg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
			</div>
		</div>
	</section>

</div>

<style>
.nl-about-section { margin-bottom: 48px; }
.nl-heading {
	font-size: 1.4rem;
	font-weight: 600;
	margin: 0 0 16px;
	color: #111;
}
.nl-subheading {
	font-size: 1.2rem;
	font-weight: 600;
	margin: 32px 0 12px;
	color: #111;
}
.nl-body {
	font-size: 1.05rem;
	line-height: 1.8;
	margin-bottom: 16px;
	color: #333;
}
.nl-programs-list {
	font-size: 1rem;
	line-height: 1.9;
	color: #444;
	margin: 16px 0;
	padding: 16px 20px;
	background: #f8f8f8;
	border-radius: 12px;
}
.nl-cta {
	font-size: 1.2rem;
	font-weight: 700;
	color: #111;
	margin-top: 32px;
	text-align: center;
}
.nl-about-ig { text-align: center; margin-bottom: 24px; }
.nl-about-ig a { color: #00a896; font-weight: 500; }
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
}
.nl-about-contact { line-height: 1.8; }
.nl-about-contact a { color: #00a896; }
@media (max-width: 768px) {
	.nl-gallery-grid { grid-template-columns: repeat(2, 1fr); }
	.nl-body { font-size: 1rem; }
	.nl-programs-list { padding: 12px 14px; }
}
@media (max-width: 480px) {
	.nl-gallery-grid { grid-template-columns: 1fr; }
}
</style>

<?php get_footer(); ?>
