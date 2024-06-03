<script>
    $(function() {
        $("#notifications-bell").click(function() {
            $.ajax({
                url: <?= json_encode($router->route('notifications.markAllAsRead')) ?>,
                type: 'patch',
                data: {},
                dataType: 'json'
            });

            $("#notifications-bell").find("#notifications-count").text(``);
        });

        const loadUnreadNotifications = function() {
            $.ajax({
                url: <?= json_encode($router->route('notifications.getAllUnread')) ?>,
                type: 'get',
                data: {},
                dataType: 'json', 
                success: function(response) {
                    if(response.data) {
                        $("#notifications-count").text(response.data.length);
                        for(const [index, notification] of Object.entries(response.data)) {
                            if($("#notifications-area").find(`[data-id='${notification.id}']`).length == 0) {
                                $("#notifications-area").append(`<div class="dropdown-divider"></div>`);
                                $("#notifications-area").append(`
                                    <div class="px-2" data-id="${notification.id}">
                                        <p class="mb-0">${notification.content}</p>
                                        <small class="text-muted">${notification.created_at}</small>
                                    </div>
                                `);
                            }
                        }
                    }
                }
            });
        };

        loadUnreadNotifications();
        var countInterval = setInterval(loadUnreadNotifications(), 15000);
    });
</script>