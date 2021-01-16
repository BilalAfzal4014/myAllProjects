export function getCategories() {
    return Promise.resolve([
        {
            id: 1,
            name: "John Doe mocked",
            gender: "Male",
        },
        {
            id: 2,
            name: "Francis Nagannou mocked",
            gender: "Male",
        }
    ]);

}
