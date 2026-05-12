<?php
/**
 * @package     Joomla.Module
 * @subpackage  mod_hc_igpulse
 *
 * @copyright   (C) 2026 Hirlei Carlos Pereira de Araújo
 * @license     MIT
 *
 * @since       1.0.0
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

if (empty($items)) {
    return;
}

HTMLHelper::_('stylesheet', 'mod_hc_igpulse/mod_hc_igpulse.css', ['relative' => true, 'version' => 'auto']);
HTMLHelper::_('stylesheet', 'mod_hc_igpulse/' . $layoutStyle . '.css', ['relative' => true, 'version' => 'auto']);

require ModuleHelper::getLayoutPath('mod_hc_igpulse', 'default_' . $layoutStyle);
