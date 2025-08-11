<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ZebraPrintSimple extends Module
{
    public function __construct()
    {
        $this->name = 'zebraprintsimple'; // Do NOT change this
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Your name'; // Change if necessary
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Zebra Label Printer (Simple)');
        $this->description = $this->l('Prints labels for products using a Zebra printer.');
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayAdminProductsExtra');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int)$params['id_product'];
        $product = new Product($id_product, false, $this->context->language->id);

        $price = Tools::displayPrice($product->price);
        $reference = $product->reference ?: $product->id;
        $barcode = $product->ean13 ?: $product->upc ?: '123456789012';

        $zpl = $this->generateZPL($price, $reference, $barcode);

        $this->context->smarty->assign([
            'zpl_code' => $zpl,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/product_button.tpl');

        $this->context->smarty->assign([
        'zpl_code' => $zpl,
        'module_dir' => $this->getPathUri(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/product_button.tpl');
    }

    private function generateZPL($price, $reference, $barcode)
    {
        // Label dimensions: 35mm x 8mm printable area (right half of a 70mm label)
        // Total printable width: 35 mm × 8 dots/mm = 280 dots (^PW280)
        // Total printable height:  8 mm × 8 dots/mm = 64 dots (^^LL64)
        

        // "^A0N,25,25" is the Font size. Adjust if necessary
        // "^FO5,5" etc is positioning (x=05, y=5 here). Adjust if necessary
        // "^BY2" is for the barcode. Change to other values (eg 3 or 4) if not readable.

        return "^XA
^PW280
^LL64

^FO5,5^GB130,54,2^FS
^FO15,10^A0N,25,25^FDΑΤΣΑΛΙ^FS
^FO15,35^A0N,20,20^FDΤιμή: {$price}€^FS

^FO145,5^GB130,54,2^FS
^FO155,10^A0N,20,20^FDΚωδικός: {$reference}^FS
^FO155,30^BY2
^BCN,30,Y,N,N
^FD{$barcode}^FS

^XZ";
    }
}