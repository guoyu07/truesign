/**
 * Created by ql_os on 2017/8/4.
 */
import React, { Component } from 'react';
import {
    AppRegistry,
    StyleSheet,
    Text,
    View,
    Image,
    TabBarIOS,
    NavigatorIOS,
    TouchableOpacity,
} from 'react-native';

import Icon from 'react-native-vector-icons/Ionicons';


export default class Detail extends Component {
    render() {
        return (
            <View style={styles.container}>
                <Text>详情页面</Text>
            </View>

        );
    }
}

const styles = StyleSheet.create({
    navigator: {
        flex: 1,
    },
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#F5FCFF',
    },

});