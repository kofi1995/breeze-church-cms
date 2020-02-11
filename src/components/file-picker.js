import React, { Component } from 'react'
import { Button } from 'semantic-ui-react'

class FilePicker extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    showFilePicker() {
        document.getElementById(this.props.id).click()
    }

    render() {
        return <div>
            <Button primary onClick={this.showFilePicker.bind(this)}>{this.props.name}</Button>
            <input type="file" hidden id={this.props.id} onChange={this.props.onFileSelected}></input>
        </div>

    }
}


export { FilePicker }