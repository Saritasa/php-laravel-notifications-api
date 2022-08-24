<?php

namespace Saritasa\PushNotifications\Services;

use Exception;

/**
 * Class AndroidPushNotificationService is used to push notification on Android.
 *
 * @package App\Model\Services\PushServices
 */
class AndroidPushNotificationService extends PushNotificationService
{
    /**
     * Endpoint of Android Google Cloud Messaging Service.
     * @var string
     */
    const ENDPOINT = 'https://android.googleapis.com/gcm/send';

    /**
     * API access key from Google API Console.
     * @var string
     */
    private $apiKey;

    /**
     * AndroidPushNotificationService constructor.
     *
     * @param $apiKey - API access key from Google API Console.
     * @param string $logFile - file path of the log
     * @param bool $isDebug - indicate if service in debug mode or not
     */
    public function __construct($apiKey, $logFile = '', $isDebug = true)
    {
        $this->apiKey = $apiKey;
        $this->logFile = $logFile;
        $this->isDebug = $isDebug;
    }

    /**
     * Make request to Android Google Cloud Messaging Service.
     *
     * @param array $fields
     * @return mixed
     * @access private
     */
    private function makeRequest(array $fields)
    {
        $headers = array(
            'Authorization: key=' . $this->apiKey,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close connection
        curl_close($ch);

        if ($code != 200) {
            $this->error("[makeRequest] - Curl response with Error code: " . $code . " - Response: " .  $res);
            return null;
        }

        return $res;
    }

    /**
     * Send a notification message to given device registration ids.
     *
     * @param mixed $registrationIDs - device registration ids
     * @param string $message
     * @param array $options
     * @return bool
     */
    public function send($registrationIDs, $message, $options = array())
    {
        $registrationIDs = (array)$registrationIDs;

        $fields = array(
            'registrationIds' => $registrationIDs,
            'data' => array("message" => $message),
        );

        try {
            $res = $this->makeRequest($fields);
            $decodedResponse = json_decode($res);

            $status = (bool) ($decodedResponse ? $decodedResponse->success : false);
            $this->info("[send] - Send push notification " .
                ($status ? 'successfully' : 'failed') . " - registration ids: " .
                json_encode($registrationIDs) . " - Msg: $message - Response: ". $res);

            return $status;
        } catch (Exception $ex) {
            $this->error("[send] - An error occurred when send push notification - registration ids: " .
                json_encode($registrationIDs) .
                " - Msg: $message" .
                " - Error: " . $ex->getCode() .
                "  " . $ex->getMessage() .
                " Line: " . $ex->getLine());
            return false;
        }
    }
}
