import { defineStore } from 'pinia'
import { reactive, onMounted } from 'vue'

export const useRegisterStore = defineStore('register', () => {
    const registerImages = reactive([]);

    onMounted(() => {
        setTimeout(() => {
            Object.assign(registerImages, [{
                id: 1,
                name: 'Bilal'
            }])
        }, 500);
    });

    const insert = (image) => {
        registerImages.push(image);
    }

    const remove = (imageP) => {
        const index = registerImages.findIndex(image => image.id === imageP.id);
        registerImages.splice(index, 1);
    }

    return { registerImages, insert, remove };
})
