import React, { Component } from 'react'
import { Table } from 'semantic-ui-react'

class PeopleList extends Component {
    render() {
        let data = this.props.data
        return (
            <Table celled padded>
                <Table.Header>
                    <Table.Row>
                           <Table.HeaderCell>First Name</Table.HeaderCell>
                           <Table.HeaderCell>Last Name</Table.HeaderCell>
                           <Table.HeaderCell>Email</Table.HeaderCell>
                           <Table.HeaderCell>Status</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>
                <Table.Body>
                    {
                        data.map((person, index) => {
                            return (
                                <Table.Row key={index}>
                                    <Table.Cell singleLine>{person.first_name}</Table.Cell>
                                    <Table.Cell singleLine>{person.last_name}</Table.Cell>
                                    <Table.Cell singleLine>{person.email_address}</Table.Cell>
                                    <Table.Cell singleLine>{person.status}</Table.Cell>
                                </Table.Row>
                            );
                        })
                    }
                </Table.Body>
            </Table>
        );
    }
}
export {PeopleList}
