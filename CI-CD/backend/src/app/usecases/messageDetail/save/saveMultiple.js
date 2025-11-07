const BaseUseCase = require('../../baseUsecase');
const SaveMessageDetailUseCase = require('./index');

module.exports = class SaveMultipleMessageDetailUseCase extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        return this.handleSaveScenarios();
    }

    handleSaveScenarios() {
        return this.saveMultiple();
    }

    saveMultiple() {
        const promises = [];
        for (const member of this.data.members)
            promises.push(this.saveSingle(member));
        return Promise.all(promises);
    }

    saveSingle(member) {
        return (new SaveMessageDetailUseCase({
            messageId: this.data.messageId,
            userId: member,
            sender: this.data.sender
        }, this.transactionInstance)).save()
    }

}