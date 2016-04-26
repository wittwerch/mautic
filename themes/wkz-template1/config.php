<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$config = array(
    "name"        => "Weltklasse Template 1",
    "features"    => array(
        "email"
    ),
    "slots"       => array(
        "email" => array(
            "emailWebLink",            
            

            "block1Heading",            
            "block1Description",

            "block2Heading",
            "block2Image",
            "block2Description",

            "block3Text",

            "block4Heading",
            "block4Image",
            "block4Description",

            "block5Heading",
            "block5Image",
            "block5Description",
            
            "unsubscribeLink"
        )
    )
);

return $config;
