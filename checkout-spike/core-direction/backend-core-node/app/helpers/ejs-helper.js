const path = require("path")
const ejs = require("ejs");

module.exports = class EjsHelper {

    static renderFileFromGivenPath(path, data) {
        const pathUptoViewsFolder = EjsHelper.getViewsAbsolutePath();
        return EjsHelper.renderFile(pathUptoViewsFolder + "/" + path, data);
    }

    static renderFileFromGivenPathWithBaseTemplate(path, data) {
        const pathUptoViewsFolder = EjsHelper.getViewsAbsolutePath();
        return EjsHelper.renderChildTemplate(pathUptoViewsFolder + "/" + path, data)
            .then((renderedChildTemplate) => {
                return EjsHelper.renderBaseTemplate(data, renderedChildTemplate)
            });
    }

    static renderChildTemplate(path, data) {
        return EjsHelper.renderFile(path, data);
    }

    static renderBaseTemplate(data, body, customBaseTemplatePath = null) {
        const baseTemplateFilePath = customBaseTemplatePath ? customBaseTemplatePath : EjsHelper.getDefaultTemplateLayout();
        return EjsHelper.renderFile(baseTemplateFilePath, {
            ...data,
            body,
            title: data.title ? data.title : "unknown"
        });
    }

    static renderFile(path, data) {
        return new Promise((resolve, reject) => {
            ejs.renderFile(path, data, function (error, renderedHtmlFile) {
                if (error)
                    return reject(error);
                resolve(renderedHtmlFile);
            });
        });
    }

    static getViewsAbsolutePath() {
        return path.join(__dirname, '../views');
    }

    static getDefaultTemplateLayout() {
        const absolutePathOfViews = EjsHelper.getViewsAbsolutePath();
        return `${absolutePathOfViews}/main-layout.ejs`;
    }
}