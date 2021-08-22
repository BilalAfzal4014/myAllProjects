interface ParentInterface {
    name: string;
    age: number;
    gender?: 'Male' | 'Female'
};

interface ChildInterface extends ParentInterface {
    privileges: Array<string>,
    description: string,
    getPrivileges: () => Array<string>,
    //getDescription: () => string,
};

class Employee implements ParentInterface { //this will guarantee the presence of all properties (which are in interface) in a class, but do not guarantee type
    name;
    age;
    //age: string; // this will produce error bcz ParentInterface has age as number, although type is not enforced by ParentInterface
    gender;

    constructor(name, age, gender: 'Male' | 'Female' = null) { // to enforce type, we need to duplicate add types here as same in ParentInterface
        this.name = name;
        this.age = age;
        if (gender)
            this.gender = gender;
    }
}

const emp1 = new Employee("Bilal", "30", "Male"); //it will work, as i am passing age type as string
console.log(emp1);

class Manager extends Employee implements ChildInterface {
    privileges;
    description;

    constructor(name, age, privileges, description) {
        super(name, age);
        this.privileges = privileges;
        this.description = description;
    }

    getPrivileges(){ //will guarantee the function return type, but can still pass anything to privileges to bypass typescript type check here
        return this.privileges;
    }

    getDescription(){
        return this.description;
    }

}

const mgr = new Manager("Amna", 30, "manager", "manager-instance");
console.log(mgr);
console.log(mgr.getPrivileges());
console.log(mgr.getDescription());