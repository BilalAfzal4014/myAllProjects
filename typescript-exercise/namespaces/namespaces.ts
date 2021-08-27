export namespace argumentsSum {
    export function sum(a: number, b: number): number { //need to write export here in-order to use outside of this namespace
        return a + b;
    }
}

export namespace argumentsConcat {
    export function concat(a: string, b: string): string {
        return a + b;
    }
}

// console.log(argumentsSum.sum(1, 1)); //will also work