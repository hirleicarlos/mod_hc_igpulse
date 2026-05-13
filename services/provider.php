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

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->registerServiceProvider(
            new ModuleDispatcherFactory('\\Joomla\\Module\\HcIgpulse')
        );

        $container->registerServiceProvider(
            new HelperFactory('\\Joomla\\Module\\HcIgpulse\\Site\\Helper')
        );

        $container->registerServiceProvider(
            new Module()
        );
    }
};
