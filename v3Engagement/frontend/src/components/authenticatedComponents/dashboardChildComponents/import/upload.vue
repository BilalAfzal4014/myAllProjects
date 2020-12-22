<template>
    <div>
        <div class="page_content_holder">
            <div class="padding_content">
                <div class="white_boxed_content">
                    <div class="import_file_loader">
                        <div class="file_uploader_div">
                            <input type="file" id="import_file" class="hidden" name="imported_file" ref="myFiles"
                                   v-on:change="this.importFile">
                            <label for="import_file"><img src="src/assets/images/upload_icon.png" alt="#"></label>
                            <span style="text-align: center;display: block;" id="fileName">{{filename}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons_strip">
            <button class="right" v-on:click="this.upload">Save</button>
            <button class="right" v-on:click="downloadSampleFile">Download Sample File</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                filename: ""
            }
        },
        destroyed() {
            this.$nextTick(() => {
                $("#footer").show();
            });
        },
        created() {
            this.$nextTick(() => {
                $("#footer").hide();
            });
        },
        methods: {
            downloadSampleFile() {
                window.location.href = this.constants.importBackendUrl();
            },
            importFile() {
                var file = this.$refs.myFiles.files[0];
                this.filename = file.name
            },
            upload() {
                let $this = this;

                let formData = new FormData();
                let file = this.$refs.myFiles.files[0];
                formData.append('file_name', file.name);
                formData.append('file', file);

                this.api("post",
                    this.constants.getUrl("backendApiUrl") + "/imports",
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'Authorization': localStorage.token
                        }
                    },
                    true
                ).then((response) => {
                    if (response.data.status == 200) {
                        this.$swal({
                            position: 'top-end',
                            type: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        $this.$router.push("/dashboard/import-listing");
                    }
                }).catch((error) => {
                    this.$swal({
                        position: 'top-end',
                        type: 'error',
                        title: error[0].response.data.error,
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
            }
        }
    }
</script>