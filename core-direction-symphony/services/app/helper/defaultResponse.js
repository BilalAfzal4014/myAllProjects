function createSuccessResponse(code, message, data) {
    return {
        "meta": {
            "code": code,
            "status": message
        },
        "data": data,
    };
}

function createErrorResponse(code, message, error) {
    return {
        "meta": {
            "code": code,
            "status": message
        },
        "error": error,
    };
}

module.exports = {
    createSuccessResponse,
    createErrorResponse
};