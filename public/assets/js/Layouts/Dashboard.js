import App from '../Utils/App.js';
import ExpiredSession from '../Components/ExpiredSession.js';
import MediaLibrary from '../Utils/MediaLibrary.js';

$(function() {
    ExpiredSession.init(
        LIBRARY.get('expiredSession').expiredUrl,
        LIBRARY.get('expiredSession').modalId,
        LIBRARY.get('expiredSession').formId,
    );
    
    MediaLibrary.setAddFileRoute(LIBRARY.get('mediaLibrary').addFileUrl);
    MediaLibrary.setRemoveFileRoute(LIBRARY.get('mediaLibrary').removeFileUrl);
    MediaLibrary.setLoadFilesRoute(LIBRARY.get('mediaLibrary').loadFilesUrl);
    MediaLibrary.setErrorMessages(LIBRARY.get('mediaLibrary').errorMessages);

    if(LIBRARY.get('messages')) {
        for(const [type, message] of Object.entries(LIBRARY.get('messages'))) {
            App.showMessage(message, type, 5000, 5000, 5000, 'toast-bottom-right');
        }
    }

    $(`[data-bs-toggle='tooltip']`).tooltip();

    $('.table-responsive').on('show.bs.dropdown', function() {
        $('.table-responsive').css('overflow', 'inherit');
    });
    
    $('.table-responsive').on('hide.bs.dropdown', function() {
        $('.table-responsive').css('overflow', 'auto');
    });
});