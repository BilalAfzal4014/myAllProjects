const BaseUseCase = require('../../baseUsecase');
const SaveMessageUseCase = require('./index');
const SaveMultipleMessageDetailUseCase = require('../../messageDetail/save/saveMultiple');

module.exports = class SaveMessageWithDetailsUseCase extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
        this.messageId = null;

        this.messageContext = {};
    }

    save() {
        return this.saveMessage()
            .then((message) => {
                this.messageContext.message = message;
                return this.saveMessageDetails();
            }).then((messageDetails) => {
                this.messageContext.messageDetails = messageDetails;
                return this.messageContext;
            });
    }


    saveMessage() {
        return (new SaveMessageUseCase({
            userChatGroupId: this.data.userChatGroupId,
            text: this.data.text
        }, this.transactionInstance)).save()
            .then((message) => {
                this.messageId = message.id;
                return message;
            });
    }

    saveMessageDetails() {
        return (new SaveMultipleMessageDetailUseCase({
            messageId: this.messageId,
            members: this.data.members,
            sender: this.data.sender
        }, this.transactionInstance)).save();
    }

}