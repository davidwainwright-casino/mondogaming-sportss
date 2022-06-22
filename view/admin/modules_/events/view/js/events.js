(function($, window, document, undefined) {
    "use strict";
    var pluginName = 'Calendar';

    // Create the plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this._name = pluginName;
        this._defaults = $.fn.Calendar.defaults;
        this.options = $.extend({}, this._defaults, options);

        this.elements = {
            toolbar: "#calnav",
            el: "#" + $(element).attr('id'),
            id: $(element).attr('id'),
        };

        var d = new Date();
        this.currentMonth = d.getMonth() + 1;
        this.currentYear = d.getFullYear();
        this.currentDay = d.getDate();

        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function() {
            this.runCalendar();
            this.setMonthly(this.currentMonth, this.currentYear);
            this.bindEvents();
            this.toolbarActions();
        },

        runCalendar: function() {
            // Add Day Of Week Titles
            var weekdays = '';
            for (var i = 0; i < this.options.lang.dayNames.length; i++) {
                var pointer = (i + this.options.weekStart) % this.options.lang.dayNames.length;
                weekdays += '<div>' + this.options.lang.dayNames[pointer] + '</div>';
            }
            $(this.element).append('<div class="header">' + weekdays + '</div><div class="content"></div>');
            // Add Header & event list markup
            $(this.element).append('<div class="list"></div>');
        },

        bindEvents: function() {
            var plugin = this;
            //var element = $(this.element);
            // Click A Day
            $(this.element).on('click', '.cell.day', function() {
                // If events, show events list
                var whichDay = $(this).data('number');
                var headHeight = $(".header", plugin.element).height();
                $('.list', plugin.element).css("top", headHeight - 2).transition('scale');
                $('.event-item[data-number="' + whichDay + '"]', plugin.element).show();
                $('.list', plugin.element).enscroll({
                    showOnHover: true,
                    spacer: 8, //push scrollbar to the edge
                    addPaddingToPane: false,
                    verticalTrackClass: 'scrolltrack',
                    verticalHandleClass: 'scrollhandle'
                });
                plugin.viewToggleButton();
            });

        },

        toolbarActions: function() {
            var plugin = this;
            var element = $(this.element);


            // Advance months
            $(this.elements.toolbar).on('click', '#next', function() {
                var newMonth, newYear;
                var setMonth = element.data('setMonth');
                var setYear = element.data('setYear');
                if (setMonth === 12) {
                    newMonth = 1;
                    newYear = setYear + 1;
                    plugin.setMonthly(newMonth, newYear);
                } else {
                    newMonth = setMonth + 1;
                    newYear = setYear;
                    plugin.setMonthly(newMonth, newYear);
                }
                plugin.viewToggleButton();
            });

            // Go back in months
            $(this.elements.toolbar).on('click', '#prev', function() {
                var newMonth, newYear;
                var setMonth = element.data('setMonth');
                var setYear = element.data('setYear');
                if (setMonth === 1) {
                    newMonth = 12;
                    newYear = setYear - 1;
                    plugin.setMonthly(newMonth, newYear);
                } else {
                    newMonth = setMonth - 1;
                    newYear = setYear;
                    plugin.setMonthly(newMonth, newYear);
                }
                plugin.viewToggleButton();
            });

            // Reset Month
            $(this.elements.toolbar).on('click', '#now', function() {
                plugin.setMonthly(plugin.currentMonth, plugin.currentYear);
                plugin.viewToggleButton();
            });

            // Back to month view
            $(document.body).on('click', '#back', function(e) {
                $(this).remove();
                $('.list', plugin.element).transition('scale');
                e.preventDefault();
            });

        },

        // Massive function to build the month
        setMonthly: function(m, y) {
			$(this.element).addClass('loading');
            var end, i;
            var that = this;
            $(this.element).data('setMonth', m).data('setYear', y);

            // Get number of days
            var dayQty = this.daysInMonth(m, y),
                // Get day of the week the first day is
                mZeroed = m - 1,
                firstDay = new Date(y, mZeroed, 1, 0, 0, 0, 0).getDay();

            // Remove old days
            $('.day, .empty', this.element).remove();
            $('.list', this.element).empty();
            // Print out the days
            for (i = 0; i < dayQty; i++) {
                var day = i + 1; // Fix 0 indexed days
                var dayNamenum = new Date(y, mZeroed, day, 0, 0, 0, 0).getDay();

                var html = '' +
                    '<div class="cell day" data-number="' + day + '">' +
                    '<div class="date">' + day + '</div>' +
                    '<div class="progress"></div>' +
                    '</div>';
                $('.content', this.element).append(html);

                $('.list', this.element).append('<div class="event-item" id="' + this.elements.id + 'day' + day + '" data-number="' + day + '"><div class="date">' + this.options.lang.dayNames[dayNamenum] + ' <span>' + day + '</span></div></div>');
            }


            // Set Today
            var setMonth = $(this.element).data('setMonth');
            var setYear = $(this.element).data('setYear');
            if (setMonth === this.currentMonth && setYear === this.currentYear) {
                $('#' + this.elements.id + ' *[data-number="' + this.currentDay + '"]').addClass('today');
            }

            // Reset button
            if (setMonth === this.currentMonth && setYear === this.currentYear) {
                $('#now, #nowalt', this.elements.toolbar).html(this.options.lang.monthNames[m - 1] + ' ' + y);
            } else {
                $('#now, #nowalt', this.elements.toolbar).html(this.options.lang.monthNames[m - 1] + ' ' + y);
            }

            // Account for empty days at start
            if (this.options.weekStart === 0 && firstDay !== 7) {
                for (i = 0; i < firstDay; i++) {
                    $('.content', this.element).prepend('<div class="cell empty"><div class="date"></div></div>');
                }
            } else if (this.options.weekStart === 1 && firstDay === 0) {
                for (i = 0; i < 6; i++) {
                    $('.content', this.element).prepend('<div class="cell empty" ><div class="date"></div></div>');
                }
            } else if (this.options.weekStart === 1 && firstDay !== 1) {
                for (i = 0; i < (firstDay - 1); i++) {
                    $('.content', this.element).prepend('<div class="cell empty" ><div class="date"></div></div>');
                }
            }

            //Account for empty days at end
            var numdays = $('.day', this.element).length;
            var numempty = $('.empty', this.element).length;
            var totaldays = numdays + numempty;
            var roundup = Math.ceil(totaldays / 7) * 7;
            var daysdiff = roundup - totaldays;
            if (totaldays % 7 !== 0) {
                for (i = 0; i < daysdiff; i++) {
                    $('.content', this.element).append('<div class="cell empty"><div class="date"></div></div>');
                }
            }

            // Events
            if (this.options.mode === 'event') {
                // Remove previous events
                $.get(this.options.url, {
                    action: "events",
                    year: setYear,
                    month: this.pad(setMonth)
                }, function(json) {
                    $(json.events).each(function() {
                        // Year [0]   Month [1]   Day [2]
                        var fullstartDate = this.date_start;
                        var startArr = fullstartDate.split("-");
                        var startYear = parseInt(startArr[0], 10);
                        var startMonth = parseInt(startArr[1], 10);
                        var startDay = parseInt(startArr[2], 10);
                        var fullendDate = this.date_end;
                        var endArr = this.date_end ? fullendDate.split("-") : '';
                        var endYear = parseInt(endArr[0], 10);
                        var endMonth = parseInt(endArr[1], 10);
                        var endDay = parseInt(endArr[2], 10);
                        var title = this.title;
                        var color = this.color ? this.color : '#2B3D4C';
                        var venue = this.venue ? this.venue : '';
                        var id = this.id;
                        var startTime = that.options.ampm === 1 ? this.time_start : that.formatTime(this.time_start);
                        var endTime = that.options.ampm === 1 ? this.time_end : that.formatTime(this.time_end);

                        // function to print out list for multi day events
                        function multidaylist(counter) {
                            var timeHtml = '';
                            var startTimehtml = '';
                            var endTimehtml = '';
                            if (startTime) {
                                startTimehtml = '<div class="time"><div class="start">' + startTime + '</div>';
                                endTimehtml = '';
                                if (endTime) {
                                    endTimehtml = '<div class="end">' + endTime + '</div>';
                                }
                                timeHtml = startTimehtml + endTimehtml + '</div>';
                            }
                            $('.event-item[data-number="' + counter + '"]', that.element).addClass('active').append('<div class="event" data-id="' + id + '" style="background:' + color + '"><a href="' + that.options.murl + 'edit/' + id + '/" data-id="' + id + '">' + title + '</a><p>' + venue + '</p> ' + timeHtml + '</div>');
                            $('.event-item[data-number="' + counter + '"]', that.element);
                        }

                        // If event is one day & within month
                        if (fullstartDate === fullendDate && startMonth === setMonth && startYear === setYear) {
                            // Add Indicators
                            $('*[data-number="' + startDay + '"] .progress', that.element).append('<div class="indicator" data-id="' + id + '" style="background:' + color + '">' + title + '</div>');
                            multidaylist(startDay);

                            // If event is multi day & within month
                        } else if (startMonth === setMonth && startYear === setYear && endMonth === setMonth && endYear === setYear) {
                            for (i = parseInt(startDay); i <= parseInt(endDay); i++) {
                                end = i === parseInt(endDay) ? " end" : '';
                                if (i === parseInt(startDay)) {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator start" data-id="' + id + '" style="background:' + color + '">' + title + '</div>');
                                } else {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator' + end + '" data-id="' + id + '" style="background:' + color + '"></div>');
                                }
                                multidaylist(i);
                            }
                            // If event is multi day, starts in prev month, and ends in current month
                        } else if ((endMonth === setMonth && endYear === setYear) && ((startMonth < setMonth && startYear === setYear) || (startYear < setYear))) {
                            for (i = 0; i <= parseInt(endDay); i++) {
                                end = (i === parseInt(endDay)) ? " end" : '';
                                if (i === 1) {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator start" data-id="' + id + '" style="background:' + color + '">' + title + '</div>');
                                } else {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator' + end + '" data-id="' + id + '" style="background:' + color + '"></div>');
                                }
                                multidaylist(i);
                            }
                            // If event is multi day, starts in this month, but ends in next
                        } else if ((startMonth === setMonth && startYear === setYear) && ((endMonth > setMonth && endYear === setYear) || (endYear > setYear))) {
                            for (i = parseInt(startDay); i <= dayQty; i++) {
                                end = (i === parseInt(endDay)) ? " end" : '';
                                if (i === parseInt(startDay)) {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator start" data-id="' + id + '" style="background:' + color + '">' + title + '</div>');
                                } else {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator' + end + '" data-id="' + id + '" style="background:' + color + '"></div>');
                                }
                                multidaylist(i);
                            }

                            // If event is multi day, starts in a prev month, ends in a future month
                        } else if (((startMonth < setMonth && startYear === setYear) || (startYear < setYear)) && ((endMonth > setMonth && endYear === setYear) || (endYear > setYear))) {
                            for (i = 0; i <= dayQty; i++) {
                                end = i === parseInt(dayQty) ? " end" : '';
                                if (i === 1) {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator start" data-id="' + id + '" style="background:' + color + '">' + title + '</div>');
                                } else {
                                    $('*[data-number="' + i + '"] .progress', that.element).append('<div class="indicator' + end + '" data-id="' + id + '" style="background:' + color + '"></div>');
                                }
                                multidaylist(i);
                            }
                        }
						
                    });
					$(that.element).removeClass('loading');
                }, "json").fail(function() {
                    console.error('Calendar.js failed to import ' + that.options.url + '. Please check for the correct path & json syntax.');
                });

            }
            var divs = $(".cell", that.element);
            for (i = 0; i < divs.length; i += 7) {
                divs.slice(i, i + 7).wrapAll("<div class='weeks'></div>");
            }
        },

        // How many days are in this month?
        daysInMonth: function(month, year) {
            return new Date(year, month, 0).getDate();
        },

        pad: function(n) {
            return n < 10 ? '0' + n : n;
        },

        formatTime: function(time) {
            return time.replace(/(\d?\d)(:\d\d)/, function(_, h, m) {
                return (h > 12 ? h - 12 : +h === 0 ? "12" : +h) + m + (h >= 12 ? "pm" : "am");
            });
        },

        viewToggleButton: function() {
            if ($('.list', this.element).is(":visible")) {
                $('.monthly-cal', this.element).remove();
                $('#now', this.elements.toolbar).after('<div id="back" class="yoyo icon button"><i class="icon calendar"></i></div>');
            }
        }

    });

    $.fn.Calendar = function(options) {
        this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });

        return this;
    };

    $.fn.Calendar.defaults = {
        weekStart: 1,
        ampm: 1,
        mode: 'event',
        url: '',
        murl: '',
        navId: "calnav",
        eventList: true,
        stylePast: true,
        lang: {
            btnSave: 'Save',
            btnCancel: 'Cancel',
            dayNames: ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'],
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        }

    };

})(jQuery, window, document);