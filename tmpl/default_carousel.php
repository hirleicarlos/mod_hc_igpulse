<?php
/**
 * @package     Joomla.Module
 * @subpackage  mod_hc_igpulse
 *
 * @copyright   (C) 2026 Hirlei Carlos Pereira de Araújo
 * @license     GNU General Public License version 2 or later
 *
 * @since       1.0.0
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Module\HcIgpulse\Site\Helper\HcIgpulseHelper;

$moduleId = 'hc-igpulse-' . $module->id;
?>
<section
    class="mod-hc-igpulse mod-hc-igpulse--carousel"
    id="<?php echo $moduleId; ?>"
    data-count-desktop="<?php echo $countDesktop; ?>"
    data-count-mobile="<?php echo $countMobile; ?>"
    data-hc-ratio="<?php echo htmlspecialchars($aspectRatio, ENT_QUOTES, 'UTF-8'); ?>"
    style="--hc-igpulse-cols-desktop:<?php echo $countDesktop; ?>;--hc-igpulse-cols-mobile:<?php echo $countMobile; ?>;<?php echo $gapEnabled ? '--hc-igpulse-gap:' . $gapSize . 'px' : '--hc-igpulse-gap:0px'; ?>"
    aria-label="<?php echo Text::_('MOD_HC_IGPULSE_ARIA_FEED'); ?>"
>
    <div class="hc-igpulse-viewport" role="region" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_ARIA_POSTS'); ?>">
        <ul class="hc-igpulse-track" role="list">
            <?php foreach ($items as $item) :
                $src     = HcIgpulseHelper::getMediaSrc($item);
                $isReel  = HcIgpulseHelper::isReel($item);
                $isAlbum = HcIgpulseHelper::isCarouselAlbum($item);
                $caption = isset($item['caption']) ? HcIgpulseHelper::formatCaption($item['caption']) : '';
                $href    = $item['permalink'] ?? '#';
                $target  = $openIn === 'newtab' ? '_blank' : '_self';
                $rel     = $openIn === 'newtab' ? 'noopener noreferrer' : '';
            ?>
            <li class="hc-igpulse-item" role="listitem">
                <?php if ($openIn === 'lightbox') : ?>
                <button type="button" class="hc-igpulse-item__trigger" data-hc-igpulse-lightbox
                    data-src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>"
                    data-caption="<?php echo $caption; ?>"
                    data-href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"
                    aria-label="<?php echo Text::_('MOD_HC_IGPULSE_ARIA_OPEN_POST'); ?>">
                <?php else : ?>
                <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"
                    class="hc-igpulse-item__trigger"
                    target="<?php echo $target; ?>"
                    <?php echo $rel ? 'rel="' . $rel . '"' : ''; ?>
                    aria-label="<?php echo Text::_('MOD_HC_IGPULSE_ARIA_OPEN_POST'); ?>">
                <?php endif; ?>
                    <figure class="hc-igpulse-item__figure">
                        <img src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?php echo $caption ?: Text::_('MOD_HC_IGPULSE_ARIA_POST_IMAGE'); ?>"
                            loading="lazy" decoding="async" class="hc-igpulse-item__img" />
                        <?php if ($isReel) : ?>
                        <span class="hc-igpulse-item__badge hc-igpulse-item__badge--reel" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_BADGE_REEL'); ?>">
                            <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>
                        </span>
                        <?php elseif ($isAlbum) : ?>
                        <span class="hc-igpulse-item__badge hc-igpulse-item__badge--album" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_BADGE_ALBUM'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 16V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14a2 2 0 0 0 2 2h14v-2H4V6H2z"/></svg>
                        </span>
                        <?php endif; ?>
                        <?php if ($showCaption && $caption) : ?>
                        <figcaption class="hc-igpulse-item__caption"><?php echo $caption; ?></figcaption>
                        <?php endif; ?>
                        <?php if ($showLikes && isset($item['like_count'])) : ?>
                        <span class="hc-igpulse-item__likes" aria-label="<?php echo (int) $item['like_count'] . ' ' . Text::_('MOD_HC_IGPULSE_LIKES'); ?>">
                            <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <?php echo (int) $item['like_count']; ?>
                        </span>
                        <?php endif; ?>
                    </figure>
                <?php if ($openIn === 'lightbox') : ?></button><?php else : ?></a><?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <button type="button" class="hc-igpulse-nav hc-igpulse-nav--prev" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_NAV_PREV'); ?>" aria-controls="<?php echo $moduleId; ?>">
        <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
    </button>
    <button type="button" class="hc-igpulse-nav hc-igpulse-nav--next" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_NAV_NEXT'); ?>" aria-controls="<?php echo $moduleId; ?>">
        <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
    </button>

    <?php if ($openIn === 'lightbox') : ?>
    <div class="hc-igpulse-lightbox" id="<?php echo $moduleId; ?>-lightbox" role="dialog" aria-modal="true" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_LIGHTBOX_LABEL'); ?>" hidden>
        <button type="button" class="hc-igpulse-lightbox__close" aria-label="<?php echo Text::_('MOD_HC_IGPULSE_LIGHTBOX_CLOSE'); ?>">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
        <figure class="hc-igpulse-lightbox__figure">
            <img src="" alt="" class="hc-igpulse-lightbox__img" />
            <figcaption class="hc-igpulse-lightbox__caption"></figcaption>
        </figure>
        <a href="#" class="hc-igpulse-lightbox__link" target="_blank" rel="noopener noreferrer"><?php echo Text::_('MOD_HC_IGPULSE_OPEN_INSTAGRAM_LINK'); ?></a>
    </div>
    <?php endif; ?>
</section>
<script>
(function () {
    'use strict';
    const section  = document.getElementById('<?php echo $moduleId; ?>');
    if (!section) return;
    const track    = section.querySelector('.hc-igpulse-track');
    const btnPrev  = section.querySelector('.hc-igpulse-nav--prev');
    const btnNext  = section.querySelector('.hc-igpulse-nav--next');
    const isMobile = window.matchMedia('(max-width: 767px)').matches;
    const visible  = isMobile
        ? parseInt(section.dataset.countMobile, 10)
        : parseInt(section.dataset.countDesktop, 10);
    const items    = Array.from(track.querySelectorAll('.hc-igpulse-item'));
    let current    = 0;

    function clamp(n, min, max) { return Math.min(Math.max(n, min), max); }
    function update() {
        const max   = Math.max(0, items.length - visible);
        current     = clamp(current, 0, max);
        const itemW = items[0] ? items[0].offsetWidth : 0;
        track.style.transform = 'translateX(-' + (current * itemW) + 'px)';
        btnPrev.disabled = current === 0;
        btnNext.disabled = current >= max;
    }
    btnPrev.addEventListener('click', function () { current--; update(); });
    btnNext.addEventListener('click', function () { current++; update(); });
    window.addEventListener('resize', update);
    update();

    <?php if ($openIn === 'lightbox') : ?>
    const lightbox  = document.getElementById('<?php echo $moduleId; ?>-lightbox');
    const lbImg     = lightbox.querySelector('.hc-igpulse-lightbox__img');
    const lbCaption = lightbox.querySelector('.hc-igpulse-lightbox__caption');
    const lbLink    = lightbox.querySelector('.hc-igpulse-lightbox__link');
    const lbClose   = lightbox.querySelector('.hc-igpulse-lightbox__close');
    section.querySelectorAll('[data-hc-igpulse-lightbox]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            lbImg.src = btn.dataset.src; lbImg.alt = btn.dataset.caption || '';
            lbCaption.textContent = btn.dataset.caption || '';
            lbLink.href = btn.dataset.href;
            lightbox.hidden = false; document.body.style.overflow = 'hidden'; lbClose.focus();
        });
    });
    function closeLightbox() { lightbox.hidden = true; document.body.style.overflow = ''; }
    lbClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lightbox.hidden) closeLightbox(); });
    <?php endif; ?>
}());
</script>
