<?php
/**
 * This file contains the class KbIpLocation_Service_GeoIp.
 *
 * PHP Version 5.0.0
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version SVN: <svn_id>
 * @link    http://www.hashbangcode.com/
 *
 */

/**
 * Include PEAR Geo_IP package.
 */
require_once 'Net/GeoIP.php';

/**
 * Include KbIpLocation_Service_Abstract
 */
require_once 'Abstract.php';

/**
 * Converts an IP address into a location through the PEAR Geo_IP package. This
 * class also downloads and updates the GeoIp.dat file from maxmind.com.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version Release: 1.0 (alpha) 16/02/2010
 * @link    http://www.hashbangcode.com/
 *
 */
class KbIpLocation_Service_GeoIp extends KbIpLocation_Service_Abstract
{
    /**
     * The location of the data update file.
     *
     * @var string
     */
    protected $updateFile = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz';

    /**
     * The location of the data update file.
     *
     * @var string
     */
    protected $geoIpDatFile = 'GeoIP.dat';
    
    /**
     * KbIpLocation_Service_GeoIp
     */
    public function __construct()
    {
    }

    /**
     * Lookup an IP address and return a KbIpLocation_Results object containing 
     * the data found.
     *
     * @param string $ip The ip address to lookup.
     *
     * @return string The location
     */
    public function getIpLocation($ip)
    {
        // Create KbNet_GeoIP object.
        $geoip = KbNet_GeoIP::getInstance(
            dirname(__FILE__) . '/data/' . $this->geoIpDatFile
        );

        try {
            $country2Char = $geoip->lookupCountryCode($ip);
            $countryName  = strtoupper($geoip->lookupCountryName($ip));
            //d($country2Char);
        } catch (Exception $e) {
           
            return false;
        }

        if ($country2Char == '' || $countryName == '') {
//             d('ss');
            return false;
        }

        return new KbIpLocation_Results($ip, $country2Char, strtoupper($countryName));
    }

    /**
     * Update the datafile.
     *
     * @return boolean True if file update sucessful.
     */
    public function updateData() 
    {
        $update = file_get_contents($this->updateFile);

        if (strlen($update) < 2) {
            return false;
        }

        if (!$handle = fopen('tmp.dat.gz', 'wb')) {
            return false;
        }

        if (fwrite($handle, $update) == false) {
            return false;
        }

        fclose($handle);

        $FileOpen = fopen('tmp.dat.gz', "rb");
        fseek($FileOpen, -4, SEEK_END);
        $buf = fread($FileOpen, 4);
        $GZFileSize = end(unpack("V", $buf));
        fclose($FileOpen);

        $gzhandle = gzopen('tmp.dat.gz', "rb");
        $contents = gzread($gzhandle, $GZFileSize);

        gzclose($gzhandle);

        $fp  = fopen(dirname(__FILE__) . "/data/" . $this->geoIpDatFile, 'wb');
        fwrite($fp, $contents);
        fclose($fp);

        // Delete the tmp file.
        unlink('tmp.dat.gz');

        return true;
    }
}