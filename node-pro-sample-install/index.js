const module1 = require("@bilalafzal/npm-first-package");
const module2 = require("package-within-package");

console.log(module1.randomNumber.createRandom(2));
console.log(module2.myRandomString.createRandom(3));
console.log(module2.moduleRandomString.generate(4));