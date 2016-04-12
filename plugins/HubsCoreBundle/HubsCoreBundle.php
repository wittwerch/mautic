<?php
/**
 * @package     diginlab
 * @copyright   2015 Digital Innaovation Lab. All rights reserved.
 * @author      Tom Röthlisberger
 * @link        http://diginlab.com
 */

namespace MauticPlugin\HubsCoreBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

class HubsCoreBundle extends PluginBundleBase
{
   public function getParent()
    {
        return 'MauticCoreBundle';
    }
}
