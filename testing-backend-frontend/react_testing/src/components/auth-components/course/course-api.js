export function getCourses(){
        return new Promise((resolve, reject) => {

            setTimeout(() => {
                resolve("Databases");
            }, 3000)

        });
}