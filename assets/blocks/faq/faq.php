<div class="faq<?php if (isset($attributes['open']) && $attributes['open']) {
                    echo ' openByDefault open';
                } ?>">
    <button class="faqToggle">
        <h4>
            <?php echo (isset($attributes['text']) ? $attributes['text'] : "Accordion Text"); ?>
        </h4>

        <div class="arrow-circle <?php echo (isset($attributes['open']) && $attributes['open']) ? 'openSvg' : 'initSvg' ?>">
            <i class="fa-regular fa-arrow-right"></i>
        </div>
    </button>
    <div class="faqContent">
        <?php echo $content; ?>
    </div>
</div>