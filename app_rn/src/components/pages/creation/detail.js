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
    Dimensions,
    ActivityIndicator,
    ListView,
    ScrollView,
    TextInput,
    Modal,
    Button,
    Alert
} from 'react-native';

import Icon from 'react-native-vector-icons/Ionicons';
import Video from 'react-native-video'
import VideoPlayer from 'react-native-video-player'
var width = Dimensions.get('window').width
import request from '../../common/request'
import config from '../../common/request_config'

var cacheResult = {
    nextPage: 1,
    items: [],
    total: 0
}
export default class Detail extends Component {
    // static navigationOptions = ({navigation,screenProps}) => ({
    //     // 这里面的属性和App.js的navigationOptions是一样的。
    //     rowData: navigation.state.params.rowData,
    //     headerTitle:navigation.state.params?navigation.state.params.headerTitle:'Detail1',
    //     headerRight:(
    //         <Text style={{color:'red',marginRight:20}} onPress={()=>navigation.state.params?navigation.state.params.navigatePress():null}>我的</Text>
    //     ),
    // });
    constructor(props) {
        super(props);
        // console.log('props')
        // console.log(this.props)
        var ds = new ListView.DataSource({
            rowHasChanged: (r1, r2) => r1 !== r2
        })
        this.state = {
            rowData: this.props.navigation.state.params.rowData,
            rate:1,
            muted:false,
            resizeMode:'contain',
            repeat:false,

            videoReady:false,

            videoProgress:0.01,
            videoTotal:0,
            currentTime:0,

            dataSource: ds.cloneWithRows([]),

            modalVisible:false,
            animationType:'none',
            isSending:false,
            content:''
        };


    }
    componentDidMount(nextProps) {
        // 通过在componentDidMount里面设置setParams将title的值动态修改
        // this.props.navigation.setParams({
        //     // headerTitle:'Detail1',
        //     // navigatePress:this.navigatePress,
        // });
        // this.setState({
        //     rowData : nextProps.rowData
        // })
        this._fetchData()
        this._setModalVisible(false)

    }
    _fetchData(){
        console.log('_fetchData')
        var that = this
        var url = config.api.base + config.api.comment
        console.log(url)
        request.get(url, {
            id: 124,
            accessToken: '123a'
        })
            .then( (data) => {
                console.log('data=>')
                console.log(data)
                if(data && data.success){
                    var comments = data.data
                    cacheResult.items = comments
                    if(comments && comments.length >0){

                        that.setState({
                            comments: comments,
                            dataSource: that.state.dataSource.cloneWithRows(comments)
                        })
                    }
                }
            })
            .catch((error)=>{
                console.log(error)
            })
    }
    _onLoadStart(){
        console.log('_onLoadStart')
    }
    _onLoad(){
        console.log('_onLoad')

    }
    _onProgress(data){
        if(!this.state.videoReady){
            this.setState({
                videoReady:true
            })
        }
        var duration = data.playableDuration
        var currentTime = data.currentTime
        var percent = Number(currentTime / duration).toFixed(5)
        if(percent > this.state.videoProgress){
            this.setState({
                videoTotal:Number(duration),
                currentTime: Number(data.currentTime).toFixed(5),
                videoProgress:percent
            })

        }
    }
    _onEnd(){
        console.log('_onEnd')

    }
    _onError(e){
        console.log('_onError',e)

    }

    _renderRow(row){
        return (
            <View key={row._id} style={styles.replayBox} >
                    <Image style={styles.replayAvatar} source={{uri: row.replayBy.avatar}} />
                    <View style={styles.replay}>
                        <Text style={styles.replayNickname}>{row.replayBy.nickname}</Text>
                        <Text style={styles.replayContent}>{row.content}</Text>
                    </View>
            </View>
        )
    }
    _focus(){
        this._setModalVisible(true)
    }
    _blur(){
        this._setModalVisible(false)

    }
    _submit(){
        var that = this
        if(!this.state.content){
            return Alert.alert('留言不能为空')
        }
        if(this.state.isSending){
            return Alert.alert('正在评论中')
        }
        this.setState({
            isSending:true
        },function () {
            var body = {
                accessToken:'abc',
                creation:'1231',
                content:this.state.content
            }
            var url = config.api.base + config.api.comment
            console.log(url)
            request.post(url,body)
                .then((data) => {
                    console.log('data',data)
                    if(data && data.success){
                        var items = cacheResult.items.slice()
                        var content = that.state.content
                        console.log('items=>',items)

                        items = [{
                            content:content,
                            replayBy:{
                                avatar:'https://dummyimage.com/640x640/70b984)',
                                nickname:'狗子说',

                            }
                        }].concat(items)
                        cacheResult.items = items
                        cacheResult.total += 1

                        that.setState({
                            content:'',
                            isSending:false,
                            dataSource:that.state.dataSource.cloneWithRows(cacheResult.items)
                        })
                        console.log('that.state.dataSource',that.state.dataSource)

                        that._setModalVisible(false)
                    }
                })
                .catch((err) => {
                    console.log('err',err)

                    that.setState({
                        isSending:false,
                    })
                    return Alert.alert('留言失败,稍后重试')
                })
        })
    }
    _setModalVisible(isVisible){
        this.setState({
            modalVisible:isVisible
        })
    }
    _renderHeader(row){
        return (
            <View style={styles.listHeader}>

                <View style={styles.commentBox}>
                    <View style={styles.comment}>
                        <TextInput
                            placeholder='敢不敢评论一个...'
                            style={styles.content}
                            multiline={true}
                            onFocus={this._focus.bind(this)}
                        ></TextInput>
                    </View>
                </View>
                <View style={styles.commentArea}>
                    <Text style={styles.commentTitle}>精彩评论</Text>

                </View>
            </View>

        )
    }

    render() {
        return (
            <View style={styles.container}>

                <View>
                    <View>
                        <VideoPlayer
                            video={{uri: this.state.rowData.video}}
                        />
                    </View>
                    <ScrollView>
                        {/*enableEmptySections={true}*/}
                        {/*showsVerticalScrollIndicator={false} //竖直滚动条*/}
                        {/*automaticallyAdjustContentInsets={false}*/}
                        {/*style={styles.scrollView}*/}
                        <View style={styles.infoBox}>
                            <Image style={styles.avatar} source={{uri: this.state.rowData.author.avatar}} />
                            <View style={styles.descBox}>
                                <Text style={styles.nickname}>{this.state.rowData.author.nickname}</Text>
                                <Text style={styles.title}>{this.state.rowData.title}</Text>
                            </View>
                        </View>
                    </ScrollView>
                </View>

                <ListView
                    style={styles.commentLineTop}
                    dataSource={this.state.dataSource}
                    renderRow={this._renderRow.bind(this)}
                    renderHeader={this._renderHeader.bind(this)}


                    enableEmptySections={true}
                    showsVerticalScrollIndicator={false} //竖直滚动条
                    automaticallyAdjustContentInsets={false}

                />
                <Modal style={styles.commentModal}
                    animationType={'fade'}
                    visible={this.state.modalVisible}
                    // onRequestClose={ () => {this._setModalVisible(false)}}

                >
                    <View style={styles.modalContainer}>
                        <Icon onPress={this._closeModal}
                              name='ios-close-outline'
                              style={styles.closeIcon}
                        />
                        <View style={styles.commentBox}>
                            <View style={styles.comment}>
                                <TextInput
                                    placeholder='敢不敢评论一个...'
                                    style={styles.content}
                                    multiline={true}
                                    defaultValue={this.state.content}
                                    onChangeText={(text)=>{
                                        this.setState({
                                            content:text
                                        })
                                    }}
                                ></TextInput>
                            </View>
                        </View>
                    </View>
                    <View style={styles.modalCtrl}>
                        <Button
                            title="取消"
                            onPress={() => {this._setModalVisible(false)}}
                        />
                        <Button
                            style={styles.commentSubmitBtn}
                            onPress={this._submit.bind(this)}
                            title="评论"
                           />
                    </View>
                </Modal>
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

        backgroundColor: '#F5FCFF',
    },
    videoBox:{
        width:width,
        height:width*0.56,
        backgroundColor:'black',

    },
    video:{
        width:width,
        height:width*0.56,
        backgroundColor:'black',
    },
    loading:{
        position:'absolute',
        left:0,
        top:(width*0.56)/2,
        width:width,
        alignSelf:'center',
        backgroundColor:'transparent'
    },
    progressBox:{
        width: width,
        height: 3,
        backgroundColor:'#ccc',

    },
    progressBar:{
        width:1,
        height:3,
        backgroundColor:'#ff6600',
    },
    infoBox:{
        width:width,
        flexDirection:'row',
        justifyContent:'center',
        marginTop:10,
        borderBottomColor:'gray',
        borderBottomWidth:1,
        paddingBottom:5

    },
    avatar:{
        width:60,
        height:60,
        marginRight:10,
        marginLeft:10,
        borderRadius:30
    },
    descBox:{
        flex:1
    },
    nickname:{
        fontSize:18

    },
    title:{
        marginTop:8,
        fontSize:16,
        color:'#666'
    },
    commentLineTop:{

    },
    replayBox:{
        flexDirection:'row',
        justifyContent:'flex-start',
        marginTop:10,
        borderBottomColor:'gray',
        borderBottomWidth:0.5,
        paddingBottom:8,
    },
    replayAvatar:{
        width:40,
        height:40,
        marginRight:10,
        marginLeft:10,
        borderRadius:20
    },
    replayNickname:{
        color:'#666'
    },
    replayContent:{
        marginTop:4,
        color:'#666'
    },
    replay:{
        flex:1
    },
    listHeader:{
        width:width
    },
    commentBox:{
        marginTop:10,
        marginBottom:10,
        padding:8,
        width:width,

    },
    content:{
        paddingLeft:2,
        color:'#333',
        borderWidth:1,
        borderColor:'#ddd',
        borderRadius:4,
        fontSize:14,
        height:80,

    },
    commentArea:{
        width:width,
        paddingBottom:6,
        paddingLeft:10,
        paddingRight:10,
        borderBottomWidth:1,
        borderBottomColor:'#eee'
    },
    commentModal:{
    },
    modalCtrl:{
        flexDirection:'row',
        justifyContent:'center',
        width:width
    },
    commentCancleBtn:{
        flex:1,
        backgroundColor:'gray',
        padding:16,
        borderWidth:1,
        borderColor:'#ee753c',
        borderRadius:4,
        color:'#ee753c'
    },
    commentSubmitBtn:{
        flex:1,
        backgroundColor:'gray',
        padding:16,
        borderWidth:1,
        borderColor:'#ee753c',
        borderRadius:4,
        color:'#ee753c'
    }





});