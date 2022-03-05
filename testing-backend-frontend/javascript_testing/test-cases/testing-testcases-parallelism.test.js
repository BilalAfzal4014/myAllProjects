describe("describe block 1", () => {
    beforeEach(() => {
        console.log("beforeEach run");
    });

    test("test1", () => {

        return new Promise((resolve, reject) => {

            console.log("test1 consoled");
            setTimeout(() => {
                console.log("setTimeout of test1 consoled");
                resolve();
            }, 5000);
        }).then(() => {
            expect({name: "Bilal"}).toEqual({name: "Bilal"});
        });
    });


    test("test2", (done) => {
        console.log("test2 consoled");
        setTimeout(() => {
            try {
                console.log("setTimeout of test2 consoled");
                expect(true).toEqual(true);
                done();
            } catch (e) {
                done(e);
            }
        }, 5000);
    });

    test("test3", () => {
        console.log("test3 consoled");
        expect(true).toEqual(true);
    });

    afterEach(() => {
        console.log("after each run");
    });
});

describe("describe block", () => {
    describe("a", () => {

        test("test1", () => {

            return new Promise((resolve, reject) => {

                console.log("test1 consoled")
                setTimeout(() => {
                    console.log("setTimeout of test1 consoled")
                    resolve();
                }, 5000)
            }).then(() => {
                expect({name: "Bilal"}).toEqual({name: "Bilal"});
            });
        });

    });

    describe("b", () => {

        test("test2", (done) => {
            console.log("test2 consoled")
            setTimeout(() => {
                try {
                    console.log("setTimeout of test2 consoled");
                    expect(true).toEqual(true);
                    done();
                } catch (e) {
                    done(e);
                }
            }, 5000)
        });
    });

    describe("c", () => {
        test("test3", () => {
            console.log("test3 consoled");
            expect(true).toEqual(true);
        });
    });
});

describe("describe block", () => {
    describe("a", () => {

        test("test1", () => {

            return new Promise((resolve, reject) => {

                console.log("test1 consoled")
                setTimeout(() => {
                    console.log("setTimeout of test1 consoled");
                    resolve();
                }, 5000);
            }).then(() => {
                expect({name: "Bilal"}).toEqual({name: "Bilal"});
            });
        });

    });

    describe("b", () => {

        test("test2", (done) => {
            console.log("test2 consoled");
            setTimeout(() => {
                try {
                    console.log("setTimeout of test2 consoled");
                    expect(true).toEqual(true);
                    done();
                } catch (e) {
                    done(e);
                }
            }, 5000)
        });
    });

    describe("c", () => {
        test("test3", () => {
            console.log("test3 consoled");
            expect(true).toEqual(true);
        });
    });
});
