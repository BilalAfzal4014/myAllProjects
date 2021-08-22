var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
;
;
var Employee = /** @class */ (function () {
    function Employee(name, age, gender) {
        if (gender === void 0) { gender = null; }
        this.name = name;
        this.age = age;
        if (gender)
            this.gender = gender;
    }
    return Employee;
}());
var emp1 = new Employee("Bilal", "30", "Male"); //it will work, as i am passing age type as string
console.log(emp1);
var Manager = /** @class */ (function (_super) {
    __extends(Manager, _super);
    function Manager(name, age, privileges, description) {
        var _this = _super.call(this, name, age) || this;
        _this.privileges = privileges;
        _this.description = description;
        return _this;
    }
    Manager.prototype.getPrivileges = function () {
        return this.privileges;
    };
    Manager.prototype.getDescription = function () {
        return this.description;
    };
    return Manager;
}(Employee));
var mgr = new Manager("Amna", 30, "manager", "manager-instance");
console.log(mgr);
console.log(mgr.getPrivileges());
console.log(mgr.getDescription());
