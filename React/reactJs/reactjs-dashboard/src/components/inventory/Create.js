import React, {Component} from 'react'
import Navbar from "../layouts/Navbar";
import Sidebar from "../layouts/Sidebar";
import Footer from "../layouts/Footer";
import axios from "axios";
import Helping from "../../helpers/Helping";
import Notification from "../layouts/Notification";
import ScreenLoad from '../layouts/ScreenLoad'

import {Progress} from 'react-sweet-progress';
import "react-sweet-progress/lib/style.css";

class Create extends Component {
    constructor(props) {
        super(props)

        this.state = {
            pageTitle: 'Create Inventory',
            buttonTitle: 'Save',
            name: '',
            category_id: '',
            url: URL + 'create-inventory',
            routeName: '/inventory',
            categories: {},
            categoriesCount: '',
            error_message: '',
            error_type: '',
            androidUrl: '',
            iosUrl: '',
            vrUrl: '',
            inventory: {},
            btnIsEnable: false,
            isLoading: true,
            androidPercent: 0,
            iosPercent: 0,
            vrPercent: 0,
            // serverUrl: 'https://tv-inventory-dev.s3.ap-south-1.amazonaws.com/',
            serverUrl: 'https://tv-inventory-dev.s3.ap-south-1.amazonaws.com/',
            // serverUrl: 'https://tv-inventory.s3.eu-west-2.amazonaws.com/',
            androidAjaxUrl: '',
            iosAjaxUrl: '',
            vrAjaxUrl: '',
        }

        this.onSave = this.onSave.bind(this)
        this.onUpdate = this.onUpdate.bind(this)
        this.handleNameChange = this.handleNameChange.bind(this)
        this.handleCategoryChange = this.handleCategoryChange.bind(this)
        this.handleAndroidUrlChange = this.handleAndroidUrlChange.bind(this)
        this.handleIosUrlChange = this.handleIosUrlChange.bind(this)
        this.handleVrUrlChange = this.handleVrUrlChange.bind(this)
    }

    handleNameChange(e) {
        this.setState({
            name: e.target.value
        })
    }

    handleCategoryChange(e) {
        this.setState({
            category_id: e.target.value
        })
    }

    handleAndroidUrlChange(e) {
        var file = e.target.files[0]

        this.getSignedUrl("android", file.name, file)
        // e.target.value = '';

        /*Helping.getBase64(file, (result) => {
            $this.updateUrls("android", result, file.name)
        });*/
    }

    handleIosUrlChange(e) {
        var file = e.target.files[0]

        this.getSignedUrl("ios", file.name, file)
        // e.target.value = '';

        /*Helping.getBase64(file, (result) => {
            $this.updateUrls("ios", result, file.name)
        });*/
    }

    handleVrUrlChange(e) {
        var file = e.target.files[0]

        this.getSignedUrl("vr", file.name, file)
        // e.target.value = '';

        /*Helping.getBase64(file, (result) => {
            $this.updateUrls("vr", result, file.name)
        });*/
    }

    getSignedUrl(platform, name, file) {
        let sel = document.getElementById("category_id");
        let categoryText = sel.options[sel.selectedIndex].text
        let $this = this

        var extension = name.replace(/^.*\./, '');
        if(extension !== "dlc") {
            $this.setState({
                error_message: "Only dlc file are allowed to upload.",
                error_type: 'danger',
                btnIsEnable: false
            })
            return;
        }

        name = name.replace('.dlc', '')

        if (document.getElementById("category_id").value.length === 0) {
            $this.setState({
                error_message: "Category should not be empty.",
                error_type: 'danger',
                btnIsEnable: false
            })
            return;
        }

        $this.setState({
            btnIsEnable: true,
            error_message: '',
            error_type: ''
        })

        axios.get(URL + 'inventory-url?category=' + categoryText + '&name=' + name + "&platforms=" + platform, Helping.getConfig())
            .then(response => {
                var signedUrl = response.data.payload.signedUrl

                if (platform === "android") {
                    var fileName = signedUrl.android.File_name

                    $this.setState({
                        androidAjaxUrl: signedUrl.android.Url,
                        androidUrl: $this.state.serverUrl + fileName.replace('.dlc', ''),
                        androidPercent: 1,
                        // btnIsEnable: false
                    })
                    $this.uploadToS3(name, file, "android")
                }
                if (platform === "ios") {
                    var fileName = signedUrl.ios.File_name

                    $this.setState({
                        iosAjaxUrl: signedUrl.ios.Url,
                        iosUrl: $this.state.serverUrl + fileName.replace('.dlc', ''),
                        iosPercent: 1,
                        // btnIsEnable: false
                    })
                    $this.uploadToS3(name, file, "ios")
                }
                if (platform === "vr") {
                    var fileName = signedUrl.vr.File_name

                    $this.setState({
                        vrAjaxUrl: signedUrl.vr.Url,
                        vrUrl: $this.state.serverUrl + fileName.replace('.dlc', ''),
                        vrPercent: 1,
                        // btnIsEnable: false
                    })
                    $this.uploadToS3(name, file, "vr")
                }
            })
            .catch(error => {
                console.log(error.response)
                if (error.response) {
                    $this.setState({
                        error_message: error.response.data.message,
                        error_type: 'danger',
                        btnIsEnable: false
                    })
                }
            })
    }

    uploadProgress(evt, platform) {
        let $this = this
        var percent = Math.round(evt.loaded / evt.total * 100);

        if (platform === "android") {
            $this.setState({
                androidPercent: percent,
                btnIsEnable: true
            })
        }
        if (platform === "ios") {
            $this.setState({
                iosPercent: percent,
                btnIsEnable: true
            })
        }
        if (platform === "vr") {
            $this.setState({
                vrPercent: percent,
                btnIsEnable: true
            })
        }
    }

    uploadToS3(name, file, platform) {
        let $this = this
        let ajaxUrl = "";

        if (platform === "android") {
            ajaxUrl = $this.state.androidAjaxUrl
        }

        if (platform === "ios") {
            ajaxUrl = $this.state.iosAjaxUrl
        }

        if (platform === "vr") {
            ajaxUrl = $this.state.vrAjaxUrl
        }

        const xhr = new XMLHttpRequest();
        xhr.open('PUT', ajaxUrl);
        xhr.upload.addEventListener("progress", function (evt) {
            $this.uploadProgress(evt, platform);
        }, false);
        xhr.onreadystatechange = () => {
            if(xhr.readyState === 4){
                $this.setState({
                    btnIsEnable: false
                })
            }
        };
        xhr.send(file);

        return false;

        var formData = new FormData();
        formData.append(name, file);

        let config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: function (progressEvent) {
                let percent = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total));

                if (platform === "android") {
                    $this.setState({
                        androidPercent: percent,
                        btnIsEnable: true
                    })
                }
                if (platform === "ios") {
                    $this.setState({
                        iosPercent: percent,
                        btnIsEnable: true
                    })
                }
                if (platform === "vr") {
                    $this.setState({
                        vrPercent: percent,
                        btnIsEnable: true
                    })
                }

                if(percent === 100) {
                    $this.setState({
                        btnIsEnable: false
                    })
                }
            }.bind(this)
        }

        axios.put(ajaxUrl, formData, config).then(response => {
            console.log(response.data)
        }).catch(error => {
            console.log(error.response.data)
        })
    }

    updateUrls(platform, image, name) {
        let sel = document.getElementById("category_id");
        let categoryText = sel.options[sel.selectedIndex].text
        let $this = this

        if (document.getElementById("category_id").value.length === 0) {
            $this.setState({
                error_message: "Category should not be empty.",
                error_type: 'danger',
                btnIsEnable: false
            })
            return;
        }

        let data = {
            image: image
        }

        $this.setState({
            btnIsEnable: true
        })

        let token = localStorage.token

        let config = {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token
            },
            onUploadProgress: function (progressEvent) {
                let percent = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total));

                if (platform === "android") {
                    $this.setState({
                        androidPercent: percent
                    })
                }
                if (platform === "ios") {
                    $this.setState({
                        iosPercent: percent
                    })
                }
                if (platform === "vr") {
                    $this.setState({
                        vrPercent: percent
                    })
                }
            }.bind(this)
        }

        axios.post(URL + 'inventory-url?category=' + categoryText + '&name=' + name + "&platforms=" + platform, data, config)
            .then(response => {
                if (platform === "android") {
                    $this.setState({
                        androidUrl: response.data.payload.url,
                        btnIsEnable: false
                    })
                }
                if (platform === "ios") {
                    $this.setState({
                        iosUrl: response.data.payload.url,
                        btnIsEnable: false
                    })
                }
                if (platform === "vr") {
                    $this.setState({
                        vrUrl: response.data.payload.url,
                        btnIsEnable: false
                    })
                }
            })
            .catch(error => {
                console.log(error.response)
                if (error.response) {
                    $this.setState({
                        error_message: error.response.data.message,
                        error_type: 'danger',
                        btnIsEnable: false
                    })
                }
            })
    }

    onSave() {
        let $this = this

        if ($this.state.name.length === 0) {
            $this.setState({
                error_message: "Name should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        if ($this.state.category_id.length === 0) {
            $this.setState({
                error_message: "Category should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        this.setState({
            btnIsEnable: true
        })

        let data = {
            name: $this.state.name,
            inventoryCategoryId: $this.state.category_id
        }

        if ($this.state.androidUrl) {
            data["androidUrl"] = $this.state.androidUrl
        }

        if ($this.state.iosUrl) {
            data["iosUrl"] = $this.state.iosUrl
        }

        if ($this.state.vrUrl) {
            data["vrUrl"] = $this.state.vrUrl
        }

        axios.post(this.state.url, data, Helping.getConfig()).then(response => {
            $this.setState({
                error_message: "Inventory created successfully.",
                error_type: 'success',
                btnIsEnable: false,
                name: '',
                category_id: '',
                androidPercent: 0,
                iosPercent: 0,
                vrPercent: 0
            })
            $this.androidFileInput.value = "";
            $this.iosFileInput.value = "";
            $this.vrFileInput.value = "";
        }).catch(error => {
            document.getElementById('nprogress').style.display = 'none';

            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false,
                    androidPercent: 0,
                    iosPercent: 0,
                    vrPercent: 0
                })
            }
        })
    }

    updateInventory(data) {
        let $this = this
        let id = this.props.match.params.id

        axios.put(URL + "update-inventory/" + id, data, Helping.getConfig()).then(response => {
            $this.setState({
                error_message: "Inventory updated successfully.",
                error_type: 'success',
                btnIsEnable: false,
                androidPercent: 0,
                iosPercent: 0,
                vrPercent: 0
            })
            $this.androidFileInput.value = "";
            $this.iosFileInput.value = "";
            $this.vrFileInput.value = "";
            $this.getById(id)
        }).catch(error => {
            document.getElementById('nprogress').style.display = 'none';

            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false,
                    androidPercent: 0,
                    iosPercent: 0,
                    vrPercent: 0
                })
            }
        })
    }

    onUpdate() {
        let $this = this

        let id = this.props.match.params.id

        this.setState({
            btnIsEnable: true
        })

        axios.get(URL + "get-inventory/" + id, Helping.getConfig()).then(response => {
            var inventory = response.data.payload.inventory
            if (Helping.isEmpty(inventory)) {
                $this.setState({
                    error_message: "Inventory not found.",
                    error_type: 'danger',
                    btnIsEnable: false
                })
                return false;
            }

            if ($this.state.name.length === 0) {
                $this.setState({
                    error_message: "Name should not be empty.",
                    error_type: 'danger',
                    btnIsEnable: false
                })
                return;
            }

            if ($this.state.category_id.length === 0) {
                $this.setState({
                    error_message: "Category should not be empty.",
                    error_type: 'danger',
                    btnIsEnable: false
                })
                return;
            }

            var data = {
                name: $this.state.name,
                inventoryCategoryId: $this.state.category_id
            }

            if ($this.state.androidUrl) {
                data["androidUrl"] = $this.state.androidUrl
            }

            if ($this.state.iosUrl) {
                data["iosUrl"] = $this.state.iosUrl
            }

            if ($this.state.vrUrl) {
                data["vrUrl"] = $this.state.vrUrl
            }

            $this.updateInventory(data)
        }).catch(error => {
            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false
                })
            }
        })
    }

    getById(id) {
        let $this = this

        axios.get(URL + "get-inventory/" + id, Helping.getConfig()).then(response => {
            var inventory = response.data.payload.inventory
            if (Helping.isEmpty(inventory)) {
                $this.setState({
                    error_message: "Inventory not found.",
                    error_type: 'danger'
                })
                return false;
            }

            $this.setState({
                inventory: inventory,
                name: inventory.name,
                category_id: inventory.inventoryCategoryId,
                isLoading: false
            })
        }).catch(error => {
            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false
                })
            }
        })
    }

    componentWillMount() {
        this.categories()

        var $this = this

        if (this.props.match.params.id) {
            let id = this.props.match.params.id
            $this.setState({
                pageTitle: 'Edit Inventory',
                buttonTitle: 'Update'
            })
            $this.getById(id)

            /*axios.get(URL + "get-inventory/" + id, Helping.getConfig()).then(response => {
                var inventory = response.data.payload.inventory
                if (Helping.isEmpty(inventory)) {
                    $this.setState({
                        error_message: "Inventory not found.",
                        error_type: 'danger'
                    })
                    return false;
                }

                $this.setState({
                    inventory: inventory,
                    name: inventory.name,
                    category_id: inventory.inventoryCategoryId,
                    isLoading: false
                })
            }).catch(error => {
                if (error.response) {
                    $this.setState({
                        error_message: error.response.data.message,
                        error_type: 'danger',
                        isLoading: false
                    })
                }
            })*/
        }
    }

    componentDidMount() {
        if (!this.props.match.params.id) {
            this.setState({
                isLoading: false
            })
        }
    }

    categories() {
        let $this = this

        axios.get(URL + 'category-list?sorting=id&sort_type=DESC', Helping.getConfig()).then(response => {
            $this.setState({
                categories: response.data.payload.inventoryCategories,
                categoriesCount: response.data.payload.inventoryCategories.length
            })
        }).catch(error => {
            console.log(error.response)
            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger'
                })
            }
        })
    }

    render() {
        if (this.state.isLoading) {
            return (
                <ScreenLoad title={this.state.pageTitle}/>
            )
        }

        return (
            <div id="wrapper">
                <Sidebar history={this.props.history} routeName={this.state.routeName}/>
                <div id="content-wrapper" className="d-flex flex-column">
                    <div id="content">
                        <Navbar/>
                        <div className="container-fluid">
                            {this.state.error_message &&
                            <Notification type={this.state.error_type} message={this.state.error_message}/>
                            }
                            <div className="card shadow mb-4">
                                <div className="card-header py-3">
                                    <h6 className="m-0 font-weight-bold text-primary">{this.state.pageTitle}</h6>
                                </div>
                                <div className="card-body">
                                    <form>
                                        <div className="form-group">
                                            <input type="text" className="form-control form-control-user"
                                                   aria-describedby="emailHelp"
                                                   placeholder="Name (Should be same as OPSkin Inventory)"
                                                   value={this.state.name}
                                                   onChange={this.handleNameChange}/>
                                        </div>
                                        <div className="form-group">
                                            <select className="form-control form-control-user" id="category_id"
                                                    value={this.state.category_id} onChange={this.handleCategoryChange}>
                                                <option value=''>Select category</option>
                                                {this.state.categoriesCount && this.state.categories.map((category, i) => (
                                                    <option key={i} value={category.id}>{category.name}</option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="form-group">
                                            {this.state.androidPercent > 0 &&
                                            <Progress percent={this.state.androidPercent}/>
                                            }

                                            <input type="file" id="android_url" onChange={this.handleAndroidUrlChange} ref={ref=> this.androidFileInput = ref}/>
                                            <p><b>Android URL:</b> <a
                                                href={this.state.inventory.androidUrl}>{this.state.inventory.androidUrl}</a>
                                            </p>
                                        </div>
                                        <div className="form-group">
                                            {this.state.iosPercent > 0 &&
                                            <Progress percent={this.state.iosPercent}/>
                                            }

                                            <input type="file" id="ios_url" onChange={this.handleIosUrlChange} ref={ref=> this.iosFileInput = ref}/>
                                            <p><b>iOS URL:</b> <a
                                                href={this.state.inventory.iosUrl}>{this.state.inventory.iosUrl}</a></p>
                                        </div>
                                        <div className="form-group">
                                            {this.state.vrPercent > 0 &&
                                            <Progress percent={this.state.vrPercent}/>
                                            }

                                            <input type="file" id="vr_url" onChange={this.handleVrUrlChange} ref={ref=> this.vrFileInput = ref}/>
                                            <p><b>VR URL:</b> <a
                                                href={this.state.inventory.vrUrl}>{this.state.inventory.vrUrl}</a></p>
                                        </div>
                                        {this.props.match.params.id ? (
                                            <button type="button" style={{float: "right"}} onClick={this.onUpdate}
                                                    className="btn btn-primary" disabled={this.state.btnIsEnable}>
                                                {this.state.buttonTitle}
                                            </button>
                                        ) : (
                                            <button type="button" style={{float: "right"}} onClick={this.onSave}
                                                    className="btn btn-primary" disabled={this.state.btnIsEnable}>
                                                {this.state.buttonTitle}
                                            </button>
                                        )}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Footer/>
                </div>
            </div>
        )
    }
}

export default Create;