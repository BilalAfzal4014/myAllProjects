class Class{
    static vars = 0;
    constructor() {
        Class.vars++;
        console.log(Class.vars)
    }

}

module.exports = new Class();