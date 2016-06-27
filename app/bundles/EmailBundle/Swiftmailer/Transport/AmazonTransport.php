<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\Swiftmailer\Transport;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AmazonTransport
 */
class AmazonTransport extends \Swift_SmtpTransport implements InterfaceCallbackTransport {

    /**
     * {@inheritdoc}
     */
    public function __construct($host = 'localhost', $port = 25, $security = null) 
    {
        parent::__construct('email-smtp.us-east-1.amazonaws.com', 587, 'tls');

        $this->setAuthMode('login');
    }

    /**
     * Returns a "transport" string to match the URL path /mailer/{transport}/callback
     *
     * @return mixed
     */
    public function getCallbackPath() 
    {
        return 'amazon';
    }

    /**
     * Handle bounces & complaints from Amazon
     *
     * http://docs.aws.amazon.com/ses/latest/DeveloperGuide/best-practices-bounces-complaints.html
     *
     * @param Request       $request
     * @param MauticFactory $factory
     *
     * @return mixed
     */
    public function handleCallbackResponse(Request $request, MauticFactory $factory) 
    {

        $logger = $factory->getLogger();
        $logger->info("Receiving bounce_complaint from Amazon");
        $translator = $factory->getTranslator();
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

        // Get Amazon bounce/complaint post payload
        $amazonPayload = json_decode($request->getContent(), TRUE);

        if (is_array($amazonPayload)) {
            // Check payload type
            if (strval(strtolower($amazonPayload['Type'])) == 'subscriptionconfirmation') {
                // Check SubscribeURL is present in the payload
                if (isset($amazonPayload['SubscribeURL']) && strlen($amazonPayload['SubscribeURL']) > 0) {
                    // Call subscription URL to confirm the subscription
                    $subscriptionUrl = $amazonPayload['SubscribeURL'];
                    // Get cURL resource
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $subscriptionUrl,
                        CURLOPT_USERAGENT => 'mautic_amazon_ses_php_curl',
                    ));
                    // Send the request & save response to $resp
                    $resp = curl_exec($curl);
                    // Close request to clear up some resources
                    curl_close($curl);
                } else {
                    $logger->debug("No SubscribeURL is found in the payload");
                }
            } elseif (strval(strtolower($amazonPayload['Type'])) == 'notification') {
                if (isset($amazonPayload['Message'])) {
                    $messageDataArray = json_decode($amazonPayload['Message'], TRUE);
                    // Check notification type is bounce
                    if (isset($messageDataArray['notificationType']) && strtolower($messageDataArray['notificationType']) == 'bounce') {
                        // Handle only hard bounces
                        if (isset($messageDataArray['bounce']) && strtolower($messageDataArray['bounce']['bounceType']) == 'permanent' && isset($messageDataArray['bounce']['bouncedRecipients'])) {
                            // Get bounced recipients in an array
                            $bouncedRecipients = $messageDataArray['bounce']['bouncedRecipients'];
                            foreach ($bouncedRecipients as $bouncedRecipientRecord) {
                                if (isset($bouncedRecipientRecord['emailAddress']) && isset($bouncedRecipientRecord['diagnosticCode'])) {
                                    $rows['bounced']['emails'][$bouncedRecipientRecord['emailAddress']] = $bouncedRecipientRecord['diagnosticCode'];
                                    $logger->debug("Mark email '" . $bouncedRecipientRecord['emailAddress'] . "' as bounced, reason: " . $bouncedRecipientRecord['diagnosticCode']);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $rows;
    }

}