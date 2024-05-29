$.validator.addMethod("noMultiSpaces", function(value, element, arg) {
    return this.optional(element) || value.search('  ') === -1;
}, "Enter letters & digits only");

$.validator.addMethod("lettersdigitsonly", function(value, element, arg) {
    return this.optional(element) || /^[a-z0-9]+$/i.test(value);
}, "Enter letters & digits only");

$.validator.addMethod("lettersonly", function(value, element, arg) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Enter letters only");

$.validator.addMethod("notEqual", function(value, element, arg){
    return this.optional(element) || arg != value;
}, "Invalid value");
 
$.validator.addMethod("time24", function(value, element, arg) { 
    return this.optional(element) || /^([01]?[0-9]|2[0-3])(:[0-5][0-9])$/.test(value);
}, "Invalid time format.");

$.validator.addMethod("time12", function(value, element, arg) { 
    return this.optional(element) || /^(((0[1-9])|(1[0-2])):([0-5])(0|5)\s(A|P|a|p)(M|m))$/.test(value);
}, "Invalid time format.");

$.validator.addMethod("date10dashed", function(value, element, arg) { 
    return this.optional(element) || /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(value);
}, "Invalid time format.");

$.validator.addMethod("dateGreaterThan", function(value, element, arg) {
    return this.optional(element) || new Date(value) >= new Date(arg);
}, 'Date must be greater');

$.validator.addMethod("dateLessThan", function(value, element, arg) {
    return this.optional(element) || new Date(value) <= new Date(arg);
}, 'Date must be less');

$.validator.addMethod("date8dashed", function(value, element, arg) { 
    return this.optional(element) || /^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/.test(value);
}, "Invalid time format.");
 
$.validator.addMethod("tel", function(value, element, arg) { 
    return this.optional(element) || /^((00|\+|0)[1-9][0-9]{6,12}|(16|19)[0-9]{2,4})$/.test(value);
}, "Enter a valid tel number");

$.validator.addMethod("sa_mob", function(value, element, arg) { 
    return this.optional(element) || /^(05(0|[3-9])[0-9]{7})$/.test(value);
}, "Enter a valid tel number");
 
$.validator.addMethod("number2decimal", function(value, element, arg) { 
    return this.optional(element) || /^[0-9]+(\.[0-9]{1,2})*$/.test(value);
}, "Enter valid amout");
 
$.validator.addMethod("geo", function(value, element, arg) { 
    return this.optional(element) || /^[0-9]{1,2}(\.[0-9]+)*[\,\:\-][0-9]{1,2}(\.[0-9]+)*$/.test(value);
}, "Enter valid geo value");

$.validator.addMethod("username", function(value, element, arg) {
    return this.optional(element) || /^[a-z0-9][a-z0-9\_\.\-]+$/i.test(value);
}, "Enter letters, digits, (-), (_), or (.) only");

$.validator.addMethod("password", function(value, element, arg) {
    return this.optional(element) || /^[a-z0-9][a-z0-9\_\.\-\!\@\#\$\%\^\&\*\+\=]+$/i.test(value);
}, "Enter letters, digits, (-!@#$%^&*+=) only");

$.validator.addMethod("ip_address", function(value, element, arg) {
    return this.optional(element) || /^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/.test(value);
}, "Enter a valid IP address");