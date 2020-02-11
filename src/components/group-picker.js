import React, { Component } from 'react'

class GroupPicker extends React.Component {

    render() {
        let groups = this.props.groups,
            current_value = this.props.current_value
        return <select onChange={event=>this.props.onGroupSelected(event.target.value)}>
            {
                groups.map((group, index) => {
                    return (
                        current_value === group.id
                            ?
                            <option key={index} selected="selected" value={group.id}>{group.group_name}</option>
                            :
                            <option key={index} value={group.id}>{group.group_name}</option>)
                })
            }
        </select>
    }

}

export {GroupPicker}
