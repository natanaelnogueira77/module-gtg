$(function () {
    const toggleLeftSidebarButton = $("#toggle-left-sidebar-button");
    const leftSidebar = $("#left-sidebar");
    
    toggleLeftSidebarButton.click(function () {
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            leftSidebar.hide('fast');
        } else {
            $(this).addClass('active');
            leftSidebar.show('fast');
        }
    });
});