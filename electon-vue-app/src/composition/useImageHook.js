import { storeToRefs } from 'pinia';
import {useRegisterStore} from '../store/register.js';
import {useUnRegisterStore} from '../store/unregister.js';

export function useImageHook() {

    const registerStore = useRegisterStore();
    const unRegisterStore = useUnRegisterStore();

    const {
        registerImages,
    } = storeToRefs(registerStore);

    const {
        insert: registerImage,
        remove: removeImageFromRegisterImage,
    } = registerStore;

    const {
        unRegisterImages
    } = storeToRefs(unRegisterStore);

    const {
        insert: insertIntoUnregisterImage,
        remove: removeImageFromUnregisterImage
    } = unRegisterStore;

    const removeImageFromRegister = (image) => {
        removeImageFromRegisterImage(image);
        insertIntoUnregisterImage(image);
    }

    const removeImageFromUnRegister = (image) => {
        removeImageFromUnregisterImage(image);
        registerImage(image);
    }

    return {
        registerImages,
        unRegisterImages,
        removeImageFromRegister,
        removeImageFromUnRegister
    };
}
