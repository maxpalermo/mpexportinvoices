<?php
/**
 * 2017 mpSOFT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    mpSOFT <info@mpsoft.it>
 *  @copyright 2017 mpSOFT Massimiliano Palermo
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of mpSOFT
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class MpExportInvoices extends Module
{
    private $debug;
    private $messages;
    private $url;
    private $user;
    private $password;
    private $json_string;
    private $filename;
    private $excel_filename;
    protected $_lang;
    
    public function __construct()
    {
        $this->name = 'mpexportinvoices';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'mpsoft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('MP Export Invoices');
        $this->description = $this->l('Export Invoices in XML format');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->_lang = ContextCore::getContext()->language->id;
        
        //field values
        $this->url = '';
        $this->user = '';
        $this->password = '';
        $this->json_string = '';
        $this->messages = [];
        $this->debug = true;
        $this->filename = dirname(__FILE__) . DIRECTORY_SEPARATOR .'json.txt';
        $this->excel_filename = dirname(__FILE__) . DIRECTORY_SEPARATOR .'export.xls';
    }
    
    public function getHookController($hook_name)
    {
        // Include the controller file
        require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');

        // Build dynamically the controller name
        $controller_name = $this->name.$hook_name.'Controller';

        // Instantiate controller
        $controller = new $controller_name($this, __FILE__, $this->_path);

        // Return the controller
        return $controller;
    }
    
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }
            return true;
      }
      
    public function uninstall()
    {
      if (!parent::uninstall()) {
        return false;
      }
      return true;
    }
    
    public function getContent()
    {
        $controller = $this->getHookController('getContent');
        return $controller->run();
    }
}