<template lang="html">
    <div class="load-moudle" @touchstart="touchStart($event)" @touchmove="touchMove($event)" @touchend="touchend($event)">
    <slot></slot>
    <footer class="load-more">
    <slot name="load-more">
    <div class="more-tip" v-if="pullUpState==1">
    <span class="more-text">{{pullUpInfo.moreText}}</span>
</div>
<div class="loading-tip" v-if="pullUpState==2">
    <span class="loading-icon"></span>
    <span class="loading-text">{{pullUpInfo.loadingText}}</span>
</div>
<div class="no-more-tip" v-if="pullUpState==3">
    <span class="connecting-line"></span>
    <span class="no-more-text">{{pullUpInfo.noMoreText}}</span>
<span class="connecting-line"></span>
    </div>
    </slot>
    </footer>
    </div>
    </template>

    <script>
export default {
    props: {
        parentPullUpState: {
            default: 0
        },
        onInfiniteLoad: {
            type: Function,
            require: false
        }
    },
    data () {
        return {
            top: 0,
            pullUpState: 0, // 1:上拉加载更多, 2:加载中……, 3:已经到底了...
            isLoading: false, // 是否正在加载
            pullUpInfo: {
                moreText: '上拉加载更多',
                loadingText: '数据加载中...',
                noMoreText: '我是有底线的'
            },
            startX: 0,
            startY: 0,
            endX: 0,
            endY: 0
        }
    },
    methods: {
        /**
         * 触摸开始，手指点击屏幕时
         * @param {object} e Touch 对象包含的属性
         */
        touchStart (e) {
            this.startX = e.touches[0].pageX
            this.startY = e.touches[0].pageY
        },

        /**
         * 接触点改变，滑动时
         * @param {object} e Touch 对象包含的属性
         */
        touchMove (e) {
            this.endX = e.changedTouches[0].pageX
            this.endY = e.changedTouches[0].pageY
            let direction = this.getSlideDirection(this.startX, this.startY, this.endX, this.endY)
            switch (direction) {
                case 0:
                    // console.log('没滑动')
                    break
                case 1:
                    // console.log('向上')
                    this.scrollToTheEnd()
                    break
                case 2:
                    // console.log('向下')
                    break
                case 3:
                    // console.log('向左')
                    break
                case 4:
                    // console.log('向右')
                    break
                default:
            }
        },

        /**
         * 触摸结束，手指离开屏幕时
         * @param {object} e Touch 对象包含的属性
         */
        touchend (e) {
            this.isLoading = false
        },

        /**
         * 判断滚动条是否到底
         */
        scrollToTheEnd () {
            let innerHeight = document.querySelector('.load-moudle').clientHeight
            // 变量scrollTop是滚动条滚动时，距离顶部的距离
            let scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop
            // 变量scrollHeight是滚动条的总高度
            let scrollHeight = document.documentElement.clientHeight || document.body.scrollHeight
            // 滚动条到底部的条件
            if (scrollTop + scrollHeight >= innerHeight) {
                if (this.pullUpState !== 3 && !this.isLoading) {
                    this.infiniteLoad()
                }
                // console.log('距顶部' + scrollTop + '滚动条总高度' + scrollHeight + '内容高度' + innerHeight)
            }
        },

        /**
         * 上拉加载数据
         */
        infiniteLoad () {
            if (this.pullUpState !== 0) {
                this.pullUpState = 2
                this.isLoading = true
                this.onInfiniteLoad(this.infiniteLoadDone)
            }
        },

        /**
         * 加载数据完成
         */
        infiniteLoadDone () {
            this.pullUpState = 1
        },

        /**
         * 返回角度
         */
        getSlideAngle (dx, dy) {
            return Math.atan2(dy, dx) * 180 / Math.PI
        },

        /**
         * 根据起点和终点返回方向 1：向上，2：向下，3：向左，4：向右,0：未滑动
         * @param {number} startX X轴开始位置
         * @param {number} startY X轴结束位置
         * @param {number} endX Y轴开始位置
         * @param {number} endY Y轴结束位置
         */
        getSlideDirection (startX, startY, endX, endY) {
            let dy = startY - endY
            let dx = endX - startX
            let result = 0
            // 如果滑动距离太短
            if (Math.abs(dx) < 2 && Math.abs(dy) < 2) {
                return result
            }
            let angle = this.getSlideAngle(dx, dy)
            if (angle >= -45 && angle < 45) {
                result = 4
            } else if (angle >= 45 && angle < 135) {
                result = 1
            } else if (angle >= -135 && angle < -45) {
                result = 2
            } else if ((angle >= 135 && angle <= 180) || (angle >= -180 && angle < -135)) {
                result = 3
            }
            return result
        }
    },
    watch: {
        parentPullUpState (curVal, oldVal) {
            this.pullUpState = curVal
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.load-more {
    width: 100%;
    color: #c0c0c0;
    background: #fafafa;
    font-size: 24px;
}

.more-tip,
.loading-tip,
.no-more-tip {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 150px;
}

.load-moudle .loading-icon {
    display: inline-flex;
    width: 35px;
    height: 35px;
    background: url(../../assets/images/refreshAndReload/loading.png) no-repeat;
    background-size: cover;
    margin-right: 5px;
    animation: rotating 2s linear infinite;
}

@keyframes rotating {
    0% {
        transform: rotate(0deg);
}
    100% {
        transform: rotate(1turn);
}
}

.connecting-line {
    display: inline-flex;
    width: 150px;
    height: 2px;
    background: #ddd;
    margin-left: 20px;
    margin-right: 20px;
}
</style>