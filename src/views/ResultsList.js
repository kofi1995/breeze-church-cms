import React, { Component } from 'react'
import { PeopleList } from '../components/people-list'
import { FilePicker } from '../components/file-picker'
import { Container, Button } from 'semantic-ui-react';
import { bulkUploadPeopleCsv } from '../datasources/people-datasource'

class ResultsList extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      uploadInprogress: false
    };
  }

  componentDidMount() {
    this.refresh()
  }

  async onFileSelected(event) {
    //TODO show upload progres and disable upload button
    try {
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

  async refresh() {

  }

  render() {
    return (
      <Container>
        {this.state.uploadInprogress
          ?
          <div><Button loading>Uploading</Button> Uploading People, this might take a while </div>
          :
          <FilePicker onClick={this.showFilePicker} onFileSelected={event => this.onFileSelected(event)}>Upload People</FilePicker>
        }
        <PeopleList data={this.state.data}></PeopleList>
      </Container>
    );
  }

}

export default ResultsList
