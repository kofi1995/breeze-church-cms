import React, { Component } from 'react'

class GroupPicker extends React.Component {

    render() {
        let groups = this.props.groups,
            current_value = this.props.current_value
        return <select onChange={event=>this.props.onGroupSelected(event.target.value)}>
            {
                groups.map((group, index) => {
                    return (
                        current_value === group.name
                            ?
                            <option key={index} selected="selected">{group.name}</option>
                            :
                            <option key={index}>{group.name}</option>)
                })
            }
        </select>
    }

}

export {GroupPicker} 