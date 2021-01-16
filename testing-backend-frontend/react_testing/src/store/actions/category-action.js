export function insertCategory(user){
    return {
        type: "INSERT_USER",
        payLoad: user
    }
}

export function removeCategory(data){
    return {
        type: "REMOVE_USER",
        payLoad: data
    }
}