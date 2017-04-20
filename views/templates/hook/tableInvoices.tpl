{*
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
*}

<style>
    .table-invoice th
    {
        text-align: center;
        background-color: #E1E2E2;
        color: #555555;
        text-shadow: 1px 1px 1px #999999;
        padding-top: 10px;
        padding-bottom: 10px;
        border: 1px solid #eeeeee;
        text-transform: uppercase;
    }
    .table-invoice tbody tr:hover td
    {
        background-color: #F6F6CC !important;
        cursor: pointer !important;
    }
    .table-invoice td
    {
        padding: 5px;
        border: 1px solid #eeeeee;
    }
</style>

<table class='table-bordered  table-invoice table-striped' style='width: 100%;'>
    <thead>
        <tr>
            <th><input type="checkbox" name='checkAll' id='checkAll'></th>
            <th>{l s='id order' mod='mpexportinvoices'}</th>
            <th>{l s='id invoice' mod='mpexportinvoices'}</th>
            <th>{l s='number' mod='mpexportinvoices'}</th>
            <th>{l s='date' mod='mpexportinvoices'}</th>
            <th>{l s='id customer' mod='mpexportinvoices'}</th>
            <th>{l s='customer' mod='mpexportinvoices'}</th>
            <th>{l s='total' mod='mpexportinvoices'}</th>
        </tr>
    </thead>
    <tbody>
        {assign var=i value=1}
        {foreach $invoices as $row}
            <tr>
                <td style='text-align: right;'>
                    <span>
                        {$i++|escape:'htmlall':'UTF-8'} 
                        <input type='checkbox' name='checkRow[{$i-1|escape:'htmlall':'UTF-8'}]' {if !empty($checkRow[$i-1])}checked='checked'{/if}>
                    </span>
                </td>
                <td style='text-align: right;'>{$row['id_order']|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: right;'>{$row['id_order_invoice']|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: right;'>{$row['number']|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: center;'>{$row['date_add']|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: right;'>{$row['id_customer']|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: left;' >{{$row['customer']|strtoupper}|escape:'htmlall':'UTF-8'}</td>
                <td style='text-align: right;'>{displayPrice price=$row['total_paid_tax_incl']|escape:'htmlall':'UTF-8'}</td>
            </tr>
        {/foreach}
    </tbody>
</table>
