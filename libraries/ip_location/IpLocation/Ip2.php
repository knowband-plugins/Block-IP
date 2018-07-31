<?php
require_once 'Results.php';

class KbIpLocation_Ip
{
    /**
     * @var string The last IP address converted.
     */
    public $ip;

    /**
     * @var KbIpLocation_Results The location object.
     */
    public $results;

    /**
     * @var KbIpLocation_Service_Abstract The location service to use when
     *                                  converting IP addresses into locations.
     */
    private $_ipLocationService;

    /**
     * KbIpLocation_Ip
     *
     * @param KbIpLocation_Service_Abstract $locationService The location service
     *                                                     to use in this lookup.
     */
    public function __construct(KbIpLocation_Service_Abstract $locationService)
    {
        $this->_ipLocationService = $locationService;
    }
    public function getIpLocation($ip)
    {
        if ($this->validateIp($ip) === false) {
            return false;
        }

        $this->results = $this->_ipLocationService->getIpLocation($ip);
        return $this->results;
    }

    /**
     * Validate IP address
     *
     * @param string $ip The IP address
     *
     * @return boolean True if IP address is valid.
     */
    public function validateIp($ip)
    {
        if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/D', $ip)) {
            return false;
        }

        $octets = explode('.', $ip);

        for ($i = 1; $i < 5; $i++) {
            $octet = (int)$octets[($i-1)];
            if ($i === 1) {
                if ($octet > 223 OR $octet < 1) {
                    return false;
                }
            } elseif ($i === 4) {
                if ($octet < 1) {
                    return false;
                }
            } else {
                if ($octet > 254) {
                    return false;
                }
            }
        }

        return true;
    }
}
?>