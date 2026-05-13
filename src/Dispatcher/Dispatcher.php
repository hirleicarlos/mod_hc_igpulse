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

namespace Joomla\Module\HcIgpulse\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher do módulo mod_hc_igpulse.
 *
 * @since 1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Prepara os dados enviados ao layout.
     *
     * @return array<string, mixed>
     *
     * @since 1.0.0
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

        $helper = $this->getHelperFactory()->getHelper('HcIgpulseHelper');

        $data['items']        = $helper->getItems($data['params']);
        $data['countDesktop'] = (int) $data['params']->get('count_desktop', 6);
        $data['countMobile']  = (int) $data['params']->get('count_mobile', 2);
        $data['colsDesktop']  = (int) $data['params']->get('columns_desktop', 3);
        $data['colsMobile']   = (int) $data['params']->get('columns_mobile', 1);
        $data['showCaption']  = (bool) $data['params']->get('show_caption', 0);
        $data['showLikes']    = (bool) $data['params']->get('show_likes', 0);
        $data['openIn']       = (string) $data['params']->get('open_in', 'instagram');
        $data['layoutStyle']  = (string) $data['params']->get('layout_style', 'carousel');
        $data['gapEnabled']  = (bool) $data['params']->get('gap_enabled', 1);
        $data['gapSize']     = (int) $data['params']->get('gap_size', 8);
        $data['aspectRatio'] = (string) $data['params']->get('aspect_ratio', 'square');

        return $data;
    }
}
