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

class MpExportInvoicesGetContentController
{
    private $module;
    private $file;
    private $context;
    private $_path;
    
    public function __construct($module, $file, $path)
    {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }
    
    public function run($params = array())
    {
        $dateFrom = Tools::getValue('input_date_from', '');
        $dateTo   = Tools::getValue('input_date_to', '');
        
        if (Tools::isSubmit('submit_invoice_search') || Tools::isSubmit('submit_invoice_export')) {
            $this->getInvoices($dateFrom, $dateTo);
            if (!Tools::isSubmit('submit_invoice_export')) {
                $this->context->smarty->assign('xml_invoices', '');
            }
        } else {
            $this->context->smarty->assign('invoices', array());
            $this->context->smarty->assign('sql', '');
            $this->context->smarty->assign('checkRow', array());
            $this->context->smarty->assign('xml_invoices', '');
        }
        
        $this->context->smarty->assign(
            'table_invoices',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . 'mpexportinvoices/views/templates/hook/tableInvoices.tpl'
            )
        );
        $this->context->smarty->assign('dateFrom', $dateFrom);
        $this->context->smarty->assign('dateTo', $dateTo);
        return $this->module->display($this->file, 'getContent.tpl');
    }
    
    private function getInvoices($dateFrom, $dateTo)
    {
        $db = Db::getInstance();
        $sql = new DbQueryCore();
        
        $sql    ->select('i.id_order')
                ->select('i.id_order_invoice')
                ->select('i.number')
                ->select('o.reference')
                ->select('i.date_add')
                ->select('o.id_customer')
                ->select('CONCAT(c.firstname,\' \',c.lastname) as customer')
                ->select('i.total_discount_tax_excl')
                ->select('i.total_products')
                ->select('i.total_shipping_tax_excl')
                ->select('i.total_wrapping_tax_excl')
                ->select('i.total_paid_tax_excl')
                ->select('i.total_paid_tax_incl')
                ->select('o.payment')
                ->from('order_invoice', 'i')
                ->innerJoin('orders', 'o', 'o.id_order=i.id_order')
                ->innerJoin('customer', 'c', 'c.id_customer=o.id_customer')
                ->orderBy('i.date_add')
                ->orderBy('i.number');

        if (empty($dateFrom) && empty($dateTo)) {
            //nothing
        } elseif (!empty($dateFrom) && empty($dateTo)) {
            $sql->where("i.date_add >= '" . $dateFrom . "'");
        } elseif (empty($dateFrom) && !empty($dateTo)) {
            $sql->where("i.date_add <= '" . $dateTo . "'");
        } elseif (!empty($dateFrom) && !empty($dateTo)) {
            $sql->where("i.date_add >= '" . $dateFrom
                    . "' and i.date_add <= DATE_ADD('$dateTo', INTERVAL 1 DAY)");
        } else {
            die($this->module->l('Fatal error during date parsing.', $this->moduleName));
        }
        
        $result = $db->executeS($sql);
        
        $this->context->smarty->assign('sql', $sql);
        $this->context->smarty->assign('invoices', $result);
        if (Tools::isSubmit('submit_invoice_export')) {
            $checkRow = Tools::getValue('checkRow', array());
            $this->context->smarty->assign('checkRow', $checkRow);
            $this->exportInvoices($result, $checkRow);
        } else {
            $this->context->smarty->assign('checkRow', array());
        }
    }
    
    private function exportInvoices($result, $checkRow)
    {
        if (count($checkRow)==0) {
            $this->context->smarty->assign('xml_invoices', 'NO INVOICES SELECTED.');
            return false;
        }
        
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><invoices></invoices>');
        
        $i=1;
        foreach ($result as $row) {
            if (!empty($checkRow[$i])) {
                $xml = $this->addInvoice($xml, $row);
            }
            $i++;
        }
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR .".."
            .DIRECTORY_SEPARATOR . ".."
            .DIRECTORY_SEPARATOR . "export"
            .DIRECTORY_SEPARATOR . "invoices(" . date('Ymd-his') . ").xml";
        
        $this->context->smarty->assign('xml_invoices', $xml->asXML());
        $xml->asXML($filename);
        chmod($filename, 0777);
        
        header('Content-disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-type: "text/xml"; charset="utf8"');
        readfile($filename);
        exit();
    }
    
    /**
     * ADD AN INVOICE ELEMENT TO XML
     * @param SimpleXMLElement $xml
     * @param array $row
     * @return SimpleXMLElement $xml
     */
    private function addInvoice($xml, $row)
    {
        $dateRow    = $row['date_add'];
        $createDate = new DateTime($dateRow);
        $date       = $createDate->format('Y-m-d');
        
        $invoice = $xml->addChild('invoice');
        $invoice->addChild('id_order', $row['id_order']);
        $invoice->addChild('id_order_invoice', $row['id_order_invoice']);
        $invoice->addChild('number', $row['number']);
        $invoice->addChild('reference', $row['reference']);
        $invoice->addChild('date', $date);
        $invoice->addChild('id_customer', $row['id_customer']);
        $invoice->addChild('discounts', $row['total_discount_tax_excl']);
        $invoice->addChild('products', $row['total_products']);
        $invoice->addChild('shipping', $row['total_shipping_tax_excl']);
        $invoice->addChild('wrapping', $row['total_wrapping_tax_excl']);
        $invoice->addChild('total', $row['total_paid_tax_excl']);
        $invoice->addChild('payment', $row['payment']);
        
        $orderList = OrderDetailCore::getList($row['id_order']);
        $products = $invoice->addChild('rows');
        
        /**
         * @var OrderDetailCore $product
         */
        foreach ($orderList as $product) {
            //print "<pre>" . print_r($product,1) . "<pre>";
            
            //Get tax rate
            $db = Db::getInstance();
            $sql = new DbQueryCore();
            
            $sql    ->select('t.rate')
                    ->from('tax', 't')
                    ->innerJoin('tax_rule', 'tr', 'tr.id_tax=t.id_tax')
                    ->where('tr.id_tax_rules_group = ' . $product['id_tax_rules_group']);
            $tax_rate = $db->getValue($sql);
            
            //Add row
            $row_product = $products->addChild('row');
            $row_product->addChild('ean13', $product['product_ean13']);
            $row_product->addChild('reference', $product['product_reference']);
            $row_product->addChild('qty', $product['product_quantity']);
            $row_product->addChild('price', $product['unit_price_tax_excl']);
            $row_product->addChild('product_price', $product['product_price']);
            $row_product->addChild('discount', $product['reduction_percent']);
            $row_product->addChild('reduction', $product['reduction_amount_tax_excl']);
            $row_product->addChild('tax_rate', $tax_rate);
        }
        
        if ($this->tableExists(_DB_PREFIX_ . 'mp_advpayment_orders')) {
            $sql_fee = $this->getFee($product['id_order']);
            $fee = $invoice->addChild('fees');
            $fee->addChild('amount', $sql_fee['fees']);
            $fee->addChild('tax_rate', $sql_fee['tax_rate']);
        }
        
        return $xml;
    }
    
    private function tableExists($tablename)
    {
        try {
            Db::getInstance()->getValue("select 1 from `$tablename`");
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    
    private function getFee($id_order)
    {
        $db = Db::getInstance();
        $sql = new DbQueryCore();
        
        $sql    ->select('fees')
                ->select('tax_rate')
                ->from('mp_advpayment_orders')
                ->where('id_order = ' . $id_order);
        
        
        $result = $db->getRow($sql);
        
        $this->context->smarty->assign('sql_fees', $sql);
        $this->context->smarty->assign('result_fees', $result);
        
        return $result;
    }
}
