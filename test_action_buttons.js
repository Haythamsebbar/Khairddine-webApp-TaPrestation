// Test script for action buttons functionality

console.log('=== Action Buttons Functionality Test ===');

// Test 1: Check if required functions are defined
console.log('\n1. Checking JavaScript functions:');
const requiredFunctions = ['acceptDemand', 'rejectDemand', 'showDemandDetails', 'showNotification'];

requiredFunctions.forEach(function(funcName) {
    if (typeof window[funcName] === 'function') {
        console.log(`✓ ${funcName} function is defined`);
    } else {
        console.log(`✗ ${funcName} function is missing`);
    }
});

// Test 2: Check if required DOM elements exist
console.log('\n2. Checking DOM elements:');
const requiredElements = [
    '.booking-item',
    '.booking-actions',
    '.action-accept',
    '.action-reject',
    '.action-details'
];

requiredElements.forEach(function(selector) {
    const elements = document.querySelectorAll(selector);
    if (elements.length > 0) {
        console.log(`✓ ${selector} found (${elements.length} elements)`);
    } else {
        console.log(`✗ ${selector} not found`);
    }
});

// Test 3: Check CSS properties
console.log('\n3. Checking CSS properties:');
const bookingItem = document.querySelector('.booking-item');
const bookingActions = document.querySelector('.booking-actions');

if (bookingItem) {
    const position = window.getComputedStyle(bookingItem).position;
    if (position === 'relative') {
        console.log('✓ Booking item has relative positioning');
    } else {
        console.log(`✗ Booking item has ${position} positioning instead of relative`);
    }
}

if (bookingActions) {
    const position = window.getComputedStyle(bookingActions).position;
    if (position === 'absolute') {
        console.log('✓ Action buttons have absolute positioning');
    } else {
        console.log(`✗ Action buttons have ${position} positioning instead of absolute`);
    }
    
    const transform = window.getComputedStyle(bookingActions).transform;
    if (transform.includes('translateY')) {
        console.log('✓ Action buttons have translateY transform');
    } else {
        console.log('✗ Action buttons missing translateY transform');
    }
}

// Test 4: Event listeners
console.log('\n4. Checking event listeners:');
const acceptButton = document.querySelector('.action-accept');
const rejectButton = document.querySelector('.action-reject');
const detailsButton = document.querySelector('.action-details');

if (acceptButton) {
    const events = getEventListeners(acceptButton);
    if (events.click) {
        console.log('✓ Accept button has click event listener');
    } else {
        console.log('✗ Accept button missing click event listener');
    }
}

if (rejectButton) {
    const events = getEventListeners(rejectButton);
    if (events.click) {
        console.log('✓ Reject button has click event listener');
    } else {
        console.log('✗ Reject button missing click event listener');
    }
}

if (detailsButton) {
    const events = getEventListeners(detailsButton);
    if (events.click) {
        console.log('✓ Details button has click event listener');
    } else {
        console.log('✗ Details button missing click event listener');
    }
}

// Test 5: Hover behavior simulation
console.log('\n5. Testing hover behavior:');
if (bookingItem && bookingActions) {
    // Simulate hover
    bookingItem.classList.add('group-hover');
    
    setTimeout(function() {
        const transform = window.getComputedStyle(bookingActions).transform;
        if (transform === 'matrix(1, 0, 0, 1, 0, 0)') {
            console.log('✓ Action buttons appear on hover');
        } else {
            console.log('✗ Action buttons do not appear on hover');
        }
        
        // Remove hover simulation
        bookingItem.classList.remove('group-hover');
    }, 100);
}

console.log('\n=== Test Complete ===');
console.log('Check the browser console for detailed results.');

// Helper function to get event listeners (Chrome DevTools only)
function getEventListeners(element) {
    if (typeof window.getEventListeners === 'function') {
        return window.getEventListeners(element);
    }
    return {};
}