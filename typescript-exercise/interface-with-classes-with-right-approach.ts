interface ParentInterface {
    name: string;
    age: number;
    gender?: 'Male' | 'Female'
};

interface ChildInterface extends ParentInterface {
    privileges: Array<string>,
    description: string,
    getPrivileges: () => Array<string>,
};

class Employee implements ParentInterface {
    name;
    age;
    gender;

    constructor(name: string, age: number, gender: 'Male' | 'Female' = null) {
        this.name = name;
        this.age = age;
        if (gender)
            this.gender = gender;
    }
}

const emp1 = new Employee("Bilal", 30, "Male");
console.log(emp1);

class Manager extends Employee implements ChildInterface {
    privileges;
    description;

    constructor(name: string, age: number, privileges: Array<string>, description: string) {
        super(name, age);
        this.privileges = privileges;
        this.description = description;
    }

    getPrivileges(): Array<string> { //if we have typecast it in constructor then no need to write return type here, but i am writing here anyway for best practise
        return this.privileges;
    }

    getDescription(): string {
        return this.description;
    }

}

const mgr = new Manager("Amna", 30, ["manager"], "manager-instance");
console.log(mgr);
console.log(mgr.getPrivileges());
console.log(mgr.getDescription());