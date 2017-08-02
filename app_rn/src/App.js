/**
 * Created by ql_os on 2017/8/1.
 */
import React, {Component} from 'react';
import {
    TabBarIOS,
    AppRegistry,
    StyleSheet,
    Text,
    View
} from 'react-native';
import TabBarExample from './components/common/TabBarIOS'
import TabBarExampleIcon from './components/common/elicon'
import Icon from 'react-native-vector-icons/FontAwesome';
export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            times: 2,
            hit: true,
        };
    }

    render() {
        return (
            <TabBarExampleIcon />

            //
            // <View style={styles.container}>
            //     <Text style={styles.app}>Apfeafewap</Text>
            //     <Icon name="rocket" size={30} color="#900" />
            // </View>
        )
    }
}
const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'flex-start',
        alignItems: 'center',
        backgroundColor: '#ffffff',
        marginTop:20
    },

})