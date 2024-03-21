$(function () {
    const toggleLeftSidebarButton = $("#toggle-left-sidebar-button");
    const leftSidebar = $("#left-sidebar");
    const mainContent = $("#main-content");
    
    toggleLeftSidebarButton.click(function () {
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            leftSidebar.hide('fast');
            mainContent.removeClass('col-md-9').removeClass('col-xl-10').removeClass('col-11')
                .addClass('col-md-12').addClass('col-xl-12').addClass('col-12');
        } else {
            $(this).addClass('active');
            leftSidebar.show('fast');
            mainContent.removeClass('col-md-12').removeClass('col-xl-12').removeClass('col-12')
                .addClass('col-md-9').addClass('col-xl-10').addClass('col-11');
        }
    });
});