<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

class KbBlockUserByIP extends ObjectModel
{
    public $id_block_ip;
    public $ip;
    public $active;
    public $date_add;
    public $date_upd;
    
    public static $definition = array(
        'table' => 'kb_block_user_ip',
        'primary' => 'id_block_ip',
        'fields' => array(
            'id_block_ip' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'ip' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHtml'
            ),
            'active' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false
            ),
        ),
    );
    
    public function __construct($id_block_ip = null, $id_lang = null)
    {
        parent::__construct($id_block_ip, $id_lang);
    }
    
    /*
     * Function to get all available blocked IP
     */
    public static function getKbBlockIP()
    {
        return Db::getInstance()->executeS('SELECT ip FROM '._DB_PREFIX_.'kb_block_user_ip WHERE active=1');
    }
    
    /*
     * Function to check if IP is exist or not
     */
    public static function isKbIPExists($ip)
    {
        $data = Db::getInstance()->getRow('SELECT ip FROM '._DB_PREFIX_.'kb_block_user_ip WHERE ip="'.pSQL($ip).'"');
        return $data['ip'];
    }
    
    /*
     * Function to get all available blocked ip of other block ID
     */
    public static function getKbIPByIDAndIp($id, $ip)
    {
        $data = Db::getInstance()->executeS(
            'SELECT ip FROM '._DB_PREFIX_.'kb_block_user_ip'
            . ' WHERE id_block_ip !='. (int) $id.' AND ip="'.pSQL($ip).'"'
        );
        return $data;
    }
    
    public static function getIpTableCount()
    {
        $sql = "SELECT COUNT(*) as count FROM " . _DB_PREFIX_ . "kb_block_user_ip";
        $result = Db::getInstance()->getRow($sql);
        if ($result['count'] >= '20') {
            return false;
        } else {
            return true;
        }
    }
}
