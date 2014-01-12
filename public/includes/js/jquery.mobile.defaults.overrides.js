$(document).bind("mobileinit", function() {



    //$.mobile.updatePagePadding = ( function() {} );

    $.mobile.defaultDialogTransition = "slidedown";
    $.mobile.defaultPageTransition = "slide";

    //$.mobile.path.set = function(){};
    
    // Although, this override does fix the strange jump down before going back, it also 
    // causes other strange back navigation issues
    /*window.history.back = ( function() {

        // Just use the standard history.go(-1) for dialogs
        if ( $.mobile.activePage.is( ".ui-dialog" ) ) {

            window.history.go( -1 );
        
        } else {

            // Instead of calling window.history.back() we're going to use the urlHistory object to get 
            // the previous url.  This fixes a bounce down to the scrollTo of the previous page caused 
            // by the call to window.history.back()
            prev = $.mobile.urlHistory.getPrev();

            // Removing the currently active entry from urlHistory
            $.mobile.urlHistory.stack.pop();

            // Removing previous item from stack too since we already have the url and it will be added back on the stack when we get there
            $.mobile.urlHistory.stack.pop();

            // Decrement urlHistory activeIndex 
            $.mobile.urlHistory.activeIndex = $.mobile.urlHistory.activeIndex - 1;  

            // The data-url attribute has to be set on the data-role="page" dive on the /playlists page so the 
            // location hash is updated properly without having to explicitely specify a dataUrl parameter of change_page 
            // change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT)
            change_page( prev.url, $.mobile.defaultPageTransition, false, false, true, null, null, true, false );
        }
    });*/

    // These should enable cross domain requests
    $.support.cors = true;
    $.mobile.allowCrossDomainPages = true;

    // Default to show text in loading message
    $.mobile.loadingMessageTextVisible = true;

    // Default of 200 is too much, so let's set it to something less
    $.mobile.buttonMarkup.hoverDelay = 25;

    // Disable zoom
    //$.mobile.zoom.enabled = false;

    $.mobile.hashListeningEnabled = true;
    //$.mobile.pushStateEnabled = true;

    // This is so the return to scrollTo will be active no matter how small the scrollTo value
    $.mobile.minScrollBack = 0;

    // We do not use cached pages, so we'd rather have this set to zero so that there is no delay
    //$.mobile.loadPage.defaults.loadMsgDelay = 75;
    //$.mobile.pageContainer.defaults.loadMsgDelay = 75;

    // Turn off the tap to toggle functionality for fixed toolbars
    //$.mobile.fixedtoolbar.tapToggle = false;

    //$.mobile.fixedtoolbar.options.updatePagePadding = false;

    // Set this to a really high number so it won't prevent the smooth scroll
    $.mobile.getMaxScrollForTransition = function() {
        return $.mobile.getScreenHeight() * 1000;
    };

    // We want to override the updatePagePadding function of the fixedtoolbar prototype 
    // We need to do this so it doesn't update page padding since we will be dropping fixed
    // toolbars inline before a transition begins
    //$.mobile.fixedtoolbar.prototype.updatePagePadding = function() { }

    /*var createHandler = function( sequential ){
        
        var simultaneous = false;

        // Default to sequential
        if( sequential === undefined ){
            sequential = true;

        } else if (sequential) {
            sequential = true;

        } else if (!sequential) {
            simultaneous = true;

        } else {
            simultaneous = true;
        }
        
        return function( name, reverse, $to, $from ) {

            var deferred = new $.Deferred(),
                reverseClass = reverse ? " reverse" : "",
                active  = $.mobile.urlHistory.getActive(),
                toScroll = ( $to.toPageScrollTo || active.lastScroll ) || $.mobile.defaultHomeScroll,
                screenHeight = $.mobile.getScreenHeight(),
                maxTransitionOverride = $.mobile.maxTransitionWidth !== false && $( window ).width() > $.mobile.maxTransitionWidth,
                none = !$.support.cssTransitions || maxTransitionOverride || !name || name === "none",
                toPreClass = " ui-page-pre-in",
                pageHasFixedFooter = false,
                fixedHeaderHeight = 40, // To account for - .ui-page-header-fixed { padding-top: 2.5em; }  - from jquery.mobile.structure-1.1.0.css
                fixedFooterHeight = 48, // To account for - .ui-page-footer-fixed { padding-bottom: 3em; } - from jquery.mobile.structure-1.1.0.css
                extraGhostPadding = 5,
                scrollToDuration = 1250,
                footerFadeOutDuration = 200,
                footerFadeInDuration = 200,
                toggleViewportClass = function(){
                    
                    // This line adds the following style to the pageContainer
                    // .ui-mobile-viewport-transitioning .ui-page { width: 100%; height: 100%; overflow: hidden; }
                    $.mobile.pageContainer.toggleClass( "ui-mobile-viewport-transitioning");
                    
                    // This will equal viewport-<name of transition> but there are only styles defined for
                    // viewport-flip and viewport-turn so it has no effect on any of the other transitions
                    $.mobile.pageContainer.toggleClass( "viewport-" + name );
                },

                cleanFrom = function(){

                    $from.addClass( 'ui-from-page-post-transition' );

                    // This line removes the below class as well as the relevant transition styles from the $from jQuery page object
                    // .ui-mobile .ui-page-active { display: block; overflow: visible; }
                    $from.removeClass( $.mobile.activePageClass + " out in reverse " + name );
                    
                    $from.removeClass( 'ui-from-page-post-transition' );

                    //$from.height( "" );
                },

                cleanTo = function( $footer, pageHasFixedFooter ) {

                    if( pageHasFixedFooter ) {

                        $to
                            .removeClass( "out in reverse " + name )        // remove classes that caused the transition to occur
                            .removeClass(".ui-page-header-fixed-pre-out")   // remove style that set page padding-top and padding-bottom to 0px
                            .addClass("ui-page-header-fixed")               // add class that sets page padding-top to 2.5em to make room for fixed header
                            .addClass("ui-page-footer-fixed");              // add class that sets page padding-bottom to 3em to make room for fixed footer

                        $to.find('div[data-role="header"]').first()
                                                                    .removeClass("ui-header-fixed-pre-out") // remove style that set header top to 0 and width to 100%
                                                                    .prop("data-position", "fixed")         // add the data-position="fixed" propery back to the header div
                                                                    .addClass("ui-header-fixed");           // add the fixed header class back to the header div manually

                        $footer
                                .removeClass("ui-footer-fixed-pre-out") // remove style that set footer bottom to 0 and width to 100%
                                .prop("data-position", "fixed")         // add the data-position="fixed" propery back to the footer div
                                .addClass("ui-footer-fixed");           // add the fixed footer class back to the footer div manually

                    } else {

                        // remove classes that caused the transition to occur
                        $to.removeClass( "out in reverse " + name); 
                    }
                },

                getFooter = function( whichpage ) {

                    // genericize which page we're analyzing
                    var $page = null;

                    // New footer JSON object containing the type and the jQuery object
                    var $footer = null;

                    if ( whichpage == 'to' ) {

                        $page = $to;

                    } else if ( whichpage == 'from' ) {

                        $page = $from;

                    } else {

                        $page = null;
                    }

                    if ((typeof $page != 'undefined') && ($page != null)) {

                        // Check to see if there is a footer and if so, store the jQuery object
                        $footer = $page.find("div[data-role='footer']").last();

                        if ($footer.length) {

                            // Is this footer a fixed footer?
                            if ($footer.attr("data-position") == "fixed") {

                                $footer.type = "fixed";

                                if ($footer.attr("class").indexOf('ui-fixed-hidden') >= 0) {

                                    // fixed but hidden
                                    $footer.type = "hidden";
                                }
                            
                            } else if ($footer.attr("data-position") == "inline") {

                                $footer.type = "inline";

                            } else {

                                $footer = null;
                            }
                        }
                    } 

                    if ((typeof $footer != 'undefined') && ($footer != null)) {

                        return $footer;
                    }

                    return false;
                },

                prepPage = function ( which, pageHasFixedFooter ) {

                    pageHasFixedFooter = false || pageHasFixedFooter;

                    $page = ( ( which == 'from' ) ? $from : $to );

                    // The to page will need some pre and post handling so everything can go in a smooth sequence
                    if ( which == 'to' ) {

                        // Set the to page opacity to 0 before setting display block and overflow visible
                        $to.addClass( 'ui-to-page-pre-transition' );
                    }

                    if (which == 'from') {

                        if (pageHasFixedFooter) {

                            $from.find('div[data-role="header"]').prop("data-position", "inline");
                            
                            $from
                                .removeClass("ui-page-header-fixed")
                                .find('div[data-role="header"]').first()
                                .removeClass("ui-header-fixed");

                            $from.find('div[data-role="footer"]').prop("data-position", "inline");

                            $from
                                .removeClass("ui-page-footer-fixed")
                                .find('div[data-role="footer"]').first()
                                .removeClass("ui-footer-fixed");
                        }

                    } else { // must be the to page

                        // get the footer object for the $to page
                        $footer = getFooter( 'to' );

                        // Check if the footer is fixed but hidden and store as a boolean
                        pageHasHiddenFixedFooter = (( $footer.type == "hidden" ) ? true : false);

                        // Check if the footer is fixed and store as a boolean to be accessed 3 more times below
                        pageHasFixedFooter = ( ( ( $footer.type == "fixed" ) || ( pageHasHiddenFixedFooter ) ) ? true : false );

                        if ( pageHasFixedFooter && !pageHasHiddenFixedFooter ) {

                            // Make sure the fixed footer on the page we're transitioning is not being displayed (we'll fade it in after the transition)
                            $footer .css('display', 'none')
                                    .prop("data-position", "inline")
                                    .removeClass("ui-footer-fixed");

                            $to
                                .removeClass("ui-page-header-fixed")
                                .find('div[data-role="header"]').first()
                                .removeClass("ui-header-fixed");

                            $to.removeClass("ui-page-footer-fixed");

                        } else if ( pageHasHiddenFixedFooter ) {

                            // Make sure the fixed footer on the page we're transitioning is not being displayed (we'll fade it in after the transition)
                            $footer.css('display', 'none');
                        
                        } else {

                            // nothing to do
                        }

                        // for some reason, the data-role="content" div was losing it's class="ui-content" so let's 
                        // first remove it in case it is there and then add it back in
                        //$to.find('div[data-role="content"]').removeClass("ui-content").addClass("ui-content");
                    }

                    // just set the page height to screenHeight * 2 so the page doesn't look truncated during transitions that use scaling
                    $page.height( screenHeight * 2 );
                },

                setToPageHeight = function() {

                    // Start it at zero so we have something to check against later
                    var totalHeight = 0;

                    defaultTotalHeight = ( ( screenHeight ) + toScroll );

                    // Let's calculate the height of $page based on the children of the main element (which theoretically should always be a page)
                    $($to).children().each( function( index ) {

                        totalHeight += $( this ).height();
                    });

                    if ( totalHeight === 0 ) {

                        // Use the old value as the fallback value
                        totalHeight = defaultTotalHeight;
                    }

                    // set the to page's height
                    $to.height( totalHeight );
                },

                startOut = function() {

                    // get the footer object for the $to page
                    $footer = getFooter( 'from' );

                    // Check if the footer is fixed but hidden and store as a boolean
                    pageHasHiddenFixedFooter = (( $footer.type == "hidden" ) ? true : false);

                    // Check if the footer is fixed and store as a boolean to be accessed 3 more times below
                    pageHasFixedFooter = ( ( ( $footer.type == "fixed" ) || ( pageHasHiddenFixedFooter ) ) ? true : false );

                    if ( pageHasHiddenFixedFooter ) {

                        // We don't need to wait for it to fade out
                        footerFadeOutDuration = 0;
                    }

                    if (pageHasFixedFooter) {

                        if ($(window).scrollTop() > 2) {

                            $.scrollTo( 2, scrollToDuration, {

                                    easing:'easeInOutExpo', 
                                    onAfter: function() { 

                                        $footer.fadeOut(footerFadeOutDuration, function() {

                                            prepPage( 'from', pageHasFixedFooter );

                                            toggleViewportClass();
                                            
                                            prepPage( 'to' );

                                            $from.addClass( name + " out" + reverseClass );

                                            // if it's using a simultaneous transition handler, call the doneOut transition 
                                            // to start the to page animating in simultaneously
                                            if( simultaneous ){
                                                doneOut();
                                            
                                            } else {
                                                $from.animationComplete( doneOut ); 
                                            }
                                        });
                                    }
                                }
                            );

                        } else {

                            $footer.fadeOut(footerFadeOutDuration, function() {

                                prepPage( 'from', pageHasFixedFooter );

                                toggleViewportClass();

                                prepPage( 'to' );

                                $from.addClass( name + " out" + reverseClass );

                                // if it's using a simultaneous transition handler, call the doneOut transition 
                                // to start the to page animating in simultaneously
                                if( simultaneous ){
                                    doneOut();
                                
                                } else {
                                    $from.animationComplete( doneOut ); 
                                }
                            });
                        }

                    } else {

                        if ($(window).scrollTop() > 2) {

                            $.scrollTo( 2, scrollToDuration, 
                                
                                {
                                    easing:'easeInOutExpo', 
                                    onAfter: function() { 

                                        prepPage( 'from', pageHasFixedFooter );

                                        toggleViewportClass();

                                        prepPage( 'to' );

                                        $from.addClass( name + " out" + reverseClass );

                                        // if it's using a simultaneous transition handler, call the doneOut transition 
                                        // to start the to page animating in simultaneously
                                        if( simultaneous ){
                                            doneOut();
                                        
                                        } else {
                                            $from.animationComplete( doneOut ); 
                                        }
                                    }
                                }
                            );

                        } else {

                            prepPage( 'from', pageHasFixedFooter );

                            toggleViewportClass();

                            prepPage( 'to' );

                            $from.addClass( name + " out" + reverseClass );

                            // if it's using a simultaneous transition handler, call the doneOut transition 
                            // to start the to page animating in simultaneously
                            if( simultaneous ){
                                doneOut();
                            
                            } else {
                                $from.animationComplete( doneOut ); 
                            }
                        }
                    }
                },

                doneOut = function() {

                    if ( $from && sequential ) {
                        cleanFrom();
                    }

                    startIn();
                },
                
                startIn = function(){

                    // Remove the opacity 0 setting before setting display block and overflow visible
                    $to.removeClass( 'ui-to-page-pre-transition' );

                    $to.addClass( name + " in" + reverseClass);

                    // Setting display block and overflow visible
                    $to.addClass( $.mobile.activePageClass );  

                    if( none ){
                        doneIn();

                    } else {
                        $to.animationComplete( doneIn );
                    }
                },

                doneIn = function() { 

                    // if we are coming FROM somewhere and if so, whether or not the transition handler is a simultaneous handler (slide)
                    if ( $from && simultaneous ) {
                        cleanFrom();
                    }
                
                    // get the footer object for the $to page
                    $footer = getFooter( 'to' );

                    // Check if the footer is fixed and store as a boolean to be accessed 3 more times below
                    pageHasFixedFooter = (( $footer.type == "fixed" ) ? true : false);

                    // Clean up the $to page
                    cleanTo( $footer, pageHasFixedFooter );

                    // Set the to page height to what it should be
                    setToPageHeight();

                    // Send focus to page as it is now display: block
                    $.mobile.focusPage( $to );

                    // In some browsers (iOS5), 3D transitions block the ability to scroll to the desired location during transition
                    // This ensures we jump to that spot after the fact, if we aren't there already.
                    if(( $( window ).scrollTop() !== toScroll ) && (toScroll > 1) ) {

                        // Scoll back down to last scroll position that is tracked in toScroll
                        $.scrollTo( toScroll, scrollToDuration,   
                                    
                                    {
                                        easing:'easeInOutExpo', 
                                        onAfter: function() { 

                                            // If there is a fixed footer, then we need to fade it back in
                                            if (pageHasFixedFooter) {

                                                // Fade in the fixed footer
                                                $footer.fadeIn(footerFadeInDuration, function() {

                                                    // re-enable the special scrollstart event handler
                                                    $.event.special.scrollstart.enabled = true;
                                                });

                                            } else {

                                                // re-enable the special scrollstart event handler
                                                $.event.special.scrollstart.enabled = true;
                                            }
                                        }
                                    } 
                        );

                    } else {

                        // If there is a fixed footer, then we need to fade it back in
                        if (pageHasFixedFooter) {

                            // Fade in the fixed footer
                            $footer.fadeIn(footerFadeInDuration, function() {

                                // re-enable the special scrollstart event handler
                                $.event.special.scrollstart.enabled = true;
                            });

                        } else {

                            // re-enable the special scrollstart event handler
                            $.event.special.scrollstart.enabled = true;
                        }
                    }

                    if ( ( $from ) || ( none ) ) {

                        // Toggle the ui-mobile-viewport-transitioning and viewport-<transition name> classes
                        toggleViewportClass();
                    }

                    // resolve the deferred promise
                    deferred.resolve( name, reverse, $to, $from, true );
                };
            
            // Theoretically, this is the only place I need to set this
            $.event.special.scrollstart.enabled = false;

            if ( $from && !none ) {

                // Kick off the first phase of the transition
                startOut();
            
            } else {

                if ( ( $from ) || ( none ) ) {
                
                    toggleViewportClass();
                }

                // Skip to the second phase of the transition if there is no from page or the transition is none
                doneOut();
            }

            // return a deferred promise to be resolved when the transition is complete
            return deferred.promise();
        };
    }

    // generate the handlers from the above
    var sequentialHandler = createHandler(),
        simultaneousHandler = createHandler( false ),
        defaultGetMaxScrollForTransition = function() {
            return $.mobile.getScreenHeight() * 1000;
        };

    // Make our transition handler the public default.
    $.mobile.defaultTransitionHandler = sequentialHandler;

    //transition handler dictionary for 3rd party transitions
    $.mobile.transitionHandlers = {
        "default": $.mobile.defaultTransitionHandler,
        "sequential": sequentialHandler,
        "simultaneous": simultaneousHandler
    };

    // Use the simultaneous transition handler for slide transitions
    $.mobile.transitionHandlers.slide = $.mobile.transitionHandlers.simultaneous;
    
    // This should set the transitionFallbacks
    $.mobile.transitionFallbacks.fade       = "fade";
    $.mobile.transitionFallbacks.pop        = "pop";
    $.mobile.transitionFallbacks.flip       = "flip";
    $.mobile.transitionFallbacks.turn       = "turn";
    $.mobile.transitionFallbacks.flow       = "flow";
    $.mobile.transitionFallbacks.slidefade  = "slidefade";
    $.mobile.transitionFallbacks.slide      = "slide";
    $.mobile.transitionFallbacks.slideup    = "slideup";
    $.mobile.transitionFallbacks.slidedown  = "slidedown";*/

    // Set the getMaxScrollForTransition to default if no implementation was set by user
    $.mobile.getMaxScrollForTransition = $.mobile.getMaxScrollForTransition || defaultGetMaxScrollForTransition;
});

$( document ).bind( "vmouseover", function() {
    // suggestion by jkane001 to improve listview scrolling performance on touchscreen devices:
    // https://forum.jquery.com/topic/why-jqm-touchscreen-list-scrolling-performance-stinks-and-what-to-do-about-it
});
