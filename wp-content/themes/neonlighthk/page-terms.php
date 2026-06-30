<?php
/**
 * Template Name: Terms & Conditions
 * Trilingual terms, privacy, cancellation/return/refund and delivery policy.
 * @package NeonLightHK
 */
get_header();
?>

<main id="primary" class="site-main nl-terms-page">

    <div class="nl-page-header">
        <h1 class="nl-page-title"><?php
            if (nl_lang()==='zh') { echo '條款及細則'; }
            elseif (nl_lang()==='cn') { echo '条款及细则'; }
            else { echo 'TERMS & CONDITIONS'; }
        ?></h1>
    </div>

    <section class="nl-section">
        <div class="nl-section__inner nl-terms-content">

<?php if (nl_lang() === 'en') : ?>

    <h2 class="nl-terms-h2">Privacy Policy</h2>
    <p>Neonlight.pro (Just Be Limited) is committed to handling all personal data collected by this online store in accordance with the Personal Data (Privacy) Ordinance. All data submitted by customers on this website is used solely for our marketing activities, service optimisation and data analysis, and will not be disclosed to any third party without the customer's consent unless permitted or required by law. This website respects customer privacy and safeguards data security in accordance with Hong Kong law, with access restricted to authorised personnel only. Collected data will be retained for a reasonable period and used for market research, product and service improvement, and customer relationship management. If you do not wish to receive promotional information, please contact us to make the necessary arrangements. This website reserves the right to amend these terms and conditions at any time. In the event of any dispute, this website reserves the right of final decision.</p>

    <h2 class="nl-terms-h2">Terms &amp; Conditions — General Conditions</h2>
    <p>This website endeavours to provide the latest product information, pricing and stock availability, and reserves the right to change prices at any time without prior notice. If an ordered product cannot be supplied due to stock shortage, this website reserves the right to decline the order or to suggest an alternative product of a similar category and price. If we are unable to provide any item or service in your order, we will notify you by phone or email.</p>

    <h2 class="nl-terms-h2">Cancellation, Return &amp; Refund Policy</h2>
    <ul class="nl-terms-list">
        <li><strong>Custom-made neon products</strong> are made-to-order. Once production has commenced, the order cannot be cancelled, returned or refunded.</li>
        <li>Cancellation requests received <strong>before production begins</strong> may be accepted at our discretion, subject to an administrative fee covering design and material costs already incurred.</li>
        <li><strong>Damaged or defective items</strong>: please report within 7 days of receipt with photographic evidence. We will arrange repair, replacement or refund at our discretion.</li>
        <li><strong>Non-custom products</strong> may be returned within 7 days of receipt, provided they are unused, in original condition and original packaging. Original shipping charges are non-refundable, and return shipping is borne by the customer unless the item is defective.</li>
        <li><strong>Workshops</strong>: payment confirms your booking. To reschedule, notify us at least 48 hours in advance. Late cancellations or no-shows are non-refundable.</li>
        <li>Refunds, where approved, will be processed to the original payment method within 14 working days.</li>
    </ul>

    <h2 class="nl-terms-h2">Delivery Policy</h2>
    <p>Local delivery is generally arranged in the following three ways:</p>
    <ul class="nl-terms-list">
        <li><strong>[Self-pickup]</strong> U/F, Central Pier 8, Hong Kong (a successful appointment must be made in advance).</li>
        <li><strong>[S.F. Express]</strong> Custom neon products are dispatched from our mainland factory. Free shipping to Hong Kong, Macau and mainland China. (Not applicable to orders with a fixed base.)</li>
        <li><strong>[FEDEX]</strong> Overseas regions are delivered by FEDEX; shipping fees must be prepaid by the customer.</li>
    </ul>

<?php elseif (nl_lang() === 'cn') : ?>

    <h2 class="nl-terms-h2">隐私政策</h2>
    <p>Neonlight.pro（Just Be Limited）承诺会根据《个人资料（隐私）条例》处理本网店所收集的所有个人资料。所有由顾客于本网站提交的资料，仅用于本店的营销活动、服务优化及数据分析，不会在未经顾客同意的情况下向第三方披露，除非法律允许或规定。本网站尊重顾客隐私，并会依据香港法例保障资料安全，仅限授权人员查阅。收集的资料将于合理时间内保存，并用作市场研究、产品服务改进及客户关系管理。如阁下不希望收到相关推广资讯，请与我们联络以作安排。本网站保留随时更改条款及细则的权利。如有任何争议，本网站拥有最终决定权。</p>

    <h2 class="nl-terms-h2">条款及细则 — 一般条款</h2>
    <p>本网站致力提供最新的产品资讯、价格及库存状况，并保留随时更改价格而不作另行通知的权利。如因缺货而未能供应订购产品，本网站有权拒绝订单，或建议提供类似类别及价格的替代产品。如我们无法提供阁下订单中的任何商品或服务，将会以电话或电邮方式通知阁下。</p>

    <h2 class="nl-terms-h2">取消、退换及退款政策</h2>
    <ul class="nl-terms-list">
        <li><strong>订制霓虹灯产品</strong>属按单生产，一经开始制作，订单即不可取消、退换或退款。</li>
        <li>于<strong>生产开始前</strong>提出的取消要求，本店可酌情受理，并须扣除因设计及备料已产生之行政费用。</li>
        <li><strong>损坏或瑕疵品</strong>：请于收货后7天内连同照片证明与本店联络，本店将酌情安排维修、更换或退款。</li>
        <li><strong>非订制产品</strong>可于收货后7天内退回，惟必须为全新、未使用且保留原包装。原运费不获退还，退件运费由客人自负（产品本身有瑕疵者除外）。</li>
        <li><strong>工作坊</strong>：付款即确认预约。如需更改日期，请于最少48小时前通知。逾期取消或缺席者，费用不获退还。</li>
        <li>获批准之退款将于14个工作天内退回原支付账户。</li>
    </ul>

    <h2 class="nl-terms-h2">运输送货</h2>
    <p>本地送货一般以以下3个方式：</p>
    <ul class="nl-terms-list">
        <li><strong>[自取]</strong> 香港中环8号码头U层（必须先预约成功）。</li>
        <li><strong>[顺丰]</strong> 订制霓虹灯产品由内地工场送出，本地香港、澳门、中国地区免运费。（不适用于含固定底座的订单）</li>
        <li><strong>[FEDEX]</strong> 海外地区将会由FEDEX派送，运费需要由客人预先寄付。</li>
    </ul>

<?php else : // zh 繁體 ?>

    <h2 class="nl-terms-h2">私隱政策</h2>
    <p>Neonlight.pro（Just Be Limited）承諾會根據《個人資料（私隱）條例》處理本網店所收集的所有個人資料。所有由顧客於本網站提交的資料，僅用於本店的營銷活動、服務優化及數據分析，不會在未經顧客同意的情況下向第三方披露，除非法律允許或規定。 本網站尊重顧客私隱，並會依據香港法例保障資料安全，僅限授權人員查閱。收集的資料將於合理時間內保存，並用作市場研究、產品服務改進及客戶關係管理。如閣下不希望收到相關推廣資訊，請與我們聯絡以作安排。 本網站保留隨時更改條款及細則的權利。如有任何爭議，本網站擁有最終決定權。</p>

    <h2 class="nl-terms-h2">條款及細則 — 一般條款</h2>
    <p>本網站致力提供最新的產品資訊、價格及庫存狀況，並保留隨時更改價格而不作另行通知的權利。如因缺貨而未能供應訂購產品，本網站有權拒絕訂單，或建議提供類似類別及價格的替代產品。如我們無法提供閣下訂單中的任何商品或服務，將會以電話或電郵方式通知閣下。</p>

    <h2 class="nl-terms-h2">取消、退換及退款政策</h2>
    <ul class="nl-terms-list">
        <li><strong>訂製霓虹燈產品</strong>屬按單生產，一經開始製作，訂單即不可取消、退換或退款。</li>
        <li>於<strong>生產開始前</strong>提出的取消要求，本店可酌情受理，並須扣除因設計及備料已產生之行政費用。</li>
        <li><strong>損壞或瑕疵品</strong>：請於收貨後7天內連同照片證明與本店聯絡，本店將酌情安排維修、更換或退款。</li>
        <li><strong>非訂製產品</strong>可於收貨後7天內退回，惟必須為全新、未使用且保留原包裝。原運費不獲退還，退件運費由客人自負（產品本身有瑕疵者除外）。</li>
        <li><strong>工作坊</strong>：付款即確認預約。如需更改日期，請於最少48小時前通知。逾期取消或缺席者，費用不獲退還。</li>
        <li>獲批准之退款將於14個工作天內退回原支付帳戶。</li>
    </ul>

    <h2 class="nl-terms-h2">運輸送貨</h2>
    <p>本地送貨一般以以下3個方式：</p>
    <ul class="nl-terms-list">
        <li><strong>[自取]</strong> 香港中環8號碼頭U層（必須先預約成功）。</li>
        <li><strong>[順豐]</strong> 訂製霓虹燈產品由內地工場送出，本地香港、澳門、中國地區免運費。（不適用於含固定底座的訂單）</li>
        <li><strong>[FEDEX]</strong> 海外地區將會由FEDEX派送，運費需要由客人預先寄付。</li>
    </ul>

<?php endif; ?>

        </div>
    </section>
</main>

<style>
.nl-terms-page .nl-section{max-width:900px;margin:0 auto;padding:40px 20px}
.nl-terms-content{text-align:left;color:#222}
.nl-terms-h2{font-family:var(--font-en);font-size:1.4rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin:40px 0 16px;color:#111;border-left:4px solid var(--nl-cyan);padding-left:12px}
.nl-terms-content p{font-size:1.05rem;line-height:1.85;color:#333;margin-bottom:16px}
.nl-terms-list{margin:0 0 24px;padding-left:22px}
.nl-terms-list li{font-size:1.05rem;line-height:1.85;color:#333;margin-bottom:12px}
.nl-terms-list strong{color:#111}
@media (max-width:768px){
    .nl-terms-page .nl-section{padding:24px 16px}
    .nl-terms-h2{font-size:1.2rem}
    .nl-terms-content p,.nl-terms-list li{font-size:1rem}
}
</style>

<?php get_footer(); ?>