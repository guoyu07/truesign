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

import {
    Navigator,
} from 'react-native-deprecated-custom-components'
import Icon from 'react-native-vector-icons/Ionicons';

import List from './creation'
import Edit from './edit'
import Account from './account'


export default class MainPage extends Component {
    constructor(props) {
        super(props);

        this.state = {
            selectedTab: 'List',
        };
    }

    componentWillMount() {
        // https://github.com/facebook/react-native/issues/1403 prevents this to work for initial load
        Icon.getImageSource('ios-settings', 30).then((source) => this.setState({ gearIcon: source }));
    }

    _renderContent(color, pageText) {
        if (!this.state.gearIcon) {
            return false;
        }
        const props = { color, pageText };
        return (
            <NavigatorIOS
                style={styles.navigator}
                initialRoute={{
                    component: ColoredView,
                    passProps: props,
                    title: pageText,
                    rightButtonIcon: this.state.gearIcon,
                }}
            />
        );
    }

    render() {
        return (
            <TabBarIOS
                tintColor="black"
                barTintColor="#F07060">
                <Icon.TabBarItemIOS
                    title="List"
                    iconName="ios-videocam-outline"
                    selectedIconName="ios-videocam"
                    selected={this.state.selectedTab === 'List'}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'List',
                        });
                    }}>
                    <Navigator
                        initialRoute={{
                            name: 'list',
                            component:List
                        }}
                        configureScene={(route) => {
                            return Navigator.SceneConfigs.FloatFromRight
                        }}
                        renderScene={(route,navigator) => {
                            var Component = route.component
                            return <Component {...route.params} navigator={navigator} />
                        }}
                    >
                    </Navigator>
                </Icon.TabBarItemIOS>
                <Icon.TabBarItemIOS
                    title="Edit"
                    iconName="ios-recording-outline"
                    selectedIconName="ios-recording"
                    selected={this.state.selectedTab === 'Edit'}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'Edit',
                        });
                    }}>
                    <Edit></Edit>
                </Icon.TabBarItemIOS>
                <Icon.TabBarItemIOS
                    title="Account"
                    iconName="ios-more-outline"
                    selectedIconName="ios-more"
                    iconColor="#ffffff"
                    selectedIconColor="#000099"
                    selected={this.state.selectedTab === 'Account'}
                    renderAsOriginal={true}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'Account',
                        });
                    }}>
                    <Account/>
                </Icon.TabBarItemIOS>
            </TabBarIOS>
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
    tabContent: {
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
    },
    tabText: {
        color: 'black',
    },
    button: {
        marginTop: 20,
        padding: 8,
        backgroundColor: 'white',
        borderRadius: 4,
    },
});