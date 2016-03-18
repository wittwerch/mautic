<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<html>
    <head>
        <?php $view['assets']->outputHeadDeclarations(); ?>
    </head>
    <body style="background: #f2f2f2;">
        <table style="border-collapse: collapse; background: #FFFFFF; width: 600px; min-height: 350px; color: #555; display: block; border: 1px solid #d5d4d4; box-shadow: 1px 1px 1px #d5d4d4; margin: 50px auto;">
            <tr>
                <td style="vertical-align: bottom;color: #ffffff;background-color: #009aa0;padding: 30px 50px;font-size: 20px;line-height: 1.5em;height: 100px;">
                    <img src="http://m.55weeks.ch/mautic/themes/55weeks/images/logo.png" alt="55weeks" width="200px"/>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 30px 50px;">
                    <h1><?php $view['slots']->output('greeting'); ?></h1>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 30px 50px; font-size: 15px; line-height: 1.5em;">
                    <?php $view['slots']->output('body'); ?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;padding: 30px 50px;font-size: 12px;line-height: 1.2em;">
                    <strong>55 weeks ag</strong>  |  Busswilstrasse 16  |  CH-3250 Lyss  |  +41 (0)32 387 38 55  | <a href="www.55weeks.ch">www.55weeks.ch</a>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;padding: 30px 50px;font-size: 12px;line-height: 1.2em;">
                    <?php $view['slots']->output('footer'); ?>
                </td>
            </tr>
        </table>
        <?php $view['slots']->output('builder'); ?>
    </body>
</html>
