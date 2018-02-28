<?php
/**
 * Zeon Solutions
 * Contact us
 *
 * @category   Zeon
 * @package    Zeon_Contactus
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html
 * GNU General Public License version 3
 */

/**
 * Zeon_Contactus List session model
 *
 * @category   Zeon
 * @package    Zeon_Contactus
 * @author     Zeon Team
 */
class Zeon_Contactus_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('contactus');
    }
}
