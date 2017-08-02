/**
 * Created by ql_os on 2017/8/2.
 */
import React, { Component } from 'react';
import {
    AppRegistry,
    StyleSheet,
    Text,
    View,
    ListView,
    Image,
    TabBarIOS,
    NavigatorIOS,
    TouchableOpacity,
    TouchableHighlight,
    Dimensions
} from 'react-native';

import Icon from 'react-native-vector-icons/Ionicons';
// import Mock from 'mockjs'
var width = Dimensions.get('window').width
export default class List extends Component {
    constructor(props) {
        super(props);
        var ds = new ListView.DataSource({
            rowHasChanged: (r1, r2) => r1 !== r2
        })
        var m_data = [
            {
                "_id":"540000199204221494","thumb":"https://dummyimage.com/1280x720/4209ca)","title":"测试内容rvh4","video":"https://pro-jp1.iamsee.com/video.mp4"
            }
            ,
            {
                "_id":"500000200506266696","thumb":"https://dummyimage.com/1280x720/940c5e)","title":"测试内容rvh4","video":"https://pro-jp1.iamsee.com/video.mp4"
            }
            ,
            {
                "_id":"650000199003131148","thumb":"https://dummyimage.com/1280x720/24f26a)","title":"测试内容rvh4","video":"https://pro-jp1.iamsee.com/video.mp4"
            }
            ,
            {
                "_id":"140000200409032006","thumb":"https://dummyimage.com/1280x720/810aac)","title":"测试内容rvh4","video":"https://pro-jp1.iamsee.com/video.mp4"
            }
         ]
        // for(let i=0;i<=1000;i++){
        //     m_data.push({
        //         "_id":"510000199704195354","thumb":"https://dummyimage.com/1200x600/9a79f7)","video":"https://pro-jp1.iamsee.com/video.mp4"
        //     })
        // }
        this.state = {
            dataSource: ds.cloneWithRows(m_data)
        };
        // super(props);
        // var ds = new ListView.DataSource({rowHasChanged: (r1, r2) => r1 !== r2});
        // this.state = {
        //     dataSource: ds.cloneWithRows(['row 1', 'row 2']),
        // };

    }
    componentDidMount(){
        this._fetchData2()
    }
    _fetchData1(){
        fetch('https://mywebsite.com/endpoint/', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                firstParam: 'yourValue',
                secondParam: 'yourOtherValue',
            })
        })
    }
    _fetchData2(){
        fetch('http://rapapi.org/mockjs/23666/api/creations?accessToken=23')
            .then((response) => response.json())
            .then((responseJson) => {
                console.log(responseJson)
            })
            .catch((error) => {
                console.error(error);
            });
    }
    reanderRow(row){
        return (
            <TouchableHighlight>
                <View style={styles.item}>
                    <Text style={styles.title}>{row.title}</Text>
                    <Image style={styles.thumb}
                    source={{url: row.thumb}}>
                    <Icon
                        name="ios-play"
                        size={28}
                        style={styles.play}
                    />
                    </Image>
                    <View style={styles.itemFooter}>
                        <View style={styles.handleBox}>
                            <Icon
                                name="ios-heart-outline"
                                size={28}
                                style={styles.up}
                            />
                            <Text style={styles.handleText} numberOfLines={1}>喜欢a2321321321aaa</Text>
                        </View>
                        <View style={styles.handleBox}>
                            <Icon
                                name="ios-chatboxes-outline"
                                size={28}
                                style={styles.commentIcon}
                            />
                            <Text style={styles.handleText}>评论</Text>
                        </View>
                    </View>
                </View>
            </TouchableHighlight>

        )
    }
    render() {
        return (
            <View style={styles.container}>
                <View style={styles.header}>
                    <Text style={styles.headerTitle}>头部页面</Text>
                </View>
                <ListView
                    dataSource={this.state.dataSource}
                    renderRow={this.reanderRow}
                    enableEmptySections={true}
                />
            </View>

        );
    }
}




const styles = StyleSheet.create({

    container: {
        flex: 1,

        backgroundColor: '#F5FCFF',
    },
    header:{
        paddingTop:25,
        paddingBottom:12,
        backgroundColor:'#ee735c',
    },
    headerTitle:{
        color:'#fff',
        fontSize:16,
        textAlign:'center',
        fontWeight:'600'
    },
    item:{
        width:width,
        marginBottom:10,
        backgroundColor:'#fff',

    },
    thumb:{
        width:width,
        height: width * 0.56,
        resizeMode: 'cover'
    },
    title:{
        padding:10,
        fontSize:18,
        color:'#333',

    },
    itemFooter:{
        flexDirection:'row',
        justifyContent:'space-between',
        backgroundColor:'#eee',
    },
    handleBox:{
        padding:10,
        flexDirection:'row',
        justifyContent:'center',
        width:width / 2 - 0.5,
        backgroundColor:'#fff'
    },
    play:{
        position:'absolute',
        bottom: 14,
        right: 14,
        width: 46,
        height: 46,
        paddingTop:9,
        paddingLeft: 18,
        backgroundColor: 'transparent',
        borderColor:'#fff',
        borderWidth:1,
        borderRadius:23,
        color:'#ed7b66'
    },
    handleText:{
      paddingLeft:12,
        fontSize:18,
        color:'#333'
    },
    up:{
        fontSize:22,
        color:'#333',
    },
    commentIcon:{
        fontSize:22,
        color:'#333'
    }


});