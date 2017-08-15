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


class ColoredView extends Component {
    componentWillMount() {
        Icon.getImageSource('md-arrow-back', 30).then((source) => this.setState({ backIcon: source }));
    }

    _navigateToSubview() {
        this.props.navigator.push({
            component: ColoredView,
            title: this.props.pageText,
            leftButtonIcon: this.state.backIcon,
            onLeftButtonPress: () => this.props.navigator.pop(),
            passProps: this.props,
        });
    }

    render() {
        return (
            <View style={[styles.tabContent, {backgroundColor: this.props.color}]}>
                <Text style={styles.tabText}>{this.props.pageText}</Text>
                <TouchableOpacity onPress={() => this._navigateToSubview()}>
                    <View style={styles.button}><Text style={styles.buttonText}>Tap Me</Text></View>
                </TouchableOpacity>
            </View>
        );
    }
}

export default class MainPage extends Component {
    constructor(props) {
        super(props);

        this.state = {
            selectedTab: 'videoshow',
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
                barTintColor="#535353">
                <Icon.TabBarItemIOS
                    title="Home"
                    iconName="ios-videocam-outline"
                    selectedIconName="ios-videocam"
                    selected={this.state.selectedTab === 'videoshow'}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'videoshow',
                        });
                    }}>
                    {this._renderContent('#e8e8e8', 'Home')}
                </Icon.TabBarItemIOS>
                <Icon.TabBarItemIOS
                    title="Profile"
                    iconName="ios-recording-outline"
                    selectedIconName="ios-recording"
                    selected={this.state.selectedTab === 'profile'}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'profile',
                        });
                    }}>
                    {this._renderContent('#e8e8e8', 'Profile')}
                </Icon.TabBarItemIOS>
                <Icon.TabBarItemIOS
                    title="Settings"
                    iconName="ios-more-outline"
                    selectedIconName="ios-more"
                    iconColor="#ffffff"
                    selectedIconColor="#000099"
                    selected={this.state.selectedTab === 'settings'}
                    renderAsOriginal={true}
                    onPress={() => {
                        this.setState({
                            selectedTab: 'settings',
                        });
                    }}>
                    {this._renderContent('#e8e8e8', 'Settings')}
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