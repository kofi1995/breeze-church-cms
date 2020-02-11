import React, { Component } from 'react'
import { Table } from 'semantic-ui-react'
import { GroupPicker } from './group-picker.js'
import { updatePerson } from '../datasources/people-datasource.js'

class GroupList extends Component {

    formatData(data) {
        return data.map((group, index) => {
            return {
                id: group.id,
                group_name: group.group_name,
                people_name: group.people.map((person, index) => {
                    return person.first_name + ' ' + person.last_name;
                }).join(", "),
            }
        })
    }

    render() {
        let data = this.formatData(this.props.data);

        return (
            <Table celled padded>
                <Table.Header>
                    <Table.Row>
                        <Table.HeaderCell>Group Name</Table.HeaderCell>
                        <Table.HeaderCell>People</Table.HeaderCell>
                    </Table.Row>
                </Table.Header>
                <Table.Body>
                    {
                        data.map((group, index) => {
                            return (
                                <Table.Row key={index}>
                                    <Table.Cell singleLine>{group.group_name}</Table.Cell>
                                    <Table.Cell singleLine>{group.people_name}</Table.Cell>
                                </Table.Row>
                            );
                        })
                    }
                </Table.Body>
            </Table>
        );
    }
}
export { GroupList }
