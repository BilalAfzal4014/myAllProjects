export function getCategories() {
    return new Promise((resolve, reject) => {

        setTimeout(() => {
            resolve([
                {
                    id: 1,
                    name: "John Doe",
                    gender: "Male",
                },
                {
                    id: 2,
                    name: "Francis Nagannou",
                    gender: "Male",
                }
            ]);
        }, 3000)

    });
}