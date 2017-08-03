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
    ImageBackground,
    TabBarIOS,
    NavigatorIOS,
    TouchableOpacity,
    TouchableHighlight,
    ActivityIndicator,
    RefreshControl,
    Dimensions
} from 'react-native';

import Icon from 'react-native-vector-icons/Ionicons';
import request from '../../common/request'
import config from '../../common/request_config'
var width = Dimensions.get('window').width

var cachedResults = {
    nextPage:1,
    items:[],
    total:0
}

class Item extends Component {
    constructor(props) {
        super(props);
        this.state = {
            row:this.props.row
        }
    }
    componentDidMount(){
    }
    componentWillReceiveProps(nextProps) {
        console.log('componentWillReceiveProps')
        this.setState({
            row:nextProps.row
        })
    }

    render(){
        var row = this.state.row
        return (
            <TouchableHighlight>
                <View style={styles.item}>
                    <Text style={styles.title}>{row.title}</Text>
                    <ImageBackground style={styles.thumb}
                                     source={{url: row.thumb}}>
                        <Icon
                            name="ios-play"
                            size={28}
                            style={styles.play}
                        />
                    </ImageBackground>
                    <View style={styles.itemFooter}>
                        <View style={styles.handleBox}>
                            <Icon
                                name="ios-heart-outline"
                                size={28}
                                style={styles.up}
                            />
                            <Text style={styles.handleText} numberOfLines={1}>喜欢</Text>
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
}
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
            dataSource: ds.cloneWithRows([]),
            isLoadingTail:false,
            isRefreshing:false
        };
        // super(props);
        // var ds = new ListView.DataSource({rowHasChanged: (r1, r2) => r1 !== r2});
        // this.state = {
        //     dataSource: ds.cloneWithRows(['row 1', 'row 2']),
        // };

    }
    componentDidMount(){
        this._fetchData(1)
    }

    _fetchData(page){
        console.log('_fetchData=>'+ page)

        var that = this
        if(page !== 0){
            this.setState({
                isLoadingTail:true
            })
        }
        else{
            this.setState({
                isRefreshing:true
            })
        }

        request.get(config.api.base + config.api.createions ,
            {
                accessToken:23,
                page:page,
            }
        )
            .then((data) => {
                console.log('data',data)
                if (data.success) {
                    var items = cachedResults.items.slice()

                    if(page !== 0){
                        items = items.concat(data.data)
                        cachedResults.nextPage = cachedResults.nextPage+1

                    }
                    else{
                        items = data.data.concat(items)
                    }
                    cachedResults.items = items
                    cachedResults.total = data.total

                    setTimeout(function () {
                        if(page !== 0){
                            that.setState({
                                isLoadingTail : false,
                                dataSource: that.state.dataSource.cloneWithRows(cachedResults.items)
                            })
                        }
                        else{
                            that.setState({
                                isRefreshing : false,
                                dataSource: that.state.dataSource.cloneWithRows(cachedResults.items)
                            })
                        }

                    },2000)

                }
            })
            .catch((error) => {
            if(page !== 0){
                this.setState({
                    isLoadingTail : false,
                })
            }
            else{
                this.setState({
                    isRefreshing : false,
                })
            }

                console.error(error);
            });
    }
    _reanderRow(row){
        return  (<Item row={row}/>)
    }

    _fetchMoreData(){

        if(!this._hasMore() || this.state.isLoadingTail){
            return;
        }
        this._fetchData(cachedResults.nextPage)
    }

    _hasMore(){
        return cachedResults.items.length !== cachedResults.total
    }
    _renderFooter(){
        if(!this._hasMore() && cachedResults.total !== 0){
            return (
                <View style={styles.loadingMore}>
                    <Text style={styles.loadingText}>没有更多了
                    </Text>
                </View>
            )
        }
        if(!this.state.isLoadingTail){
            return <View style={styles.loadingMore}></View>
        }
        return (
            <ActivityIndicator
                style={styles.loadingMore}
            />
        )
    }
    _onRefresh(){
        if(this.state.isRefreshing || !this._hasMore()){
            return
        }

        this._fetchData(0)
    }
    render() {
        return (
            <View style={styles.container}>
                <View style={styles.header}>
                    <Text style={styles.headerTitle}>头部页面</Text>
                </View>
                <ListView
                    dataSource={this.state.dataSource}
                    renderRow={this._reanderRow}
                    enableEmptySections={true}
                    onEndReached={this._fetchMoreData.bind(this)}
                    refreshControl={
                        <RefreshControl
                            refreshing={this.state.isRefreshing}
                            onRefresh={this._onRefresh.bind(this)}
                            tintColor="#ed7b66"
                            title="刷新中..."
                            titleColor="#ed7b66"
                            // colors={['#ff0000', '#00ff00', '#0000ff']}
                            // progressBackgroundColor="#ffff00"
                        />
                    }
                    onEndReachedThreshold={20}
                    renderFooter={this._renderFooter.bind(this)}

                    showsVerticalScrollIndicator={false} //竖直滚动条

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
    },
    loadingMore:{
        marginVertical:20
    },
    loadingText:{
        color:'#777',
        textAlign:'center'
    }


});