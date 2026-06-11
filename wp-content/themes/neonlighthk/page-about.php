<?php
/**
 * Template Name: About Us
 * @package NeonLightHK
 */
get_header();
?>

<main id="primary" class="site-main nl-about-page">

    <div class="nl-page-header">
        <h1 class="nl-page-title"><?php
            if (nl_lang()==='zh') { echo '關於我們'; }
            elseif (nl_lang()==='cn') { echo '关于我们'; }
            else { echo 'ABOUT US'; }
        ?></h1>
    </div>

    <section class="nl-section">
        <div class="nl-section__inner">
            <div class="nl-about-intro">
<?php if (nl_lang() === 'en') : ?>
    <p class="nl-about-intro__text">JUST BE (Art and Culture Workshop) is located in the 1868 Art Village in Ma Wan, while IRREGULart is located in Tsim Sha Tsui, Central, and Stanley. The workshops offer a variety of diverse art courses, guiding participants to unleash their creativity and enhance their artistic expression. They also provide one-stop services such as neon light customization, event coordination, venue balloon decoration, and magic shows, making them popular with families, couples, friends' gatherings, birthday parties, schools, businesses, and various organizations.</p>

    <h2 class="nl-about-programs__heading">ART WORKSHOP PROGRAMS</h2>
    <p class="nl-about-intro__text">JUST BE and IRREGULart offer a diverse range of art and culture workshops:</p>
    <p class="nl-programs-list">Tie-dyeing | Turkish mosaic lamp making | Turkish coffee experience | Art jamming | Fluid painting | Stained glass nightlights | Floating vases | Eco-friendly art creation | TUFTING | Neon light art | Perler bead crafts | Film photography and developing | Hanfu experience | Chinese painting | Fashion design | Professional makeup courses | Painting, ink painting, and watercolor courses | Handicrafts | Paper crafts | Handmade weaving | Parent-child workshops | STEM courses | Electronic keyboard, handpan, guitar, Hawaiian guitar, Indian accordion, Indian mridangam drum experience | Intangible cultural heritage lacquer fan making | Parent-child cooking classes | Professional coffee roasting | Cookie, specialty cheesecake, traditional mooncake, and snow skin mooncake making</p>
    <p class="nl-about-intro__text">Outreach services, customized solutions, and on-site customization services are also available, covering various intangible cultural heritage themed workshops.</p>

    <p class="nl-about-intro__text">The workshop regularly offers various themed workshops, allowing participants to create unique crafts by hand and connect with diverse global art and culture through hands-on experience. A complete set of craft materials is provided, enabling participants to personally experience the entire process from material selection and design to the finished product. Completed pieces can be taken home, adding a personal touch to daily life.</p>
    <p class="nl-about-intro__text">Customers can also bring their own old utensils for upcycling. Through simple craft techniques, old items are given a new look, giving furniture and old objects a second life, allowing meaningful items to retain unique memories.</p>
    <p class="nl-about-cta">Interested? Inquire and register now!</p>

<?php elseif (nl_lang() === 'cn') : ?>
    <p class="nl-about-intro__text">JUST BE（艺术文化工作坊）位于马湾1868的艺术村、IRREGULart位于尖沙咀、中环以及赤柱，工作坊开设各式多元化艺术课程，既可引导学员发挥创意、提升艺术表达能力，亦提供霓虹灯订制、活动统筹、场地气球布置及魔术表演等一站式服务，深受亲子活动、情侣约会、好友聚会、生日派对、学校、企业及各类团体机构青睐。</p>

    <h2 class="nl-about-programs__heading">课程项目</h2>
    <p class="nl-about-intro__text">JUST BE、IRREGULart艺术文化工作坊课程种类丰富多元：</p>
    <p class="nl-programs-list">彩色扎染｜土耳其马赛克灯制作｜土耳其咖啡体验｜Art jamming｜流体画｜玻璃彩绘小夜灯｜浮游花瓶｜环保艺术创作｜TUFTING簇绒｜霓虹冷光线艺术｜拼豆手作｜菲林摄影连冲晒｜汉服体验｜中国画｜服装设计｜专业化妆课程｜绘画、水墨画、水彩课程｜手工劳作｜纸艺工艺｜手作编织｜亲子专属工作坊｜STEM科创课程｜电子琴、手碟、结他、夏威夷小结他、印度手风琴、印度密当加鼓乐器体验｜非遗飘漆扇制作｜亲子小厨师烹饪课｜职人咖啡烘焙｜曲奇｜特色芝士蛋糕｜传统月饼及冰皮月饼手作</p>
    <p class="nl-about-intro__text">另备外展服务、客制化方案及上门定制服务，同时涵盖各类非遗主题工作坊。</p>

    <p class="nl-about-intro__text">工作坊定期推出各式主题体验课，学员可在课堂亲手打造独一无二的特色工艺，藉手作接触全球多元艺术文化。场内备齐全套手作原材料，由选料、设计到成品全流程亲自动手制作，完成作品可自行带走，为日常增添专属仪式感。</p>
    <p class="nl-about-intro__text">顾客亦可自备旧器皿到场改造，透过简单手作技法为旧物重塑全新样貌，赋予家俬、旧物件二次价值，让具纪念意义的旧物重焕生机、留存独特回忆。</p>
    <p class="nl-about-cta">心动就快查询报名啦</p>

<?php else : // zh ?>
    <p class="nl-about-intro__text">JUST BE（藝術文化工作坊）位於馬灣1868的藝術村、IRREGULart位於尖沙咀、中環以及赤柱，工作坊開設各式多元化藝術課程，既可引導學員發揮創意、提升藝術表達能力，亦提供霓虹燈訂製、活動統籌、場地氣球佈置及魔術表演等一站式服務，深受親子活動、情侶約會、好友聚會、生日派對、學校、企業及各類團體機構青睞。</p>

    <h2 class="nl-about-programs__heading">課程項目</h2>
    <p class="nl-about-intro__text">JUST BE、IRREGULart藝術文化工作坊課程種類豐富多元：</p>
    <p class="nl-programs-list">彩色扎染｜土耳其馬賽克燈製作｜土耳其咖啡體驗｜Art jamming｜流體畫｜玻璃彩繪小夜燈｜浮游花瓶｜環保藝術創作｜TUFTING簇絨｜霓虹冷光線藝術｜拼豆手作｜菲林攝影連沖曬｜漢服體驗｜中國畫｜服裝設計｜專業化妝課程｜繪畫、水墨畫、水彩課程｜手工勞作｜紙藝工藝｜手作編織｜親子專屬工作坊｜STEM科創課程｜電子琴、手碟、結他、夏威夷小結他、印度手風琴、印度密當加鼓樂器體驗｜非遺飄漆扇製作｜親子小廚師烹飪課｜職人咖啡烘焙｜曲奇｜特色芝士蛋糕｜傳統月餅及冰皮月餅手作</p>
    <p class="nl-about-intro__text">另備外展服務、客製化方案及上門定制服務，同時涵蓋各類非遺主題工作坊。</p>

    <p class="nl-about-intro__text">工作坊定期推出各式主題體驗課，學員可在課堂親手打造獨一無二的特色工藝，藉手作接觸全球多元藝術文化。場內備齊全套手作原材料，由選料、設計到成品全流程親自動手製作，完成作品可自行帶走，為日常增添專屬儀式感。</p>
    <p class="nl-about-intro__text">顧客亦可自備舊器皿到場改造，透過簡單手作技法為舊物重塑全新樣貌，賦予傢俬、舊物件二次價值，讓具紀念意義的舊物重煥生機、留存獨特回憶。</p>
    <p class="nl-about-cta">心動就快查詢報名啦</p>
<?php endif; ?>
            </div>
        </div>
    </section>
</main>

<style>
.nl-about-page .nl-section{max-width:900px;margin:0 auto;padding:40px 20px}
.nl-about-intro{text-align:center;margin-bottom:48px}
.nl-about-intro__text{font-size:1.15rem;line-height:1.8;color:#222;margin-bottom:16px}
.nl-about-programs__heading{font-size:1.5rem;font-weight:700;margin:32px 0 24px;color:#111;text-align:center}
.nl-programs-list{font-size:1rem;line-height:1.9;color:#444;margin:16px 0;padding:16px 20px;background:#f8f8f8;border-radius:12px}
.nl-about-cta{font-size:1.2rem;font-weight:700;color:#111;margin-top:32px;text-align:center}
@media (max-width:768px){
    .nl-about-page .nl-section{padding:24px 16px}
    .nl-about-intro__text{font-size:1rem}
    .nl-about-programs__heading{font-size:1.25rem}
    .nl-programs-list{padding:12px 14px}
    .nl-about-cta{font-size:1.1rem}
}
</style>

<?php get_footer(); ?>
