/* eslint-disable no-undef */
// Use CJS require so Laravel Mix/Babel can bundle this file.
const Turbo = require('@hotwired/turbo');

// Guard against Turbo Drive re-injecting this bundle (CustomElementRegistry:
// "turbo-frame has already been used").
if (!window.Turbo) {
    window.Turbo = Turbo;
    if (typeof Turbo.start === 'function') {
        Turbo.start();
    }
}

module.exports = window.Turbo;
