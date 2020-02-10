import React, { Component } from 'react'
import { TableList } from '../components/people-list'
import {FilePicker} from '../components/file-picker'
import { Container } from 'semantic-ui-react';

class ResultsList extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],

    };
  }

  componentDidMount() {
    fetch("http://localhost:8000/api/people")
      .then(response => response.json())
      .then(data => this.setState({ data: data.data }));
  }

  onFileSelected(event){
    alert(event.target.files[0].name)
    //TODO show upload progres and disable upload button
  }

  render() {
    return (
      <Container>
          <FilePicker primary onClick={this.showFilePicker} onFileSelected={this.onFileSelected}>Upload People</FilePicker>
          <TableList data={this.state.data}></TableList>
      </Container>
    );
  }

}

export default ResultsList
