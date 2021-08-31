function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function showToast(params) {
    const epoch = new Date().getSeconds();

    $('.toast').each(function () {
        const epochData = $(this).data('epoch');
        const duration = epoch - epochData;
        if (duration > 0) {
            $('.time', $(this)).text(`${epoch - epochData} seconds ago`);
        }
    });

    $('.toast').addClass('down');


    const uuid = uuidv4();
    const templateToast = `
        <div class="toast showing mb-2 overflow-hidden" data-epoch="${epoch}" data-guid="${uuid}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <div class="rounded me-2 bg-${params.type}" style="width:15px;height:15px;"></div>
                <strong class="me-auto">${params.title}</strong>
                <small class="text-muted time">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${params.message}
            </div>
            <div class="w-100" style="height:5px;">
                <div class="h-100 bg-${params.type} toast-proggres" style="width:0%;transition: all .2s;"></div>
            </div>
        </div>
    `;
    $('#toast-container').prepend(templateToast);

    const toastEl = $(`.toast[data-guid=${uuid}]`);
    let proggres = 0;
    const duration = params.duration;
    const interval = setInterval(() => {
        $('.toast-proggres', toastEl).width(proggres + "%");
        proggres += 1;
    }, (duration / 100));

    setTimeout(() => {
        toastEl.remove();
        clearInterval(interval);
    }, duration);
}

function handleLogicToast() {
    $(document).on('click', '.btn-close[data-bs-dismiss=toast]', function () {
        $(this).parents().eq(1).remove();
    })
}