import React, { Component } from 'react'
import { Table } from 'semantic-ui-react'
import { GroupPicker } from './group-picker.js'
import { updatePerson } from '../datasources/people-datasource.js'

class PeopleList extends Component {

    async updateGroup(person, new_group) {
        let previous_group = person.group_id
        person.group_id = new_group
        try {
            await updatePerson(person)
        } catch (e) {
            alert(e.message)
            //if the update fails, reverse the changes
            person.group = previous_group
        }

    }

    render() {
        let data = this.props.data,
            groups = [{ id: 0, group_name: "No Group" }, ...this.props.groups]
        return (
            <Table celled padded>
                <Table.Header>
                    <Table.Row>
                        <Table.HeaderCell>First Name</Table.HeaderCell>
                        <Table.HeaderCell>Last Name</Table.HeaderCell>
                        <Table.HeaderCell>Email</Table.HeaderCell>
                        <Table.HeaderCell>Status</Table.HeaderCell>
                        <Table.HeaderCell>Group</Table.HeaderCell>
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
                                    <Table.Cell singleLine textAlign='center'>
                                        <GroupPicker groups={groups} current_value={person.group_id} onGroupSelected={(group) => this.updateGroup(person, group)}></GroupPicker>
                                    </Table.Cell>
                                </Table.Row>
                            );
                        })
                    }
                </Table.Body>
            </Table>
        );
    }
}
export { PeopleList }
