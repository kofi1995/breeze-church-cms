import React, { Component } from 'react'
import { GroupList } from '../components/group-list'
import { FilePicker } from '../components/file-picker'
import { Container, Button } from 'semantic-ui-react';
import { fetchGroups, bulkUploadGroupCsv } from '../datasources/people-datasource'

class GroupsResultsList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            data: [],
            groups: [],
            uploadInprogress: false
        };
    }

    componentDidMount() {
        this.refresh()
    }

    async onUploadGroups(event) {
        try {
            alert("uploading groups")
            this.setState({ uploadInprogress: true })
            await bulkUploadGroupCsv(event.target.files[0])
            this.setState({ uploadInprogress: false })
            alert("upload successful")
            this.refresh()
        } catch (err) {
            this.setState({ uploadInprogress: false })
            alert(err.message)
        }
    }

    async refresh() {
        let groups = await fetchGroups()
        this.setState({ data: groups})
    }

    render() {
        return (
            <Container>
                {this.state.uploadInprogress
                    ?
                    <div><Button loading>Uploading</Button> Uploading..., this might take a while </div>
                    :
                    <div style={{ display: "flex" }}>
                        <FilePicker name="Upload Groups" id="file-picker-groups" onFileSelected={event => this.onUploadGroups(event)}>Upload Groups</FilePicker>
                    </div>
                }
                <GroupList data={this.state.data}></GroupList>
            </Container >
        );
    }

}

export default GroupsResultsList
