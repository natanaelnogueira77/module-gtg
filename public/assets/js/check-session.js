$(document).ready(function() {
    var is_session_expired = 'no';
    function check_session() {
        $.ajax({
            url: "check-session.php",
            method: "POST",
            success: function(data) {
                if(data == '1') {
                    $('#loginModal').modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                    $('#loginModal').modal('show');
                    is_session_expired = 'yes';
                }
            }
        });
    }
    
    var count_interval = setInterval(function(){
        check_session();
        if(is_session_expired == 'yes') {
            clearInterval(count_interval);
        }
    }, 10000);
    
    $('#login_form').on('submit', function(event) {
        var button = this.submitting;
        event.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        
        $.ajax({
            url: "check-login.php",
            method: "POST",
            data: formData,
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    form.closest(".modal").modal('toggle');
                }

                if(data.msg) showToastrMsg(data.msg.message, data.msg.type);
                reactivateButton(button, 'Entrar');
            }
        });
    });
});