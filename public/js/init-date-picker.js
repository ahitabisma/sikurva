/**
 * Reusable Safari-compatible datepicker component
 *
 * Usage:
 * 1. Import this file in your JS
 * 2. Call: initSafariCompatibleDatepicker('input-id', options, 'placeholder text');
 */

// Browser detection - only run once when script loads
const isSafari = (() => {
    const ua = navigator.userAgent.toLowerCase();
    return ua.indexOf('safari') !== -1 && ua.indexOf('chrome') === -1;
})();

const safariVersion = (() => {
    if (!isSafari) return 0;
    const ua = navigator.userAgent;
    const match = ua.match(/Version\/(\d+)\.(\d+)/);
    return (match && match.length >= 3) ? parseInt(match[1], 10) : 0;
})();

// Safari detection constants
const isOlderSafari = isSafari && safariVersion >= 12 && safariVersion <= 15;
const isSafari14 = isSafari && safariVersion === 14;

// Chrome detection and version parsing
const uaFull = navigator.userAgent;
const isChrome = /Chrome|CriOS/i.test(uaFull) && !/Edg/i.test(uaFull) && !/OPR|Opera/i.test(uaFull);
const chromeVersion = (() => {
    const m = uaFull.match(/(?:Chrome|CriOS)\/(\d+)/i);
    return m ? parseInt(m[1], 10) : 0;
})();
const isOldChrome = isChrome && chromeVersion > 0 && chromeVersion < 111;

// Firefox detection
const isFirefox = /Firefox\/\d+/i.test(uaFull) && !/Seamonkey/i.test(uaFull);
const firefoxVersion = (() => {
    const m = uaFull.match(/Firefox\/(\d+)/i);
    return m ? parseInt(m[1], 10) : 0;
})();

const isOldFirefox = isFirefox && firefoxVersion > 0 && firefoxVersion < 128;

// Unified flag for applying compatibility fixes
const needFixes = isSafari || isOldChrome || isOldFirefox;

// Add browser-specific CSS only once
if (!document.getElementById('flatpickr-browser-fixes')) {
    const browserFixStyle = document.createElement('style');
    browserFixStyle.id = 'flatpickr-browser-fixes';

    // General fixes for all browsers
    let cssContent = `
        .flatpickr-calendar {
            min-width: 280px !important;
        }
        .flatpickr-weekdays {
            display: flex !important;
            width: 100% !important;
        }
        .flatpickr-weekday {
            flex: 1 1 0 !important;
            max-width: 14.28% !important;
            display: block !important;
        }
        .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }
        .flatpickr-monthSelect-month, .flatpickr-yearSelect-month {
            display: inline-block !important;
            width: auto !important;
        }
        /* Improved month header styling for better centering */
        .flatpickr-month {
            display: flex !important;
            width: 100% !important;
            align-items: center !important;
            justify-content: center !important;
            height: 40px !important;
            padding: 0 10px !important;
        }
        .flatpickr-current-month {
            display: flex !important;
            width: 100% !important;
            align-items: center !important;
            justify-content: center !important;
            left: 0 !important;
            position: relative !important;
            padding: 0 !important;
            height: auto !important;
            text-align: center !important;
        }
        /* Fix for month and year dropdowns */
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            display: inline-block !important;
            height: auto !important;
            flex: 0 1 auto !important;
            font-size: inherit !important;
        }
    `;

    // Safari and old Chrome specific fixes
    if (needFixes) {
        cssContent += `
        /* Safari/Old-Chrome only: Fixed styles for year input wrapper */
        .numInputWrapper {
            display: inline-block !important;
            visibility: visible !important;
            position: relative !important;
            height: auto !important;
            flex: 0 1 auto !important;
            margin-left: 15px !important;
            width: 70px !important; /* Fixed width to prevent overlap */
        }
        .flatpickr-current-month .numInputWrapper {
            width: 70px !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
        /* Safari/Old-Chrome only: Critical fix for year input */
        .flatpickr-current-month input.cur-year {
            display: inline-block !important;
            visibility: visible !important;
            height: auto !important;
            font-size: inherit !important;
            width: 50px !important;
            padding: 0 2px !important;
            vertical-align: initial !important;
            background: transparent !important;
            opacity: 1 !important;
            box-sizing: border-box !important;
            text-align: center !important;
            border: none !important;
            outline: none !important;
        }
        .numInputWrapper .cur-year {
            display: inline-block !important;
            visibility: visible !important;
            position: relative !important;
            opacity: 1 !important;
            width: 50px !important;
        }
        /* Safari/Old-Chrome only: Fix for arrow buttons */
        .numInputWrapper span.arrowUp,
        .numInputWrapper span.arrowDown {
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            width: 16px !important;
            height: 16px !important;
            right: -5px !important;
            position: absolute !important;
            cursor: pointer !important;
        }
        .numInputWrapper span.arrowUp {
            top: 0 !important;
        }
        .numInputWrapper span.arrowDown {
            bottom: 0 !important;
        }
        `;
    }
    // Modern Chrome (>=111) and other browsers use default library styles without any custom CSS

    browserFixStyle.textContent = cssContent;
    document.head.appendChild(browserFixStyle);
}

/**
 * Initialize a flatpickr datepicker with Safari compatibility fixes
 * @param {string} inputId - The ID of the input element (without #)
 * @param {object} customOptions - Optional custom flatpickr options
 * @param {string} placeholder - Optional placeholder text for the input
 * @returns {object} Flatpickr instance
 */
function initSafariCompatibleDatepicker(inputId, customOptions = {}, placeholder = "Masukkan tanggal") {
    // Calculate year range (100 years in the past by default)
    const currentYear = new Date().getFullYear();
    const minYear = currentYear - 100;

    // Default options
    const defaultOptions = {
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "d F Y",
        disableMobile: true, // Always disable mobile for consistent behavior
        locale: {
            firstDayOfWeek: 0,
            weekdays: {
                shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
            },
            months: {
                shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                    "September", "Oktober", "November", "Desember"]
            }
        },
        showMonths: 1,
        static: needFixes, // Use static positioning for Safari and old Chrome
        monthSelectorType: "dropdown",
        yearSelectorType: "dropdown",
        minDate: `${minYear}-01-01`,
        maxDate: "today",
        prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>',
        nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>',
        onReady: function (dateObj, dateStr, instance) {
            // Set placeholder on the alt input
            const altInput = instance.altInput;
            if (altInput) {
                altInput.placeholder = placeholder;
            }

            // Apply fixes based on browser - for Safari and old Chrome
            setTimeout(function () {
                instance.redraw();

                // Only apply custom fixes to Safari and old Chrome, let modern Chrome use defaults
                if (needFixes) {
                    fixCalendarDisplay(instance);
                    fixYearAndMonthDisplay(instance);
                    addYearArrowHandlers(instance);
                    addNavigationListeners(instance);

                    // Create a custom dropdown for Safari 14
                    if (isSafari14) {
                        createCustomYearDropdown(instance);
                    }
                }
            }, 200);
        },
        onChange: function (selectedDates, dateStr, instance) {
            // Only apply fixes to Safari and old Chrome
            if (needFixes) {
                setTimeout(function () {
                    fixCalendarDisplay(instance);
                    fixYearAndMonthDisplay(instance);
                }, 50);
            }
        },
        onOpen: function (selectedDates, dateStr, instance) {
            // Only apply fixes to Safari and old Chrome
            if (needFixes) {
                setTimeout(function () {
                    fixCalendarDisplay(instance);
                    fixYearAndMonthDisplay(instance);
                    addYearArrowHandlers(instance);
                    addNavigationListeners(instance);
                }, 50);
            }
        },
        onMonthChange: function (selectedDates, dateStr, instance) {
            // Only apply fixes to Safari and old Chrome
            if (needFixes) {
                setTimeout(function () {
                    fixYearAndMonthDisplay(instance);
                }, 10);
            }
        },
        onYearChange: function (selectedDates, dateStr, instance) {
            // Only apply fixes to Safari and old Chrome
            if (needFixes) {
                setTimeout(function () {
                    // Multiple timeouts for better reliability
                    forceUpdateYear(instance);
                    setTimeout(() => forceUpdateYear(instance), 50);
                    setTimeout(() => forceUpdateYear(instance), 100);
                }, 10);
            }
        }
    };

    // Function to force update year display
    function forceUpdateYear(instance) {
        if (instance.currentYearElement && instance.currentYear) {
            // Make the year visible and set its value
            instance.currentYearElement.value = instance.currentYear;
            instance.currentYearElement.style.display = 'inline-block';
            instance.currentYearElement.style.visibility = 'visible';
            instance.currentYearElement.style.opacity = '1';

            // Safari/Old-Chrome specific wrapper styling
            if (needFixes) {
                const wrapper = instance.currentYearElement.parentNode;
                if (wrapper) {
                    wrapper.style.display = 'inline-block';
                    wrapper.style.visibility = 'visible';
                    wrapper.style.opacity = '1';
                    wrapper.style.marginLeft = '15px';
                    wrapper.style.width = '70px';
                    wrapper.style.position = 'relative';
                }
            }

            // Update custom dropdown if it exists
            const yearSelect = instance.calendarContainer.querySelector('.yearSelect-dropdown');
            if (yearSelect) {
                yearSelect.value = instance.currentYear;
            }
        }
    }

    // Merge default options with custom options
    const options = { ...defaultOptions, ...customOptions };

    // Initialize flatpickr with merged options
    const fpInstance = flatpickr(`#${inputId}`, options);

    // Helper functions for compatibility
    function fixCalendarDisplay(instance) {
        if (!instance || !instance.calendarContainer) return;

        // Force visibility of weekday container
        const weekdayContainer = instance.calendarContainer.querySelector('.flatpickr-weekdays');
        if (weekdayContainer) {
            weekdayContainer.style.display = 'flex';
            weekdayContainer.style.width = '100%';
        }

        // Make sure all weekdays are visible
        const weekdays = instance.calendarContainer.querySelectorAll('.flatpickr-weekday');
        if (weekdays.length > 0) {
            weekdays.forEach(function (day) {
                day.style.display = 'block';
                day.style.flexBasis = '14.28%';
                day.style.maxWidth = '14.28%';
                day.style.padding = '0';
                day.style.textAlign = 'center';
            });
        }

        // Force calendar to have correct width
        const calendar = instance.calendarContainer.querySelector('.flatpickr-calendar');
        if (calendar) {
            calendar.style.width = 'auto';
            calendar.style.minWidth = '280px';
        }

        // Make days container full width
        const daysContainer = instance.calendarContainer.querySelector('.flatpickr-days');
        if (daysContainer) {
            daysContainer.style.width = '100%';
        }
    }

    function fixYearAndMonthDisplay(instance) {
        if (!instance || !instance.calendarContainer) return;

        // Get the current month/year elements
        const monthElement = instance.calendarContainer.querySelector('.flatpickr-month');
        const currentMonthElement = instance.calendarContainer.querySelector('.flatpickr-current-month');
        const currentYearElement = instance.calendarContainer.querySelector('.numInputWrapper');
        const yearInput = instance.currentYearElement;
        const monthSelect = instance.calendarContainer.querySelector('.flatpickr-monthDropdown-months');

        // Center the month container
        if (monthElement) {
            monthElement.style.display = 'flex';
            monthElement.style.width = '100%';
            monthElement.style.alignItems = 'center';
            monthElement.style.justifyContent = 'center';
            monthElement.style.padding = '0 10px';
            monthElement.style.height = '40px';
        }

        // Center current month section
        if (currentMonthElement) {
            currentMonthElement.style.display = 'flex';
            currentMonthElement.style.width = '100%';
            currentMonthElement.style.alignItems = 'center';
            currentMonthElement.style.justifyContent = 'center';
            currentMonthElement.style.padding = '0';
            currentMonthElement.style.position = 'relative';
            currentMonthElement.style.left = '0';
        }

        // Style month selector
        if (monthSelect) {
            monthSelect.style.display = 'inline-block';
            monthSelect.style.height = 'auto';
        }

        // Browser-specific styling
        if (needFixes) {
            // Safari/Old-Chrome specific year input wrapper styling
            if (currentYearElement) {
                currentYearElement.style.display = 'inline-block';
                currentYearElement.style.visibility = 'visible';
                currentYearElement.style.margin = '0 0 0 15px';
                currentYearElement.style.verticalAlign = 'middle';
                currentYearElement.style.width = '70px';
                currentYearElement.style.position = 'relative';
            }

            // Safari/Old-Chrome specific year input styling
            if (yearInput) {
                yearInput.style.display = 'inline-block';
                yearInput.style.visibility = 'visible';
                yearInput.style.opacity = '1';
                yearInput.style.width = '50px';
                yearInput.style.textAlign = 'center';
                yearInput.style.padding = '0 2px';
                yearInput.style.height = 'auto';
                yearInput.style.fontSize = 'inherit';
                yearInput.style.verticalAlign = 'initial';
                yearInput.style.border = 'none';
                yearInput.style.outline = 'none';
                yearInput.style.background = 'transparent';
                yearInput.style.boxSizing = 'border-box';

                // Make sure the year value is set correctly
                if (instance.currentYear) {
                    yearInput.value = instance.currentYear;
                }

                // Add input event listener if not already added
                if (!yearInput.hasAttribute('year-input-handler-added')) {
                    yearInput.setAttribute('year-input-handler-added', 'true');
                    yearInput.addEventListener('input', function () {
                        const yearSelect = instance.calendarContainer.querySelector('.yearSelect-dropdown');
                        if (yearSelect && yearInput.value) {
                            yearSelect.value = yearInput.value;
                        }
                    });
                }
            }

            // Safari/Old-Chrome specific arrow button styling
            const arrowUp = instance.calendarContainer.querySelector('.arrowUp');
            const arrowDown = instance.calendarContainer.querySelector('.arrowDown');

            if (arrowUp) {
                arrowUp.style.opacity = '1';
                arrowUp.style.visibility = 'visible';
                arrowUp.style.display = 'block';
                arrowUp.style.position = 'absolute';
                arrowUp.style.right = '2px';
                arrowUp.style.top = '0';
                arrowUp.style.width = '16px';
                arrowUp.style.height = '16px';
                arrowUp.style.cursor = 'pointer';
            }

            if (arrowDown) {
                arrowDown.style.opacity = '1';
                arrowDown.style.visibility = 'visible';
                arrowDown.style.display = 'block';
                arrowDown.style.position = 'absolute';
                arrowDown.style.right = '2px';
                arrowDown.style.bottom = '0';
                arrowDown.style.width = '16px';
                arrowDown.style.height = '16px';
                arrowDown.style.cursor = 'pointer';
            }
        } else {
            // Chrome and other browsers - use default library styles, no custom styling
            if (yearInput && instance.currentYear) {
                yearInput.value = instance.currentYear;
            }
        }
    }

    function addYearArrowHandlers(instance) {
        if (!instance || !instance.calendarContainer) return;

        const numInputWrapper = instance.calendarContainer.querySelector('.numInputWrapper');
        if (!numInputWrapper) return;

        const arrowUp = numInputWrapper.querySelector('.arrowUp');
        const arrowDown = numInputWrapper.querySelector('.arrowDown');

        if (arrowUp) {
            // Remove existing listeners to prevent duplicates
            arrowUp.removeEventListener('click', yearArrowClickHandler);
            // Add click listener
            arrowUp.addEventListener('click', yearArrowClickHandler);
        }

        if (arrowDown) {
            // Remove existing listeners to prevent duplicates
            arrowDown.removeEventListener('click', yearArrowClickHandler);
            // Add click listener
            arrowDown.addEventListener('click', yearArrowClickHandler);
        }

        // Function to handle year arrow clicks
        function yearArrowClickHandler() {
            // Use multiple timeouts with increasing delays for reliability
            setTimeout(updateYearDisplays, 10);
            setTimeout(updateYearDisplays, 50);
            setTimeout(updateYearDisplays, 100);
            setTimeout(updateYearDisplays, 200);
            setTimeout(updateYearDisplays, 300);
        }

        function updateYearDisplays() {
            // Force year to be visible and updated with all possible style enforcers
            if (instance.currentYearElement && instance.currentYear) {
                // Update value and ensure visibility
                instance.currentYearElement.value = instance.currentYear;

                // Apply multiple CSS properties to ensure visibility
                const styles = {
                    'display': 'inline-block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'position': 'relative',
                    'width': '50px', // Fixed width instead of ch units
                    'textAlign': 'center',
                    'height': 'auto',
                    'fontSize': 'inherit',
                    'verticalAlign': 'initial',
                    'border': 'none',
                    'boxSizing': 'border-box',
                    'outline': 'none',
                    'background': 'transparent',
                    'padding': '0 2px'
                };

                Object.assign(instance.currentYearElement.style, styles);

                // Apply styles to parent container too with increased margin
                const wrapper = instance.currentYearElement.parentNode;
                if (wrapper) {
                    Object.assign(wrapper.style, {
                        'display': 'inline-block',
                        'visibility': 'visible',
                        'opacity': '1',
                        'position': 'relative',
                        'margin': '0 0 0 15px', // Increased from 5px to 15px
                        'width': '70px' // Fixed width to prevent overlap
                    });
                }

                // Fix month element centering
                const currentMonth = wrapper ? wrapper.parentNode : null;
                if (currentMonth) {
                    Object.assign(currentMonth.style, {
                        'display': 'flex',
                        'justifyContent': 'center',
                        'alignItems': 'center'
                    });
                }

                // Update custom dropdown if it exists
                const yearSelect = instance.calendarContainer.querySelector('.yearSelect-dropdown');
                if (yearSelect) {
                    yearSelect.value = instance.currentYear;
                }
            }
        }
    }

    function addNavigationListeners(instance) {
        if (!instance || !instance.calendarContainer) return;

        // Get the month navigation buttons
        const prevMonthButton = instance.calendarContainer.querySelector('.flatpickr-prev-month');
        const nextMonthButton = instance.calendarContainer.querySelector('.flatpickr-next-month');

        if (prevMonthButton) {
            // Remove existing listeners to prevent duplicates
            prevMonthButton.removeEventListener('click', navClickHandler);
            // Add click listener
            prevMonthButton.addEventListener('click', navClickHandler);
        }

        if (nextMonthButton) {
            // Remove existing listeners to prevent duplicates
            nextMonthButton.removeEventListener('click', navClickHandler);
            // Add click listener
            nextMonthButton.addEventListener('click', navClickHandler);
        }

        // Function to handle navigation clicks
        function navClickHandler() {
            // Use multiple timeouts for reliability
            setTimeout(() => fixYearAndMonthDisplay(instance), 10);
            setTimeout(() => fixYearAndMonthDisplay(instance), 50);
            setTimeout(() => fixYearAndMonthDisplay(instance), 100);
        }
    }

    function createCustomYearDropdown(instance) {
        if (!instance || !instance.currentYearElement) return;

        // Only create if not already created
        if (instance.calendarContainer.querySelector('.yearSelect-dropdown')) return;

        // Get the current year and create year range
        const currentYear = new Date().getFullYear();
        const yearContainer = instance.currentYearElement.parentNode;

        if (!yearContainer) return;

        // Create a select element for years
        const yearSelect = document.createElement('select');
        yearSelect.className = 'yearSelect-dropdown';
        yearSelect.setAttribute('aria-label', 'Year');

        // Add years (100 years in the past)
        for (let year = currentYear; year >= currentYear - 100; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }

        // Set initial value to the currently selected year
        yearSelect.value = instance.currentYear;

        // Handle year change
        yearSelect.addEventListener('change', function () {
            const newYear = parseInt(this.value, 10);
            instance.currentYear = newYear;
            instance.redraw();

            // Force update the year with multiple timeouts for reliability
            setTimeout(updateAfterYearChange, 10);
            setTimeout(updateAfterYearChange, 50);
            setTimeout(updateAfterYearChange, 150);

            function updateAfterYearChange() {
                if (instance.currentYearElement) {
                    // Update both the native year element and our custom dropdown
                    instance.currentYearElement.value = newYear;

                    // Force visibility
                    instance.currentYearElement.style.display = 'inline-block';
                    instance.currentYearElement.style.visibility = 'visible';
                    instance.currentYearElement.style.opacity = '1';

                    // Make sure any parent containers are also visible
                    const wrapper = instance.currentYearElement.parentNode;
                    if (wrapper) {
                        wrapper.style.display = 'inline-block';
                        wrapper.style.visibility = 'visible';
                        wrapper.style.opacity = '1';
                        wrapper.style.marginLeft = '15px'; // Adjusted margin
                        wrapper.style.width = '70px'; // Fixed width to prevent overlap
                        wrapper.style.position = 'relative';
                    }

                    // Trigger input event to propagate value changes
                    instance.currentYearElement.dispatchEvent(new Event('input'));
                    yearSelect.value = newYear;
                }
            }
        });

        // Replace the numeric input with our dropdown
        yearContainer.style.position = 'relative';
        yearContainer.appendChild(yearSelect);

        // Style the dropdown
        yearSelect.style.position = 'absolute';
        yearSelect.style.left = '0';
        yearSelect.style.top = '0';
        yearSelect.style.width = '100%';
        yearSelect.style.height = '100%';
        yearSelect.style.cursor = 'pointer';
        yearSelect.style.zIndex = '1';
        yearSelect.style.opacity = '0'; // Hide visually but keep functional
    }

    return fpInstance;
}
