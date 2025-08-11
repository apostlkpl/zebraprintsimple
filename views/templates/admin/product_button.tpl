<div class="panel">
    <h3><i class="icon-tags"></i> Zebra Label Printer</h3>
    <p>Use the buttons below to print or preview a ZPL label for this product.</p>

    <textarea id="zpl_content" style="display:none;">{$zpl_code|escape:'htmlall':'UTF-8'}</textarea>

    <button class="btn btn-warning" onclick="sendToZebra(); return false;">
        <i class="icon-print"></i> Αποστολή σε Zebra
    </button>
    
    <button id="preview-button" type="button" class="btn btn-info">
        <i class="icon-eye"></i> Προεπισκόπηση
    </button>
</div>

<div id="zpl-preview-container" style="margin-top: 15px; display: none;">
    <p>Loading preview...</p>
</div>

<script src="https://unpkg.com/@zebra_technologies/browser-print@1.1.2/dist/BrowserPrint.min.js"></script>
<script src="{$module_dir}js/label_preview.js"></script>
<script>
function sendToZebra() {
    var zpl = document.getElementById("zpl_content").value;
    if (!window.BrowserPrint) {
        alert("❌ Zebra Browser Print not available. Make sure it's running in your system tray.");
        return;
    }
    BrowserPrint.getDefaultDevice(function(printer) {
        if (!printer) {
            alert("🖨️ Zebra printer not found. Connect your printer and try again.");
            return;
        }
        printer.send(zpl, undefined, function(error) {
            if (error) {
                alert("⚠️ Printing error: " + error);
            } else {
                alert("✅ Label successfully sent to Zebra!");
            }
        });
    }, function(error) {
        alert("⚠️ SDK Error: " + error);
    });
}
</script>