document.addEventListener('DOMContentLoaded', () => {
    // console.log('Script is running!'); // For DEBUGGING purposes, please do delete later
    const zplCodeElement = document.getElementById('zpl_content');
    const previewContainer = document.getElementById('zpl-preview-container');
    const previewButton = document.getElementById('preview-button');

    if (zplCodeElement && previewContainer && previewButton) {
        const zplCode = zplCodeElement.value;
        const width = 1.378; // IN INCHES: Total printable width: 35 mm / 25.4 mm/inch = 1.378 inches
        const height = 0.315;  // IN INCHES: otal printable height: 8 mm / 25.4 mm/inch = 0.315 inches

        // Function to render the ZPL code as an image using the Labelary API
        const renderZpl = () => {
            const previewImage = document.createElement('img');
            previewImage.src = `https://api.labelary.com/v1/printers/8dpmm/labels/${width}x${height}/0/${encodeURIComponent(zplCode)}`;
            previewImage.alt = 'ZPL Label Preview';
            previewImage.style.border = '1px solid #ccc';
            previewImage.style.maxWidth = '100%';
            
            previewContainer.innerHTML = '';
            previewContainer.appendChild(previewImage);
        };

        previewButton.addEventListener('click', () => {
            if (previewContainer.style.display === 'none' || previewContainer.innerHTML === '') {
                renderZpl();
                previewContainer.style.display = 'block';
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
});