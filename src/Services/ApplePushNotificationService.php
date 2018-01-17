<?php

namespace Saritasa\PushNotifications\Services;

use Exception;

/**
 * Class ApplePushNotificationService is used to push notification on IOS.
 *
 * @package App\Model\Services\PushServices
 */
class ApplePushNotificationService extends PushNotificationService
{
    /**
     * Production endpoint of Apple Push Notification Service.
     * @var string
     */
    const PRODUCTION_ENDPOINT = 'gateway.push.apple.com';

    /**
     * Sandbox endpoint of Apple Push Notification Service.
     * @var string
     */
    const SANDBOX_ENDPOINT = 'gateway.sandbox.push.apple.com';

    /**
     * Production default ssl pem file.
     * @var string
     */
    const PRODUCTION_DEFAULT_SSL_PEM_FILE = '/PemFiles/ssl.pem';

    /**
     * Sandbox default ssl pem file.
     * @var string
     */
    const SANDBOX_DEFAULT_SSL_PEM_FILE = '/PemFiles/ssl-sandbox.pem';

    /**
     * Port is used for Apple Push Notification Service.
     * @var int
     */
    const PORT = 2195;

    /**
     * Steam context option local_cert.
     * @var string
     */
    const STREAM_OPTION_LOCAL_CERT = 'local_cert';

    /**
     * Steam context option passpharse.
     * @var string
     */
    const STREAM_OPTION_PASSPHARSE = 'passphrase';

    /**
     * Indicate if service in sandbox mode or production mode.
     * @var bool
     */
    private $isSandbox;

    /**
     * File path of the certificate.
     * @var string
     */
    private $sslPem = 'ssl.pem';

    /**
     * Pass phrase of the certificate.
     * @var string
     */
    private $passPhrase = '';

    /**
     * The socket connection to Apple Push Notification Service.
     * @var mixed
     */
    private $connection;

    /**
     * Status of the connection.
     * @var bool
     */
    private $status;

    /**
     * ApplePushNotificationService constructor.
     *
     * @param bool $isSandBox - indicate if service in sandbox mode or production mode
     * @param string $sslPem - file path of the certificate
     * @param string $passPhrase - pass phrase of the certificate
     * @param string $logFile - file path of the log
     * @param bool $isDebug - indicate if service in debug mode or not
     */
    public function __construct($isSandBox = false, $sslPem = '', $passPhrase = '', $logFile = '', $isDebug = true)
    {
        $this->sslPem = $sslPem;
        $this->passPhrase = $passPhrase;
        $this->isSandbox = $isSandBox;
        $this->logFile = $logFile;
        $this->isDebug = $isDebug;
        $this->setDefaultPemFileIfEmpty();
    }

    /**
     * Set default ssl pem file if it's empty.
     *
     * @return void
     * @access private
     */
    private function setDefaultPemFileIfEmpty()
    {
        if (empty($this->sslPem)) {
            $this->sslPem = realpath(__DIR__) .
                ($this->isSandbox ? self::SANDBOX_DEFAULT_SSL_PEM_FILE : self::PRODUCTION_DEFAULT_SSL_PEM_FILE);
        }
    }

    /**
     * Get service endpoint.
     *
     * @return string
     * @access protected
     */
    protected function getEndPoint()
    {
        return $this->isSandbox ? self::SANDBOX_ENDPOINT : self::PRODUCTION_ENDPOINT;
    }

    /**
     * Get socket endpoint, used to open a socket to Apple Push Notification Service.
     *
     * @return string
     * @access protected
     */
    protected function getSocketEndPoint()
    {
        return 'tls://' . $this->getEndPoint() . ':' . self::PORT;
    }

    /**
     * Get connection info for writing log data.
     *
     * @return array
     * @access private
     */

    private function getConnectionInfo()
    {
        return [
            'host' => $this->getSocketEndPoint(),
            'ssl_file' =>  $this->sslPem,
            'pass_phrase'=> $this->passPhrase
        ];
    }

    /**
     * Connect to Apple Push Notification Service by opening a socket.
     *
     * @return bool|string
     * @access public
     */
    public function connect()
    {
        // close current connection before connect.
        $this->closeConnection();

        try {
            // Setup stream
            $streamContext = stream_context_create();
            stream_context_set_option($streamContext, 'ssl', self::STREAM_OPTION_LOCAL_CERT, $this->sslPem);
            stream_context_set_option($streamContext, 'ssl', self::STREAM_OPTION_PASSPHARSE, $this->passPhrase);

            // Open a connection to the Apple Push Notification Service
            $this->connection = stream_socket_client(
                $this->getSocketEndPoint(),
                $error,
                $errorString,
                60,
                STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
                $streamContext
            );

            if (!$this->connection) {
                $this->closeConnection();
            } else {
                $this->status = true;
            }
        } catch (Exception $ex) {
            $this->error("[connect] - An error occurred when connect - " . json_encode($this->getConnectionInfo()) .
                " - Error: " . $ex->getCode() . "  " .$ex->getMessage() . " Line: ".$ex->getLine());
        }

        return $this->status;
    }

    /**
     * Get status of the connection.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Detect and set custom data for payload.
     *
     * @param array $payload
     * @param string $message
     * @return void
     * @access private
     */
    private function detectAndSetCustomData(array &$payload, $message)
    {
        $customData = json_decode($message, true);
        if ($customData && !empty($customData['message'])) {
            $payload['aps']['alert'] = $customData['message'];
            $payload['aps']['data'] = $customData;
        }
    }

    /**
     * Build encoded payload data for writing to socket.
     *
     * @param string $message
     * @param array $options
     * @return string
     * @access private
     */
    private function buildEncodedPayload($message, $options = array())
    {
        $payload = array(
            'aps' => array(
                'alert' => $message,
                'badge' => isset($options['badge']) ? $options['badge'] : 1,
                'sound' => isset($options['sound']) ? $options['sound'] : 'default'
            ),
            'color' => isset($options['color']) ? $options['color'] : 'green'
        );

        $this->detectAndSetCustomData($payload, $message);

        return $encodedPayload = json_encode($payload);
    }

    /**
     * Build the binary message for writing to socket.
     *
     * @param string $deviceToken
     * @param string $encodedPayload
     * @return string
     * @access private
     */
    private function buildMessage($deviceToken, $encodedPayload)
    {
        return chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($encodedPayload)) . $encodedPayload;
    }

    /**
     * Send a notification message to given device token.
     *
     * @param mixed $deviceToken
     * @param string $message
     * @param array $options
     * @return bool
     * @access public
     */
    public function send($deviceToken, $message, $options = array())
    {
        $devices = is_array($deviceToken) ? $deviceToken : [$deviceToken];
        if (!$devices) {
            return true;
        }

        $encodedPayload = $this->buildEncodedPayload($message, $options);
        foreach ($devices as $device) {
            $this->writePayload($device, $encodedPayload);
        }

        return true;
    }

    /**
     * Write encoded payload data to socket.
     *
     * @param string $device
     * @param string $encodedPayload encoded payload data
     * @return int
     * @access private
     */
    private function writePayload($device, $encodedPayload)
    {
        $result = 0;
        try {
            $this->connect();
            $binaryMessage = $this->buildMessage($device, $encodedPayload);
            $result = fwrite($this->connection, $binaryMessage, strlen($binaryMessage));
            $status = $result ? 'successfully' : 'failed';
            $this->info("[send] - Send push notification $status - Device: $device - Msg: $encodedPayload");
        } catch (Exception $ex) {
            $this->error("[send] - An error occurred when send push notification - Device: ". $device .
                " - Msg: $encodedPayload - Error: " . $ex->getCode() . "  " .
                $ex->getMessage() . " Line: " . $ex->getLine());
        } finally {
            $this->closeConnection();
        }
        return $result;
    }

    /**
     * Close connection from Apple Push Notification Service.
     *
     * @return void
     * @access public
     */
    public function closeConnection()
    {
        if ($this->connection) {
            $this->info("[closeConnection] - Close connection.");
            @fclose($this->connection);
        }
        $this->connection = null;
        $this->status = false;
    }

    /**
     * ApplePushNotificationService destructor.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->closeConnection();
    }
}
