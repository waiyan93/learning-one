var url = 'data/3.pdf';
var thePdf = null;
var scale = 1;
PDFJS.getDocument(url).promise.then(function(pdf) {
    thePdf = pdf;
    viewer = document.getElementById('viewer');

    // for(page = 1; page <= pdf.numPages; page++) {
        canvas = document.createElement("canvas");    
        canvas.className = 'page-canvas'; 
        canvas.id = 'page-' + 10;
        viewer.appendChild(canvas);  
        renderPage(10, canvas);
    // } 
});

function renderPage(pageNumber, canvas) {
    thePdf.getPage(pageNumber).then(function(page) {
        viewport = page.getViewport(scale);
        canvas.height = viewport.height;
        canvas.width = viewport.width;    
        $('.page-width').val(canvas.width);
        $('.page-height').val(canvas.height);      
        page.render({canvasContext: canvas.getContext('2d'), viewport: viewport});
    });
}

$(document).ready(function(){
    var xPosition = 0;
    var yPosition = 0;
    var boxWidth = 250;
    var boxHeight = 150;
    
    $('#select-content').click(function(){
        $('.select-box').show(); 
        $('.select-box').css('position', 'absolute'); 
        $('.select-box').draggable(); 
        $('.select-box').resizable(); 
    });

    $('.select-box').draggable({
        start: function( event, ui ) {
            $('.select-box h5').remove();
            $('#add-link').hide();
            $('#cancel').hide();
        },
        stop: function( event, ui ) {
            xPosition = ui.position.left;
            yPosition = ui.position.top;
            $('.x-position').html('X-Position(px) : '+ xPosition);
            $('.y-position').html('Y-Position(px) : '+ yPosition);
            $('.x-position').val(xPosition);
            $('.y-position').val(yPosition);
            $('#add-link').show();
            $('#cancel').show();
        },
    });

    $('.select-box').resizable({
        stop: function( event, ui ) {
            boxWidth = ui.size.width;
            boxHeight = ui.size.height;
            $('.box-width').html('Width(px) : '+ boxWidth);
            $('.box-height').html('Height(px) : '+ boxHeight);
            $('.box-width').val(boxWidth);
            $('.box-height').val( boxHeight);
        }
    });

    $("#add-link").click(function(){
        modal({show: true,});
    });

    $('#cancel').click(function() {
        $(".select-box").hide();
    });
});