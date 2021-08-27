function sum(a: number, b: number): number{
    return a + b;
}

function sumThreeNumbers(a: number, b: number, c: number): ReturnType<typeof sum>{
    return sum(a, b) + c;
}

console.log(sumThreeNumbers(1, 2, 3));