<?php
/**
 * @package     Mautic
 * @copyright   2016 Digital Innovation Lab. All rights reserved.
 * @author      Christian Wittwer
 * @link        http://www.diginlab.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\Swiftmailer\Transport;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SparkpostTransport
 */
class SparkpostTransport extends \Swift_SmtpTransport implements InterfaceCallbackTransport
{
    /**
     * {@inheritdoc}
     */
    public function __construct($host = 'localhost', $port = 25, $security = null)
    {
        parent::__construct('smtp.sparkpostmail.com', 587, 'tls');

        $this->setAuthMode('login');
    }

    /**
     * Returns a "transport" string to match the URL path /mailer/{transport}/callback
     *
     * @return mixed
     */
    public function getCallbackPath()
    {
        return 'sparkpost';
    }

    /**
     * Handle webhook from SparkPost
     *
     * https://developers.sparkpost.com/api/#/reference/webhooks/documentation
     *
     * @param Request       $request
     * @param MauticFactory $factory
     *
     * @return mixed
     */
    public function handleCallbackResponse(Request $request, MauticFactory $factory)
    {

        $logger = $factory->getLogger();
        $logger->info("Receiving Webhook from SparkPost");

        $rows = array(
            'bounced' => array(
                'hashIds' => array(),
                'emails' => array()
            ),
            'unsubscribed' => array(
                'hashIds' => array(),
                'emails' => array()
            )
        );

        $json_body = json_decode($request->getContent(), true);

        $sparkpostEvent = $json_body[0]['msys'];

        if ($sparkpostEvent && in_array('message_event', $sparkpostEvent)) {
            $event = $sparkpostEvent['message_event'];

            if ($event['type'] = 'bounce') {

                # Handle only hard bounces
                # https://support.sparkpost.com/customer/portal/articles/1929896
                if (in_array($event['bounce_class'], array('10', '30'))) {
                    $rows['bounced']['emails'][$event['rcpt_to']] = $event['reason'];
                }
            }
        }

        return $rows;
    }
}
