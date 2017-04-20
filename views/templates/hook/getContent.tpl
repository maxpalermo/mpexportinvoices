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

<form class='defaultForm form-horizontal' method='post' id="form_export_invoices">
    <div class="panel">
        <div class="panel-heading">
            <span>
                <i class="icon-list"></i>
                {l s='Export invoices' mod='mpexportinvoices'}
            </span>
        </div>
        <!-- INPUT SECTION -->
        <div>
            <label class="control-label col-lg-3 ">{l s='Date From' mod='mpexportinvoices'}</label>
            <div class="input-group input fixed-width-lg">
                <input type="text" id="input_date_from" name="input_date_from" class="input-date fixed-width-lg" readonly="readonly">
                <span class="input-group-addon"><i class='icon-calendar'></i></span>
            </div>
            <br>
            <label class="control-label col-lg-3 ">{l s='Date To' mod='mpexportinvoices'}</label>
            <div class="input-group input fixed-width-lg">
                <input type="text" id="input_date_to" name="input_date_to" class="input-date fixed-width-lg" readonly="readonly">
                <span class="input-group-addon"><i class='icon-calendar'></i></span>
            </div>
            <br>
        </div>
        <div class='panel-footer'>
            <button type="submit" value="1" id="submit_invoice_search" name="submit_invoice_search" class="btn btn-default pull-right">
                <i class="icon-2x icon-search"></i> 
                {l s='Get Invoices' mod='mpexportinvoices'}
            </button>
        </div>
    </div>
    <!-- TABLE SECTION -->
    <div class='panel'>
        <div class='panel-heading'>
            <span>
                <i class='icon-table'></i>
                {l s='Invoices List' mod='mpexportinvoices'}
            </span>
        </div>
        <div class='panel-body'>
            {$table_invoices}
        </div>
        <div class='panel-footer'>
            <button type="submit" value="1" id="submit_invoice_export" name="submit_invoice_export" class="btn btn-default pull-right">
                <i class="icon-download"></i> 
                {l s='Export Invoices List' mod='mpexportinvoices'}
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(window).bind("load",function()
    {
        $(".input-date").datepicker({ dateFormat: 'yy-mm-dd' });
        
        {if !empty($dateFrom)}
            $("#input_date_from").val("{$dateFrom}");
        {/if}
        
        {if !empty($dateTo)}
            $("#input_date_to").val("{$dateTo}");
        {/if}
            
        $("#checkAll").on("change",function(){
            $("input[name^='checkRow'").attr('checked',this.checked);
        });
    });
</script>