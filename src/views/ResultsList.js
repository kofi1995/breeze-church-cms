import React, { Component } from 'react'
import { PeopleList } from '../components/people-list'
import { FilePicker } from '../components/file-picker'
import { Container, Button } from 'semantic-ui-react';
import { bulkUploadPeopleCsv, fetchPeople, fetchGroups, bulkUploadGroupCsv } from '../datasources/people-datasource'

class ResultsList extends Component {
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

  async onUploadPeople(event) {
    try {
      alert("uploading people")
      this.setState({ uploadInprogress: true })
      await bulkUploadPeopleCsv(event.target.files[0])
      this.setState({ uploadInprogress: false })
      alert("upload successful")
      this.refresh()
    } catch (err) {
      this.setState({ uploadInprogress: false })
      alert(err.message)
    }
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
    let people = await fetchPeople(),
      groups = await fetchGroups()
    this.setState({ data: people, groups: groups })
  }

  render() {
    return (
      <Container>
        {this.state.uploadInprogress
          ?
          <div><Button loading>Uploading</Button> Uploading..., this might take a while </div>
          :
          <div style={{ display: "flex" }}>
            <FilePicker name="Upload People"  id="file-picker-people" onFileSelected={event => this.onUploadPeople(event)}>Upload People</FilePicker>
            <FilePicker name="Upload Groups" id="file-picker-groups" onFileSelected={event => this.onUploadGroups(event)}>Upload Groups</FilePicker>
          </div>
        }
        <PeopleList data={this.state.data} groups={this.state.groups}></PeopleList>
      </Container >
    );
  }

}

export default ResultsList
