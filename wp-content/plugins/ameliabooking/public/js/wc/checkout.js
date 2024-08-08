function setAmeliaFieldValue(selector, value) {
  let element = document.querySelector(selector);

  if (typeof element !== 'undefined' && element) {
    const valueSetter = Object.getOwnPropertyDescriptor(element, 'value').set;
    const prototype = Object.getPrototypeOf(element);
    const prototypeValueSetter = Object.getOwnPropertyDescriptor(prototype, 'value').set;

    if (valueSetter && valueSetter !== prototypeValueSetter) {
      prototypeValueSetter.call(element, value);
    } else {
      valueSetter.call(element, value);
    }

    element.dispatchEvent(new Event('input', { bubbles: true }));
  }
}

document.addEventListener('DOMContentLoaded', function() {
  if ('ameliaCustomer' in window) {
    let ameliaCustomerInterval = setInterval(
      function () {
        if (document.body.classList.contains('woocommerce-checkout')) {
          clearInterval(ameliaCustomerInterval);
          setAmeliaFieldValue('#email', ameliaCustomer.email);
          setAmeliaFieldValue('#billing-first_name', ameliaCustomer.firstName);
          setAmeliaFieldValue('#billing-last_name', ameliaCustomer.lastName);
          setAmeliaFieldValue('#billing-phone', ameliaCustomer.phone);
        }
      }, 500
    )
  }
});
