import React, { Component } from 'react'
import { Button } from 'semantic-ui-react'

class FilePicker extends Component {
    constructor(props) {
        super(props)
        this.state = {}
    }

    showFilePicker(event, data) {
        document.getElementById("file-picker").click()
    }

    render() {
        return <div>
            <Button primary onClick={this.showFilePicker}>Upload People</Button>
            <input type="file" hidden id="file-picker" onChange={this.props.onFileSelected}></input>
        </div>

    }
}


export { FilePicker }