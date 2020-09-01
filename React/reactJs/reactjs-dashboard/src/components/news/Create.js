import React, {Component} from 'react'
import Navbar from "../layouts/Navbar";
import Sidebar from "../layouts/Sidebar";
import Footer from "../layouts/Footer";
import axios from "axios";
import Helping from "../../helpers/Helping";
import Notification from "../layouts/Notification";
import ScreenLoad from '../layouts/ScreenLoad'

class Create extends Component {
    constructor(props) {
        super(props)

        this.state = {
            pageTitle: 'Create News',
            buttonTitle: 'Save',
            title: '',
            description: '',
            imageUrl: '',
            selectedFile: null,
            image: '',
            url: URL + 'news',
            routeName: '/news',
            error_message: '',
            error_type: '',
            isLoading: true,
            btnIsEnable: false
        }

        this.onUpdate = this.onUpdate.bind(this)
        this.onSave = this.onSave.bind(this)
        this.handleTitleChange = this.handleTitleChange.bind(this)
        this.handleDescriptionChange = this.handleDescriptionChange.bind(this)
        this.handleSelectedFile = this.handleSelectedFile.bind(this)
    }

    handleTitleChange(e) {
        this.setState({
            title: e.target.value
        })
    }

    handleDescriptionChange(e) {
        this.setState({
            description: e.target.value
        })
    }

    componentDidMount() {
        if (!this.props.match.params.id) {
            this.setState({
                isLoading: false
            })
        }
    }

    componentWillMount() {
        var $this = this

        if (this.props.match.params.id) {
            let id = this.props.match.params.id
            $this.setState({
                pageTitle: 'Edit News',
                buttonTitle: 'Update'
            })

            $this.getById(id)
        }
    }

    getById(id) {
        var $this = this

        axios.get(this.state.url + "/" + id, Helping.getConfig()).then(response => {
            var news = response.data.payload.news
            $this.setState({
                title: news.title,
                description: news.description,
                imageUrl: news.imageUrl,
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
        })
    }

    handleSelectedFile(e) {
        var $this = this
        this.setState({
            selectedFile: e.target.files[0]
        })

        Helping.getBase64(e.target.files[0], (result) => {
            $this.setState({
                image: result
            })
        });
    }

    onSave() {
        let $this = this

        if ($this.state.title.length === 0) {
            $this.setState({
                error_message: "Title should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        if ($this.state.description.length === 0) {
            $this.setState({
                error_message: "Description should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        this.setState({
            btnIsEnable: true
        })

        let data = {
            title: $this.state.title,
            description: $this.state.description,
            image: $this.state.image
        }

        axios.post($this.state.url, data, Helping.getConfig()).then(response => {
            console.log(response.data)
            $this.setState({
                error_message: 'News created successfully.',
                error_type: 'success',
                btnIsEnable: false,
                title: '',
                description: ''
            })
            $this.fileInput.value = "";
        }).catch(error => {
            console.log(error.response.data)
            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false
                })
            }
        })
    }

    onUpdate() {
        let $this = this

        if ($this.state.title.length === 0) {
            $this.setState({
                error_message: "Title should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        if ($this.state.description.length === 0) {
            $this.setState({
                error_message: "Description should not be empty.",
                error_type: 'danger'
            })
            return;
        }

        this.setState({
            btnIsEnable: true
        })

        let data = {
            title: $this.state.title,
            description: $this.state.description,
            image: $this.state.image
        }

        let id = this.props.match.params.id

        axios.put($this.state.url + '/' + id, data, Helping.getConfig()).then(response => {
            console.log(response.data)
            $this.setState({
                error_message: 'News updated successfully.',
                error_type: 'success',
                btnIsEnable: false
            })
            $this.fileInput.value = "";
            $this.getById(id)
        }).catch(error => {
            console.log(error.response.data)
            if (error.response) {
                $this.setState({
                    error_message: error.response.data.message,
                    error_type: 'danger',
                    btnIsEnable: false
                })
            }
        })
    }

    render() {
        if(this.state.isLoading) {
            return (
                <ScreenLoad title={this.state.pageTitle} />
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
                                                   placeholder="Enter title"
                                                   value={this.state.title}
                                                   onChange={this.handleTitleChange}/>
                                        </div>
                                        <div className="form-group">
                                            <textarea className="form-control form-control-user"
                                                      placeholder="Enter description"
                                                      value={this.state.description}
                                                      onChange={this.handleDescriptionChange}>
                                            </textarea>
                                        </div>
                                        <div className="form-group">
                                            <p><b>Image URL</b> <a href={this.state.imageUrl}>{this.state.imageUrl}</a>
                                            </p>
                                            <input type="file" onChange={this.handleSelectedFile} ref={ref=> this.fileInput = ref}/>
                                            <br/>
                                            {this.state.imageUrl &&
                                            <img src={this.state.imageUrl} height="100" alt="News" width="100"/>
                                            }
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