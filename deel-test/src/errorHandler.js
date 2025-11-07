const returnErrorMessageIfCustom = (error) => {
    try {
        const errorInfo = JSON.parse(error.message);
        return errorInfo.custom ? errorInfo.message : '';
    } catch (_) {
        return '';
    }
};

module.exports = {
    returnErrorMessageIfCustom
};