import React from 'react'
import Highcharts from 'highcharts'
import HighchartsReact from 'highcharts-react-official'

function UserStats(props) {
    return (
        <HighchartsReact highcharts={Highcharts} options={props.options}/>
    )
}

export default UserStats