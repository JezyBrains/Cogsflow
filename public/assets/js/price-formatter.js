/**
 * Price Formatter Utility
 * Adds thousands separator formatting to price input fields
 */

class PriceFormatter {
    constructor() {
        this.separator = ',';
        this.decimal = '.';
    }

    /**
     * Format number with thousands separator
     */
    formatNumber(value) {
        if (!value) return '';
        
        // Remove existing separators and convert to string
        let numStr = value.toString().replace(/,/g, '');
        
        // Split into integer and decimal parts
        let parts = numStr.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1] || '';
        
        // Add thousands separator to integer part
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, this.separator);
        
        // Combine parts
        return decimalPart ? `${integerPart}${this.decimal}${decimalPart}` : integerPart;
    }

    /**
     * Remove formatting to get raw number
     */
    unformatNumber(value) {
        if (!value) return '';
        return value.toString().replace(/,/g, '');
    }

    /**
     * Initialize price formatting for input fields
     */
    initializePriceInputs(selectors = []) {
        const defaultSelectors = [
            '#unit_price',
            '#total_amount', 
            '#advance_payment',
            '#amount',
            'input[name*="price"]',
            'input[name*="amount"]',
            'input[name*="cost"]'
        ];
        
        const allSelectors = [...defaultSelectors, ...selectors];
        
        allSelectors.forEach(selector => {
            const inputs = document.querySelectorAll(selector);
            inputs.forEach(input => this.setupPriceInput(input));
        });
    }

    /**
     * Setup individual price input field
     */
    setupPriceInput(input) {
        if (!input || input.dataset.priceFormatted) return;
        
        // Mark as formatted to avoid double initialization
        input.dataset.priceFormatted = 'true';
        
        // Format existing value
        if (input.value) {
            input.value = this.formatNumber(input.value);
        }

        // Handle input event (while typing)
        input.addEventListener('input', (e) => {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const rawValue = this.unformatNumber(oldValue);
            
            // Only format if it's a valid number
            if (rawValue && !isNaN(rawValue)) {
                const formattedValue = this.formatNumber(rawValue);
                e.target.value = formattedValue;
                
                // Restore cursor position (approximately)
                const newCursorPos = cursorPosition + (formattedValue.length - oldValue.length);
                e.target.setSelectionRange(newCursorPos, newCursorPos);
            }
        });

        // Handle focus event (select all for easy editing)
        input.addEventListener('focus', (e) => {
            // Remove formatting on focus for easier editing
            const rawValue = this.unformatNumber(e.target.value);
            e.target.value = rawValue;
            e.target.select();
        });

        // Handle blur event (format the final value)
        input.addEventListener('blur', (e) => {
            const rawValue = this.unformatNumber(e.target.value);
            if (rawValue && !isNaN(rawValue)) {
                e.target.value = this.formatNumber(rawValue);
            }
        });

        // Handle form submission (remove formatting before submit)
        const form = input.closest('form');
        if (form && !form.dataset.priceFormatterAttached) {
            form.dataset.priceFormatterAttached = 'true';
            form.addEventListener('submit', (e) => {
                // Remove formatting from all price inputs before submission
                const priceInputs = form.querySelectorAll('input[data-price-formatted="true"]');
                priceInputs.forEach(priceInput => {
                    const rawValue = this.unformatNumber(priceInput.value);
                    priceInput.value = rawValue;
                });
            });
        }
    }

    /**
     * Manually format a specific input
     */
    formatInput(selector) {
        const input = document.querySelector(selector);
        if (input) {
            this.setupPriceInput(input);
        }
    }

    /**
     * Get raw value from formatted input
     */
    getRawValue(selector) {
        const input = document.querySelector(selector);
        return input ? this.unformatNumber(input.value) : '';
    }
}

// Global instance
window.priceFormatter = new PriceFormatter();

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.priceFormatter.initializePriceInputs();
});
