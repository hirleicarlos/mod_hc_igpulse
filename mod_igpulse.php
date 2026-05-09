<?php
/**
 * @package     mod_igpulse
 * @subpackage  mod_igpulse
 * @copyright   (C) 2026 hirleicarlos
 * @license     MIT
 * @link        https://github.com/hirleicarlos/mod_igpulse
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'mod_igpulse/mod_igpulse.css', ['relative' => true, 'version' => 'auto']);

require_once __DIR__ . '/helper.php';

$items        = ModIgPulseHelper::getItems($params);
$countDesktop = (int) $params->get('count_desktop', 6);
$countMobile  = (int) $params->get('count_mobile', 2);
$colsDesktop  = (int) $params->get('columns_desktop', 3);
$colsMobile   = (int) $params->get('columns_mobile', 1);
$showCaption  = (bool) $params->get('show_caption', 0);
$showLikes    = (bool) $params->get('show_likes', 0);
$openIn       = $params->get('open_in', 'instagram');
$layoutStyle  = $params->get('layout_style', 'carousel');

if (empty($items)) {
    return;
}

require ModuleHelper::getLayoutPath('mod_igpulse', $layoutStyle);
