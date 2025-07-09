// دالة التصدير إلى PDF
async function exportToPDF() {
    try {
        // تحميل المكتبات إذا لم تكن محملة مسبقاً
        await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
        await loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
        
        // الانتظار حتى يتم تحميل المكتبات بالكامل
        await new Promise(resolve => {
            if (window.jspdf && window.html2canvas) {
                resolve();
            } else {
                setTimeout(resolve, 100);
            }
        });

        exportTableToPDF();
    } catch (error) {
        console.error('حدث خطأ أثناء التصدير:', error);
        alert('حدث خطأ أثناء التصدير، يرجى المحاولة مرة أخرى');
    }
}

// دالة تحويل الجدول إلى PDF
function exportTableToPDF() {
    const element = document.querySelector('.prod-table');
    
    // تأكد من أن اتجاه النص RTL للعربية
    element.style.direction = 'rtl';
    element.style.textAlign = 'right';

    html2canvas(element, {
        scale: 2,
        logging: false,
        useCORS: true
    }).then(canvas => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm'
        });

        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        
        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('المنتجات.pdf');
    });
}

// دالة مساعدة لتحميل النصوص البرمجية ديناميكياً
function loadScript(url) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${url}"]`)) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = url;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}