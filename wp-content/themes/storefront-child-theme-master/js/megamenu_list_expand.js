jQuery(function($) {

    $(document).ready(function(){
        $(".showmore").click(function(){
            $(this).toggleClass("rotate");
        });
    });

    $(document).ready(function(){
        $("div > span > span").css("background-color", "blue");
      });

    $('li.mega-menu-column > ul.mega-sub-menu > li.mega-menu-item > ul.mega-sub-menu').each(function(){
        var $ul = $(this),
            $lis = $ul.find('li:gt(4)'),
            isExpanded = $ul.hasClass('expanded');
        $lis[isExpanded ? 'show' : 'hide']();
        
        if($lis.length > 0){
            $ul
                .append($('<li class="showmore list-expand">' + (isExpanded ? ' Hvis mindre' : ' Hvis mere') + '</li>')
                .click(function(event){
                    var isExpanded = $ul.hasClass('expanded');
                    event.preventDefault();
                    $(this).html(isExpanded ? ' Hvis mere' : ' Hvis mindre');
                    $ul.toggleClass('expanded');
                    $lis.toggle(500);
                }));
        }
    });
});