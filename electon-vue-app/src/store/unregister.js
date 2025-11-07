import {defineStore} from 'pinia'
import {reactive, onMounted} from 'vue'

export const useUnRegisterStore = defineStore('unregister', () => {
    const unRegisterImages = reactive([]);

    onMounted(() => {
        setTimeout(() => {
            Object.assign(unRegisterImages, [{
                id: 2,
                name: 'Amna'
            }]);
        }, 500);
    });

    const insert = (image) => {
        unRegisterImages.push(image);
    }

    const remove = (imageP) => {
        const index = unRegisterImages.findIndex(image => image.id === imageP.id);
        unRegisterImages.splice(index, 1);
    }

    return {unRegisterImages, insert, remove};
})
