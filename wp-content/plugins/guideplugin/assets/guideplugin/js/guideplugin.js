(function($) {

    'use strict';

    let ajaxRequest = null;

    let ajax = function(action, data, callbackData = {}, ajaxAbort = true) {

        if (ajaxAbort && ajaxRequest != null) {
            ajaxRequest.abort();
        }

        ajaxRequest = $.ajax({
            type: "post",
            dataType: "json",
            url: ajax_object.url,
            data: {
                action: action,
                nonce: ajax_object.nonce,
                data: data,
                lang: ajax_object.lang,
            },
            success: function(response) {
                ajaxResponseCallback(action, response, callbackData);
            }
        });
    }


    let ajaxResponseCallback = function(action, response, callbackData) {
        switch (action) {
            case 'guideplugin_guide_initialize':
                handleInitialize(callbackData, response);
                break;
            case 'guideplugin_guide_updated_filter':
                handleUpdateFilter(callbackData, response);
                break;
            case 'guideplugin_guide_next_filter':
                handleNextFilter(callbackData, response);
                break;
            case 'guideplugin_guide_results':
                handleResults(callbackData, response);
                break;
            default:
                // statements_def
                break;
        }
    }


    let updateFilter = function($guide, $filter) {

        let filterValues = getValues($guide);

        showButtonSpinner($guide);

        let data = {
            guide_id: $guide.attr('data-id'),
            current_index: $filter.attr('data-index'),
            filter_values: filterValues
        };

        ajax('guideplugin_guide_updated_filter', data, { 'guide': $guide, 'filter': $filter });
    }


    let nextFilter = function($guide, $filter) {

        let filterValues = getValues($guide);
        let currentFilterValues = filterValues[filterValues.length - 1].values;

        if (currentFilterValues.length == 0 && $filter.attr('data-input-required') == 1) {
            $guide.find('.guideplugin-input-required-modal').guideplugin_modal('open');
            return;
        }

        showButtonSpinner($guide);

        let data = {
            guide_id: $guide.attr('data-id'),
            current_index: $filter.attr('data-index'),
            filter_values: filterValues
        };

        ajax('guideplugin_guide_next_filter', data, { 'guide': $guide, 'filter': $filter });
    }


    let submitGuide = function($guide, $filter) {

        let filterValues = getValues($guide);
        let currentFilterValues = filterValues[filterValues.length - 1].values;

        if (currentFilterValues.length == 0 && $filter.attr('data-input-required') == 1) {
            $guide.find('.guideplugin-input-required-modal').guideplugin_modal('open');
            return;
        }

        if ($guide.attr('data-progress-modal') == 'true') {
            initializeModalProgress($guide);
        }

        showButtonSpinner($guide);
        getResults($guide, $filter);
    }


    let loadMore = function($guide, $filter) {
        showButtonSpinner($guide);
        getResults($guide, $filter);
    }


    let getResults = function($guide, $filter) {

        let data = {
            guide_id: $guide.attr('data-id'),
            filter_values: getValues($guide, $filter.attr('data-index')),
            page: $guide.find('.guide-results').attr('data-next-page')
        };

        ajax('guideplugin_guide_results', data, { 'guide': $guide });
    }


    let resetGuide = function($guide) {
        animateResultsOut($guide);
        $guide.find('.guide-results').attr('data-next-page', 1).html('');
    }


    let handleUpdateFilter = function(callbackData, response) {

        let $guide = callbackData['guide'];
        let $filter = callbackData['filter'];

        if (response.success == true) {

            let sliderIndex = $guide.find('.guide-slider ').slick('slickCurrentSlide');
            let sliderIndexNext = sliderIndex + 1;

            $guide.find('.guide-slider [data-slick-index="' + sliderIndex + '"] .guide-slider-item').replaceWith(response.data.current_filter);

            initializeIonRangeSlider($guide.find('.js-range-slider'));
            hideButtonSpinner($guide);
            updateProgressBar($guide);
            nextOnSelect($guide, $filter);
        }
    }


    let handleNextFilter = function(callbackData, response) {

        let $guide = callbackData['guide'];
        let $filter = callbackData['filter'];

        if (response.success == true) {

            let sliderIndex = $guide.find('.guide-slider ').slick('slickCurrentSlide');
            let sliderIndexNext = sliderIndex + 1;

            $guide.find('.guide-slider').slick('slickAdd', '<div><div>' + response.data.next_filter + '</div></div>');

            initializeIonRangeSlider($guide.find('.js-range-slider'));

            hideButtonSpinner($guide); // hide before start the slide animation because of adaptive height = 'inherit'

            if ($guide.find('.slick-slide[data-slick-index=' + sliderIndexNext + '] img').length > 0) {
                $guide.find('.slick-slide[data-slick-index=' + sliderIndexNext + '] img').one('load', function() {
                    slideNextFilter($guide, response);
                });
                return;
            }
            slideNextFilter($guide, response);
        }
    }


    let slideNextFilter = function($guide, response) {
        $guide.find('.guide-slider').slick('slickNext');
        if (isMobile()) { scrollTo($guide.find('.guide-form')); }
        $guide.attr('data-filter-count', response.data.filter_count);

        updateProgressBar($guide);
    }


    let handleResults = function(callbackData, response) {

        let $guide = callbackData['guide'];

        hideButtonSpinner($guide);

        $guide.find('.guide-results .guide-result-buttons').remove();
        $guide.find('.guide-results').append(response.data.result_html);

        if (conditionShowProgress($guide) == false && $guide.find('.guide-results').attr('data-next-page') == 1) {
            finalizeResultTransition($guide);
        }

        $guide.find('.guide-results').attr('data-next-page', response.data.next_page);

    }


    let nextOnSelect = function($guide, $filter) {
        /**
         * Handle next on select option
         */
        if ($filter.attr('data-next-on-select') == 1) {
            if ($filter.hasClass('guide-last-filter')) {
                submitGuide($guide, $filter);
                return;
            }
            nextFilter($guide, $filter);
            return;
        }
    }


    let initializeModalProgress = function($guide) {
        let time = 250;
        let $modal = $guide.find('.guideplugin-progress-modal');

        scrollTo($guide);

        if (conditionShowProgress($guide)) {
            $modal.guideplugin_modal('open');
            $modal.find('.guideplugin-modal-spinner').show();
            $modal.find('.guideplugin-step-progress .guide-progress-step').each(function(index) {
                let $step = $(this);
                setTimeout(function() { animateStepTransition($guide, $modal, $step, index); }, time);
                time += 2500;
            });

        }
    }


    let conditionShowProgress = function($guide) {
        return ($guide.attr('data-progress-modal') == 'true' && $guide.find('.guideplugin-step-progress .guide-progress-step').length > 0);
    }


    let resetModalProgress = function($modal) {
        $modal.find('.guide-progress-step').removeClass('guide-progress-step-show guide-progress-step-hide');
        $modal.find('.guideplugin-modal-spinner-wrapper').removeClass('guideplugin-modal-spinner-finish');
        $modal.find('.guide-search-progress .guide-progress-bar').width('0%');
    }


    let animateStepTransition = function($guide, $modal, $step, index) {
        let progress = 100 / $modal.find('.guide-progress-step').length * (index + 1);
        $modal.find('.guide-search-progress .guide-progress-bar').width(progress + '%');

        $modal.find('.guide-progress-step').eq(index).addClass('guide-progress-step-show');
        if (index > 0) {
            $modal.find('.guide-progress-step').eq((index - 1)).addClass('guide-progress-step-hide');
        }

        if ($modal.find('.guide-progress-step').length == (index + 1)) {
            setTimeout(function() {
                $modal.find('.guideplugin-modal-spinner-wrapper').addClass('guideplugin-modal-spinner-finish');
            }, 1000);

            setTimeout(function() {
                $modal.guideplugin_modal('close');
                setTimeout(function() {
                    resetModalProgress($modal);
                }, 2000);
            }, 2000);

            setTimeout(function() {
                finalizeResultTransition($guide);
            }, 1500);
        }
    }


    let finalizeResultTransition = function($guide) {

        if ($guide.attr('data-confetti') == 'true') {
            animateConfetti($guide);
        }

        setTimeout(function() {
            animateResultsIn($guide);
        }, 300);
    }


    let animateConfetti = function($guide) {
        let originX = ($guide.offset().left + ($guide.width() / 2)) / $(window).width();
        confetti({
            particleCount: 100,
            spread: 70,
            zIndex: 99999,
            origin: { x: originX, y: 0.5 }
        });
    }


    let animateResultsIn = function($guide) {
        $guide.find('.guide-form').hide();
        $guide.find('.guide-results').show().addClass('guide-result-fade-in');
    }


    let animateResultsOut = function($guide) {
        scrollTo($guide);
        $guide.find('.guide-results').hide();
        $guide.find('.guide-form').show().addClass('guide-result-fade-in');
        setTimeout(function() {
            $guide.find('.guide-slider').slick('setPosition');
        }, 20);
    }


    let updateProgressBar = function($guide) {
        let currentFilterNumber = parseInt($guide.find('.slick-slide.slick-current').attr('data-slick-index')) + 1;
        let filterCount = parseInt($guide.attr('data-filter-count'));
        let progressBarWidth = currentFilterNumber / filterCount * 100;
        $guide.find('.guide-slider-progress .guide-progress-bar').css('width', progressBarWidth + '%');
    }


    let changedInputTrigger = function($input) {
        let $guide = $input.closest('.guideplugin');
        let $filter = $input.closest('.guide-filter');
        updateFilter($guide, $filter);
    }


    let showButtonSpinner = function($guide) {
        $guide.find('.guide-form-buttons .guide-button').addClass('guide-button-hidden');
        $guide.find('.guide-form-buttons .guide-button-spinner').show();
        $guide.find('.guide-result-buttons .guide-button').addClass('guide-button-hidden');
        $guide.find('.guide-result-buttons .guide-button-spinner').show();
    }


    let hideButtonSpinner = function($guide) {
        $guide.find('.guide-form-buttons .guide-button').removeClass('guide-button-hidden');;
        $guide.find('.guide-form-buttons .guide-button-spinner').hide();
        $guide.find('.guide-result-buttons .guide-button').removeClass('guide-button-hidden');
        $guide.find('.guide-result-buttons .guide-button-spinner').hide();
    }


    let getValues = function($guide) {
        let values = [];
        $guide.find('.guide-filter').each(function(index) {
            switch ($(this).attr('data-type')) {
                case 'cards':
                    values.push(getCardValues($(this)));
                    break;
                case 'slider':
                    values.push(getSliderValues($(this)));
                    break;
                default:
                    // statements_def
                    break;
            }
        });
        return values;
    }


    let getCardValues = function($filter) {
        let values = [];
        let $selectedCards = $filter.find(':input:checked');
        $selectedCards.each(function(index) {
            values.push($(this).attr('data-card-value'));
        });

        return {
            'filter_identifier': $filter.attr('data-unique-identifier'),
            'values': values
        };
    }


    let getSliderValues = function($filter) {
        return {
            'filter_identifier': $filter.attr('data-unique-identifier'),
            'values': $filter.find(':input').val().split(';')
        };
    }


    let isMobile = function() {
        if ($(window).width() < 992) {
            return true;
        }
        return false;
    }


    let scrollTo = function($element) {
        $('html, body').animate({
            scrollTop: $element.offset().top
        }, 'slow');
    }


    let initializeIonRangeSlider = function($element) {
        $element.ionRangeSlider({
            prettify: function(num) {
                return prettifyIonRangeSliderValue(num, $element);
            },
            onFinish: function(data) {
                changedInputTrigger(data.input);
            },
        });
    }


    let prettifyIonRangeSliderValue = function(num, $element) {
        if ($element.attr('data-prettify') && $.isNumeric(num)) {
            var si = [
                { value: 1, symbol: "" },
                { value: 1E3, symbol: "k" },
                { value: 1E6, symbol: "M" },
                { value: 1E9, symbol: "G" },
                { value: 1E12, symbol: "T" },
                { value: 1E15, symbol: "P" },
                { value: 1E18, symbol: "E" }
            ];
            var rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
            var i;
            for (i = si.length - 1; i > 0; i--) {
                if (num >= si[i].value) {
                    break;
                }
            }
            return String((num / si[i].value).toFixed(2).replace(rx, "$1") + si[i].symbol).replace('.', ',');
        }
        return num;
    }


    let handleInitialize = function(callbackData, response) {
        let $guide = callbackData['guide'];
        $guide.find('.guide-slider').append(response.data.current_filter);
        setTimeout(function() {
            $guide.removeClass('guide-loading');
            $guide.find('.guide-slider').slick({
                dots: false,
                infinite: false,
                speed: 400,
                slidesToShow: 1,
                slidesToScroll: 1,
                adaptiveHeight: true,
                arrows: false,
                draggable: false,
                touchMove: false,
                swipe: false
            });
            initializeIonRangeSlider($guide.find('.js-range-slider'));
        }, 100);
    }


    let init = function() {
        $('.guideplugin').each(function(i) {
            let data = { 'guide_id': $(this).attr('data-id') };
            ajax('guideplugin_guide_initialize', data, { 'guide': $(this) }, false);
        });
    }


    $(document).ready(function() {
        init();
    });


    $(document).on('submit', '.guideplugin form.guide-form', function(e) {
        e.preventDefault();
    });


    $(document).on('change', '.guideplugin .guide-card input[type=radio]', function() {
        $(this).closest('.guide-filter-cards').find('.guide-card').removeClass('selected animate');
        $(this).closest('.guide-card').addClass('selected animate');
        changedInputTrigger($(this));
    });


    $(document).on('change', '.guideplugin .guide-card input[type=checkbox]', function() {
        $(this).closest('.guide-card').toggleClass('selected animate');
        changedInputTrigger($(this));
    });


    $(document).on('click', '.guideplugin button.guide-button', function() {
        let $guide = $(this).closest('.guideplugin');
        let $filter = $(this).closest('.guide-filter');
        let $slider = $guide.find('.guide-slider');
        let currentSlide = $slider.slick('slickCurrentSlide');
        let nextSlide = currentSlide + 1;

        switch ($(this).attr('data-action')) {
            case 'next':
                nextFilter($guide, $filter);
                break;
            case 'previous':
                $slider.slick('slickPrev');
                updateProgressBar($guide);
                setTimeout(function() {
                    $slider.slick('slickRemove', currentSlide);
                }, 500);
                break;
            case 'finish':
                submitGuide($guide, $filter);
                break;
            case 'reset':
                resetGuide($guide);
                break;
            case 'load_more':
                loadMore($guide, $filter);
                break;
            default:
                // statements_def
                break;
        }
    });

}(jQuery));