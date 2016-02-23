$(document).ready(function(){
    $( "#datepicker" ).datepicker();
    $('.events-cont ul li span').matchHeight();

    jQuery(function(){
        initOpenClose();
    });

    // open-close init
    function initOpenClose() {
        jQuery('.open-close').openClose({
            activeClass: 'active',
            opener: '.opener',
            slider: '.navigation',
            animSpeed: 400,
            effect: 'slide'
        });
    };

    function OpenClose(options) {
        this.options = $.extend({
            addClassBeforeAnimation: true,
            hideOnClickOutside: false,
            activeClass:'active',
            opener:'.opener',
            slider:'.slide',
            animSpeed: 400,
            effect:'fade',
            event:'click'
        }, options);
        this.init();
    };
    OpenClose.prototype = {
        init: function() {
            if(this.options.holder) {
                this.findElements();
                this.attachEvents();
                this.makeCallback('onInit');
            }
        },
        findElements: function() {
            this.holder = $(this.options.holder);
            this.opener = this.holder.find(this.options.opener);
            this.slider = this.holder.find(this.options.slider);
        },
        attachEvents: function() {
            // add handler
            var self = this;
            this.eventHandler = function(e) {
                e.preventDefault();
                if (self.slider.hasClass(slideHiddenClass)) {
                    self.showSlide();
                } else {
                    self.hideSlide();
                }
            };
            self.opener.bind(self.options.event, this.eventHandler);

            // hover mode handler
            if(self.options.event === 'over') {
                self.opener.bind('mouseenter', function() {
                    self.showSlide();
                });
                self.holder.bind('mouseleave', function() {

                    self.hideSlide();
                });
            }

            // outside click handler
            self.outsideClickHandler = function(e) {
                if(self.options.hideOnClickOutside) {
                    var target = $(e.target);
                    if (!target.is(self.holder) && !target.closest(self.holder).length) {
                        self.hideSlide();
                    }
                }
            };

            // set initial styles
            if (this.holder.hasClass(this.options.activeClass)) {
                $(document).bind('click touchstart', self.outsideClickHandler);
            } else {
                this.slider.addClass(slideHiddenClass);
            }
        },
        showSlide: function() {
            var self = this;
            if (self.options.addClassBeforeAnimation) {
                self.holder.addClass(self.options.activeClass);
            }
            self.slider.removeClass(slideHiddenClass);
            $(document).bind('click touchstart', self.outsideClickHandler);

            self.makeCallback('animStart', true);
            toggleEffects[self.options.effect].show({
                box: self.slider,
                speed: self.options.animSpeed,
                complete: function() {
                    if (!self.options.addClassBeforeAnimation) {
                        self.holder.addClass(self.options.activeClass);
                    }
                    self.makeCallback('animEnd', true);
                }
            });
        },
        hideSlide: function() {
            var self = this;
            if (self.options.addClassBeforeAnimation) {
                self.holder.removeClass(self.options.activeClass);
            }
            $(document).unbind('click touchstart', self.outsideClickHandler);

            self.makeCallback('animStart', false);
            toggleEffects[self.options.effect].hide({
                box: self.slider,
                speed: self.options.animSpeed,
                complete: function() {
                    if (!self.options.addClassBeforeAnimation) {
                        self.holder.removeClass(self.options.activeClass);
                    }
                    self.slider.addClass(slideHiddenClass);
                    self.makeCallback('animEnd', false);
                }
            });
        },
        destroy: function() {
            this.slider.removeClass(slideHiddenClass).css({display:''});
            this.opener.unbind(this.options.event, this.eventHandler);
            this.holder.removeClass(this.options.activeClass).removeData('OpenClose');
            $(document).unbind('click touchstart', this.outsideClickHandler);
        },
        makeCallback: function(name) {
            if(typeof this.options[name] === 'function') {
                var args = Array.prototype.slice.call(arguments);
                args.shift();
                this.options[name].apply(this, args);
            }
        }
    };

    // add stylesheet for slide on DOMReady
    var slideHiddenClass = 'js-slide-hidden';
    $(function() {
        var tabStyleSheet = $('<style type="text/css">')[0];
        var tabStyleRule = '.' + slideHiddenClass;
        tabStyleRule += '{position:absolute !important;left:-9999px !important;top:-9999px !important;display:block !important}';
        if (tabStyleSheet.styleSheet) {
            tabStyleSheet.styleSheet.cssText = tabStyleRule;
        } else {
            tabStyleSheet.appendChild(document.createTextNode(tabStyleRule));
        }
        $('head').append(tabStyleSheet);
    });

    // animation effects
    var toggleEffects = {
        slide: {
            show: function(o) {
                o.box.stop(true).hide().slideDown(o.speed, o.complete);
            },
            hide: function(o) {
                o.box.stop(true).slideUp(o.speed, o.complete);
            }
        },
        fade: {
            show: function(o) {
                o.box.stop(true).hide().fadeIn(o.speed, o.complete);
            },
            hide: function(o) {
                o.box.stop(true).fadeOut(o.speed, o.complete);
            }
        },
        none: {
            show: function(o) {
                o.box.hide().show(0, o.complete);
            },
            hide: function(o) {
                o.box.hide(0, o.complete);
            }
        }
    };

    // jQuery plugin interface
    $.fn.openClose = function(opt) {
        return this.each(function() {
            jQuery(this).data('OpenClose', new OpenClose($.extend(opt, {holder: this})));
        });
    };

    $(function() {
        var jcarousel = $('.jcarousel2');

        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this),
                    width = carousel.innerWidth();

                if (width >= 600) {
                    width = width / 4;
                } else if (width >= 350) {
                    width = width / 2;
                }

                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });

        $('.jcarousel2-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel2-control-next')
            .jcarouselControl({
                target: '+=1'
            });

        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .on('click', function(e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                }
            });
    });

    $(function(){
        var connector = function(itemNavigation, carouselStage) {
            return carouselStage.jcarousel('items').eq(itemNavigation.index());
        };

        $(function() {
            // Setup the carousels. Adjust the options for both carousels here.
            var carouselStage      = $('.carousel-stage').jcarousel();
            var carouselNavigation = $('.carousel-navigation').jcarousel();

            // We loop through the items of the navigation carousel and set it up
            // as a control for an item from the stage carousel.
            carouselNavigation.jcarousel('items').each(function() {
                var item = $(this);

                // This is where we actually connect to items.
                var target = connector(item, carouselStage);

                item
                    .on('jcarouselcontrol:active', function() {
                        carouselNavigation.jcarousel('scrollIntoView', this);
                        item.addClass('active');
                    })
                    .on('jcarouselcontrol:inactive', function() {
                        item.removeClass('active');
                    })
                    .jcarouselControl({
                        target: target,
                        carousel: carouselStage
                    });
            });

            // Setup controls for the stage carousel
            $('.prev-stage')
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .jcarouselControl({
                    target: '-=1'
                });

            $('.next-stage')
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .jcarouselControl({
                    target: '+=1'
                });

            // Setup controls for the navigation carousel
            $('.prev-navigation')
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .jcarouselControl({
                    target: '-=1'
                });

            $('.next-navigation')
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .jcarouselControl({
                    target: '+=1'
                });
        });
    });

    $('.profile-form .btn-submit').click(function(e) {
        var obj = $(this);
        var body = $("body");
        if (obj.attr("data-value") == 'Edit') {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(".profile-cont").offset().top
            }, 1000);
            if (body.hasClass("rtl")) {
                $(".profile-cont h1").text("تعديل الملف الشخصي");
            } else{
                $(".profile-cont h1").text("Edit Profile");
            }
            if(body.hasClass("rtl")){
                obj.val("حفظ");
            }else {
                obj.val("Save");
            };
            $('.profile-form input[disabled="false"]').removeAttr("disabled");
            $('.profile-form select[disabled="false"]').removeAttr("disabled");
            obj.attr("data-value", "Save");
        } else{
            e.preventDefault();
            $.ajax({
                type: 'POST',
                data: $('.profile-form').serialize(),
                url: "http://localhost/Dropbox/soghandev/en/register",
                //url: "http://soghan.ae/register",
                cache: false,
                success: function (data) {
                    window.location='';
                }
            });
        }
    });

    $(".header-top .logged-link").on("click", function(e){
        e.preventDefault();
        $(".dropdown").toggle();
    });

    function winResize(){
        var outerWidth = $(".carousel-stage").outerWidth();
        var outerHeight = $(".carousel-stage").outerHeight();
        $(".carousel-stage li > img").css("width", outerWidth);
        $(".carousel-stage li > img").css("height", outerHeight);
    };
    winResize();
    $(window).resize(function(){
        winResize();
    });
});


