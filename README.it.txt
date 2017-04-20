Documento: Esempio file XML per esportazione Fatture di Prestashop
Autore   : Massimiliano Palermo <maxx.palermo@gmail.com>


Struttura file XML
==================

<invoices>
	<invoice>
		<id_order>1256</id_order>
		<id_order_invoice>1143</id_order_invoice>
		<number>576</number>
		<reference>10001256</reference>
		<date>2017-04-18</date>
		<id_customer>11</id_customer>
		<discounts>0.000000</discounts>
		<products>209.020000</products>
		<shipping>8.200000</shipping>
		<wrapping>0.000000</wrapping>
		<total>225.109344</total>
		<payment>Pagamento tramite Paypal</payment>
		<rows>
			<row>
				<ean13>8000000188472</ean13>
				<reference>ISA009026</reference>
				<qty>10</qty>
				<price>20.901639</price>
				<product_price>20.901639</product_price>
				<discount>0.00</discount>
				<reduction>0.000000</reduction>
				<tax_rate>22.000</tax_rate>
				</row>
			</rows>
			<fees>
				<amount>7.889344</amount>
				<tax_rate>22.000000</tax_rate>
			</fees>
	</invoice>
</invoices>

LEGENDA:
========

<invoices> = Elenco di tutte le fatture
	<invoice> = Nodo che contiene le informazioni di una singola fattura
		<id_order> = Codice dell'ordine di Prestashop
		<id_order_invoice> = Codice della fattura di Prestashop
		<number> = Numero della fattura
		<reference> = Riferimento ordine Prestashop
		<date> = Data della fattura
		<id_customer> = Codice cliente di Prestashop (IDWEB)
		<discounts> = Sconti sulla fattura
		<products> = Totale prodotti IVA ESCLUSA
		<shipping> = Costi di spedizione IVA ESCLUSA
		<wrapping> = Costi di imballaggio IVA ESCLUSA
		<total> = Totale fattura IVA ESCLUSA
		<payment> = Metodo di pagamento
		<rows> = Elenco dei prodotti della fattura
			<row> = Riga prodotto
				<ean13> = Codice a barre
				<reference> = Riferimento fornitore
				<qty> = Quantità 
				<price> = Prezzo di vendita IVA ESCLUSA
				<product_price> = Prezzo prodotto senza sconti
				<discount> = Percentuale di sconto
				<reduction> = Riduzione prezzo
				<tax_rate> = Percentuale IVA
		<fees> = Lista altre commissioni e sconti
			<fee> = Comissione o sconto
				<amount> = Commissione, se negativo sconto IVA ESCLUSA
				<tax_rate> = Percentuale IVA
				

