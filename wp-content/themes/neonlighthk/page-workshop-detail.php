<?php
/**
 * Template Name: Workshop Detail
 * @package NeonLightHK
 */
get_header();

$workshop_id = sanitize_text_field($_GET['id'] ?? '');

$workshops = [
    ['id'=>'neon-eng','title'=>'霓虹燈英文潦草','title_en'=>'Neon English Cursive','subtitle'=>'8cm height · 冷光線藝術工作坊','duration'=>'2 hr','price'=>328,'price_display'=>'HK$328 / person','image'=>'workshop-neon.jpg'],
    ['id'=>'neon-cn','title'=>'霓虹燈單字中文','title_en'=>'Neon Chinese Character','subtitle'=>'12cm height · 圈底連中文 · 冷光線藝術工作坊','duration'=>'2 hr','price'=>398,'price_display'=>'HK$398 / person','image'=>'workshop-neon.jpg'],
    ['id'=>'neon-art','title'=>'霓虹燈 Art Jamming','title_en'=>'Neon Art Jamming','subtitle'=>'20x20cm · 冷光線藝術工作坊','duration'=>'2 hr','price'=>498,'price_display'=>'HK$498 / person','image'=>'workshop-neon.jpg'],
    ['id'=>'neon-pixel','title'=>'霓虹燈拼豆','title_en'=>'Neon Pixel Beads','subtitle'=>'10x20cm / 15x15cm · 冷光線藝術工作坊','duration'=>'2 hr','price'=>568,'price_display'=>'HK$568 / person','image'=>'workshop-neon.jpg'],
];

$ws = null;
foreach ($workshops as $w) {
    if ($w['id'] === $workshop_id) { $ws = $w; break; }
}

if (!$ws) {
    wp_redirect(home_url('/workshop/'));
    exit;
}

$lang = nl_lang();
$title = $lang==='en' ? $ws['title_en'] : $ws['title'];

$locations = [
    ['name'=>'中環8號碼頭','name_en'=>'Central Pier 8','address'=>'香港中環8號碼頭U層','address_en'=>'U/F,Central Pier 8,Hong Kong'],
    ['name'=>'尖沙咀東匯大廈','name_en'=>'Tsim Sha Tsui','address'=>'尖沙咀寶勒巷27號東匯大廈14樓全層','address_en'=>'14/F, Tung Wui Commercial Building, 27 Prat Avenue, Tsim Sha Tsui, Kowloon, HK'],
    ['name'=>'馬灣公園','name_en'=>'Ma Wan','address'=>'馬灣1868馬灣後街8號39號屋地下','address_en'=>'G39, House 39, No.8 Ma Wan Back Street, Ma Wan Park Phase II, Ma Wan NT'],
    ['name'=>'赤柱大街','name_en'=>'Stanley','address'=>'香港赤柱大街78-79號Solo地下10號舖','address_en'=>'Unit 10, Solo, G/F, 78-79 Stanley Main Street, Stanley, Hong Kong'],
];
?>

<style>
.nl-detail-hero{background:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url('<?php echo get_template_directory_uri(); ?>/assets/images/<?php echo esc_attr($ws['image']); ?>') center/cover no-repeat;color:#fff;padding:120px 20px 80px;text-align:center}
.nl-detail-hero h1{font-size:2.8rem;font-weight:700;margin-bottom:12px;letter-spacing:2px}
.nl-detail-hero p{font-size:1.1rem;opacity:.9}
.nl-detail-body{max-width:800px;margin:0 auto;padding:40px 20px}
.nl-detail-meta{display:flex;gap:24px;flex-wrap:wrap;justify-content:center;margin-bottom:40px}
.nl-detail-meta span{background:#f5f5f5;padding:10px 20px;border-radius:999px;font-size:.95rem}
.nl-detail-section{margin-bottom:48px}
.nl-detail-section h2{font-size:1.4rem;margin-bottom:16px;font-weight:600}
.nl-detail-section p{color:#555;line-height:1.7}
.nl-detail-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:32px;margin-bottom:24px}
.nl-detail-price{font-size:2rem;font-weight:700;color:#00d4b0;margin-bottom:8px}
.nl-detail-book{display:inline-block;padding:14px 40px;background:#00d4b0;color:#fff;border-radius:30px;font-weight:600;font-size:1rem;text-decoration:none;cursor:pointer;border:none}
.nl-detail-book:hover{background:#00bfa0}
.nl-detail-back{display:inline-block;margin-top:40px;color:#666;text-decoration:none;font-size:.95rem}
.nl-detail-back:hover{color:#00d4b0}

/* Modal styles */
.nl-booking-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998}
.nl-booking-modal{display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;width:92%;max-width:640px;max-height:92vh;overflow-y:auto;border-radius:20px;z-index:9999;padding:44px 40px}
.nl-booking-modal.active,.nl-booking-overlay.active{display:block}
.nl-booking-modal__close{position:absolute;top:18px;right:24px;font-size:32px;cursor:pointer;background:none;border:none}
.nl-booking-modal__title{font-size:1.8rem;margin-bottom:10px}
.nl-booking-modal__price{color:#00d4b0;font-weight:bold;font-size:1.35rem;margin-bottom:28px}
.nl-booking-step{display:none}
.nl-booking-step.active{display:block}
.nl-booking-step__title{font-size:1.4rem;margin-bottom:22px}
.nl-location-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.nl-location-card{border:2px solid #eee;border-radius:10px;padding:18px;cursor:pointer;transition:all .2s}
.nl-location-card:hover{border-color:#00d4b0}
.nl-location-card.selected{border-color:#00d4b0;background:#f0fffb}
.nl-location-card__name{font-weight:bold;margin-bottom:6px;font-size:1.05rem}
.nl-location-card__addr{font-size:.9rem;color:#666;line-height:1.4}
.nl-booking-field{margin-bottom:20px}
.nl-booking-field label{display:block;margin-bottom:8px;font-size:1rem;font-weight:500}
.nl-booking-field input,.nl-booking-field select,.nl-booking-field textarea{width:100%;padding:14px;border:1px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box}
.nl-booking-actions{display:flex;justify-content:space-between;margin-top:32px}
.nl-booking-actions button{padding:14px 32px;border-radius:30px;border:none;cursor:pointer;font-size:16px}
.nl-btn-primary{background:#00d4b0;color:#fff}
.nl-btn-primary:disabled{background:#ccc;cursor:not-allowed}
.nl-btn-secondary{background:#f0f0f0;color:#333}
@media(max-width:640px){
  .nl-detail-hero h1{font-size:2rem}
  .nl-detail-hero{padding:80px 16px 50px}
  .nl-location-grid{grid-template-columns:1fr}
  .nl-booking-modal{width:98%;max-width:none;padding:40px 24px 32px;border-radius:20px}
  .nl-booking-modal__close{font-size:36px;top:14px;right:18px}
  .nl-booking-modal__title{font-size:1.9rem;margin-bottom:10px}
  .nl-booking-modal__price{font-size:1.4rem;margin-bottom:32px}
  .nl-booking-step__title{font-size:1.5rem;margin-bottom:24px}
  .nl-booking-field{margin-bottom:24px}
  .nl-booking-field label{font-size:1.15rem;margin-bottom:10px}
  .nl-booking-field input,.nl-booking-field select,.nl-booking-field textarea{padding:16px;font-size:17px;border-radius:8px}
  .nl-location-card{padding:22px;border-radius:12px}
  .nl-location-card__name{font-size:1.25rem;margin-bottom:8px}
  .nl-location-card__addr{font-size:1.05rem;line-height:1.5}
  .nl-booking-actions{margin-top:32px}
  .nl-booking-actions button{padding:16px 36px;font-size:17px;border-radius:30px}
}
</style>

<section class="nl-detail-hero">
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo esc_html($ws['subtitle']); ?></p>
</section>

<div class="nl-detail-body">
    <div class="nl-detail-meta">
        <span>⏱ <?php echo esc_html($ws['duration']); ?></span>
        <span>💰 <?php echo esc_html($ws['price_display']); ?></span>
    </div>

    <div class="nl-detail-card">
        <div class="nl-detail-price"><?php echo esc_html($ws['price_display']); ?></div>
        <p style="color:#666;margin-bottom:24px"><?php echo esc_html($ws['subtitle']); ?></p>
        <button class="nl-detail-book js-open-booking"
                data-workshop-id="<?php echo esc_attr($ws['id']); ?>"
                data-title="<?php echo esc_attr($title); ?>"
                data-price="<?php echo esc_attr($ws['price']); ?>"
                data-price-display="<?php echo esc_attr($ws['price_display']); ?>">
            <?php echo nl_t('ws_book'); ?>
        </button>
    </div>

    <div class="nl-detail-section">
        <h2><?php echo $lang==='en' ? 'About This Workshop' : '關於此工作坊'; ?></h2>
        <p><?php echo $lang==='en'
            ? 'Join us for a hands-on EL wire art workshop where you\'ll create your own neon light masterpiece. All materials and tools are provided. No prior experience needed.'
            : '參加我們的冷光線藝術工作坊，親手製作屬於你的霓虹燈作品。我們提供所有材料及工具，無需任何經驗。'; ?></p>
    </div>

    <div class="nl-detail-section">
        <h2><?php echo $lang==='en' ? 'What\'s Included' : '費用包括'; ?></h2>
        <p><?php echo $lang==='en'
            ? '• All materials and tools<br>• Professional instructor guidance<br>• Your finished neon artwork to take home<br>• Workshop completion certificate'
            : '• 所有材料及工具<br>• 專業導師指導<br>• 完成作品可帶回家<br>• 工作坊完成證書'; ?></p>
    </div>

    <div class="nl-detail-section">
        <h2><?php echo $lang==='en' ? 'Location' : '地點'; ?></h2>
        <p>PMQ元創方 · 中環鴨巴甸街35號<br>Central, Hong Kong</p>
    </div>

    <a href="<?php echo home_url('/workshop/'); ?>" class="nl-detail-back">← <?php echo $lang==='en' ? 'Back to Workshops' : '返回工作坊列表'; ?></a>
</div>

<!-- Booking Modal -->
<div class="nl-booking-overlay" id="bookingOverlay"></div>
<div class="nl-booking-modal" id="bookingModal">
    <button class="nl-booking-modal__close" id="bookingClose">×</button>
    <h3 class="nl-booking-modal__title" id="modalWorkshopTitle">Workshop</h3>
    <p class="nl-booking-modal__price" id="modalWorkshopPrice">$0</p>

    <form id="bookingForm" method="post" action="">
        <?php wp_nonce_field('nl_booking_form','nl_booking_nonce'); ?>
        <input type="hidden" name="nl_booking_workshop_id" id="bookingWorkshopId">
        <input type="hidden" name="nl_booking_workshop_title" id="bookingWorkshopTitleInput">
        <input type="hidden" name="nl_booking_price" id="bookingPrice">
        <input type="hidden" name="selected_location" id="selectedLocation">

        <!-- Step 1: Location -->
        <div class="nl-booking-step active" id="step1">
            <h4 class="nl-booking-step__title">1. <?php echo nl_t('ws_step1'); ?></h4>
            <p style="font-size:.85rem;color:#666;margin-bottom:12px"><strong><?php echo nl_t('ws_advance'); ?></strong></p>
            <div class="nl-location-grid">
                <?php foreach ($locations as $idx=>$loc): ?>
                <div class="nl-location-card" data-location="<?php echo $idx; ?>">
                    <div class="nl-location-card__name"><?php echo nl_lang()==='en' ? esc_html($loc['name_en']) : esc_html($loc['name']); ?></div>
                    <div class="nl-location-card__addr"><?php echo nl_lang()==='en' ? esc_html($loc['address_en']) : esc_html($loc['address']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="nl-booking-actions">
                <span></span>
                <button type="button" class="nl-btn-primary" id="btnStep1Next" disabled><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 2: Date &amp; Time -->
        <div class="nl-booking-step" id="step2">
            <h4 class="nl-booking-step__title">2. <?php echo nl_t('ws_step2'); ?></h4>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_date'); ?></label>
                <input type="date" name="booking_date" id="bookingDate" required min="">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_time'); ?></label>
                <select name="booking_time" id="bookingTime" required>
                    <option value=""><?php echo nl_t('ws_time_sel'); ?></option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                </select>
            </div>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep2Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="button" class="nl-btn-primary" id="btnStep2Next" disabled><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 3: Booking Form -->
        <div class="nl-booking-step" id="step3">
            <h4 class="nl-booking-step__title">3. <?php echo nl_t('ws_step3'); ?></h4>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_name'); ?></label>
                <input type="text" name="customer_name" required placeholder="Full name">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_email_ph'); ?></label>
                <input type="email" name="customer_email" required>
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_phone_ph'); ?></label>
                <input type="tel" name="customer_phone" required placeholder="eg. 6123 4567">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_group_size'); ?></label>
                <select name="group_size" id="groupSize" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6-10</option>
                    <option value="10">10+</option>
                </select>
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_remarks'); ?></label>
                <textarea name="remarks" rows="3" placeholder="Any special requests..."></textarea>
            </div>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep3Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="button" class="nl-btn-primary" id="btnStep3Next"><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 4: Payment -->
        <div class="nl-booking-step" id="step4">
            <h4 class="nl-booking-step__title">4. <?php echo nl_t('ws_step4'); ?></h4>
            <div style="background:#f8f8f8;padding:16px;border-radius:8px;margin-bottom:20px">
                <p><strong><?php echo nl_t('ws_title'); ?>:</strong> <span id="confirmWorkshop"></span></p>
                <p><strong><?php echo nl_t('ws_step1'); ?>:</strong> <span id="confirmLocation"></span></p>
                <p><strong><?php echo nl_t('ws_step2'); ?>:</strong> <span id="confirmDateTime"></span></p>
                <p><strong><?php echo nl_t('ws_name'); ?>:</strong> <span id="confirmName"></span></p>
                <p><strong><?php echo nl_t('ws_group_size'); ?>:</strong> <span id="confirmGroup"></span></p>
                <hr style="margin:12px 0;border:none;border-top:1px solid #ddd">
                <p style="font-size:1.2rem;color:#00d4b0"><strong><?php echo nl_t('ws_price'); ?>: <span id="confirmTotal"></span></strong></p>
            </div>
            <p style="font-size:.85rem;color:#666;margin-bottom:16px">
                <?php echo nl_t('ws_confirm'); ?>
            </p>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep4Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="submit" class="nl-btn-primary" name="nl_booking_submit" value="1"><?php echo nl_t('ws_pay_now'); ?></button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    const modal=document.getElementById('bookingModal');
    const overlay=document.getElementById('bookingOverlay');
    const closeBtn=document.getElementById('bookingClose');
    let selectedLocation=null;
    let currentStep=1;

    // Open modal
    document.querySelectorAll('.js-open-booking').forEach(btn=>{
        btn.addEventListener('click',function(){
            document.getElementById('modalWorkshopTitle').textContent=this.dataset.title;
            document.getElementById('modalWorkshopPrice').textContent=this.dataset.priceDisplay;
            document.getElementById('bookingWorkshopId').value=this.dataset.workshopId;
            document.getElementById('bookingWorkshopTitleInput').value=this.dataset.title;
            document.getElementById('bookingPrice').value=this.dataset.price;
            modal.classList.add('active');
            overlay.classList.add('active');
            resetSteps();
        });
    });

    closeBtn.addEventListener('click',closeModal);
    overlay.addEventListener('click',closeModal);
    function closeModal(){modal.classList.remove('active');overlay.classList.remove('active');}

    // 2-day advance restriction
    const dateInput=document.getElementById('bookingDate');
    const minDate=new Date();
    minDate.setDate(minDate.getDate()+2);
    dateInput.min=minDate.toISOString().split('T')[0];

    // Location selection
    let selectedLocationIdx=null;
    document.querySelectorAll('.nl-location-card').forEach(card=>{
        card.addEventListener('click',function(){
            document.querySelectorAll('.nl-location-card').forEach(c=>c.classList.remove('selected'));
            this.classList.add('selected');
            selectedLocation=parseInt(this.dataset.location);
            selectedLocationIdx=selectedLocation;
            document.getElementById('selectedLocation').value=selectedLocation;
            document.getElementById('btnStep1Next').disabled=false;
            validateStep2();
        });
    });

    // Step navigation
    document.getElementById('btnStep1Next').addEventListener('click',()=>goToStep(2));
    document.getElementById('btnStep2Back').addEventListener('click',()=>goToStep(1));
    document.getElementById('btnStep2Next').addEventListener('click',()=>goToStep(3));
    document.getElementById('btnStep3Back').addEventListener('click',()=>goToStep(2));
    document.getElementById('btnStep3Next').addEventListener('click',()=>{
        const workshop=document.getElementById('bookingWorkshopTitleInput').value;
        const locCards=document.querySelectorAll('.nl-location-card');
        const locName=locCards[selectedLocation]?.querySelector('.nl-location-card__name')?.textContent||'';
        const date=document.getElementById('bookingDate').value;
        const time=document.getElementById('bookingTime').value;
        const name=document.querySelector('input[name="customer_name"]').value;
        const group=document.getElementById('groupSize').value;
        const price=parseFloat(document.getElementById('bookingPrice').value)||0;

        document.getElementById('confirmWorkshop').textContent=workshop;
        document.getElementById('confirmLocation').textContent=locName;
        document.getElementById('confirmDateTime').textContent=date+' '+time;
        document.getElementById('confirmName').textContent=name;
        document.getElementById('confirmGroup').textContent=group+' 人 / pax';
        const total=price>0?'HK$'+(price*parseInt(group)):'Price negotiable';
        document.getElementById('confirmTotal').textContent=total;
        goToStep(4);
    });
    document.getElementById('btnStep4Back').addEventListener('click',()=>goToStep(3));

    function goToStep(n){
        document.querySelectorAll('.nl-booking-step').forEach(s=>s.classList.remove('active'));
        document.getElementById('step'+n).classList.add('active');
        currentStep=n;
    }
    function resetSteps(){
        selectedLocation=null;
        selectedLocationIdx=null;
        document.querySelectorAll('.nl-location-card').forEach(c=>c.classList.remove('selected'));
        document.getElementById('bookingForm').reset();
        document.getElementById('btnStep1Next').disabled=true;
        document.getElementById('btnStep2Next').disabled=true;
        document.getElementById('bookingDate').min=minDate.toISOString().split('T')[0];
        goToStep(1);
    }

    // Date/time validation
    dateInput.addEventListener('change',validateStep2);
    document.getElementById('bookingTime').addEventListener('change',validateStep2);
    function validateStep2(){
        const dateVal=dateInput.value;
        const timeVal=document.getElementById('bookingTime').value;
        let err='';
        if(dateVal){
            const sel=new Date(dateVal);
            const min=new Date(minDate.toISOString().split('T')[0]);
            if(sel<min){ err='Please book at least 2 days in advance.'; }
        }
        if(dateVal && selectedLocationIdx===0){
            const dow=new Date(dateVal).getDay();
            if(dow===3){ err='Central Pier 8 is closed on Wednesdays. Please choose another date.'; }
        }
        document.getElementById('btnStep2Next').disabled=!(dateVal&&timeVal&&!err);
    }
});
</script>

<?php get_footer(); ?>
