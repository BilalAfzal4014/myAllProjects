var KeyValuePair = /** @class */ (function () {
    function KeyValuePair() {
    }
    KeyValuePair.prototype.setKeyValue = function (key, val) {
        this.key = key;
        this.val = val;
    };
    KeyValuePair.prototype.display = function () {
        console.log("Key = " + this.key + ", val = " + this.val);
    };
    return KeyValuePair;
}());
var kvp1 = new KeyValuePair();
kvp1.setKeyValue(1, "Steve");
kvp1.display();
