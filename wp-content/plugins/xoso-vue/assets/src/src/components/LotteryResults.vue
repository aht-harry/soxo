<template>
    <table class="table-result table-xsmb" :class="getClassTable">
        <tbody>
            <tr v-if="prizeCodes.length > 0">
                <th>ĐB</th>
                <td>
                    <span id="mb_prizeDB_item0" class="special-prize">{{ prizeCodes }}</span>
                </td>
            </tr>
            <tr v-for="(prize, index) in parsedPrizes" :key="index">
                <th>{{ prize.name }}</th>
                <td>
                    <span v-for="(number, numIndex) in prize.numbers" :key="numIndex"
                        :id="'mb_prize' + prize.id + '_item' + numIndex" :class="'prize' + prize.id">
                        {{ number }}
                    </span>
                </td>
            </tr>
            <tr class="loadding" v-if="loading">
                <DefaultLoader :color="'#ed1c25'" />
            </tr>
        </tbody>
    </table>
</template>
<script>

import DefaultLoader from '../components/loadding/DefaultLoader.vue';

export default {
    components: {
        DefaultLoader
    },
    props: {
        activeRegion: {
            type: String,
            required: true,
            default: 'mien_bac'
        },
        prizeCodes: {
            type: Array,
            required: true,
            default: () => []
        },
        loading: {
            type: Boolean,
            required: false,
            default: false
        },
        prizes: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    computed: {
        parsedPrizes() {
            return this.prizes.map((prize, index) => {
                return {
                    name: (index + 1).toString(),
                    id: index + 1,
                    numbers: prize.split(',')
                };
            });
        },
        getClassTable(){
            return (this.activeRegion == 'mien_bac')? this.activeRegion : 'khac';
        }
    }
}
</script>

<style scoped>
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%
}

table td,
table th {
    padding: 3px;
    text-align: center;
    border: solid 1px #dddfe2
}

table thead th {
    background: #f0f8ff;
    font-size: 14px;
    font-weight: 500
}

.table-result {
    position: relative;
    text-align: center;
    font-family: arial;
}
.table-result .loadding{
    position: absolute;
    z-index: 2;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    background-color: #0000001a !important;
}
.table-lottery th,
.table-result td {
    padding: 0
}

.table-result tr:nth-of-type(odd) {
    background-color: #f0f8ff;
}

.table-result thead th {
    background-color: #f0f8ff;
    font-weight: 500
}

.table-result th {
    font-weight: 400;
    font-size: 16px
}

.name-prize {
    width: 10%
}

.number-prize {
    width: 90%
}

.code-DB1,
.code-DB10,
.code-DB11,
.code-DB12,
.code-DB13,
.code-DB14,
.code-DB15,
.code-DB2,
.code-DB3,
.code-DB4,
.code-DB5,
.code-DB6,
.code-DB7,
.code-DB8,
.code-DB9 {
    float: left;
    height: 40px;
    line-height: 40px;
    color: #ed1c25;
    font-weight: 700
}

.code-DB10,
.code-DB11,
.code-DB12,
.code-DB13,
.code-DB14,
.code-DB15,
.code-DB16,
.code-DB17,
.code-DB18,
.code-DB19,
.code-DB20 {
    float: left;
    margin: 0 6px;
    color: #ed1c25;
    font-weight: 700
}

.code-DB1 {
    width: 100%;
    font-size: 22px
}

.code-DB2 {
    width: 50%;
    font-size: 22px
}

.code-DB3 {
    width: 33.3%;
    font-size: 22px
}

.code-DB4 {
    width: 25%;
    font-size: 22px
}

.code-DB5 {
    width: 20%;
    font-size: 22px
}

.code-DB6 {
    width: 16.5%;
    font-size: 20px
}

.code-DB7 {
    width: 14%;
    font-size: 20px
}

.code-DB8 {
    width: 12.5%;
    font-size: 20px
}

.code-DB9 {
    width: 11%;
    font-size: 20px
}

.code-DB10 {
    width: 20%;
    font-size: 20px
}

.special-prize {
    float: left;
    width: 100%;
    height: 40px;
    line-height: 40px;
    font-size: 34px;
    font-weight: 700;
    color: #ed1c25
}

.prize1 {
    float: left;
    width: 100%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}

.prize2 {
    float: left;
    width: 50%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}
.khac .prize2,.khac .prize5,.khac .prize7,.khac .prize8{
    width: 100%;
}
.khac .prize4#mb_prize4_item4,.khac .prize4#mb_prize4_item5,.khac .prize4#mb_prize4_item6{
    width: 33.33333%;
}
.khac .prize3{
    width: 50%;
}
.khac .prize7{
    color: #666;
}
.prize3 {
    float: left;
    width: 33.3%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}

.prize4 {
    float: left;
    width: 25%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}

.prize5 {
    float: left;
    width: 33.3%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}

.prize6 {
    float: left;
    width: 33.3%;
    height: 40px;
    line-height: 40px;
    font-size: 28px;
    font-weight: 700
}

.prize7,.prize8 {
    float: left;
    width: 25%;
    height: 40px;
    line-height: 40px;
    font-weight: 700;
    font-size: 34px;
    color: #ed1c25
}

.prize-col2 {
    width: 40%
}

.prize-col3 {
    width: 30%
}

.prize-col4 {
    width: 22.5%
}

.xs_prize1 {
    float: left;
    width: 100%;
    height: 40px;
    line-height: 40px;
    font-size: 26px;
    font-weight: 700
}

.xs_prize2 {
    float: left;
    width: 50%;
    height: 40px;
    line-height: 40px;
    font-size: 26px;
    font-weight: 700
}

.xs_prize3 {
    float: left;
    width: 33.3%;
    height: 40px;
    line-height: 40px;
    font-size: 26px;
    font-weight: 700
}

.xs_prize4 {
    float: left;
    width: 25%;
    height: 40px;
    line-height: 40px;
    font-size: 26px;
    font-weight: 700
}

.prize_db {
    color: #ed1c25
}
</style>