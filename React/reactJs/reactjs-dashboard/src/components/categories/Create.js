import React, {Component} from 'react'
import Navbar from "../layouts/Navbar";
import Sidebar from "../layouts/Sidebar";
import Footer from "../layouts/Footer";
import axios from "axios";
import Notification from "../layouts/Notification";
import Helping from "../../helpers/Helping";
import ScreenLoad from '../layouts/ScreenLoad'

class Create extends Component {
    constructor(props) {
        super(props)

        this.state = {
            pageTitle: 'Create List Category',
            buttonTitle: 'Save',
            name: '',
            id: '',
            url: URL + 'create-category',
            routeName: '/categories',
            error_message: '',
            error_type: '',
            isLoading: true,
            btnIsEnable: false
        }

        this.onSave = this.onSave.bind(this)
        this.onUpdate = this.onUpdate.bind(this)
        this.handleNameChange = this.handleNameChange.bind(this)
    }

    componentDidMount() {
        if (!this.props.match.params.id) {
            this.setState({
                isLoading: false
            })
        }
    }

    handleNameChange(e) {
        this.setState({
            name: e.target.value
        })
    }

    componentWillMount() {
        var $this = this

        if (this.props.match.params.id) {
            let id = this.props.match.params.id
            $this.setState({
                pageTitle: 'Edit Category',
                buttonTitle: 'Update'
            })

            axios.get(URL + 'get-category/' + id, Helping.getConfig()).then(response => {
                console.log(response.data)
                $this.setState({
                    id: id,
                    name: response.data.payload.inventoryCategory.name,
                    isLoading: false
                })
            }).catch(error => {
                console.log(error.response)
                if (error.response) {
                    $this.setState({
                        error_message: error.response.data.message,
                        error_type: 'danger',
                        isLoading: false
                    })
                }
            })
        }
    }

    onSave() {
        let $this = this

        if ($this.state.name.length === 0) {
            $this.setState({
                error_message: 'Name should not be empty.',
                error_type: 'danger'
            })
            return false
        }

        this.setState({
            btnIsEnable: true
        })

        axios.post(this.state.url, {name: $this.state.name}, Helping.getConfig()).then(response => {
            console.log(response.data)
            $this.setState({
                error_message: 'Category created successfully.',
                error_type: 'success',
                btnIsEnable: false,
                name: ''
            })
        }).catch(error => {
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

    onUpdate() {
        let $this = this

        if ($this.state.name.length === 0) {
            $this.setState({
                error_message: 'Name should not be empty.',
                error_type: 'danger'
            })
            return false
        }

        this.setState({
            btnIsEnable: true
        })

        let id = this.props.match.params.id

        axios.put(URL + 'update-category/' + id, {name: $this.state.name}, Helping.getConfig()).then(response => {
            console.log(response.data)
            $this.setState({
                error_message: 'Category updated successfully.',
                error_type: 'success',
                btnIsEnable: false
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
                                                   placeholder="Enter name"
                                                   value={this.state.name}
                                                   onChange={this.handleNameChange}/>
                                        </div>
                                        { this.props.match.params.id ? (
                                            <button type="button" style={{float: "right"}} onClick={this.onUpdate}
                                                    className="btn btn-primary" disabled={this.state.btnIsEnable}>
                                                {this.state.buttonTitle}
                                            </button>
                                        ): (
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