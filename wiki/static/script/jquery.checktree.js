/**
    Project: CheckTree jQuery Plugin
    Version: 0.4
    
    Legacy Project Website (to v0.3): http://jquery-checktree.googlecode.com/
    Author: JJ Geeway <jj@geewax.org>
    
    Project Website (since v0.4): https://github.com/yeurch/checktree
    Author: Richard Fawcett <r_d_fawcett@hotmail.com>
    Twitter: @yeurch

    License:
        The CheckTree jQuery plugin is currently available for use in all personal or 
        commercial projects under both MIT and GPL licenses. This means that you can choose 
        the license that best suits your project and use it accordingly.
*/
(function(jQuery) {
jQuery.fn.checkTree = function(settings) {

    settings = jQuery.extend({
        /* Callbacks
            The callbacks should be functions that take one argument. The checkbox tree
            will return the jQuery wrapped LI element of the item that was checked/expanded.
        */
        onExpand: null,
        onCollapse: null,
        onCheck: null,
        onUnCheck: null,
        onHalfCheck: null,
        onLabelHoverOver: null,
        onLabelHoverOut: null,
        
        /* Valid choices: 'expand', 'check' */
        labelAction: "expand",
        
        // Debug (currently does nothing)
        debug: false
    }, settings);

    var $tree = this;
    var $lis = $tree.find('li');
    var $checkboxes = $lis.find(":checkbox");

    // Hide all checkbox inputs
    $checkboxes.css('display', 'none');

    $lis.not(':has(.arrow)').each(function() {
        // This little piece here is by far the slowest.
        jQuery(this).prepend('<div class="arrow"></div><div class="checkbox"></div>');
    });

    /*
    What to do when the arrow is clicked
    Tried:
        - $lis.filter(':has(li)').find(' > .arrow')
        - $lis.filter(':has(li)').find('.arrow')
        - $tree.find('li:has(li) .arrow')
        - $tree.find('li:has(li) > .arrow') <- This was the fastest.
    */
    $tree.find('li:has(li) > .arrow')
        .click(function() {
            var $this = jQuery(this);
            $this
                .toggleClass('expanded')
                .toggleClass('collapsed')
                .siblings("ul:first")
                    .toggle()
            ;
            
            // Handle callbacks
            if (settings.onExpand && $this.hasClass('expanded')) {
                settings.onExpand($this.parent());
            }
            else if (settings.onCollapse && $this.hasClass('collapsed')){
                settings.onCollapse($this.parent());
            }
        })
        .addClass(function(){
            return $(this).siblings('ul.expanded').length === 1 ? 'expanded' : 'collapsed';
        })
    ;
    
    // Remove the now redundant 'expanded' class from any sub-lists
    $tree.find('ul').removeClass('expanded');
    
    // Hide all collapsed sub-trees
    $tree.find('li:has(> .arrow.collapsed) > ul').css('display', 'none');

    /*
    What to do when the checkbox is clicked
    */
    /*$tree.find('.checkbox').click(function() {
        var $this = jQuery(this);
        $this
            .toggleClass('checked')
            .removeClass('half_checked')
            .siblings(':checkbox:first').prop('checked', $this.hasClass('checked'))
        ;
        
        // $this.filter('.checked').siblings('ul:first').find('.checkbox:not(.checked)')
        //     .removeClass('half_checked')
        //     .addClass('checked')
        //     .siblings(':checkbox').prop('checked', true)
        // ;
        $this.filter(':not(.checked)').siblings('ul:first').find('.checkbox.checked')
            .removeClass('checked half_checked')
            .siblings(':checkbox').prop('checked', false)
        ;
        
        // Send a change event to our parent checkbox:
        $this.parents("ul:first").siblings(":checkbox:first").trigger('refresh');
        if(!$this.hasClass('checked') && !$this.hasClass('half_checked')){

            // if($this.parents("ul:first").siblings("li")){
            //     var flag = true;
            //     $this.parents("ul:first").siblings("li").each(function(){
            //         if(!$(this).find(".checkbox").hasClass('checked')){
            //             flag = false;
            //             return;
            //         };

            //     })
            //     if(!flag){
            //        $this.parents("ul:first").parents("ul:first").siblings('.checkbox:first').removeClass("checked half_checked")
            //     }else{

            //         $this.parents("ul:first").parents("ul:first").siblings('.checkbox:first').addClass("half_checked")
            //     }

            // }
            if($this.parents("ul:first").siblings('ul').length>0){
              $this.parents("ul:first").siblings('ul').each(function(ind, el){
                    var checkbox = $(el).children("li").children('.checkbox')
                    if(checkbox.hasClass('checked') || checkbox.hasClass('half_checked')){
                       $(this).parents('.tree').children('li').children('.checkbox').addClass('half_checked')
                    }              
                })  
            }
        }else{
            if($this.parents("ul:first").siblings("li")){
                var flag = true;
                $this.parents("ul:first").siblings("li").each(function(){
                    if(!$(this).find(".checkbox").hasClass('checked')){
                        flag = false;
                        return;
                    };

                })
                // if(!flag){
                //     if($this.hasClass('checked') || $this.hasClass('half_checked')){

                //         $this.parents("ul:first").parents("ul:first").siblings('.checkbox:first').addClass("half_checked").removeClass("checked");
                //     }
                // }else{
                //     if($this.hasClass('half_checked')){

                //         $this.parents("ul:first").parents("ul:first").siblings('.checkbox:first').addClass("half_checked").removeClass('checked');
                //     }
                //     if($this.hasClass('checked')){

                //         $this.parents("ul:first").parents("ul:first").siblings('.checkbox:first').addClass("checked").removeClass('half_checked');
                //     }
                // }

            }
        }
        // Handle callbacks
        if (settings.onCheck && $this.hasClass('checked')) {
            alert(1);
            settings.onCheck($this.parent());
        }
        else if (settings.onUnCheck && $this.hasClass('checked') == false) {
            settings.onUnCheck($this.parent());
        }
    });*/
    $tree.find('.checkbox').click(function() {
        // var firstTree = $(this).siblings('ul').length > 0 ? true : false;// 只有子级菜单
        var secondTree = $(this).parent('li').parent('ul').parent('ul').length > 0 ? true : false;// 上下都有菜单
        var thirdTree = $(this).siblings('ul').length > 0 ? false : true;// 只有父级
        console.log(secondTree,thirdTree)
        if($(this).hasClass('checked') || $(this).hasClass('half_checked')){
            // 已选中
            if(thirdTree){
                // 只有父级
                $(this).removeClass('checked');
            }else if(secondTree){
                // 上下都有菜单
                $(this)
                    .removeClass('checked')
                    .siblings('ul:first').find('.checkbox').removeClass('checked');
            }else{
                // 只有子级菜单
                $(this).removeClass('checked').siblings('ul:first').find('.checked').removeClass('checked');
            }

        }else{
            // 未选中
            if(thirdTree){
                // 只有父级
                $(this).addClass('checked');
                var baba = $(this).parents('ul:first').parent('li');// 爸爸
                var tree = $(this).parents('.tree:first');// 爷爷
                tree.children('li').children('.checkbox').addClass('checked');
                baba.children('.checkbox').addClass('checked');
            }else if(secondTree){
                // 上下都有菜单
                $(this).addClass('checked').parent('li').parent('ul').parent('ul').siblings('.checkbox').addClass('checked')
            }else{
                // 只有子级菜单
                $(this).addClass('checked');
            }
        }

    });
    /*
    What to do when a checkbox gets a change event
    (Fired when the children of this checkbox have changed)
    */
    /*$checkboxes.on('refresh', function() {
        // If all the children are checked, this should be checked:
        var $this = jQuery(this);
        var $checkbox = $this.siblings('.checkbox:first');
        var $checkboxP = $this.parents("ul:first").parents('ul:first').siblings('.checkbox:first');
        var any_unchecked_all = $checkboxP.siblings('ul').find(':checkbox:not(:checked):first').length == 1;
        var any_checked_all = $checkboxP.siblings('ul').find(':checkbox:checked:first').length == 1;
        var any_checked = $this.siblings('ul:first').find(':checkbox:checked:first').length == 1;
        var any_unchecked = $this.siblings('ul:first').find(':checkbox:not(:checked):first').length == 1;
        
        if (any_checked) {
            $this.prop('checked', true);
            if (any_unchecked) {
                $checkbox
                    .addClass('half_checked')
                    .removeClass('checked')
                ;
                console.log($checkboxP);
                if($checkboxP){

                  $checkboxP
                    .addClass('half_checked')
                    .removeClass('checked') 
                }
                if (settings.onHalfCheck) {
                    settings.onHalfCheck($this.parent());
                }
            }
            else {
                $checkbox
                    .addClass('checked')
                    .removeClass('half_checked')
                ;
                if($checkboxP){
                    if(any_unchecked_all){
                       $checkboxP
                         .addClass('half_checked')
                         .removeClass('checked')  
                     }else{

                          $checkboxP
                            .addClass('checked')
                            .removeClass('half_checked')
                     }
                }
            }
        }
        else {
            if(any_checked_all){
                $checkboxP
                 .addClass('half_checked')
                 .removeClass('checked') 
            }
            if($checkboxP){

              // $checkboxP.removeClass('checked half_checked')
            }
            $checkbox.removeClass('checked half_checked');
            $this.prop('checked', false);
        }
        
        // Bubble up to our parent checkbox
        $this.parents('ul:first').siblings(':checkbox:first').trigger('refresh');
    });*/

    /*
    What to do when a label is hovered or clicked
    */
    $tree.find('label')
        .click(function() {
            switch(settings.labelAction) {
                case 'expand':
                    jQuery(this).siblings('.arrow:first').click();
                    break;
                case 'check':
                    jQuery(this).siblings('.checkbox:first').click();
                    break;
            }
        })
        
        .hover(
            function() {
                jQuery(this).addClass("hover");
                if (settings.onLabelHoverOver) {
                    settings.onLabelHoverOver(jQuery(this).parent());
                }
            },
            function() {
                jQuery(this).removeClass("hover");
                if (settings.onLabelHoverOut) {
                    settings.onLabelHoverOut(jQuery(this).parent());
                }
            }
        )
    ;

    /*
    Extra convenience methods
    */
    $tree.clear = function() {
        $tree.find('.checkbox')
            .removeClass('checked')
            .siblings(':checkbox').prop('checked', false)
        ;
    };
};
})(jQuery);